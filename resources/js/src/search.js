import { overlay } from './overlay.js';
import { BASE_GAP, VIEWPORT_PADDING, coordsFor, fits, viewport } from './position.js';

// =========================================================================
// Search — the autocomplete combobox
// =========================================================================
//
// A port of the interactive half of
// `src/tedi/components/form/search/search.component.ts` (Angular 8.1 #613,
// with the 9.0.1 design-review fixes #727 / #777).
//
// Like `filter()` and `multiselect()` it composes `overlay()` rather than
// `dropdown()`. Upstream drives the rows with CDK's
// `ActiveDescendantKeyManager`: focus never leaves the `<input>`, which is a
// `role="combobox"` whose `aria-activedescendant` names the highlighted
// `[role="option"]` — not the roving-tabindex MENU pattern `dropdown()`
// implements. `overlay()` supplies the open state, the positioning engine's
// lifecycle (resize / scroll / ResizeObserver) and `hideOnScroll`; this file
// owns everything else.
//
// What this file deliberately does NOT take from `overlay()`:
//
//   - `position()` is overridden. Upstream anchors the panel to the bordered
//     input box (`.tedi-form-field__box`), not to anything the search template
//     owns, and sizes it to the whole host (`panelWidth = host.offsetWidth`,
//     so it also spans a trailing button). The box lives inside form-field's
//     markup, where this component cannot hang an `x-ref`, so it is resolved
//     with `closest()` from the input. Placement is upstream's
//     SEARCH_OVERLAY_POSITIONS: below, start-aligned, flipping above only
//     when it does not fit below and does fit above, with FIELD_OVERLAY_GAP
//     (4px) between field and panel.
//   - Outside-click and Escape. `overlay()`'s versions close with focus
//     returned to the trigger; upstream's Escape only closes (focus is already
//     in the input), and its outside click is CDK's `overlayOutsideClick`,
//     which ignores the origin box. Both are re-implemented below.
//
// ROW MODEL. Unlike `multiselect()`, nothing about the rows is passed in at
// init. Upstream's search never filters — the consumer hands it already
// filtered `suggestions` on every keystroke — so here the rows arrive by
// server render: a Livewire round-trip morphs the panel's contents. Every
// row fact is therefore read off the DOM when it is needed:
//
//   rows             li[tedi-search-suggestion], in DOM order
//   disabled         aria-disabled="true"
//   label            data-tedi-search-label  (what the field is filled with)
//   id               the li's own id         (the aria-activedescendant target)
//   item             data-tedi-search-item   (JSON; `tedi-search:select`'s detail.item)
//   panel state      data-tedi-search-state  loading|results|empty  } on a hidden
//   live-region text data-tedi-search-announcement               } child span
//
// The two panel facts sit on a CHILD of the panel, not on the panel itself,
// because Livewire's morph skips patching the attributes of an element that
// `x-show` is currently showing — exactly the moment a `wire:model.live`
// round-trip turns "empty" into "results".
//
// A MutationObserver on the panel bumps `_rev` whenever the morph changes
// any of that, which is what makes the getters below reactive to it. It
// watches childList / characterData and only the two data-attributes above —
// never the class / aria-selected attributes this file writes itself.
//
// EVENTS. Upstream's `searchEvent` and `suggestionSelect` outputs become DOM
// CustomEvents dispatched FROM THE INPUT (so a listener the consumer put on
// it through the attribute bag sees them, and they bubble to everything
// above):
//
//   tedi-search:search   detail { value }                  Enter / button
//   tedi-search:select   detail { index, label, id, item }  a row was accepted
//
// `item` is the row's JSON-decoded `data-tedi-search-item` (upstream's
// `suggestionSelect` payload). Prefer it to `index`: the `input` event fired
// on select makes a `wire:model.live` list re-query, after which the index
// points into a different list.
//
// They exist because in combobox mode Enter is overloaded: it accepts the
// highlighted row OR submits, and upstream suppresses `searchEvent` in the
// first case. A consumer's own `wire:keydown.enter` cannot tell the two
// apart. Selecting also writes the label into the input and fires `input`
// and `change` on it, which is upstream's `onChange(label)` — the path
// `wire:model` listens on.

var FIELD_OVERLAY_GAP = 4;

var FOCUSABLE_SELECTOR = [
    'a[href]',
    'area[href]',
    "input:not([disabled]):not([type='hidden'])",
    'select:not([disabled])',
    'textarea:not([disabled])',
    'button:not([disabled])',
    'summary',
    'iframe',
    'audio[controls]',
    'video[controls]',
    "[contenteditable]:not([contenteditable='false'])",
    "[tabindex]:not([tabindex='-1'])",
].join(',');

/**
 * Port of `tedi/utils/elements.util.ts` — getFocusableElements(). The same
 * port lives in dropdown.js, unexported; it is repeated rather than imported
 * so this module does not reach into another behaviour's internals.
 */
function getFocusableElements(container) {
    return Array.prototype.filter.call(
        container.querySelectorAll(FOCUSABLE_SELECTOR),
        function (el) {
            var style = window.getComputedStyle(el);

            if (style.display === 'none' || style.visibility === 'hidden') return false;
            if (el.hasAttribute('hidden')) return false;
            if (el.closest('[inert]')) return false;

            var fieldset = el.closest('fieldset[disabled]');
            if (fieldset) {
                var legend = fieldset.querySelector('legend');
                if (!legend || !legend.contains(el)) return false;
            }

            if (el.tagName !== 'SUMMARY' && el.closest('details:not([open])')) return false;

            return true;
        }
    );
}

/**
 * `tediSearch` — `tediOverlay` plus the search autocomplete's combobox layer.
 *
 * Config:
 *   minQueryLength  characters required before the panel shows  default 0
 *   hideOnScroll    close when the page (not the panel) scrolls  default false
 *   open            upstream's `panelOpen` model, initial value  default false
 *   listboxId       id of the [role="listbox"], for aria-controls
 *
 * Markup contract:
 *   x-ref="trigger"  the <input> (also the overlay's resize-observed anchor)
 *   x-ref="panel"    .tedi-search__panel, holding the hidden state child
 *                    documented in the header
 *   x-ref="footer"   optional .tedi-search__footer
 */
export function search(config) {
    var cfg = config || {};

    var base = overlay({
        placement: 'bottom-start',
        offset: FIELD_OVERLAY_GAP - BASE_GAP,
        hideOnScroll: !!cfg.hideOnScroll,
        dismissible: false,
        closeOnEscape: false,
        open: !!cfg.open,
    });

    var baseInit = base.init;
    var baseDestroy = base.destroy;
    var baseOnOpen = base._onOpen;
    var baseOnClose = base._onClose;

    // defineProperties over the descriptors, not Object.assign — see the same
    // note in filter.js: Object.assign would read every getter below once and
    // copy the resulting value, freezing it at its initial state.
    return Object.defineProperties(base, Object.getOwnPropertyDescriptors({
        minQueryLength: Number(cfg.minQueryLength) || 0,
        listboxId: cfg.listboxId || null,
        /** Raw field value; upstream's `value()`. */
        value: '',
        activeIndex: -1,
        _rev: 0,
        /** Upstream's `justSelected`: suppresses the focus-driven reopen. */
        _justSelected: false,
        _writing: false,
        _observer: null,

        init: function () {
            var self = this;
            var input = this.$refs.trigger;

            this.value = input ? input.value : '';

            baseInit.call(this);

            this.$watch('activeIndex', function () { self._applyActive(); });

            // The panel may change size when its rows change, so a visible
            // panel is re-measured once it is shown.
            this.$watch('panelVisible', function (visible) {
                if (visible) self._schedule();
            });

            if (window.MutationObserver && this.$refs.panel) {
                this._observer = new MutationObserver(function () {
                    self._rev++;

                    // Upstream's effect on resolvedSuggestions(): a stale
                    // active index would point at a different row.
                    self.activeIndex = -1;
                    self._applyActive();

                    if (self.open) self._schedule();
                });

                this._observer.observe(this.$refs.panel, {
                    childList: true,
                    subtree: true,
                    characterData: true,
                    attributes: true,
                    attributeFilter: ['data-tedi-search-state', 'data-tedi-search-announcement', 'data-tedi-search-label', 'data-tedi-search-item'],
                });
            }
        },

        destroy: function () {
            if (this._observer) {
                this._observer.disconnect();
                this._observer = null;
            }

            baseDestroy.call(this);
        },

        // -- derived state (upstream's computed()s) --------------------

        get options() {
            // Read for reactivity: the DOM itself is not reactive.
            this._rev;

            var panel = this.$refs.panel;

            return panel ? Array.prototype.slice.call(panel.querySelectorAll('li[tedi-search-suggestion]')) : [];
        },

        /** The hidden child carrying the panel's server-rendered state. */
        _stateNode: function () {
            var panel = this.$refs.panel;

            return panel ? panel.querySelector('[data-tedi-search-state]') : null;
        },

        get panelState() {
            this._rev;

            var node = this._stateNode();

            return node ? node.getAttribute('data-tedi-search-state') : 'empty';
        },

        get loading() {
            return this.panelState === 'loading';
        },

        get hasSuggestions() {
            return this.panelState === 'results';
        },

        get query() {
            return String(this.value || '').trim();
        },

        get meetsMinQueryLength() {
            return this.query.length >= this.minQueryLength;
        },

        get panelVisible() {
            if (!this.open || !this.meetsMinQueryLength) return false;

            return this.loading || this.hasSuggestions || this.query.length > 0;
        },

        get ariaControls() {
            return this.panelVisible && this.hasSuggestions ? this.listboxId : null;
        },

        get activeOptionId() {
            if (this.activeIndex < 0) return null;

            var row = this.options[this.activeIndex];

            return row ? row.id : null;
        },

        get announcement() {
            if (!this.panelVisible) return '';

            this._rev;

            var node = this._stateNode();

            return node ? node.getAttribute('data-tedi-search-announcement') || '' : '';
        },

        // -- open / close ----------------------------------------------

        openPanel: function () {
            this.show();
        },

        closePanel: function () {
            this.hide(false);
            // `_onClose` resets the active row, as upstream's closePanel does.
        },

        closeAndRestoreFocus: function () {
            this.closePanel();
            this._justSelected = true;
            this._focusInput();
        },

        _onOpen: function () {
            var self = this;

            baseOnOpen.call(this);

            // CDK's overlayOutsideClick: a press outside the pane closes it,
            // except on the origin (the field box) itself.
            this._listen(document, 'mousedown', function (event) {
                var surface = self._surface();
                var panel = self.$refs.panel;
                var target = event.target;

                if ((surface && surface.contains(target)) || (panel && panel.contains(target))) return;

                self.closePanel();
            });
        },

        _onClose: function () {
            baseOnClose.call(this);

            // The options are hidden with the panel, so a lingering active
            // index would leave aria-activedescendant pointing at nothing.
            this.activeIndex = -1;
        },

        // -- event handlers (bound on the root, filtered by target) ----

        onInput: function (event) {
            if (this._writing || event.target !== this.$refs.trigger) return;

            this._justSelected = false;
            this.value = event.target.value;
            this.openPanel();
        },

        onFocusIn: function (event) {
            if (event.target !== this.$refs.trigger) return;

            if (this._justSelected) {
                this._justSelected = false;

                return;
            }

            this.openPanel();
        },

        /**
         * Upstream binds this on the host: the panel is portalled out of it
         * there, so both roots are consulted. Here the panel is inside the
         * root, so the root alone answers it.
         */
        onFocusOut: function (event) {
            var next = event.relatedTarget;

            if (!next || !this.$root.contains(next)) this.closePanel();
        },

        onKeydown: function (event) {
            var footer = this.$refs.footer;

            if (footer && footer.contains(event.target)) {
                this._onFooterKeydown(event);

                return;
            }

            if (event.target !== this.$refs.trigger) return;

            switch (event.key) {
                case 'Escape':
                    this.closePanel();

                    return;

                case 'ArrowDown':
                case 'ArrowUp':
                    event.preventDefault();
                    this._onArrowKey(event);

                    return;

                case 'Tab':
                    this._onTabFromInput(event);

                    return;

                case 'Enter':
                    this._onEnter(event);

                    return;
            }
        },

        /** Keep focus in the input while a row is pressed. */
        onPanelMousedown: function (event) {
            if (event.target.closest('li[tedi-search-suggestion]')) event.preventDefault();
        },

        onPanelClick: function (event) {
            var row = event.target.closest('li[tedi-search-suggestion]');
            if (!row) return;

            this.select(this.options.indexOf(row));
        },

        /** The trailing search button (upstream's `(click)="emitSearch()"`). */
        emitSearch: function () {
            var input = this.$refs.trigger;
            if (!input) return;

            input.dispatchEvent(new CustomEvent('tedi-search:search', {
                bubbles: true,
                detail: { value: input.value },
            }));
        },

        // -- selection --------------------------------------------------

        /** Upstream's selectSuggestion(). */
        select: function (index) {
            var row = this.options[index];
            var input = this.$refs.trigger;

            if (!row || !input || row.getAttribute('aria-disabled') === 'true') return;

            var label = row.getAttribute('data-tedi-search-label') || '';

            this._justSelected = true;
            this.value = label;

            // Upstream's onChange(label): the path wire:model listens on. The
            // flag keeps our own input handler from reopening the panel.
            this._writing = true;
            input.value = label;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            this._writing = false;

            input.dispatchEvent(new CustomEvent('tedi-search:select', {
                bubbles: true,
                detail: { index: index, label: label, id: row.id, item: this._itemOf(row) },
            }));

            this.closePanel();
        },

        _itemOf: function (row) {
            var json = row.getAttribute('data-tedi-search-item');
            if (json === null) return null;

            try {
                return JSON.parse(json);
            } catch (e) {
                return null;
            }
        },

        // -- keyboard ---------------------------------------------------

        _onArrowKey: function (event) {
            if (!this.panelVisible) {
                this.openPanel();

                return;
            }

            if (!this.hasSuggestions) return;

            if (event.key === 'ArrowDown') {
                this._setNextActive();
            } else {
                this._setPreviousActive();
            }
        },

        _onEnter: function (event) {
            if (this.panelVisible && this.activeIndex >= 0 && this.options[this.activeIndex]) {
                event.preventDefault();
                this.select(this.activeIndex);

                return;
            }

            this.emitSearch();
        },

        /**
         * Upstream's onTabFromInput(). Its panel is portalled to the end of
         * the document, so Tab would never reach the footer by itself. This
         * panel is inside the root, but it is positioned out of flow, so the
         * explicit hop is kept for the same order upstream gives.
         */
        _onTabFromInput: function (event) {
            if (event.shiftKey || !this.panelVisible) return;

            var first = this._footerFocusables()[0];
            if (!first) return;

            event.preventDefault();
            first.focus();
        },

        /** Upstream's onFooterKeydown(). */
        _onFooterKeydown: function (event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                this.closeAndRestoreFocus();

                return;
            }

            if (event.key !== 'Tab') return;

            var focusables = this._footerFocusables();
            var index = focusables.indexOf(event.target);

            if (event.shiftKey && index === 0) {
                event.preventDefault();
                this._justSelected = true;
                this._focusInput();

                return;
            }

            if (!event.shiftKey && index === focusables.length - 1) {
                event.preventDefault();
                this.closePanel();
                this._focusAfterField();
            }
        },

        _footerFocusables: function () {
            var footer = this.$refs.footer;

            return footer ? getFocusableElements(footer) : [];
        },

        /** Upstream's focusAfterField(). */
        _focusAfterField: function () {
            var input = this.$refs.trigger;
            var panel = this.$refs.panel;
            var focusables = getFocusableElements(document.body).filter(function (el) {
                return !(panel && panel.contains(el));
            });

            var next = focusables[focusables.indexOf(input) + 1];
            if (next) next.focus();
        },

        _focusInput: function () {
            if (this.$refs.trigger) this.$refs.trigger.focus();
        },

        /**
         * ActiveDescendantKeyManager.withWrap(): setNextItemActive /
         * setPreviousItemActive, skipping disabled rows. Unlike the dropdown's
         * menu (CONVENTIONS.md §11), these DO wrap — upstream opts in here.
         * From nothing active, ArrowUp goes to the last enabled row (CDK's
         * `setPreviousItemActive` special case).
         */
        _setNextActive: function () {
            this._step(this.activeIndex, 1);
        },

        _setPreviousActive: function () {
            if (this.activeIndex < 0) {
                this._step(this.options.length, -1);

                return;
            }

            this._step(this.activeIndex, -1);
        },

        _step: function (from, delta) {
            var rows = this.options;
            var count = rows.length;

            for (var i = 1; i <= count; i++) {
                var index = (((from + delta * i) % count) + count) % count;

                if (rows[index].getAttribute('aria-disabled') !== 'true') {
                    this.activeIndex = index;

                    return;
                }
            }
        },

        /**
         * Highlightable.setActiveStyles / setInactiveStyles, plus the key
         * manager's scrollIntoView. Written to the DOM because the rows are
         * server-rendered and may be replaced by a morph at any time.
         */
        _applyActive: function () {
            var active = this.activeIndex;

            this.options.forEach(function (row, index) {
                var isActive = index === active;

                row.classList.toggle('tedi-search-suggestion--active', isActive);
                row.setAttribute('aria-selected', isActive ? 'true' : 'false');

                if (isActive) row.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            });
        },

        // -- positioning (overrides overlay's) ---------------------------

        /** The bordered box wrapping input and icons, or the input itself. */
        _surface: function () {
            var input = this.$refs.trigger;
            if (!input) return null;

            return input.closest('.tedi-form-field__box, .tedi-form-field__input') || input;
        },

        position: function () {
            var anchor = this._surface();
            var panel = this.$refs.panel;

            if (!anchor || !panel) return;

            panel.style.position = 'fixed';
            panel.style.left = '0px';
            panel.style.top = '0px';
            panel.style.width = this.$root.offsetWidth + 'px';

            var anchorRect = anchor.getBoundingClientRect();
            var panelRect = panel.getBoundingClientRect();
            var view = viewport();
            var size = { width: panelRect.width, height: panelRect.height };
            var side = 'bottom';

            if (!fits('bottom', anchorRect, size, FIELD_OVERLAY_GAP, view)
                && fits('top', anchorRect, size, FIELD_OVERLAY_GAP, view)) {
                side = 'top';
            }

            var coords = coordsFor(side, 'start', anchorRect, size, FIELD_OVERLAY_GAP);

            var maxX = view.width - size.width - VIEWPORT_PADDING;
            if (coords.x > maxX) coords.x = maxX;
            if (coords.x < VIEWPORT_PADDING) coords.x = VIEWPORT_PADDING;

            panel.style.left = Math.round(coords.x) + 'px';
            panel.style.top = Math.round(coords.y) + 'px';

            this.side = side;
        },
    }));
}
