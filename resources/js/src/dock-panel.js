/**
 * `tediDockPanel` and `tediDockPanelSheet`: port of `DockPanel` in
 * react/src/community/components/dock-panel/dock-panel.tsx (CONVENTIONS.md §13).
 *
 * `tedi:dock-panel` renders up to three siblings: the docked panel (this
 * scope), a `display: contents` wrapper holding a `tedi:sheet-content`
 * (`tediDockPanelSheet`), and `tedi-dock-panel__sheet-spacer`. Upstream renders
 * one shell or the other. Here both are server-rendered, and this scope shows
 * one and hides the other:
 *
 * - **Presentation.** `mobile` null follows TEDI's md breakpoint
 *   (`isBreakpointBelow(bp, 'md')` is `(min-width: 48rem)` not matching, the
 *   query `breakpoint.js` uses). true is always the sheet, and false is always
 *   docked, with no sheet rendered. The first presentation is resolved after
 *   init, as upstream's `hasMounted` does, and reported then.
 * - **Content moves rather than re-renders.** Upstream portals content, footer
 *   and header actions into detached `tedi-dock-panel__slot` nodes and appends
 *   them to whichever shell is showing. The server renders those slot nodes
 *   inside the docked hosts, and `_moveSlots()` appends them to the sheet's
 *   hosts and back. The panel `id` moves with the content host, as upstream's
 *   `<div id={panelId}>` inside `Sheet.Body` does. A focused element inside a
 *   moved node, or the toggle, gets focus back after the move.
 * - **State** is written onto the markup imperatively by `_apply()`, not with
 *   `x-bind`, so one function owns it and can re-run after a Livewire morph:
 *   `data-collapsed`, the `hidden` content, the toggle's `aria-expanded`, name
 *   and icon, the minimized title's `text-small`, the spacer, and the
 *   `<html data-tedi-dock-panel-sheet="minimized">` marker (reference-counted
 *   across panels, as upstream does).
 * - **Livewire morph** (CONVENTIONS.md §11). Before a morph of anything that
 *   contains the panel, `_restoreServerLayout()` puts the moved nodes and the
 *   id back where the server renders them, so the diff is clean. After it,
 *   `_afterMorph()` adopts a changed server `collapsed` (controlled mode),
 *   then re-applies the client state.
 * - **minimizeCounter.** A MutationObserver watches `data-minimize-counter`.
 *   A change requests minimization while the panel is expanded, whether a
 *   morph or an Alpine binding made it.
 * - **shortTitle.** `FittingTitle`'s measurement: a ResizeObserver on each
 *   fitting title and its hidden full-title copy, comparing width (or height,
 *   on the vertical minimized rail).
 *
 * Callbacks become DOM events dispatched from the docked panel, bubbling:
 * `tedi:collapsed-change` `{ collapsed }`, `tedi:close`, and
 * `tedi:presentation-change` `{ presentation }`.
 *
 * Not ported: `resizable` (React's community Resizer fails the core-token gate).
 */

import { BREAKPOINTS } from './breakpoint.js';
import { sheet } from './sheet.js';

const MINIMIZED_SHEET_ATTRIBUTE = 'data-tedi-dock-panel-sheet';
// Counted so one panel restoring doesn't clear the attribute while another sheet is still minimized.
let minimizedSheetCount = 0;

const SLOT_NAMES = ['content', 'footer', 'actions'];

const TABBABLE_SELECTOR = [
    'a[href]',
    'button:not([disabled])',
    'input:not([disabled]):not([type="hidden"])',
    'select:not([disabled])',
    'textarea:not([disabled])',
    '[contenteditable]:not([contenteditable="false"])',
    '[tabindex]:not([tabindex="-1"])',
].join(', ');

function setHidden(element, hidden) {
    if (element) element.toggleAttribute('hidden', hidden);
}

export function dockPanel(config) {
    const cfg = config || {};

    return {
        collapsed: !!cfg.collapsed,
        presentation: 'docked',

        init() {
            this._root = this.$el;
            this._sheet = null;
            this._ready = false;
            this._reported = null;
            this._marked = false;
            this._sheetFocusResolved = false;
            this._serverCollapsed = this.collapsed;
            this._counter = this._root.getAttribute('data-minimize-counter');
            this._collectSlots();

            if (cfg.mobile === null && typeof window.matchMedia === 'function') {
                this._query = window.matchMedia(`(min-width: ${BREAKPOINTS.md}rem)`);
                this._onQuery = () => this._present();
                this._query.addEventListener('change', this._onQuery);
            }

            if (typeof MutationObserver !== 'undefined') {
                this._counterObserver = new MutationObserver(() => this._checkCounter());
                this._counterObserver.observe(this._root, { attributes: true, attributeFilter: ['data-minimize-counter'] });
            }

            if (typeof ResizeObserver !== 'undefined') {
                this._fitObserver = new ResizeObserver(() => this._fitTitles());
            }

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const offs = [
                    livewire.hook('morph', ({ el }) => {
                        if (el && this._root && el.contains(this._root)) this._restoreServerLayout();
                    }),
                    livewire.hook('morphed', ({ el }) => {
                        if (el && this._root && el.contains(this._root)) this._afterMorph();
                    }),
                ];
                this._offMorph = () => offs.forEach((off) => typeof off === 'function' && off());
            }

            // The sheet sibling initialises after this element and attaches itself.
            this.$nextTick(() => {
                this._ready = true;
                this._present();
                this._observeTitles();
            });
        },

        destroy() {
            this._query?.removeEventListener('change', this._onQuery);
            this._counterObserver?.disconnect();
            this._fitObserver?.disconnect();
            this._offMorph?.();
            this._mark(false);
            this._root = null;
        },

        // -- public API ---------------------------------------------------------

        toggle() {
            this.requestCollapsed(!this.collapsed);
        },

        minimize() {
            if (!this.collapsed) this.requestCollapsed(true);
        },

        expand() {
            if (this.collapsed) this.requestCollapsed(false);
        },

        close() {
            this._dispatch('tedi:close');
        },

        /** Upstream's requestCollapsed: the next state, plus onCollapsedChange. */
        requestCollapsed(next) {
            next = !!next;
            if (next === this.collapsed) return;
            this.collapsed = next;
            this._apply();
            this._dispatch('tedi:collapsed-change', { collapsed: next });

            // Minimizing hides the slots, and the browser would otherwise drop focus to the body.
            if (next && this.presentation === 'sheet' && this._inSlot(document.activeElement)) {
                this._toggle('sheet')?.focus();
            }
        },

        // -- called by tediDockPanelSheet ----------------------------------------

        _attachSheet(scope, element) {
            this._sheet = scope;
            this._sheetElement = element;
            this._spacer = document.querySelector(`[data-tedi-dock-panel-spacer="${CSS.escape(cfg.panelId)}"]`);
            if (this._ready) this._present();
        },

        /** Upstream's handleSheetDismiss: a drag down on the sheet. */
        _sheetDismiss() {
            if (this.collapsed) this.close();
            else this.requestCollapsed(true);
        },

        // -- presentation --------------------------------------------------------

        _isMobile() {
            if (!this._sheet) return false;
            if (cfg.mobile !== null && cfg.mobile !== undefined) return !!cfg.mobile;
            return this._query ? !this._query.matches : false;
        },

        _present() {
            if (!this._ready || !this._root) return;

            const next = this._isMobile() ? 'sheet' : 'docked';
            const changed = next !== this.presentation;

            if (changed) {
                // Moving a node blurs whatever is focused inside it.
                const active = document.activeElement;
                let restore = null;
                if (active instanceof HTMLElement && this._inSlot(active)) restore = active;
                else if (active && this._toggle(this.presentation) === active) restore = 'toggle';

                this.presentation = next;
                this._apply();

                if (restore === 'toggle') this._toggle(next)?.focus();
                else if (restore && restore.isConnected) restore.focus();
            } else {
                this._apply();
            }

            if (this.presentation === 'sheet' && !this._sheetFocusResolved) {
                // Upstream's initialFocus: a panel that opens as a sheet focuses its first
                // tabbable element unless minimized; one that was docked first leaves focus alone.
                this._sheetFocusResolved = true;
                if (!changed || this.collapsed || this._reported === 'docked') return this._report();
                this._sheet?._focusFirst();
            } else if (this.presentation === 'docked') {
                this._sheetFocusResolved = true;
            }

            this._report();
        },

        _report() {
            if (this._reported === this.presentation) return;
            this._reported = this.presentation;
            this._dispatch('tedi:presentation-change', { presentation: this.presentation });
        },

        // -- DOM ---------------------------------------------------------------

        _collectSlots() {
            this._slots = {};
            SLOT_NAMES.forEach((name) => {
                this._slots[name] = this._root.querySelector(`[data-tedi-dock-panel-slot="${name}"]`);
            });
        },

        _host(container, name) {
            return container ? container.querySelector(`[data-tedi-dock-panel-host="${name}"]`) : null;
        },

        _toggle(presentation) {
            const container = presentation === 'sheet' ? this._sheetElement : this._root;
            return container ? container.querySelector(`[data-tedi-dock-panel-toggle="${presentation}"]`) : null;
        },

        _inSlot(element) {
            if (!element) return false;
            return SLOT_NAMES.some((name) => this._slots[name]?.contains(element));
        },

        _moveSlots(container) {
            SLOT_NAMES.forEach((name) => {
                const slot = this._slots[name];
                const host = this._host(container, name);
                if (slot && host && slot.parentElement !== host) host.appendChild(slot);
            });
        },

        /** Writes the current state onto the server markup. Idempotent. */
        _apply() {
            const root = this._root;
            if (!root) return;

            const collapsed = this.collapsed;
            const isSheet = this.presentation === 'sheet';
            const label = collapsed ? cfg.labels.expand : cfg.labels.collapse;

            // Docked panel.
            root.setAttribute('data-collapsed', collapsed ? 'true' : 'false');
            root.style.display = isSheet ? 'none' : '';
            root.querySelector('[data-tedi-dock-panel-title]')?.classList.toggle('text-small', collapsed);
            root.querySelectorAll('[data-tedi-dock-panel-collapsible], .tedi-dock-panel__header-actions')
                .forEach((element) => setHidden(element, collapsed));

            const toggle = this._toggle('docked');
            if (toggle) {
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                toggle.setAttribute('aria-label', label);
                toggle.setAttribute('title', label);
                const icon = toggle.querySelector('tedi-icon');
                if (icon) icon.textContent = collapsed ? 'zoom_out_map' : 'zoom_in_map';
            }

            // The panel id sits on the docked root, or on the sheet's content host.
            const sheetContent = this._host(this._sheetElement, 'content');
            if (isSheet && sheetContent) {
                root.removeAttribute('id');
                sheetContent.id = cfg.panelId;
                this._moveSlots(this._sheetElement);
            } else {
                if (sheetContent) sheetContent.removeAttribute('id');
                root.id = cfg.panelId;
                this._moveSlots(root);
            }

            // Sheet.
            if (this._sheet) {
                this._sheet.collapsed = collapsed;
                this._sheet._setPresented(isSheet);
                setHidden(this._host(this._sheetElement, 'actions'), collapsed);
                this._toggle('sheet')?.setAttribute('aria-label', label);
            }

            if (this._spacer) this._spacer.style.display = isSheet && collapsed ? '' : 'none';
            this._mark(isSheet && collapsed);
            this._fitTitles();
        },

        _mark(on) {
            if (on === this._marked) return;
            this._marked = on;
            const html = document.documentElement;
            if (on) {
                minimizedSheetCount += 1;
                html.setAttribute(MINIMIZED_SHEET_ATTRIBUTE, 'minimized');
            } else {
                minimizedSheetCount -= 1;
                if (minimizedSheetCount === 0) html.removeAttribute(MINIMIZED_SHEET_ATTRIBUTE);
            }
        },

        _dispatch(name, detail) {
            this._root?.dispatchEvent(new CustomEvent(name, { detail, bubbles: true }));
        },

        _checkCounter() {
            if (!this._root) return;
            const value = this._root.getAttribute('data-minimize-counter');
            if (value === this._counter) return;
            this._counter = value;
            this.minimize();
        },

        // -- Livewire morph ------------------------------------------------------

        _restoreServerLayout() {
            const active = document.activeElement;
            this._morphFocus = active instanceof HTMLElement && this._inSlot(active) ? active : null;
            const sheetContent = this._host(this._sheetElement, 'content');
            if (sheetContent) sheetContent.removeAttribute('id');
            if (this._root) {
                this._root.id = cfg.panelId;
                this._moveSlots(this._root);
            }
        },

        _afterMorph() {
            if (!this._root) return;
            this._collectSlots();

            const serverCollapsed = this._root.getAttribute('data-collapsed') === 'true';
            if (serverCollapsed !== this._serverCollapsed) {
                // A changed server value is the application applying its state (controlled mode).
                this._serverCollapsed = serverCollapsed;
                this.collapsed = serverCollapsed;
            }

            this._apply();
            this._observeTitles();
            if (this._morphFocus && this._morphFocus.isConnected) this._morphFocus.focus();
            this._morphFocus = null;
        },

        // -- shortTitle ----------------------------------------------------------

        _titles() {
            const titles = Array.from(this._root?.querySelectorAll('[data-tedi-dock-panel-fit]') || []);
            if (this._sheetElement) titles.push(...this._sheetElement.querySelectorAll('[data-tedi-dock-panel-fit]'));
            return titles;
        },

        _observeTitles() {
            if (!this._fitObserver) return;
            this._fitObserver.disconnect();
            this._titles().forEach((title) => {
                this._fitObserver.observe(title);
                const measure = title.querySelector('.tedi-dock-panel__title-measure');
                if (measure) this._fitObserver.observe(measure);
            });
            this._fitTitles();
        },

        _fitTitles() {
            this._titles().forEach((container) => {
                const measure = container.querySelector('.tedi-dock-panel__title-measure');
                if (!measure) return;
                // A shell that is not showing has no box; leave its title until it does.
                if (container.getClientRects().length === 0) return;

                // Minimized side rails set the title vertically, so the available space is its height.
                const isVertical = (getComputedStyle(container).writingMode || '').startsWith('vertical');
                const dimension = isVertical ? 'height' : 'width';
                const available = container.getBoundingClientRect()[dimension];
                const needed = measure.getBoundingClientRect()[dimension];
                const text = needed > available ? container.dataset.shortTitle : container.dataset.title;

                const node = container.firstChild;
                if (node && node.nodeType === Node.TEXT_NODE) {
                    if (node.nodeValue !== text) node.nodeValue = text;
                } else {
                    container.insertBefore(document.createTextNode(text), measure);
                }
            });
        },
    };
}

/**
 * The sheet half: `tediSheet` (resources/js/src/sheet.js) with upstream's
 * DockPanel wiring of `Sheet`. It is always "open" while presented, there is
 * no outside-press dismissal, and Escape minimizes (only while expanded). A
 * drag down minimizes, or requests close once minimized
 * (`handleSheetDismiss`). Presentation switches are immediate, not animated
 * open/close transitions, and never move focus by themselves. The collapse
 * state is the panel's.
 */
export function dockPanelSheet(config) {
    const cfg = config || {};
    const base = sheet({ open: false, collapsed: false, closeOnBackdropClick: false, closeOnEscape: false });
    const scope = {};
    // Copy with descriptors so the base's getters/setters (`expanded`, `panelStyle`) stay live.
    Object.defineProperties(scope, Object.getOwnPropertyDescriptors(base));

    const overrides = {
        init() {
            base.init.call(this);

            const panel = document.querySelector(`[data-tedi-dock-panel="${CSS.escape(cfg.panelId)}"]`);
            this._dock = panel && window.Alpine ? window.Alpine.$data(panel) : null;

            this.$watch('collapsed', (value) => {
                if (this._dock && this._dock.collapsed !== value) this._dock.requestCollapsed(value);
            });

            this._onEscape = (event) => {
                if (event.key === 'Escape' && this.open && !this.collapsed && this._dock) {
                    this._dock.requestCollapsed(true);
                }
            };
            document.addEventListener('keydown', this._onEscape);

            this._dock?._attachSheet(this, this.$el);
        },

        destroy() {
            document.removeEventListener('keydown', this._onEscape);
            base.destroy.call(this);
        },

        /** Drag-to-dismiss lands here. */
        hide() {
            this._dock?._sheetDismiss();
        },

        dockClose() {
            this._dock?.close();
        },

        _setPresented(on) {
            if (on === this.open) return;
            this.open = on;
        },

        _onOpen() {
            clearTimeout(this._timer);
            this.active = true;
            this.status = 'open';
        },

        _onClose() {
            clearTimeout(this._timer);
            this.resetDrag();
            this.active = false;
            this.status = 'unmounted';
        },

        _focusFirst() {
            this.$nextTick(() => {
                const panel = this.$refs.panel;
                if (!panel) return;
                const first = Array.prototype.find.call(panel.querySelectorAll(TABBABLE_SELECTOR), (el) => el.tabIndex >= 0 && !el.closest('[hidden]'));
                (first || panel).focus({ preventScroll: true });
            });
        },
    };

    Object.defineProperties(scope, Object.getOwnPropertyDescriptors(overrides));
    return scope;
}
