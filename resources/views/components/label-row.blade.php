{{--
    TEDI Label row.
    Port of angular/src/tedi/components/form/label-row/label-row.component.{ts,html}

    A thin flex wrapper that lays a label out alongside trailing content (e.g.
    an info tooltip trigger). No props on the Angular side beyond the host
    class.

    The root is the selector's own element, `<tedi-label-row>` (CONVENTIONS.md
    §4): since Angular 8.0 form-field and input-group project
    `label[tedi-label], tedi-label-row` into their label slot by that element.
    `.tedi-label-row { display: inline-flex }` supplies the display.
--}}
<tedi-label-row {{ $attributes->class(['tedi-label-row']) }}>
    {{ $slot }}
</tedi-label-row>
