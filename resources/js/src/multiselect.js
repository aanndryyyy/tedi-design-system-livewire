import { overlay } from './overlay.js';

// =========================================================================
// Multiselect — selection state + the listbox keyboard layer
// =========================================================================
//
// A port of the interactive half of
// `community/components/form/select/multiselect.component.ts`.
//
// Like `filter()` it composes `overlay()` rather than `dropdown()`: upstream's
// panel is a `cdkListbox`, and CDK's listbox defaults to
// `useActiveDescendant`, which is a `tabindex="0"` container with
// `aria-activedescendant` roving over its `[role="option"]` children — not the
// roving-tabindex MENU pattern `dropdown()` implements. Anchoring,
// outside-click dismissal, Escape and the trigger focus return all come from
// `overlay()` unchanged (CONVENTIONS.md §11).
//
// Names that had to change, because `overlay()` already owns them:
//
//   Angular `toggleIsOpen()`  ->  `toggle()` / `show()` / `hide()`, the
//                                 overlay's own open-close API.
//
// Upstream's `handleValueChange()` demultiplexes one `cdkListboxValueChange`
// into three actions by sniffing magic values out of the emitted array
// (`SELECT_ALL`, `SELECTGROUP_<label>`). There is no cdkListbox here, so the
// magic values have nothing to travel through: `activate(index)` dispatches on
// the row's own `kind` instead. Same three actions, no sentinel strings.
//
// ROW MODEL. Blade renders the panel once, server-side, and hands the same row
// list here as `rows` — one entry per rendered `li`, in DOM order:
//
//   { kind: 'select-all' }
//   { kind: 'group', group: 'Tallinn' }        // only when selectableGroups
//   { kind: 'option', value, label, group, disabled }
//
// Unselectable group headings are `role="presentation"` and are NOT in `rows`,
// exactly as upstream leaves them out of the listbox. Arrow navigation moves
// over `rows` and skips disabled entries, so which row the keyboard lands on
// (and which id `aria-activedescendant` announces) matches upstream.
//
// SELECTION ORDER. `value` keeps selection order, as upstream's cdkListbox
// value does. The trigger's tags are rendered in OPTIONS order instead — every
// option has a tag in the DOM from the first paint, shown or hidden by
// `isOptionSelected()`, because CONVENTIONS.md §8 requires the static render to
// carry the real class list rather than conjuring elements from JS.
//
// `model` is a getter/setter pair rather than a plain field: it is the target
// of `x-modelable` on the root element, which is how `wire:model` binds a
// `string[]` here. See multiselect.blade.php's header for why there is no
// hidden native control to bind instead.

/**
 * `tediMultiselect` — `tediOverlay` plus the multiselect's selection state and
 * its `aria-activedescendant` listbox keyboard layer.
 *
 * Takes every `tediOverlay` config key, and adds:
 *   baseId        string — prefix for the per-row ids
 *   rows          the row model documented above, in DOM order
 *   value         string[] — the initially selected values
 *   disabled      boolean
 *   dropdownWidth 'trigger' | 'auto'
 *
 * Markup contract, on top of tediOverlay's trigger/panel refs:
 *   x-ref="optionsList"  the [role="listbox"] element
 *   rows are             li[tedi-dropdown-item], in `rows` order
 */
export function multiselect(config) {
    var cfg = config || {};
    var base = overlay(cfg);
    var baseOnOpen = base._onOpen;

    // defineProperties over the descriptors, not Object.assign — see the same
    // note in filter.js: Object.assign would read every getter below once and
    // copy the resulting value, freezing it at its initial state.
    return Object.defineProperties(base, Object.getOwnPropertyDescriptors({
        rows: cfg.rows || [],
        value: Array.isArray(cfg.value) ? cfg.value.slice() : [],
        disabled: !!cfg.disabled,
        activeRowIndex: -1,

        // -- derived state (upstream's computed()s) --------------------

        /** Upstream's options(), minus the group headings. */
        get optionRows() {
            return this.rows.filter(function (row) { return row.kind === 'option'; });
        },

        /** Upstream's allOptions(): every ENABLED option's value. */
        get allOptionValues() {
            return this.optionRows
                .filter(function (row) { return !row.disabled; })
                .map(function (row) { return row.value; });
        },

        get allOptionsSelected() {
            var vals = this.value;
            var all = this.allOptionValues;

            // Upstream compares lengths against selectedOptions(); comparing
            // membership instead is equivalent and survives a consumer writing
            // a value that is not in the option list.
            return all.length > 0 && all.every(function (v) { return vals.indexOf(v) !== -1; });
        },

        get hasSelection() {
            return this.value.length > 0;
        },

        get activeDescendantId() {
            if (this.activeRowIndex === -1) return null;

            return this.rowId(this.activeRowIndex);
        },

        /** Upstream's writeValue(), exposed to x-modelable / wire:model. */
        get model() {
            return this.value;
        },

        set model(incoming) {
            this.value = Array.isArray(incoming) ? incoming.slice() : [];
        },

        // -- per-row helpers used by the template ----------------------

        rowId: function (index) {
            return cfg.baseId + '-row-' + index;
        },

        isOptionSelected: function (value) {
            return this.value.indexOf(value) !== -1;
        },

        /** Upstream's getLabel(). */
        getLabel: function (value) {
            var row = this.optionRows.find(function (r) { return r.value === value; });

            return row ? row.label : undefined;
        },

        /** Upstream's isGroupSelected(): every ENABLED option in the group. */
        isGroupSelected: function (group) {
            var vals = this.value;
            var members = this._groupValues(group);

            return members.length > 0 && members.every(function (v) { return vals.indexOf(v) !== -1; });
        },

        _groupValues: function (group) {
            return this.optionRows
                .filter(function (row) { return row.group === group && !row.disabled; })
                .map(function (row) { return row.value; });
        },

        // -- selection ------------------------------------------------

        /**
         * The one entry point for activating a row, by mouse or by keyboard.
         * Upstream's handleValueChange() dispatches on sentinel values in the
         * emitted array; this dispatches on the row's `kind` (see the header).
         */
        activate: function (index) {
            var row = this.rows[index];
            if (!row || row.disabled || this.disabled) return;

            if (row.kind === 'select-all') {
                this.toggleSelectAll();
            } else if (row.kind === 'group') {
                this.toggleGroupSelection(row.group);
            } else {
                this.toggleOption(row.value);
            }
        },

        toggleOption: function (value) {
            var current = this.value;

            this.value = current.indexOf(value) !== -1
                ? current.filter(function (v) { return v !== value; })
                : current.concat([value]);
        },

        /** Upstream's toggleSelectAll(). */
        toggleSelectAll: function () {
            this.value = this.allOptionsSelected ? [] : this.allOptionValues;
        },

        /** Upstream's toggleGroupSelection(). */
        toggleGroupSelection: function (group) {
            var members = this._groupValues(group);
            if (!members.length) return;

            if (this.isGroupSelected(group)) {
                this.value = this.value.filter(function (v) { return members.indexOf(v) === -1; });

                return;
            }

            var next = this.value.slice();

            members.forEach(function (v) {
                if (next.indexOf(v) === -1) next.push(v);
            });

            this.value = next;
        },

        /** Upstream's deselect() — the tag's own close button. */
        deselect: function (event, value) {
            event.stopPropagation();
            event.preventDefault();

            if (this.disabled) return;

            this.value = this.value.filter(function (v) { return v !== value; });
        },

        /** Upstream's clear() — the trigger's clear button. */
        clear: function (event) {
            event.preventDefault();
            event.stopPropagation();

            this.value = [];
        },

        // -- open/close ------------------------------------------------

        /**
         * Upstream guards toggleIsOpen() on disabled(); overlay() has no such
         * notion, so the guard lives here and covers every open path.
         */
        toggleOpen: function () {
            if (this.disabled) return;

            this.toggle();
        },

        _onOpen: function () {
            var self = this;

            baseOnOpen.call(this);

            // Upstream's focusListboxWhenVisible effect focuses the listbox as
            // soon as the overlay renders it; $nextTick is the Alpine
            // equivalent of waiting for that render.
            this.$nextTick(function () {
                self.syncPanelWidth();

                var list = self.$refs.optionsList;
                if (list) list.focus();
            });
        },

        /**
         * Upstream's setDropdownWidth() + its `window:resize` HostListener.
         * `dropdownWidthRef` was an ElementRef, which cannot cross into Blade
         * (CONVENTIONS.md §5), so the prop is 'trigger' (upstream's default —
         * the host's own width) or 'auto' (upstream's explicit `null`).
         */
        syncPanelWidth: function () {
            var panel = this.$refs.panel;
            if (!panel) return;

            if (cfg.dropdownWidth === 'auto') {
                panel.style.width = 'auto';

                return;
            }

            var anchor = this.$refs.trigger;
            var width = anchor ? anchor.getBoundingClientRect().width : 0;

            panel.style.width = width ? width + 'px' : 'auto';
        },

        // -- the listbox's roving aria-activedescendant -----------------

        onListFocus: function () {
            if (this.activeRowIndex === -1) {
                this.activeRowIndex = this._nextEnabledIndex(-1, 1);
            }
        },

        onListBlur: function () {
            this.activeRowIndex = -1;
        },

        onListKeydown: function (event) {
            var index;

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    index = this._nextEnabledIndex(this.activeRowIndex, 1);
                    if (index !== -1) this._setActiveRow(index);
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    index = this._nextEnabledIndex(this.activeRowIndex, -1);
                    if (index !== -1) this._setActiveRow(index);
                    break;

                case 'Home':
                    event.preventDefault();
                    this._setActiveRow(this._nextEnabledIndex(-1, 1));
                    break;

                case 'End':
                    event.preventDefault();
                    this._setActiveRow(this._nextEnabledIndex(this.rows.length, -1));
                    break;

                case 'Enter':
                case ' ':
                    event.preventDefault();
                    this.activate(this.activeRowIndex);
                    break;
            }
        },

        /**
         * Arrow keys do NOT wrap at the ends — upstream's behaviour, and the
         * same ruling dropdown.js took (CONVENTIONS.md §11).
         */
        _nextEnabledIndex: function (from, direction) {
            var index = from + direction;

            while (index >= 0 && index < this.rows.length) {
                if (!this.rows[index].disabled) return index;

                index += direction;
            }

            return -1;
        },

        _setActiveRow: function (index) {
            this.activeRowIndex = index;

            var container = this.$refs.optionsList;
            if (!container || index === -1) return;

            var items = container.querySelectorAll('li[tedi-dropdown-item]');

            if (items[index]) items[index].scrollIntoView({ block: 'nearest' });
        },
    }));
}
