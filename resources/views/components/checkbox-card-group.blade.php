{{--
    TEDI Checkbox Card Group.
    Port of angular/tedi/components/form/checkbox-card-group/checkbox-card-group.component.ts

    A plain flex-wrap layout wrapper for <x-checkbox-card> children; Angular's
    selector `tedi-checkbox-card-group` is an element selector, so it maps to
    a <div> with the host class, no attribute needed.
--}}
<div {{ $attributes->class(['tedi-checkbox-card-group']) }}>
    {{ $slot }}
</div>
