import { overlay } from './overlay.js';

// =========================================================================
// Dropdown keyboard layer
// =========================================================================
//
// A direct port of the focus/keyboard half of
// `tedi/components/overlay/dropdown/`, which is spread across three files
// upstream:
//
//   dropdown.component.ts          activeIndex, updateTabindexes(),
//                                  focusFirst/Last/Active/Next/PrevItem(),
//                                  setActiveToSelectedOrFirst(),
//                                  tabOutOfDropdown()
//   dropdown-item.component.ts     @HostListener('keydown') — Arrow/Home/
//                                  End/Enter/Space/Tab; mousedown guard
//   dropdown-trigger.directive.ts  ArrowDown/ArrowUp open-and-focus
//
// Two structural divergences, both forced by Blade having no component
// instances to query:
//
//   1. Angular reads its items from `contentChildren(DropdownItemComponent)`
//      and their `disabled()` / `value()` signals. Here the DOM is the only
//      registry, so items are found with a querySelector and their state is
//      read off the attributes the template already emits: `aria-disabled`
//      for disabled, `aria-selected` for the listbox selection that
//      `setActiveToSelectedOrFirst` keys on.
//   2. Angular binds `keydown` per item. Here it is one delegated listener
//      on the panel — `<li tedi-dropdown-item>` is an anonymous Blade
//      component with no place to hang per-instance Alpine state, and the
//      handler needs the sibling list anyway.
//
// Everything else follows upstream exactly, including the details that are
// easy to get wrong by inventing instead of porting:
//
//   - Arrow keys do NOT wrap at the ends (getNextEnabledIndex returns null).
//   - Disabled items keep their roving tabindex in menus so they stay
//     discoverable, but lose it in listboxes.
//   - Opening focuses the selected item, or the first enabled one — even
//     when opened by mouse. That is upstream's `showDropdown('selected')`.
//   - Tab does not move within the panel: it closes the dropdown and jumps
//     to the next focusable element AFTER the trigger in document order.
//     Upstream has no focus trap here, and neither does this.

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

/** Port of `tedi/utils/elements.util.ts` — getFocusableElements(). */
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
 * `tediDropdown` — `tediOverlay` plus the ARIA menu/listbox keyboard layer.
 *
 * Takes every `tediOverlay` config key (see above) and adds none of its own:
 * the item role is read from the rendered `<ul role>` rather than configured,
 * so <tedi:dropdown-content dropdown-role> stays the single source of truth.
 *
 * Markup contract, on top of tediOverlay's trigger/panel refs:
 *   the panel contains  <ul role="menu|listbox">
 *   items are           <li tedi-dropdown-item>
 *   disabled items say  aria-disabled="true"
 *   the selected item   aria-selected="true"   (listbox only)
 */
export function dropdown(config) {
    var base = overlay(config);
    var baseInit = base.init;
    var baseHide = base.hide;
    var baseOnOpen = base._onOpen;

    return Object.assign(base, {
        /** Index into items() of the element holding tabindex="0". */
        activeIndex: null,
        /**
         * Where the next open should land focus — upstream's
         * `showDropdown(initialFocus)` argument. Set by the trigger's arrow
         * keys and consumed (and reset) by `_onOpen`. It exists because
         * `show()` and the focus that follows it are separated by a tick
         * here: without it, the trigger's own `$nextTick` and `_onOpen`'s
         * would both focus, and the later one would win by accident.
         */
        _initialFocus: 'selected',

        init: function () {
            baseInit.call(this);
            this.updateTabindexes();
        },

        hide: function (focusTrigger) {
            this.activeIndex = null;
            this.updateTabindexes();
            baseHide.call(this, focusTrigger);
        },

        _onOpen: function () {
            var self = this;
            var initialFocus = this._initialFocus;
            this._initialFocus = 'selected';

            baseOnOpen.call(this);

            // Upstream defers the focus so the overlay content is attached
            // first; $nextTick is the Alpine equivalent of that setTimeout.
            this.$nextTick(function () {
                self.setActiveToSelectedOrFirst();

                if (initialFocus === 'first') {
                    self.focusFirstItem();
                } else if (initialFocus === 'last') {
                    self.focusLastItem();
                } else {
                    self.focusActiveItem();
                }
            });
        },

        // -- item registry --------------------------------------------

        items: function () {
            if (!this.$refs.panel) return [];

            return Array.prototype.slice.call(
                this.$refs.panel.querySelectorAll('li[tedi-dropdown-item]')
            );
        },

        /** 'menu' | 'listbox', read from the list the content component renders. */
        _role: function () {
            var list = this.$refs.panel && this.$refs.panel.querySelector('ul[role]');

            return list ? list.getAttribute('role') : 'menu';
        },

        _isDisabled: function (item) {
            return item.getAttribute('aria-disabled') === 'true';
        },

        // -- roving tabindex ------------------------------------------

        updateTabindexes: function () {
            var self = this;
            var role = this._role();
            var active = this.activeIndex;

            this.items().forEach(function (item, i) {
                if (i === active && !self._isDisabled(item)) {
                    item.setAttribute('tabindex', '0');
                } else if (role === 'listbox' && self._isDisabled(item)) {
                    item.removeAttribute('tabindex');
                } else {
                    item.setAttribute('tabindex', '-1');
                }
            });
        },

        setActiveToSelectedOrFirst: function () {
            var self = this;
            var items = this.items();

            var selected = items.findIndex(function (item) {
                return item.getAttribute('aria-selected') === 'true';
            });

            if (selected !== -1 && !this._isDisabled(items[selected])) {
                this.activeIndex = selected;
                this.updateTabindexes();
                return;
            }

            var first = items.findIndex(function (item) { return !self._isDisabled(item); });

            if (first !== -1) {
                this.activeIndex = first;
                this.updateTabindexes();
            }
        },

        // -- focus movement -------------------------------------------

        focusActiveItem: function () {
            if (this.activeIndex == null) return;

            var item = this.items()[this.activeIndex];
            if (!item) return;

            item.focus();
            item.scrollIntoView({ block: 'nearest', inline: 'nearest' });
        },

        focusFirstItem: function () {
            var self = this;
            var items = this.items();
            var index = items.findIndex(function (item) { return !self._isDisabled(item); });

            if (index !== -1) this._activate(index);
        },

        focusLastItem: function () {
            var items = this.items();

            for (var i = items.length - 1; i >= 0; i--) {
                if (!this._isDisabled(items[i])) {
                    this._activate(i);
                    return;
                }
            }
        },

        focusNextItem: function (fromEl) {
            var items = this.items();
            var from = items.indexOf(fromEl);
            if (from === -1) return;

            // No wrap-around: upstream's getNextEnabledIndex stops at the end.
            for (var i = from + 1; i < items.length; i++) {
                if (!this._isDisabled(items[i])) {
                    this._activate(i);
                    return;
                }
            }
        },

        focusPrevItem: function (fromEl) {
            var items = this.items();
            var from = items.indexOf(fromEl);
            if (from === -1) return;

            for (var i = from - 1; i >= 0; i--) {
                if (!this._isDisabled(items[i])) {
                    this._activate(i);
                    return;
                }
            }
        },

        _activate: function (index) {
            this.activeIndex = index;
            this.updateTabindexes();
            this.focusActiveItem();
        },

        // -- keyboard entry points -------------------------------------

        /**
         * The trigger's ArrowDown/ArrowUp. Escape is deliberately absent —
         * tediOverlay already closes on it at the document level, and
         * handling it here too would fire `hide` twice.
         */
        triggerKeydown: function (event) {
            if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;

            event.preventDefault();
            var last = event.key === 'ArrowUp';

            if (this.open) {
                last ? this.focusLastItem() : this.focusFirstItem();
                return;
            }

            this._initialFocus = last ? 'last' : 'first';
            this.show();
        },

        /** Delegated from the panel; `event.target` is inside an item. */
        menuKeydown: function (event) {
            var item = event.target.closest('li[tedi-dropdown-item]');
            if (!item || !this.$refs.panel.contains(item)) return;

            if (this._isDisabled(item)) {
                event.preventDefault();
                return;
            }

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    this.focusNextItem(item);
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    this.focusPrevItem(item);
                    break;

                case 'Home':
                    event.preventDefault();
                    this.focusFirstItem();
                    break;

                case 'End':
                    event.preventDefault();
                    this.focusLastItem();
                    break;

                case 'Enter':
                case ' ':
                    // Upstream calls its own onItemSelect(). Here the item's
                    // click handler IS that logic — `x-on:click="hide(true)"`
                    // plus whatever wire:click the consumer bound — so
                    // activation has to go through a real click event or
                    // keyboard users would silently skip both.
                    event.preventDefault();
                    item.click();
                    break;

                case 'Tab':
                    event.preventDefault();
                    this.tabOutOfDropdown(event.shiftKey);
                    break;
            }
        },

        /**
         * Tab/Shift+Tab close the panel and continue the page's tab order
         * from the trigger, rather than stepping through the items. The
         * panel is excluded from the sweep: the active item holds
         * tabindex="0" while open and would otherwise be the "next" stop.
         */
        tabOutOfDropdown: function (shiftKey) {
            var panel = this.$refs.panel;
            var trigger = this._focusableTrigger();

            var focusable = getFocusableElements(document.body).filter(function (el) {
                return !panel || !panel.contains(el);
            });

            var index = focusable.indexOf(trigger);
            var next = shiftKey ? focusable[index - 1] : focusable[index + 1];

            this.hide(false);

            if (next) {
                this.$nextTick(function () { next.focus(); });
            }
        },
    });
}
