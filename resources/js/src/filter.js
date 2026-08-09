import { overlay } from './overlay.js';

// =========================================================================
// Filter — selection state + the listbox keyboard layer
// =========================================================================
//
// A port of the interactive half of `tedi/components/filter/filter.component.ts`.
//
// It composes `overlay()` rather than `dropdown()`. The two keyboard layers are
// NOT interchangeable: `dropdown()` implements ARIA's roving-tabindex menu
// pattern over `li[tedi-dropdown-item]`, whereas the filter's option list is a
// single `tabindex="0"` element with `aria-activedescendant` moving over
// `div[role="option"]` children — upstream's `onOptionsKeydown` /
// `activeOptionIndex`. Anchoring, outside-click dismissal, Escape and the
// trigger focus return all come from `overlay()` unchanged (CONVENTIONS.md §11).
//
// Names that had to change, because `overlay()` already owns them:
//
//   Angular `toggle()`  ->  `toggleSelected()`. `toggle()` is the overlay's
//                           open/close and is what the dropdown trigger binds.
//
// Divergences from upstream, all forced by Blade rendering the option list once
// on the server instead of re-rendering it per keystroke:
//
//   1. `activeOptionIndex` indexes the FULL option list, not `filteredOptions()`.
//      Upstream can renumber ids on every search keystroke; here every option's
//      `id` is server-rendered and must stay stable, because it is what
//      `aria-activedescendant` points at. Navigation skips filtered-out options
//      as well as disabled ones, so the observable behaviour — which option the
//      arrows land on, and which id is announced — is identical.
//   2. There is no FilterGroup instance to delegate to. `toggleSelected()`
//      always flips this filter's own `selected`; a managed group binds
//      `wire:model` per child, the same ruling checkbox-group/radio-group took.
//   3. `cleared` is an `output()` and is not re-emitted (CONVENTIONS.md §7.2).
//      The custom-content branch's clear button carries whatever the consumer
//      passed in `clear-attributes` instead.
//
// `model` is a getter/setter pair rather than a plain field. It is the target of
// `x-modelable` on the root element, and it reproduces upstream's
// `writeValue()`: a multi-select filter's model is `string[]`, a single-select
// filter's is `string`, and a plain toggle chip's is `boolean`. A hidden
// `<input>` could carry only the last two, which is why this component has no
// native control (see filter.blade.php's header comment).

/** Upstream's getTabStops() selector, verbatim. */
var TAB_STOP_SELECTOR = 'input:not([disabled]):not([tabindex="-1"]), button:not([disabled]), '
    + '[role="option"][tabindex="0"], [role="listbox"][tabindex="0"], [role="checkbox"][tabindex="0"]';

/**
 * `tediFilter` — `tediOverlay` plus the filter's selection state and its
 * `aria-activedescendant` listbox keyboard layer.
 *
 * Takes every `tediOverlay` config key, and adds:
 *   options              [{ label, value, disabled }] — the server-rendered list
 *   value                string | string[]
 *   selected             boolean, for the no-options toggle chip
 *   text                 the label, for displayText
 *   allowMultiple        boolean
 *   preserveLabel        boolean
 *   clearSearchOnSelect  boolean
 *   hidePrependWhenSelected  boolean
 *
 * Markup contract, on top of tediOverlay's trigger/panel refs:
 *   x-ref="optionsList"  the [role="listbox"] element
 *   options are          .tedi-filter-dropdown__item, in `options` order
 *   x-ref="searchClear"  optional; the form-field clear button (see syncSearchClear)
 */
export function filter(config) {
    var cfg = config || {};
    var base = overlay(cfg);
    var baseOnOpen = base._onOpen;

    // defineProperties over the descriptors, not Object.assign: assign *reads*
    // an accessor on the source and copies the resulting value, so every getter
    // below would land on `base` as a plain field frozen at its initial value —
    // isSelected stuck on false, displayText stuck on the server-rendered label.
    // dropdown.js can use Object.assign because it adds no accessors.
    return Object.defineProperties(base, Object.getOwnPropertyDescriptors({
        options: cfg.options || [],
        value: cfg.value === undefined ? '' : cfg.value,
        selected: !!cfg.selected,
        searchTerm: '',
        activeOptionIndex: -1,

        /**
         * Where the next open should land focus — upstream's
         * `focusDropdownContent(keyboard, focusLast)` arguments. 'mouse' is
         * upstream's `keyboard = false`, which suppresses the auto-select that
         * focusing the list would otherwise trigger.
         */
        _initialFocus: 'mouse',
        _suppressNextOptionsFocusAutoSelect: false,

        // -- derived state (upstream's computed()s) --------------------

        get hasOptions() {
            return this.options.length > 0;
        },

        get isMultiSelect() {
            return this.hasOptions && !!cfg.allowMultiple;
        },

        get isSingleSelect() {
            return this.hasOptions && !cfg.allowMultiple;
        },

        get singleValue() {
            return typeof this.value === 'string' ? this.value : '';
        },

        get multiValues() {
            return Array.isArray(this.value) ? this.value : [];
        },

        get isSelected() {
            if (this.isMultiSelect) return this.multiValues.length > 0;
            if (this.isSingleSelect) return this.singleValue !== '';

            return this.selected;
        },

        get selectedCount() {
            return this.multiValues.length;
        },

        get hidePrepend() {
            return this.isSelected && cfg.hidePrependWhenSelected !== false;
        },

        get selectedLabel() {
            var val = this.singleValue;
            if (!val) return null;

            var option = this.options.find(function (opt) { return opt.value === val; });

            return option ? option.label : null;
        },

        get displayText() {
            if (this.isSingleSelect) {
                var label = this.selectedLabel;

                if (label && cfg.preserveLabel) return cfg.text + ': ' + label;

                return label === null ? cfg.text : label;
            }

            return cfg.text;
        },

        get filteredOptions() {
            var term = this.searchTerm.toLowerCase();
            if (!term) return this.options;

            return this.options.filter(function (opt) {
                return opt.label.toLowerCase().indexOf(term) !== -1;
            });
        },

        get allFilteredSelected() {
            var vals = this.multiValues;
            var filtered = this.filteredOptions.filter(function (opt) { return !opt.disabled; });

            if (filtered.length === 0) return false;

            return filtered.every(function (opt) { return vals.indexOf(opt.value) !== -1; });
        },

        get someFilteredSelected() {
            var vals = this.multiValues;
            var filtered = this.filteredOptions.filter(function (opt) { return !opt.disabled; });
            var count = filtered.filter(function (opt) { return vals.indexOf(opt.value) !== -1; }).length;

            return count > 0 && count < filtered.length;
        },

        get activeDescendantId() {
            if (this.activeOptionIndex === -1) return null;

            return this.optionId(this.activeOptionIndex);
        },

        /** Upstream's writeValue(), exposed to x-modelable / wire:model. */
        get model() {
            if (this.isMultiSelect) return this.multiValues;
            if (this.isSingleSelect) return this.singleValue;

            return this.selected;
        },

        set model(incoming) {
            if (this.isMultiSelect) {
                this.value = Array.isArray(incoming) ? incoming : [];
            } else if (this.isSingleSelect) {
                this.value = typeof incoming === 'string' ? incoming : '';
            } else {
                this.selected = !!incoming;
            }
        },

        // -- per-option helpers used by the template -------------------

        optionId: function (index) {
            return cfg.baseId + '-option-' + index;
        },

        isOptionSelected: function (value) {
            if (this.isSingleSelect) return this.singleValue === value;

            return this.multiValues.indexOf(value) !== -1;
        },

        isOptionVisible: function (index) {
            var option = this.options[index];
            if (!option) return false;
            if (!this.searchTerm) return true;

            return option.label.toLowerCase().indexOf(this.searchTerm.toLowerCase()) !== -1;
        },

        // -- selection ------------------------------------------------

        /** Upstream's toggle(); renamed because tediOverlay owns `toggle`. */
        toggleSelected: function () {
            this.selected = !this.selected;
        },

        selectOption: function (value) {
            this.value = this.singleValue === value ? '' : value;

            if (cfg.clearSearchOnSelect) this.searchTerm = '';

            this.hide(true);
        },

        toggleOption: function (value) {
            var current = this.multiValues;

            this.value = current.indexOf(value) !== -1
                ? current.filter(function (v) { return v !== value; })
                : current.concat([value]);

            if (cfg.clearSearchOnSelect) this.searchTerm = '';
        },

        toggleSelectAll: function () {
            var filtered = this.filteredOptions.filter(function (opt) { return !opt.disabled; });
            var values = this.multiValues;

            if (this.allFilteredSelected) {
                var drop = filtered.map(function (opt) { return opt.value; });

                this.value = values.filter(function (v) { return drop.indexOf(v) === -1; });

                return;
            }

            var next = values.slice();

            filtered.forEach(function (opt) {
                if (next.indexOf(opt.value) === -1) next.push(opt.value);
            });

            this.value = next;
        },

        clearSelection: function () {
            this.value = [];
        },

        clearSingleSelection: function () {
            this.value = '';
        },

        onSearchClear: function () {
            this.searchTerm = '';
        },

        /**
         * form-field decides its clear button's visibility from the `value` it
         * was given at render time, and there is no way to re-render it from
         * here. The button itself is always in the DOM when `clearable`, so this
         * reproduces form-field's own hidden/disabled bookkeeping on it as
         * `searchTerm` changes.
         *
         * Bound with x-effect on the BUTTON, through form-field's
         * `clear-attributes`, not on the search wrapper: Alpine initialises a
         * parent's directives before its children's, so an effect on the
         * wrapper would run before `x-ref="searchClear"` existed, bail out at
         * the guard below, and — having read no reactive state — never run
         * again. Reading `searchTerm` before the guard makes the dependency
         * register even on a bail-out.
         */
        syncSearchClear: function () {
            var show = this.searchTerm !== '';
            var button = this.$refs.searchClear;
            if (!button) return;

            var wrapper = button.closest('.tedi-form-field__buttons');

            button.disabled = !show;
            button.setAttribute('tabindex', show ? '0' : '-1');

            if (wrapper) {
                wrapper.classList.toggle('tedi-form-field__buttons--hidden', !show);

                if (show) {
                    wrapper.removeAttribute('aria-hidden');
                } else {
                    wrapper.setAttribute('aria-hidden', 'true');
                }
            }
        },

        /**
         * `tedi:dropdown-item-value` renders its checkbox/radio itself and puts
         * the attribute bag on its own root, so the indicator cannot be bound
         * from the template. These two keep it in step with the live selection,
         * bound with x-effect from the row. Server-side both are already correct
         * — this only matters once Alpine takes over.
         */
        syncOptionIndicator: function (row, index) {
            var option = this.options[index];
            if (!option) return;

            var input = row.querySelector('.tedi-dropdown-item-value__checkbox, .tedi-dropdown-item-value__radio');

            if (input) input.checked = this.isOptionSelected(option.value);
        },

        syncSelectAllIndicator: function (row) {
            var input = row.querySelector('.tedi-dropdown-item-value__checkbox');
            if (!input) return;

            input.checked = this.allFilteredSelected;
            input.indeterminate = this.someFilteredSelected;
        },

        // -- focus ----------------------------------------------------

        _onOpen: function () {
            var self = this;
            var initialFocus = this._initialFocus;
            this._initialFocus = 'mouse';

            baseOnOpen.call(this);

            // Upstream defers with setTimeout so the overlay content exists;
            // $nextTick is the Alpine equivalent.
            this.$nextTick(function () { self.focusDropdownContent(initialFocus); });
        },

        /** Upstream's focusDropdownContent(keyboard, focusLast). */
        focusDropdownContent: function (mode) {
            if (!this.open) return;

            if (mode === 'mouse') {
                this._suppressNextOptionsFocusAutoSelect = true;
                this.activeOptionIndex = -1;
            }

            var stops = this._tabStops();
            var target = mode === 'last' ? stops[stops.length - 1] : stops[0];

            if (target) target.focus();
        },

        /**
         * The trigger's ArrowDown/ArrowUp. Escape is deliberately absent:
         * tediOverlay already closes on it at the document level and returns
         * focus here, so a second handler would fire `hide` twice
         * (CONVENTIONS.md §11).
         */
        triggerKeydown: function (event) {
            if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;

            event.preventDefault();
            var mode = event.key === 'ArrowUp' ? 'last' : 'first';

            if (this.open) {
                this.focusDropdownContent(mode);

                return;
            }

            this._initialFocus = mode;
            this.show();
        },

        /**
         * Upstream's handleDropdownKeydown, minus its Escape branch — see
         * triggerKeydown above. Delegated from the panel, which is where
         * <tedi:dropdown-content> binds it.
         */
        menuKeydown: function (event) {
            if (event.key === 'Tab') this._handleTabKey(event);
        },

        /**
         * Upstream cycles Tab inside the panel rather than leaving it — the
         * opposite of tediDropdown's tabOutOfDropdown. Ported as it is.
         */
        _handleTabKey: function (event) {
            var stops = this._tabStops();
            if (!stops.length) return;

            var current = stops.indexOf(document.activeElement);

            if (event.shiftKey && current <= 0) {
                event.preventDefault();
                stops[stops.length - 1].focus();
            } else if (!event.shiftKey && current === stops.length - 1) {
                event.preventDefault();
                stops[0].focus();
            }
        },

        _tabStops: function () {
            var panel = this.$refs.panel;
            if (!panel) return [];

            return Array.prototype.slice.call(panel.querySelectorAll(TAB_STOP_SELECTOR));
        },

        // -- the option list's roving aria-activedescendant ------------

        onOptionsFocus: function () {
            if (this._suppressNextOptionsFocusAutoSelect) {
                this._suppressNextOptionsFocusAutoSelect = false;

                return;
            }

            if (this.activeOptionIndex === -1) {
                this.activeOptionIndex = this._nextEnabledIndex(-1, 1);
            }
        },

        onOptionsBlur: function () {
            this.activeOptionIndex = -1;
            this._suppressNextOptionsFocusAutoSelect = false;
        },

        onOptionsMousedown: function () {
            this._suppressNextOptionsFocusAutoSelect = true;
            this.activeOptionIndex = -1;
        },

        onOptionsKeydown: function (event) {
            var index;

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    index = this._nextEnabledIndex(this.activeOptionIndex, 1);
                    if (index !== -1) this._setActiveOption(index);
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    index = this._nextEnabledIndex(this.activeOptionIndex, -1);
                    if (index !== -1) this._setActiveOption(index);
                    break;

                case 'Home':
                    event.preventDefault();
                    this._setActiveOption(this._nextEnabledIndex(-1, 1));
                    break;

                case 'End':
                    event.preventDefault();
                    this._setActiveOption(this._nextEnabledIndex(this.options.length, -1));
                    break;

                case 'Enter':
                case ' ': {
                    event.preventDefault();
                    var option = this.options[this.activeOptionIndex];

                    if (option && !option.disabled) {
                        if (this.isSingleSelect) {
                            this.selectOption(option.value);
                        } else {
                            this.toggleOption(option.value);
                        }
                    }
                    break;
                }
            }
        },

        /** Skips disabled options AND options the search term filtered out. */
        _nextEnabledIndex: function (from, direction) {
            var index = from + direction;

            while (index >= 0 && index < this.options.length) {
                if (!this.options[index].disabled && this.isOptionVisible(index)) return index;

                index += direction;
            }

            return -1;
        },

        _setActiveOption: function (index) {
            this.activeOptionIndex = index;

            var container = this.$refs.optionsList;
            if (!container || index === -1) return;

            var items = container.querySelectorAll('.tedi-filter-dropdown__item');

            if (items[index]) items[index].scrollIntoView({ block: 'nearest' });
        },
    }));
}
