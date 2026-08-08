{{--
    TEDI Footer Bottom.
    Port of angular/tedi/components/layout/footer/footer-bottom/footer-bottom.component.{ts,html}

    Divergences (document, don't fake — CONVENTIONS.md §7):
    - `mobileLayout` (BreakpointService) is not ported, so `tedi-footer-bottom--mobile`
      is never emitted.
    - Angular inserts a decorative `•` separator SVG between projected `<tedi-link>`
      children only in the mobile layout, discovered via `@ContentChildren` +
      `Renderer2` DOM manipulation. That is runtime DOM introspection gated on the
      unported mobile breakpoint, so it is not ported either — no explicit prop
      recreates it because it has no effect once `mobileLayout` is gone.
--}}
@props([])

<div {{ $attributes->class(['tedi-footer-bottom']) }}>
    {{ $slot }}
</div>
