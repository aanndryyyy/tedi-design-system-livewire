{{--
    TEDI Option Content Meta.
    Port of angular/src/tedi/components/helpers/option-content/option-content-meta.component.ts

    Element selector `tedi-option-content-meta`, emitted literally next to
    the class (CONVENTIONS.md §4). No inputs upstream.
--}}
<tedi-option-content-meta {{ $attributes->class([
    'tedi-option-content__meta',
]) }}>{{ $slot }}</tedi-option-content-meta>
