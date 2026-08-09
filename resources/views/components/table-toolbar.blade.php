{{--
    TEDI Table Toolbar.
    Port of angular/tedi/components/content/table/table-toolbar/table-toolbar.component.ts

    Angular's selector is the element `tedi-table-toolbar`, so per CONVENTIONS.md
    §4 the root is that literal element, carrying the `tedi-table-toolbar` class
    and the static `data-name` host attribute Angular emits.

    The component has no inputs and no template beyond `<ng-content />`.
--}}
<tedi-table-toolbar data-name="tedi-table-toolbar" {{ $attributes->class(['tedi-table-toolbar']) }}>
    {{ $slot }}
</tedi-table-toolbar>
