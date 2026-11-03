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
//   - Focus moves first and the panel closes second (Angular 9), so focus is
//     never left on a detached element for a screen reader to announce.
//
// Angular 9 additions, also ported:
//
//   - `dropdown-role="list"`: not a widget. No roving tabindex, no item key
//     handling, no trigger arrow keys; opening focuses the panel's first
//     focusable element, and Escape (tediOverlay) focuses the trigger and
//     closes. Tab is the browser's.
//   - `interactive-content` items: the `li` is `role="none"` and the item's
//     role, ARIA state and roving tabindex live on its first `a[href], button`.
//     `_target(item)` resolves that element; every state read goes through it.
//   - #669 trigger keys: Enter/Space for non-native triggers, Enter for a
//     checkbox/radio trigger, Space activating on keyup (initTrigger,
//     triggerKeydown, triggerKeyup, triggerFocusout).

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

/** dropdown-trigger.directive.ts — native controls keep their own semantics and keys. */
var NATIVE_INTERACTIVE_SELECTOR =
    'button, a[href], input:not([type="hidden"]), select, textarea';

var TRIGGER_FOCUSABLE_SELECTOR = NATIVE_INTERACTIVE_SELECTOR + ', [tabindex]';

/** Native controls that fire `click` on Space but not on Enter. */
var SPACE_ACTIVATED_SELECTOR = 'input[type="checkbox"], input[type="radio"]';

/** dropdown-item.component.ts — the control an interactive item projects. */
var INTERACTIVE_CONTENT_SELECTOR = 'a[href], button';

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
 * The trigger half of `dropdown-trigger.directive.ts` that every component
 * rendering `tedi:dropdown-trigger` needs, whatever its own x-data is:
 * `tediDropdown` here, and `tediFilter`, which composes `overlay()` directly
 * but writes the same trigger template. Mixed in with Object.assign — it holds
 * no accessors. The host supplies `open`, `toggle()`, `$refs.trigger`,
 * `$refs.panel` and `$watch`.
 */
export var dropdownTrigger = {
    /** Only a Space press that starts on the trigger may activate it on keyup. */
    _spacePressed: false,

    /**
     * The trigger directive's ngAfterViewInit + effect(). `el` is the
     * `tedi-dropdown-trigger` wrapper; the decorated element is the
     * resolved control inside it.
     *
     * config.ariaHaspopup  explicit value, or null to follow the role
     * config.containerId   pairs id / aria-controls, or null
     */
    initTrigger: function (el, config) {
        var cfg = config || {};
        var focusable = this._focusableTrigger();

        // Non-native triggers need button semantics, but a role or
        // tabindex the consumer set (e.g. role="checkbox") is theirs.
        if (!focusable.matches(NATIVE_INTERACTIVE_SELECTOR)) {
            if (!focusable.hasAttribute('role')) focusable.setAttribute('role', 'button');
            if (!focusable.hasAttribute('tabindex')) focusable.setAttribute('tabindex', '0');
        }

        if (cfg.containerId) {
            focusable.setAttribute('id', cfg.containerId + '_trigger');
        }

        var hasPopup = cfg.ariaHaspopup;
        if (!hasPopup) {
            // The panel is a later sibling, so its x-ref is not registered
            // yet when this x-init runs; read the server-rendered list
            // through the shared parent instead.
            var list = el.parentElement && el.parentElement.querySelector('.tedi-dropdown__panel ul[role]');
            var role = list ? list.getAttribute('role') : 'menu';
            hasPopup = role === 'menu' || role === 'listbox' ? role : 'false';
        }

        if (hasPopup === 'false') {
            focusable.removeAttribute('aria-haspopup');
        } else {
            focusable.setAttribute('aria-haspopup', hasPopup);
        }

        var sync = function (open) {
            focusable.setAttribute('aria-expanded', open ? 'true' : 'false');

            // The panel's list is only meaningful while open, so a
            // permanent aria-controls would point at hidden content.
            if (cfg.containerId && open) {
                focusable.setAttribute('aria-controls', cfg.containerId);
            } else {
                focusable.removeAttribute('aria-controls');
            }
        };

        sync(this.open);
        this.$watch('open', sync);
    },

    /**
     * The trigger directive's element resolution: a natively interactive
     * control is the trigger itself, otherwise the first control or
     * `[tabindex]` inside the wrapper. Wider than tediOverlay's, which
     * tooltip and popover share.
     */
    _focusableTrigger: function () {
        var el = this.$refs.trigger;
        if (!el) return null;
        if (el.matches(NATIVE_INTERACTIVE_SELECTOR)) return el;

        return el.querySelector(TRIGGER_FOCUSABLE_SELECTOR) || el;
    },

    /** 'menu' | 'listbox' | 'list', read from the list the content component renders. */
    _role: function () {
        var list = this.$refs.panel && this.$refs.panel.querySelector('ul[role]');

        return list ? list.getAttribute('role') : 'menu';
    },

    /**
     * Whether the trigger must activate itself on `key`, because the
     * element does not turn that key into a native click: both keys for
     * a generic trigger, Enter for a checkbox or radio. Other native
     * controls handle both. Keys from other controls inside the wrapper
     * are theirs.
     */
    _triggerHandlesKey: function (event, key) {
        var el = this._focusableTrigger();
        if (event.target !== el) return false;
        if (!el.matches(NATIVE_INTERACTIVE_SELECTOR)) return true;

        return key === 'Enter' && el.matches(SPACE_ACTIVATED_SELECTOR);
    },

    /**
     * Enter / Space on the trigger (#669). Returns whether the key was
     * dealt with, so the host's own keydown handler can stop there.
     */
    triggerActivationKeydown: function (event) {
        if (event.key === 'Enter') {
            if (!this._triggerHandlesKey(event, 'Enter')) return false;
            // Also keeps Enter on a checkbox from submitting its form.
            event.preventDefault();
            this.toggle();
            return true;
        }

        if (event.key === ' ') {
            if (!this._triggerHandlesKey(event, ' ')) return false;
            // Match button timing (activate on keyup) and stop the page scrolling.
            event.preventDefault();
            this._spacePressed = true;
            return true;
        }

        return false;
    },

    triggerKeyup: function (event) {
        if (event.key !== ' ' || !this._spacePressed) return;

        this._spacePressed = false;
        if (event.target !== this._focusableTrigger()) return;
        this.toggle();
    },

    triggerFocusout: function (event) {
        if (event.target === this._focusableTrigger()) this._spacePressed = false;
    },
};

/**
 * `tediDropdown` — `tediOverlay` plus the ARIA menu/listbox keyboard layer.
 *
 * Takes every `tediOverlay` config key (see above) and adds none of its own:
 * the item role is read from the rendered `<ul role>` rather than configured,
 * so <tedi:dropdown-content dropdown-role> stays the single source of truth.
 *
 * Markup contract, on top of tediOverlay's trigger/panel refs:
 *   the panel contains  <ul role="menu|listbox|list">
 *   items are           <li tedi-dropdown-item>
 *   disabled items say  aria-disabled="true"
 *   the selected item   aria-selected="true"   (listbox only)
 * — the last two on the item's `_target()`: the `li`, or for an
 * interactive-content item (`li[role="none"]`) its projected control.
 */
export function dropdown(config) {
    var base = overlay(config);
    var baseInit = base.init;
    var baseHide = base.hide;
    var baseOnOpen = base._onOpen;

    return Object.assign(base, dropdownTrigger, {
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
            var self = this;

            baseInit.call(this);
            this.updateTabindexes();

            // Interactive-content items stamp their ARIA onto the projected
            // control in their own x-init, which runs after this one.
            this.$nextTick(function () { self.updateTabindexes(); });
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

            // A plain `list` has no active item and no roving tabindex:
            // opening it just moves focus to its first link.
            if (!this._isWidget()) {
                this.$nextTick(function () {
                    if (!self.open || !self.$refs.panel) return;

                    var first = getFocusableElements(self.$refs.panel)[0];
                    if (first) first.focus();
                });
                return;
            }

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


        /** Whether the content is a composite widget (menu, listbox) rather than a plain list. */
        _isWidget: function () {
            return this._role() !== 'list';
        },

        /**
         * The element that carries the item's role, ARIA state, roving
         * tabindex and focus — upstream's focusTarget(): the projected control
         * for an interactive-content item, the `li` otherwise.
         */
        _target: function (item) {
            if (item.getAttribute('role') === 'none') {
                return item.querySelector(INTERACTIVE_CONTENT_SELECTOR) || item;
            }

            return item;
        },

        _controlOwnsSemantics: function (item) {
            return this._target(item) !== item;
        },

        _isDisabled: function (item) {
            return this._target(item).getAttribute('aria-disabled') === 'true';
        },

        // -- roving tabindex ------------------------------------------

        updateTabindexes: function () {
            if (!this._isWidget()) return;

            var self = this;
            var role = this._role();
            var active = this.activeIndex;

            this.items().forEach(function (item, i) {
                var target = self._target(item);

                if (i === active && !self._isDisabled(item)) {
                    target.setAttribute('tabindex', '0');
                } else if (role === 'listbox' && self._isDisabled(item)) {
                    target.removeAttribute('tabindex');
                } else {
                    target.setAttribute('tabindex', '-1');
                }
            });
        },

        setActiveToSelectedOrFirst: function () {
            var self = this;
            var items = this.items();

            var selected = items.findIndex(function (item) {
                return self._target(item).getAttribute('aria-selected') === 'true';
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

            var target = this._target(item);
            target.focus();
            target.scrollIntoView({ block: 'nearest', inline: 'nearest' });
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
         * The trigger's Enter / Space / ArrowDown / ArrowUp. Escape is
         * deliberately absent — tediOverlay already closes on it at the
         * document level, and handling it here too would fire `hide` twice.
         */
        triggerKeydown: function (event) {
            if (this.triggerActivationKeydown(event)) return;
            if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;
            if (!this._isWidget()) return;

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
            // A plain `list` is not a composite widget: arrow keys, Enter and
            // Space belong to the projected control. Escape is tediOverlay's.
            if (!this._isWidget()) return;

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
                    // Upstream calls its own onItemSelect(). Here the item's
                    // click handler IS that logic — `x-on:click="hide(true)"`
                    // plus whatever wire:click the consumer bound — so
                    // activation has to go through a real click event or
                    // keyboard users would silently skip both.
                    //
                    // An interactive item's control activates itself on
                    // Enter, and preventing the default would swallow a
                    // link's navigation; the resulting click bubbles to the
                    // item and closes the dropdown.
                    if (this._controlOwnsSemantics(item)) break;
                    event.preventDefault();
                    item.click();
                    break;

                case ' ':
                    event.preventDefault();
                    this._target(item).click();
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

            // Move focus first, then close (Angular 9's moveFocusOut). Closing
            // while the panel still holds focus would drop it to `body` for a
            // tick, which a screen reader reads as the whole page.
            (next || trigger).focus();
            this.hide(false);
        },
    });
}
