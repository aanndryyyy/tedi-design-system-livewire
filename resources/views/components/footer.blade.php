{{--
    TEDI Footer.
    Port of angular/tedi/components/layout/footer/footer.component.{ts,html}

    Angular's `[tedi-footer-start]` / `[tedi-footer-end]` attribute selectors
    become named slots `start` / `end` (CONVENTIONS.md §2); the `tedi-footer-bottom`
    element selector becomes the named slot `bottom`. The default slot holds the
    main body (tedi:footer.body), mirroring `<ng-content select="tedi-footer-body" />`.

    `mobileLayout` (BreakpointService) is not ported — CONVENTIONS.md §7 — so the
    `tedi-footer--mobile` modifier is never emitted.
--}}
@props([])

<footer {{ $attributes->class(['tedi-footer']) }}>
    <div class="tedi-footer__container">
        {{ $start ?? '' }}

        <div class="tedi-footer__center">
            {{ $slot }}
        </div>

        {{ $end ?? '' }}
    </div>

    {{ $bottom ?? '' }}
</footer>
