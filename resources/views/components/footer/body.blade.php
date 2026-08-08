{{--
    TEDI Footer Body.
    Port of angular/tedi/components/layout/footer/footer-body/footer-body.component.ts

    `mobileLayout` (BreakpointService) is not ported (CONVENTIONS.md §7) — the
    `tedi-footer-body--mobile` modifier is never emitted.
--}}
@props([])

<div {{ $attributes->class(['tedi-footer-body']) }}>
    {{ $slot }}
</div>
