{{--
    TEDI Multiselect (community).
    Port of angular/community/components/form/select/multiselect.component.{ts,html}

    The one component in Angular's `community/` entry point that is neither a
    duplicate of a `tedi/` component nor superseded by one: `tedi/`'s select is
    a different component, and this package ports THAT as a native-<select>
    subset (see select.blade.php and CONVENTIONS.md §7 item 4). So a
    tag-rendering multi-select combobox has no other home here.

    It is buildable now for the reason CONVENTIONS.md §11 gives: overlay
    positioning is no longer out of scope. The panel is anchored by this
    package's own engine rather than CDK Overlay, so it stays in the document
    where it was written — a multiselect inside a `transform`ed ancestor is
    positioned relative to that ancestor (§11).

    ROOT ELEMENT. Angular's selector is the element `tedi-multiselect`, but the
    vendored SCSS has no `tedi-multiselect { … }` element rule — every rule
    keys on `.tedi-select` and its descendants — so per the batch ruling in
    CONVENTIONS.md §4 the root is a `<div>` carrying the host classes, the way
    `select`, `filter` and `search` do.

    INTERACTIVITY. One `x-data="tediMultiselect(…)"` on the root, from
    resources/js/src/multiselect.js. It composes `tediOverlay` and adds the
    selection state plus the `aria-activedescendant` listbox layer that
    upstream gets from `cdkListbox` (whose `useActiveDescendant` default is
    what that layer reproduces). Per CONVENTIONS.md §8 every row — and every
    selected-value tag — is in the DOM with its real class list while closed,
    so the static render is correct without JS.

    VALUE BINDING. There is no native form control, so `wire:model` binds
    through `x-modelable="model"` on the root, exactly as `filter` does and for
    the same reason: upstream's `writeValue()` takes `string[]`, which does not
    survive a hidden input's attribute round-trip. Consequences: a consumer
    `class` merges onto this root, and the component submits nothing in a plain
    non-Livewire `<form>` POST.

    KNOWN DIVERGENCES:

    * The option rows use `tedi:dropdown-item-value` rather than upstream's
      community `tedi-checkbox` element. That component's markup
      (`tedi-checkbox__input-area` / `__indicator` / `__label`) is styled only
      by `community/.../checkbox.component.scss`, which is NOT vendored here —
      the vendored checkbox is `tedi/`'s attribute-based
      `input[tedi-checkbox]`. `dropdown-item-value` is the port's existing
      checkbox-inside-a-dropdown-row idiom (filter uses it too), it emits only
      classes the vendored SCSS defines, and its `pointer-events: none` on
      `__checkbox` reproduces exactly what upstream's
      `tedi-select__multiselect-checkbox` rule was for. Consequently
      `tedi-select__multiselect-checkbox` and `tedi-select__group-checkbox` are
      NOT emitted — the first is styled only in the un-vendored community
      stylesheet, the second is styled nowhere at all (CONVENTIONS.md §4).

    * `tedi-card--spacing-none` is DROPPED. Upstream wraps the panel in the
      deprecated community `tedi-card` with `spacing="none"`; that stylesheet
      is not vendored and nothing styles the class. The panel composes this
      package's `tedi:card` / `tedi:card-content` with zero padding instead,
      which is the same rendered result through classes that do have rules.

    * `dropdownWidthRef` was an `ElementRef`, which cannot cross into Blade
      (CONVENTIONS.md §5). It becomes `dropdown-width`: `trigger` (upstream's
      default — measure the host) or `auto` (upstream's explicit `null`).

    * Per-option custom content (upstream's `tedi-select-option` with a
      projected template, which drives `tedi-select__dropdown-item--custom-content`
      / `--label`) is not ported: options are a data prop here, per
      CONVENTIONS.md §5, and a data prop has nowhere to carry markup.

    * The trigger's tags render in OPTIONS order, not selection order.
      CONVENTIONS.md §8 requires every tag to exist in the static render with
      its real class list, so one tag per option is rendered and shown or
      hidden — there is no `x-for` to re-order. The bound value keeps selection
      order, as upstream's does.

    * Typeahead is NOT ported. `cdkListbox` ships it (typing "Ta" moves the
      active option to Tallinn); the keyboard layer here is arrows, Home, End,
      Enter and Space. `filter` never had this gap to record because upstream's
      filter is not a cdkListbox.

    * The clear button is written out rather than delegated to
      `tedi:closing-button`, the way `filter` writes out its own: an echoed
      attribute bag inside a component tag's attribute list
      is not parsed by Blade's component-tag compiler, and `clear-attributes`
      has to reach that button. The class list and icon are identical.

    * `output()`s are not re-emitted (CONVENTIONS.md §7.2). Bind `wire:model`
      for the value; the clear button takes `clear-attributes` for anything
      else, the channel `search` and `filter` already use.
--}}
@props([
    /** Id for the trigger, also used for the label's `for`. Auto-generated when omitted. */
    'inputId' => null,
    /** Label text rendered above the control. */
    'label' => null,
    /** Shows the label's required marker. */
    'required' => false,
    /** Shown in the trigger when nothing is selected. */
    'placeholder' => '',
    /** default|valid|error */
    'state' => 'default',
    /** small|default */
    'size' => 'default',
    /** Let the selected tags wrap onto several rows instead of clipping to one. */
    'multiRow' => false,
    /** Give each selected tag a close button. */
    'clearableTags' => false,
    /** Show the "select all" row above the options. */
    'selectAll' => false,
    /** Make each group heading a selectable row that toggles its whole group. */
    'selectableGroups' => false,
    /** Show the trigger's clear (×) button once something is selected. */
    'clearable' => true,
    /** trigger|auto — panel width follows the trigger, or fits its content. */
    'dropdownWidth' => 'trigger',
    /**
     * Options to render. Accepts a flat value => label map, or a list of
     * ['value' => …, 'label' => …, 'disabled' => bool, 'group' => string].
     */
    'options' => [],
    /** Selected values. */
    'value' => [],
    'disabled' => false,
    /** ['text' => …, 'type' => 'hint', 'position' => 'left'] */
    'feedbackText' => null,
    /** Extra attributes forwarded to the clear button (e.g. wire:click). */
    'clearAttributes' => [],
])

@aware([
    'disabled' => false,
    'invalid' => false,
])

@php
    // Unconditional: the trigger id, the label's `for` and every row id derive
    // from it, so it must not depend on a branch being taken.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-multiselect');
    $listboxId = $inputId.'-listbox';
    $labelId = $inputId.'-label';

    $state = $invalid ? 'error' : $state;

    $normalizedOptions = [];
    foreach ($options as $key => $option) {
        if (is_array($option)) {
            $normalizedOptions[] = [
                'value' => (string) ($option['value'] ?? $key),
                'label' => (string) ($option['label'] ?? $key),
                'disabled' => (bool) ($option['disabled'] ?? false),
                'group' => (string) ($option['group'] ?? ''),
            ];
        } else {
            $normalizedOptions[] = [
                'value' => (string) (is_int($key) ? $option : $key),
                'label' => (string) $option,
                'disabled' => false,
                'group' => '',
            ];
        }
    }

    // optionGroups(): first-appearance order, ungrouped options under ''.
    $groups = [];
    foreach ($normalizedOptions as $option) {
        $groups[$option['group']][] = $option;
    }

    // Two lists, deliberately: $renderRows drives the markup and includes the
    // presentation-only group headings; $rows is what the listbox navigates and
    // therefore excludes them, exactly as upstream leaves them out of cdkListbox.
    $renderRows = [];
    $rows = [];

    $pushRow = function (array $row) use (&$renderRows, &$rows) {
        $row['index'] = count($rows);
        $renderRows[] = $row;
        $rows[] = ['kind' => $row['kind'], 'disabled' => $row['disabled']]
            + array_intersect_key($row, array_flip(['value', 'label', 'group']));
    };

    if ($normalizedOptions !== [] && $selectAll) {
        $pushRow(['kind' => 'select-all', 'disabled' => false]);
    }

    foreach ($groups as $groupLabel => $groupOptions) {
        if ($groupLabel !== '') {
            if ($selectableGroups) {
                $pushRow(['kind' => 'group', 'group' => $groupLabel, 'disabled' => false]);
            } else {
                $renderRows[] = ['kind' => 'group-heading', 'group' => $groupLabel, 'index' => null];
            }
        }

        foreach ($groupOptions as $option) {
            $pushRow($option + ['kind' => 'option']);
        }
    }

    $selected = array_values(array_map('strval', (array) $value));
    $selectedSet = array_flip($selected);
    $hasSelection = $selected !== [];

    $allOptionValues = array_values(array_map(
        fn ($option) => $option['value'],
        array_filter($normalizedOptions, fn ($option) => ! $option['disabled'])
    ));
    $allSelected = $allOptionValues !== []
        && array_diff($allOptionValues, $selected) === [];

    // `tedi:dropdown-item-value` renders its checkbox itself and puts the
    // attribute bag on its own root, so the indicator cannot be bound from the
    // template — the same bind filter.js does from syncOptionIndicator().
    // Server-side it is already correct; this only matters once Alpine takes over.
    $checkboxEffect = fn (string $expression): string =>
        "\$el.querySelector('.tedi-dropdown-item-value__checkbox').checked = ".$expression;

    // Built here rather than inline: a `new \Foo(…)` echo inside a component
    // tag's attribute list is not parsed by Blade's component-tag compiler,
    // which leaves the whole <x-tedi::…> tag in the output verbatim.
    $clearBag = \Tedi\Livewire\Tedi::consumerAttributes($clearAttributes);

    $groupSelected = function (string $group) use ($groups, $selected): bool {
        $members = array_values(array_map(
            fn ($option) => $option['value'],
            array_filter($groups[$group] ?? [], fn ($option) => ! $option['disabled'])
        ));

        return $members !== [] && array_diff($members, $selected) === [];
    };
@endphp

<div
    x-data="tediMultiselect({
        placement: 'bottom-start',
        matchTriggerWidth: false,
        baseId: @js($inputId),
        rows: @js($rows),
        value: @js($selected),
        disabled: @js((bool) $disabled),
        dropdownWidth: @js($dropdownWidth),
    })"
    x-modelable="model"
    x-on:resize.window="syncPanelWidth()"
    {{ $attributes->class([
        'tedi-select',
        'tedi-select--multiselect',
    ]) }}
>
    @if ($label)
        <tedi:label-row>
            <tedi:form.label
                :id="$labelId"
                :for="$inputId"
                :required="$required"
                :size="$size === 'small' ? 'small' : 'default'"
            >
                {{ $label }}
            </tedi:form.label>
        </tedi:label-row>
    @endif

    <div
        id="{{ $inputId }}"
        role="combobox"
        aria-haspopup="listbox"
        aria-controls="{{ $listboxId }}"
        @if ($label) aria-labelledby="{{ $labelId }}" @endif
        aria-expanded="false"
        tabindex="{{ $disabled ? '-1' : '0' }}"
        @class([
            'tedi-select__trigger',
            'tedi-input',
            'tedi-input--disabled' => $disabled,
            'tedi-input--small' => $size === 'small',
            'tedi-input--error' => $state === 'error',
            'tedi-input--valid' => $state === 'valid',
        ])
        x-ref="trigger"
        x-on:click="toggleOpen()"
        x-on:keydown.enter.prevent="toggleOpen()"
        x-on:keydown.space.prevent="toggleOpen()"
        x-on:keydown.arrow-down.prevent="disabled || show()"
        x-bind:aria-expanded="open"
    >
        <span class="tedi-select__label">
            <div
                @class([
                    'tedi-select__multiselect-container',
                    'tedi-select__multiselect-container--single-row' => ! $multiRow,
                ])
                @style(['display: none' => ! $hasSelection])
                x-show="hasSelection"
            >
                {{-- One tag per OPTION, shown or hidden — see the header note on
                     tag ordering and CONVENTIONS.md §8. --}}
                @foreach ($normalizedOptions as $option)
                    <tedi:tag
                        :closable="$clearableTags"
                        :close-attributes="[
                            'x-on:click' => 'deselect($event, '.json_encode($option['value']).')',
                        ]"
                        @style(['display: none' => ! isset($selectedSet[$option['value']])])
                        :x-show="'isOptionSelected('.json_encode($option['value']).')'"
                    >{{ $option['label'] }}</tedi:tag>
                @endforeach
            </div>

            <span
                class="tedi-select__label--placeholder"
                @style(['display: none' => $hasSelection])
                x-show="! hasSelection"
            >{{ $placeholder }}</span>
        </span>

        @if ($clearable)
            {{-- Written out rather than delegated to <tedi:closing-button>: a
                 bare attribute-bag echo inside a component tag's attribute list is
                 not parsed by Blade's component-tag compiler (the whole tag
                 survives into the output verbatim), and `clear-attributes` has
                 to reach this button. Same class list, same icon. --}}
            <button
                type="button"
                aria-label="{{ __('tedi::tedi.close') }}"
                title="{{ __('tedi::tedi.close') }}"
                class="tedi-closing-button tedi-closing-button--small tedi-select__clear"
                @style(['display: none' => ! $hasSelection])
                x-show="hasSelection"
                x-on:click="clear($event)"
                {{ $clearBag }}
            >
                <tedi:icon name="close" :size="18" aria-hidden="true" />
            </button>
        @endif

        <tedi:icon class="tedi-select__arrow" name="arrow_drop_down" variant="filled" color="inherit" />
    </div>

    @if ($feedbackText)
        <tedi:feedback-text
            :text="$feedbackText['text']"
            :type="$feedbackText['type'] ?? 'hint'"
            :position="$feedbackText['position'] ?? 'left'"
        />
    @endif

    <tedi:card
        class="tedi-select__dropdown"
        x-ref="panel"
        x-show="open"
        x-cloak
        x-bind:data-placement="side"
    >
        <tedi:card-content :padding="0">
            <ul
                id="{{ $listboxId }}"
                class="tedi-select__options"
                role="listbox"
                aria-multiselectable="true"
                tabindex="0"
                @if ($label) aria-labelledby="{{ $labelId }}" @endif
                x-ref="optionsList"
                x-on:focus="onListFocus()"
                x-on:blur="onListBlur()"
                x-on:keydown="onListKeydown($event)"
                x-bind:aria-activedescendant="activeDescendantId"
            >
                @forelse ($renderRows as $row)
                    @if ($row['kind'] === 'select-all')
                        <li
                            tedi-dropdown-item
                            id="{{ $inputId }}-row-{{ $row['index'] }}"
                            role="option"
                            aria-selected="{{ $allSelected ? 'true' : 'false' }}"
                            x-bind:aria-selected="allOptionsSelected"
                            x-on:click="activate({{ $row['index'] }})"
                        >
                            <tedi:dropdown-item-value type="checkbox" :selected="$allSelected"
                                :x-effect="$checkboxEffect('allOptionsSelected')">
                                {{ __('tedi::tedi.select.select-all') }}
                            </tedi:dropdown-item-value>
                        </li>
                    @elseif ($row['kind'] === 'group')
                        <li
                            tedi-dropdown-item
                            id="{{ $inputId }}-row-{{ $row['index'] }}"
                            class="tedi-select__group-name tedi-select__group-name--selectable"
                            role="option"
                            aria-selected="{{ $groupSelected($row['group']) ? 'true' : 'false' }}"
                            x-bind:aria-selected="isGroupSelected(@js($row['group']))"
                            x-on:click="activate({{ $row['index'] }})"
                        >
                            <tedi:dropdown-item-value type="checkbox" :selected="$groupSelected($row['group'])"
                                :x-effect="$checkboxEffect('isGroupSelected('.json_encode($row['group']).')')">
                                <tedi:text color="tertiary">{{ $row['group'] }}</tedi:text>
                            </tedi:dropdown-item-value>
                        </li>
                    @elseif ($row['kind'] === 'group-heading')
                        <li tedi-dropdown-item class="tedi-select__group-name" role="presentation">
                            <tedi:text color="tertiary">{{ $row['group'] }}</tedi:text>
                        </li>
                    @else
                        <li
                            tedi-dropdown-item
                            id="{{ $inputId }}-row-{{ $row['index'] }}"
                            role="option"
                            aria-selected="{{ isset($selectedSet[$row['value']]) ? 'true' : 'false' }}"
                            @if ($row['disabled']) aria-disabled="true" @endif
                            x-bind:aria-selected="isOptionSelected(@js($row['value']))"
                            x-on:click="activate({{ $row['index'] }})"
                        >
                            <tedi:dropdown-item-value
                                type="checkbox"
                                :selected="isset($selectedSet[$row['value']])"
                                :disabled="$row['disabled']"
                                :x-effect="$checkboxEffect('isOptionSelected('.json_encode($row['value']).')')"
                            >
                                {{ $row['label'] }}
                            </tedi:dropdown-item-value>
                        </li>
                    @endif
                @empty
                    <li tedi-dropdown-item class="tedi-select__no-options" role="presentation">
                        {{ __('tedi::tedi.select.no-options') }}
                    </li>
                @endforelse
            </ul>
        </tedi:card-content>
    </tedi:card>
</div>
