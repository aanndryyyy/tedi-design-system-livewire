{{--
    TEDI Dropdown Item Value Meta.
    Port of angular/tedi/components/overlay/dropdown/dropdown-item-value/dropdown-item-value-meta.component.ts

    Element selector `tedi-dropdown-item-value-meta`, emitted literally next to
    the class (CONVENTIONS.md §4). No inputs upstream.
--}}
<tedi-dropdown-item-value-meta {{ $attributes->class([
    'tedi-dropdown-item-value__meta',
]) }}>{{ $slot }}</tedi-dropdown-item-value-meta>
