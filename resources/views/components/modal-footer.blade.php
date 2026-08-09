{{--
    TEDI Modal footer.
    Port of angular/tedi/components/overlay/modal/modal-footer/modal-footer.component.ts

    Class-only shell; the footer is a flex row that right-aligns its buttons.
    Angular's stories change that with an inline `style="justify-content: space-between"`,
    which merges through `$attributes` here just the same.
--}}
<tedi-modal-footer {{ $attributes->class(['tedi-modal-footer']) }}>
    {{ $slot }}
</tedi-modal-footer>
