/**
 * tediSidenav — the sidenav's collapse / mobile-drawer state and, since
 * Angular 10.0.0-rc.26 (#916), the open mobile drawer's focus trap.
 *
 * A port of the client-side half of
 * angular/src/tedi/components/layout/sidenav/sidenav.component.ts and
 * angular/src/tedi/services/sidenav/sidenav.service.ts. The collapse, item
 * count, drawer offset and scroll lock were inline Alpine on
 * `tedi:sidenav` until the focus trap made the scope too large to read
 * inline (CONVENTIONS.md §8); the property names are unchanged, because
 * `tedi:sidenav.item` reads them through Alpine's merged data stack
 * (`$data.tediSidenavCollapsed`, `$data.tediSidenavItemToggled`, …).
 *
 * While the mobile drawer is open:
 *
 *   - focus moves into the nav (its first focusable element, as CDK's
 *     `focusInitialElementWhenReady()` does) and Tab / Shift+Tab wrap inside
 *     it, as CDK's `FocusTrap` does;
 *   - everything outside the nav and the toggle is made `inert` (upstream's
 *     `hideOutside()`, verbatim): elements that contain a kept element are
 *     walked into, already-inert elements are left alone, and the attributes
 *     set are removed on close. The kept elements are upstream's
 *     `KEEP_ACTIVE_SELECTOR` translated to this package: the overlay
 *     (`tedi-sidenav-overlay`, it closes the drawer), the toast container
 *     (`tedi-toast-container`, upstream's CDK overlay container) and the
 *     announcer regions (`announcer.js`, upstream's CDK live announcer);
 *   - Escape closes the drawer (`onEscape()` → `closeMobileDrawer()`).
 *
 * Closing the drawer by any path (the toggle, the overlay, Escape) returns
 * focus to the toggle when focus was in the drawer, as
 * `closeMobileDrawer()`'s `toggle()?.focus()` does. The toggle is upstream's
 * registered toggle element: here the first `button[tedi-sidenav-toggle]`
 * that is not `--hidden`, since the toggle is a DOM sibling with no
 * registry (see sidenav.blade.php).
 *
 * Known gap: a Livewire morph of an element made inert drops the attribute
 * (the server HTML lacks it), so a component that re-renders behind the open
 * drawer becomes reachable again until the drawer is reopened.
 *
 * Config keys:
 *   collapsed       — the server-rendered `isCollapsed()` (already false while mobile).
 *   mobile          — `isMobile()`, the `mobile` prop.
 *   mobileOpen      — the server-rendered `isMobileOpen()`.
 *   mobileItemOpen  — the server-rendered `isMobileItemOpen()`.
 */
import { getFocusableElements } from './dropdown.js';

/** sidenav.component.ts `KEEP_ACTIVE_SELECTOR`, translated (see above). */
const KEEP_ACTIVE_SELECTOR =
    'tedi-sidenav-overlay, tedi-toast-container, [id^="tedi-toast-announcer-"]';

const TOGGLE_SELECTOR = 'button[tedi-sidenav-toggle]:not(.tedi-sidenav-toggle--hidden)';

/**
 * sidenav.component.ts `hideOutside()`: makes everything outside the `keep`
 * elements inert and returns a function that removes the attributes it set.
 */
function hideOutside(keep) {
    const hidden = [];
    const isKept = (el) => keep.includes(el) || el.matches(KEEP_ACTIVE_SELECTOR);

    const walk = (parent) => {
        for (const child of Array.from(parent.children)) {
            if (isKept(child)) {
                continue;
            }

            if (keep.some((el) => child.contains(el))) {
                walk(child);
            } else if (!child.hasAttribute('inert')) {
                child.setAttribute('inert', '');
                hidden.push(child);
            }
        }
    };

    walk(keep[0].getRootNode());

    return () => hidden.forEach((el) => el.removeAttribute('inert'));
}

export function sidenav(config = {}) {
    const {
        collapsed = false,
        mobile = false,
        mobileOpen = false,
        mobileItemOpen = false,
    } = config;

    return {
        tediSidenavCollapsed: !!collapsed,
        tediSidenavMobileOpen: !!mobileOpen,
        tediSidenavDrawerTop: null,
        tediSidenavPreviousOverflow: null,
        tediSidenavOpenCount: mobileItemOpen ? 1 : 0,
        _tediSidenavRoot: null,
        _tediSidenavTrap: null,

        init() {
            this._tediSidenavRoot = this.$el;
        },

        get tediSidenavDrawerOpen() {
            return !!mobile && this.tediSidenavMobileOpen;
        },

        get tediSidenavMobileItemOpen() {
            return !!mobile && this.tediSidenavOpenCount > 0;
        },

        tediSidenavLockScroll(lock) {
            if (lock && this.tediSidenavPreviousOverflow === null) {
                this.tediSidenavPreviousOverflow = document.body.style.overflow;
                document.body.style.overflow = 'hidden';
            } else if (!lock && this.tediSidenavPreviousOverflow !== null) {
                document.body.style.overflow = this.tediSidenavPreviousOverflow;
                this.tediSidenavPreviousOverflow = null;
            }
        },

        /** The drawer-open effect: focus in, Tab wraps, page inert, Escape closes. */
        tediSidenavTrapFocus(open) {
            if (open && !this._tediSidenavTrap) {
                const nav = this._tediSidenavRoot;
                const toggle = document.querySelector(TOGGLE_SELECTOR);
                const showOutside = hideOutside(toggle ? [nav, toggle] : [nav]);

                const onKeydown = (event) => {
                    if (event.key === 'Escape') {
                        this.tediSidenavCloseDrawer();
                        return;
                    }

                    if (event.key !== 'Tab' || !nav.contains(document.activeElement)) return;

                    const focusable = getFocusableElements(nav);
                    if (!focusable.length) return;

                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];

                    if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                };

                document.addEventListener('keydown', onKeydown);

                this._tediSidenavTrap = () => {
                    document.removeEventListener('keydown', onKeydown);
                    showOutside();

                    const active = document.activeElement;
                    if (toggle && (!active || active === document.body || nav.contains(active))) {
                        toggle.focus();
                    }
                };

                // focusInitialElementWhenReady(): after the drawer has rendered.
                this.$nextTick(() => {
                    if (!this._tediSidenavTrap || nav.contains(document.activeElement)) return;

                    const target = nav.querySelector('[cdkFocusInitial], [cdkfocusinitial]')
                        || getFocusableElements(nav)[0];
                    if (target) target.focus();
                });
            } else if (!open && this._tediSidenavTrap) {
                const release = this._tediSidenavTrap;
                this._tediSidenavTrap = null;
                release();
            }
        },

        /** sidenav.service.ts `closeMobileDrawer()`; focus follows from the trap release. */
        tediSidenavCloseDrawer() {
            this.tediSidenavMobileOpen = false;
            this.$dispatch('tedi-sidenav:toggle', { open: false });
            this.tediSidenavCloseAll();
        },

        destroy() {
            this.tediSidenavLockScroll(false);
            this.tediSidenavTrapFocus(false);
        },

        tediSidenavItemToggled(isOpen) {
            this.tediSidenavOpenCount = Math.max(0, this.tediSidenavOpenCount + (isOpen ? 1 : -1));
        },

        tediSidenavCloseAll() {
            this.tediSidenavOpenCount = 0;
            this.$dispatch('tedi-sidenav:close-all');
        },
    };
}
