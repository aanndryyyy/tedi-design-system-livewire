{{--
    TEDI Modal footer.
    Port of angular/tedi/components/overlay/modal/modal-footer/modal-footer.component.ts

    Class-only shell; the footer is a flex row that right-aligns its content.
    Left/right content (upstream #668): put the `tedi-modal-footer-start`
    attribute on each element that should sit on the left — the element
    itself, not a wrapper, so it keeps the footer's gap. The vendored SCSS gives
    the last such element `margin-inline-end: auto`. On a Blade component the
    bare attribute passes through `$attributes`, e.g.
    `tedi:button tedi-modal-footer-start variant="secondary"`.
--}}
<tedi-modal-footer {{ $attributes->class(['tedi-modal-footer']) }}>
    {{ $slot }}
</tedi-modal-footer>
