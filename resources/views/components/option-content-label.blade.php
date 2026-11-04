{{--
    TEDI Option Content Label.
    Port of angular/src/tedi/components/helpers/option-content/option-content-label.component.ts

    Element selector `tedi-option-content-label`, so the literal element is
    emitted alongside the class (CONVENTIONS.md §4). The class rule supplies the
    flex/overflow behaviour; the element is a flex item of
    `.tedi-option-content__content`, so no display fallback is needed.
--}}
@props([
    /** Whether the label clips overflowing content for text ellipsis. */
    'clipContent' => true,
])

<tedi-option-content-label {{ $attributes->class([
    'tedi-option-content__label',
    'tedi-option-content__label--no-clip' => ! $clipContent,
]) }}>{{ $slot }}</tedi-option-content-label>
