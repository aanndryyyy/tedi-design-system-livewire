{{--
    TEDI Search Footer Actions — the row of buttons inside a search footer.
    Port of angular/src/tedi/components/form/search/search-footer-actions.component.ts

    Angular's selector is the element `tedi-search-footer-actions`, so per
    CONVENTIONS.md §4 the root is that literal custom element, carrying the
    host class (`.tedi-search-footer-actions` supplies `display: flex`).

    Use it inside `tedi:search`'s `footer` slot.
--}}
<tedi-search-footer-actions {{ $attributes->class(['tedi-search-footer-actions']) }}>
    {{ $slot }}
</tedi-search-footer-actions>
