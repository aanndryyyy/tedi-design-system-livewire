{{--
    TEDI Label row.
    Port of angular/tedi/components/form/label-row/label-row.component.{ts,html}

    A thin flex wrapper that lays a label out alongside trailing content (e.g.
    an info tooltip trigger). No props on the Angular side beyond the host
    class.
--}}
<div {{ $attributes->class(['tedi-label-row']) }}>
    {{ $slot }}
</div>
