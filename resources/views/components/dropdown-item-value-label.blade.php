{{--
    TEDI Dropdown Item Value Label.
    Port of angular/tedi/components/overlay/dropdown/dropdown-item-value/dropdown-item-value-label.component.ts

    Element selector `tedi-dropdown-item-value-label`, so the literal element is
    emitted alongside the class (CONVENTIONS.md §4). The class rule supplies the
    flex/overflow behaviour; the element is a flex item of
    `.tedi-dropdown-item-value__content`, so no display fallback is needed.
--}}
@props([
    /** Whether the label clips overflowing content for text ellipsis. */
    'clipContent' => true,
])

<tedi-dropdown-item-value-label {{ $attributes->class([
    'tedi-dropdown-item-value__label',
    'tedi-dropdown-item-value__label--no-clip' => ! $clipContent,
]) }}>{{ $slot }}</tedi-dropdown-item-value-label>
