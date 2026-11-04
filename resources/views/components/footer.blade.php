{{--
    TEDI Footer.
    Port of angular/src/tedi/components/layout/footer/footer.component.{ts,html}

    Angular's `[tedi-footer-start]` / `[tedi-footer-end]` attribute selectors
    become named slots `start` / `end` (CONVENTIONS.md §2); the `tedi-footer-bottom`
    element selector becomes the named slot `bottom`. The default slot holds the
    main body (tedi:footer.body), mirroring `<ng-content select="tedi-footer-body" />`.

    `mobileLayout` (BreakpointService) is not ported — CONVENTIONS.md §7 — so the
    `tedi-footer--mobile` modifier is never emitted.

    Angular 8+ puts core's `no-print` utility on the `tedi-footer` host, which
    wraps upstream's inner `<footer class="tedi-footer">`. The port has the
    `<footer>` as its single root (CONVENTIONS.md §6), so it carries both.
--}}
@props([])

<footer {{ $attributes->class(['tedi-footer', 'no-print']) }}>
    <div class="tedi-footer__container">
        {{ $start ?? '' }}

        <div class="tedi-footer__center">
            {{ $slot }}
        </div>

        {{ $end ?? '' }}
    </div>

    {{ $bottom ?? '' }}
</footer>
