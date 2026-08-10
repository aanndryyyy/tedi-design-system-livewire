{{--
    TEDI Multi Value Field — DOCUMENTED SUBSET.
    Port of react/src/tedi/components/form/multi-value-field/multi-value-field.tsx
    (CONVENTIONS.md §13).

    A read-only field that displays a set of chosen values as removable tags,
    with an optional clear control and trailing icon. It is the value *display*
    half of a picker — upstream pairs it with a popover that does the choosing —
    and it submits through a hidden input carrying the values as JSON.

    THE ROOT CLASS IS DROPPED. Upstream's outer div is
    `styles['tedi-multi-value-field']`, but the stylesheet defines no such rule
    (only `__inner`, `__tags`, `__right-area`, `__icon-wrapper`,
    `__overflow-tag` and the two `--row` modifiers). Per CONVENTIONS.md §4 a
    class with no rule is not emitted; the same goes for upstream's
    `__clear-button`. Restore them if TEDI ships the rules.

    NOT PORTED — the row-overflow measurement. In `tags-direction="row"`
    upstream measures each rendered tag with a ResizeObserver, works out how
    many fit alongside a 44px `+N` counter, and hides the rest. That is a
    runtime measurement with no server-side analogue (CONVENTIONS.md §13.5), so
    the count becomes the explicit `visible-count` prop: pass it and the
    component renders that many tags plus the counter; leave it null and every
    tag renders, which is exactly what upstream shows before its first
    measurement lands.

    Upstream's `onChange` and per-tag removal callbacks are not re-emitted
    (CONVENTIONS.md §7 item 2) — bind `wire:click` on the tag's close button
    through `close-attributes`, or handle it on the element itself.

    The hidden input reproduces upstream's serialisation exactly, including its
    empty case: `JSON.stringify(values)` when there are values, and an empty
    string — not `[]` — when there are none.

    `{{ $attributes }}` goes on the root, not on the hidden input: the input is
    a submission detail, and `wire:model` on this component would have nothing
    to bind to (it never takes user input). Drive the values from the server.
--}}
@props([
    /** Id used for the label's `for`. Auto-generated when omitted. */
    'id' => null,
    /** Label rendered above the field. */
    'label' => null,
    /** Name of the hidden input. Omit to render no input at all. */
    'name' => null,
    /** The values, as strings. */
    'values' => [],
    /** primary|secondary|danger — tag colour. */
    'tagColor' => 'primary',
    /** stack: tags wrap. row: one row plus a +N counter. */
    'tagsDirection' => 'stack',
    /** Material Symbols name for the trailing icon. */
    'icon' => null,
    /** Renders the icon as a <button>. Attributes for it go in `icon-button-attributes`. */
    'iconIsButton' => false,
    /** Extra attributes for the icon button, e.g. ['aria-expanded' => 'false']. */
    'iconButtonAttributes' => [],
    /** Show the clear control when there are values. */
    'isClearable' => true,
    /** Marks the label required and the hidden input required. */
    'required' => false,
    /** Disables removal and the clear control. */
    'disabled' => false,
    /** row mode: how many tags fit on one row. Null renders them all. */
    'visibleCount' => null,
])

@php
    $id = $id ?? \Tedi\Livewire\Tedi::id('tedi-multi-value-field');
    $values = array_values($values);
    $isRow = $tagsDirection === 'row';

    // hiddenCount — upstream only counts when a measurement exists.
    $visible = ($isRow && $visibleCount !== null)
        ? array_slice($values, 0, (int) $visibleCount)
        : $values;
    $hiddenCount = ($isRow && $visibleCount !== null)
        ? max(0, count($values) - (int) $visibleCount)
        : 0;

    $showClear = ! $disabled && $isClearable && count($values) > 0;
@endphp

<div {{ $attributes }}>
    @if (filled($label))
        <tedi:form.label for="{{ $id }}" :required="(bool) $required">{{ $label }}</tedi:form.label>
    @endif

    <div @class([
        'tedi-multi-value-field__inner',
        'tedi-multi-value-field__inner--row' => $isRow,
    ])>
        @if (count($values) > 0)
            <div @class([
                'tedi-multi-value-field__tags',
                'tedi-multi-value-field__tags--row' => $isRow,
            ])>
                @foreach ($visible as $index => $value)
                    <tedi:tag
                        :type="$tagColor"
                        :closable="! $disabled"
                        data-tedi-tag-index="{{ $index }}"
                    >{{ $value }}</tedi:tag>
                @endforeach

                @if ($hiddenCount > 0)
                    <tedi:tag
                        :type="$tagColor"
                        class="tedi-multi-value-field__overflow-tag"
                        aria-label="{{ __('tedi::tedi.multi-value-field.hidden-count', ['count' => $hiddenCount]) }}"
                    >+{{ $hiddenCount }}</tedi:tag>
                @endif
            </div>
        @endif

        @if ($showClear || $icon)
            <div class="tedi-multi-value-field__right-area">
                @if ($showClear)
                    <tedi:closing-button
                        :icon-size="18"
                        :aria-label="__('tedi::tedi.clear')"
                        data-name="closing-button"
                    />
                @endif

                @if ($showClear && $icon)
                    <tedi:separator axis="vertical" size="1.5rem" :spacing="0.25" color="primary" />
                @endif

                @if ($icon && $iconIsButton)
                    <button type="button" class="tedi-multi-value-field__icon-wrapper" {{ new \Illuminate\View\ComponentAttributeBag($iconButtonAttributes) }}>
                        <tedi:icon :name="$icon" :size="18" />
                    </button>
                @elseif ($icon)
                    <div class="tedi-multi-value-field__icon-wrapper">
                        <tedi:icon :name="$icon" :size="18" />
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if ($name)
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ count($values) ? json_encode($values) : '' }}"
            @if ($required) required @endif
            @disabled($disabled)
        />
    @endif
</div>
