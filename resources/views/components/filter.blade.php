{{--
    TEDI Filter.
    Port of angular/tedi/components/filter/filter.component.{ts,html}

    Angular's selector is the element `tedi-filter`, but the vendored SCSS has
    no `tedi-filter { … }` ELEMENT rule — every rule keys on the `.tedi-filter`
    class and its `__`/`--` descendants, including the sibling and child rules
    in filter-group.component.scss — so per the batch ruling in CONVENTIONS.md
    §4 the root is a `<div>` carrying the host class, the way toggle,
    search and checkbox-group do.

    Angular's three content-projection directives become named slots
    (CONVENTIONS.md §3): `tediFilterPrepend` -> `prepend`,
    `tediFilterAppend` -> `append`, `tediFilterContent` -> `content`. The
    prepend directive's own `hideWhenSelected` input has nowhere to live on a
    slot, so it is the `hide-prepend-when-selected` prop here, with Angular's
    `?? true` default.

    INTERACTIVITY. One `x-data="tediFilter(…)"` on the root, from
    resources/js/src/filter.js. It composes `tediOverlay` — anchoring, outside
    click, Escape and the focus return are the shared engine (CONVENTIONS.md
    §11) — and adds the filter's own selection state plus its
    `aria-activedescendant` listbox layer. It deliberately does NOT compose
    `tediDropdown`: that is ARIA's roving-tabindex *menu* pattern over
    `li[tedi-dropdown-item]`, which is not the markup this component emits.
    Per CONVENTIONS.md §8 the panel and every option are in the DOM with their
    real class lists while closed, so the static render is correct without JS.

    VALUE BINDING. There is no native form control, so `wire:model` binds
    through `x-modelable="model"` on the root — Livewire's `wire:model` installs
    Alpine's `x-model` on the element, and `x-modelable` hands it the component's
    `model` accessor (verified against the bundled livewire.js: directive order
    is `model` then `modelable`). This is a deliberate departure from the hidden
    `<input>` shape the other form components use: a hidden input can carry a
    string, but Angular's `writeValue()` writes `string[]` for a multi-select
    filter and `boolean` for a plain toggle chip, and neither survives an
    attribute round-trip. Consequences worth knowing: a consumer `class` merges
    onto this root (which is what CONVENTIONS.md §4 wants), and the component
    submits nothing in a plain non-Livewire `<form>` POST.

    KNOWN DIVERGENCES:

    * `tedi-filter-dropdown--custom` and `tedi-filter-dropdown__item--select-all`
      are DROPPED. Angular emits both, but dist/tedi.css ships no rule for
      either (verified: 0 hits), so per CONVENTIONS.md §4 they are omitted.
      Restore them here if TEDI ever ships the rules.

    * `cleared` is an `output()` and is not re-emitted (CONVENTIONS.md §7.2).
      In the custom-content branch the clear button has no state of its own to
      clear, so it carries whatever the consumer passes in `clear-attributes`
      (the same channel search.blade.php uses for its un-ported outputs). In the
      option branches it also clears the selection, as Angular does.

    * Angular delegates `toggle()` to an enclosing FilterGroup's
      ControlValueAccessor. That is not ported (see filter-group.blade.php);
      each grouped filter toggles its own `selected` and carries its own
      `wire:model`. `disabled` and `managed` DO cross the boundary via `@aware`
      — neither is a name this component declares for a different purpose.

    * `group-allow-multiple` is an explicit prop with no Angular counterpart.
      Angular reads the group's mode from `filterGroup.allowMultiple()`, a
      different object from its own `allowMultiple()`, and uses it for one
      thing: a filter in a managed SINGLE-select group is a `role="radio"`,
      while one in a multi-select group keeps `aria-pressed`. `@aware` cannot
      deliver it — this component declares `allowMultiple` itself, and Laravel
      resolves the child's own data before walking up, which is the case
      CONVENTIONS.md §3 bans outright. Collapsing the two onto one variable
      would also merge two meanings Angular deliberately keeps apart ("my
      dropdown is multi-select" vs "my group is multi-select"). So:
      **inside a `tedi:filter-group` with `allow-multiple`, pass
      `group-allow-multiple` on each child filter too.** Omitted, the child
      assumes a single-select group, matching filter-group's own default.

    * The clear button is written out rather than delegated to `tedi:button`,
      the way dropdown-item-value writes out its checkbox: `tedi:button` renders
      an icon-start icon with `color="inherit"`, and Angular's is
      `color="brand"`. Writing it out keeps both the exact class list
      (`--neutral --small --pr`, no `--pl`) and the exact icon.

    * The count badge is written out for the same reason: `tedi:status-badge`
      omits its text `<span>` entirely when `text` is empty, so there would be
      no element for `x-text` to fill once the count changes at runtime.

    * The panel stays in the document instead of being re-parented into a CDK
      overlay container, so a filter inside a `transform`ed ancestor is
      positioned relative to that ancestor (CONVENTIONS.md §11).

    * The button content is written twice — once inside the dropdown trigger,
      once for the plain toggle chip. CONVENTIONS.md §2 forbids opening a
      component tag in one `@if` branch and closing it in another, and this is
      the duplication that rule asks for.
--}}
@props([
    /** Filter label text. */
    'text' => '',
    /** primary|secondary */
    'variant' => 'primary',
    /** default|large */
    'size' => 'default',
    /** Selected state of the boolean toggle chip (used when no options are given). */
    'selected' => false,
    /** Multi-select mode for THIS filter's own dropdown. Only has an effect together with `options`. */
    'allowMultiple' => false,
    /**
     * Mirror of the enclosing `tedi:filter-group`'s `allow-multiple`. Explicit
     * rather than @aware because this component declares `allowMultiple`
     * itself (CONVENTIONS.md §3). Only affects the group ARIA: with `managed`,
     * false makes this filter a `role="radio"`, true keeps `aria-pressed`.
     */
    'groupAllowMultiple' => false,
    /** Dropdown options: a list of ['label' => …, 'value' => …, 'disabled' => bool]. */
    'options' => [],
    /** Selected value (single-select) or array of values (multi-select). */
    'value' => '',
    /** Show the search field in the dropdown. */
    'showSearch' => false,
    /** Whether the dropdown search field has a clear (×) button. */
    'searchClearable' => true,
    /** Clear the search field after an option is selected or toggled. */
    'clearSearchOnSelect' => false,
    /** Show the "Select all" row in a multi-select dropdown. */
    'showSelectAll' => false,
    /** Show the "Clear selection" action at the bottom of the dropdown. */
    'showClear' => false,
    /** Override for the "Select all" label. Defaults to the translated string. */
    'selectAllLabel' => null,
    /** Override for the "Clear selection" label. Defaults to the translated string. */
    'clearLabel' => null,
    /** Keep the label as a prefix when a value is selected: "Teenus: Optometristi vastuvõtt". */
    'preserveLabel' => false,
    /** Whether the filter is disabled. Also inherited from an enclosing filter-group. */
    'disabled' => false,
    /** Angular's tediFilterPrepend `hideWhenSelected`; hides the prepend slot once selected. */
    'hidePrependWhenSelected' => true,
    /** Extra attributes for the dropdown's clear button, e.g. ['wire:click' => 'reset']. */
    'clearAttributes' => [],
])
@aware([
    'disabled' => false,
    'managed' => false,
])

@php
    $normalized = [];

    foreach ($options as $option) {
        $normalized[] = [
            'label' => (string) ($option['label'] ?? ''),
            'value' => (string) ($option['value'] ?? ''),
            'disabled' => (bool) ($option['disabled'] ?? false),
        ];
    }

    $hasOptions = count($normalized) > 0;
    $hasCustomContent = isset($content) && $content->isNotEmpty();
    $hasDropdown = $hasOptions || $hasCustomContent;

    $isMultiSelect = $hasOptions && $allowMultiple;
    $isSingleSelect = $hasOptions && ! $allowMultiple;

    $singleValue = is_string($value) ? $value : '';
    $multiValues = is_array($value) ? array_values($value) : [];

    if ($isMultiSelect) {
        $isSelected = count($multiValues) > 0;
    } elseif ($isSingleSelect) {
        $isSelected = $singleValue !== '';
    } else {
        $isSelected = (bool) $selected;
    }

    $selectedCount = count($multiValues);

    $selectedLabel = null;
    foreach ($normalized as $option) {
        if ($option['value'] === $singleValue && $singleValue !== '') {
            $selectedLabel = $option['label'];
            break;
        }
    }

    if ($isSingleSelect) {
        $displayText = $selectedLabel === null
            ? $text
            : ($preserveLabel ? $text.': '.$selectedLabel : $selectedLabel);
    } else {
        $displayText = $text;
    }

    $hidePrepend = $isSelected && $hidePrependWhenSelected;
    $iconSize = $size === 'large' ? 24 : 18;
    $isGroupedRadio = $managed && ! $groupAllowMultiple;

    $baseId = \Tedi\Livewire\Tedi::id('tedi-filter');
    $resolvedSelectAllLabel = $selectAllLabel ?? __('tedi::tedi.select.select-all');
    $resolvedClearLabel = $clearLabel ?? __('tedi::tedi.filter.clear-selection');

    // The server render has no search term, so "filtered" is the whole list.
    $filteredEnabled = array_values(array_filter($normalized, fn ($o) => ! $o['disabled']));
    $selectedEnabled = count(array_filter($filteredEnabled, fn ($o) => in_array($o['value'], $multiValues, true)));
    $allFilteredSelected = count($filteredEnabled) > 0 && $selectedEnabled === count($filteredEnabled);
    $someFilteredSelected = $selectedEnabled > 0 && $selectedEnabled < count($filteredEnabled);

    $alpine = [
        // tediOverlay config. `offset: -4` because tediOverlay's offset is extra
        // px on top of an 8px base gap, whereas upstream's dropdown replaces the
        // base gap with its own default of 4 (CONVENTIONS.md §11).
        'placement' => 'bottom-start',
        'offset' => -4,
        'matchTriggerWidth' => true,
        // tediFilter config.
        'baseId' => $baseId,
        'text' => $text,
        'options' => $normalized,
        'value' => $isMultiSelect ? $multiValues : $singleValue,
        'selected' => (bool) $selected,
        'allowMultiple' => (bool) $allowMultiple,
        'preserveLabel' => (bool) $preserveLabel,
        'clearSearchOnSelect' => (bool) $clearSearchOnSelect,
        'hidePrependWhenSelected' => (bool) $hidePrependWhenSelected,
    ];
@endphp

<div {{ $attributes->class([
        'tedi-filter',
        'tedi-filter--primary' => $variant === 'primary',
        'tedi-filter--secondary' => $variant === 'secondary',
        'tedi-filter--large' => $size === 'large',
        'tedi-filter--selected' => $isSelected,
        'tedi-filter--disabled' => (bool) $disabled,
    ]) }}
    x-data="tediFilter({{ \Illuminate\Support\Js::from($alpine) }})"
    x-modelable="model"
    x-bind:class="{ 'tedi-filter--selected': isSelected }"
>
    @if ($hasDropdown)
        <tedi-dropdown>
            <tedi:dropdown-trigger aria-haspopup="dialog">
                <button class="tedi-filter__button" type="button" @disabled($disabled)>
                    <div @class([
                        'tedi-filter__prepend',
                        'tedi-filter__prepend--hidden' => $hidePrepend,
                    ]) x-bind:class="{ 'tedi-filter__prepend--hidden': hidePrepend }">{{ $prepend ?? '' }}</div>

                    <span class="tedi-filter__text" x-text="displayText">{{ $displayText }}</span>

                    <div class="tedi-filter__append">{{ $append ?? '' }}</div>

                    @if ($isMultiSelect)
                        <div
                            class="tedi-status-badge tedi-status-badge--color-brand tedi-status-badge--variant-filled tedi-filter__count"
                            @style(['display: none' => ! ($isSelected && $selectedCount > 0)])
                            x-show="isSelected && selectedCount > 0"
                        >
                            <span class="tedi-status-badge__text" x-text="selectedCount">{{ $selectedCount }}</span>
                        </div>
                    @endif

                    <tedi:icon
                        class="tedi-filter__icon"
                        name="arrow_drop_down"
                        variant="filled"
                        :size="$iconSize"
                        color="inherit"
                    />
                </button>
            </tedi:dropdown-trigger>

            <tedi:dropdown-content>
                <x-slot:before>
                    <div class="tedi-filter-dropdown" role="dialog" aria-label="{{ $text }}">
                        @if ($hasCustomContent)
                            <div class="tedi-filter-dropdown__custom-content">{{ $content }}</div>
                        @else
                            @if ($showSearch)
                                <div class="tedi-filter-dropdown__search">
                                    {{-- x-effect rides along with x-ref on the clear button itself:
                                         on the wrapper it would run before the ref existed and,
                                         having read nothing reactive, never run again. --}}
                                    <tedi:form-field
                                        icon="search"
                                        :clearable="$searchClearable"
                                        :clear-attributes="[
                                            'x-ref' => 'searchClear',
                                            'x-effect' => 'syncSearchClear()',
                                            'x-on:click' => 'onSearchClear()',
                                        ]"
                                    >
                                        <tedi:text-field
                                            type="text"
                                            role="searchbox"
                                            :aria-label="$text"
                                            x-model="searchTerm"
                                        />
                                    </tedi:form-field>
                                </div>
                                <tedi:separator />
                            @endif

                            @if ($isMultiSelect && $showSelectAll)
                                <div
                                    class="tedi-filter-dropdown__item"
                                    role="checkbox"
                                    aria-checked="{{ $allFilteredSelected ? 'true' : ($someFilteredSelected ? 'mixed' : 'false') }}"
                                    tabindex="0"
                                    x-show="filteredOptions.length > 0"
                                    x-effect="syncSelectAllIndicator($el)"
                                    x-on:click="toggleSelectAll()"
                                    x-on:keydown.enter="toggleSelectAll()"
                                    x-on:keydown.space.prevent="toggleSelectAll()"
                                    x-bind:aria-checked="allFilteredSelected ? 'true' : (someFilteredSelected ? 'mixed' : 'false')"
                                >
                                    <tedi:dropdown-item-value
                                        type="checkbox"
                                        :selected="$allFilteredSelected"
                                        :indeterminate="$someFilteredSelected"
                                    >
                                        <tedi:dropdown-item-value-label>{{ $resolvedSelectAllLabel }}</tedi:dropdown-item-value-label>
                                    </tedi:dropdown-item-value>
                                </div>
                                <tedi:separator x-show="filteredOptions.length > 0" />
                            @endif

                            <div
                                class="tedi-filter-dropdown__options"
                                role="listbox"
                                @if ($isMultiSelect) aria-multiselectable="true" @endif
                                aria-label="{{ $text }}"
                                tabindex="0"
                                x-ref="optionsList"
                                x-on:focus="onOptionsFocus()"
                                x-on:blur="onOptionsBlur()"
                                x-on:mousedown="onOptionsMousedown()"
                                x-on:keydown="onOptionsKeydown($event)"
                                x-bind:aria-activedescendant="activeDescendantId"
                            >
                                @foreach ($normalized as $i => $option)
                                    @php
                                        $optionSelected = $isSingleSelect
                                            ? $option['value'] === $singleValue
                                            : in_array($option['value'], $multiValues, true);
                                        $optionJs = \Illuminate\Support\Js::from($option['value']);
                                    @endphp
                                    <div
                                        @class([
                                            'tedi-filter-dropdown__item',
                                            'tedi-filter-dropdown__item--disabled' => $option['disabled'],
                                            'tedi-filter-dropdown__item--selected' => $isSingleSelect && $optionSelected,
                                        ])
                                        role="option"
                                        aria-selected="{{ $optionSelected ? 'true' : 'false' }}"
                                        @if ($option['disabled']) aria-disabled="true" @endif
                                        id="{{ $baseId }}-option-{{ $i }}"
                                        x-show="isOptionVisible({{ $i }})"
                                        x-effect="syncOptionIndicator($el, {{ $i }})"
                                        @if (! $option['disabled'])
                                            x-on:click="{{ $isSingleSelect ? 'selectOption' : 'toggleOption' }}({{ $optionJs }})"
                                        @endif
                                        x-bind:class="{
                                            'tedi-filter-dropdown__item--focused': activeOptionIndex === {{ $i }},
                                            'tedi-filter-dropdown__item--selected': {{ $isSingleSelect ? 'isOptionSelected('.$optionJs.')' : 'false' }},
                                        }"
                                        x-bind:aria-selected="isOptionSelected({{ $optionJs }}) ? 'true' : 'false'"
                                    >
                                        <tedi:dropdown-item-value
                                            :type="$isMultiSelect ? 'checkbox' : 'default'"
                                            :selected="$optionSelected"
                                            :disabled="$option['disabled']"
                                        >
                                            <tedi:dropdown-item-value-label>{{ $option['label'] }}</tedi:dropdown-item-value-label>
                                        </tedi:dropdown-item-value>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($showClear)
                            <tedi:separator />
                            <div class="tedi-filter-dropdown__clear">
                                <button
                                    type="button"
                                    @class([
                                        'tedi-button',
                                        'tedi-button--neutral',
                                        'tedi-button--small',
                                        'tedi-button--pr',
                                    ])
                                    @if (! $hasCustomContent)
                                        x-on:click="{{ $isSingleSelect ? 'clearSingleSelection()' : 'clearSelection()' }}"
                                    @endif
                                    {{ $attributes->only([])->merge($clearAttributes) }}
                                >
                                    <tedi:icon name="refresh" :size="18" color="brand" />
                                    <span>{{ $resolvedClearLabel }}</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </x-slot:before>
            </tedi:dropdown-content>
        </tedi-dropdown>
    @else
        <button
            class="tedi-filter__button"
            type="button"
            @disabled($disabled)
            @if ($isGroupedRadio)
                role="radio"
                aria-checked="{{ $isSelected ? 'true' : 'false' }}"
                x-bind:aria-checked="isSelected ? 'true' : 'false'"
            @else
                aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                x-bind:aria-pressed="isSelected ? 'true' : 'false'"
            @endif
            x-on:click="toggleSelected()"
        >
            <tedi:icon
                class="tedi-filter__icon"
                name="check"
                :size="$iconSize"
                color="inherit"
                {{-- x-cloak, not an inline `display: none`: tedi:icon emits its own
                     `style`, so a second one would be a duplicate attribute. The
                     [x-cloak] rule in resources/scss/_alpine.scss hides the icon
                     until Alpine boots and x-show takes over — and keeps it
                     hidden for good when there is no JS, which is the correct
                     static render for an unselected chip. --}}
                :x-cloak="! $isSelected"
                x-show="isSelected"
            />

            <div @class([
                'tedi-filter__prepend',
                'tedi-filter__prepend--hidden' => $hidePrepend,
            ]) x-bind:class="{ 'tedi-filter__prepend--hidden': hidePrepend }">{{ $prepend ?? '' }}</div>

            <span class="tedi-filter__text" x-text="displayText">{{ $displayText }}</span>

            <div class="tedi-filter__append">{{ $append ?? '' }}</div>
        </button>
    @endif
</div>
