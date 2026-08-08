{{--
    TEDI Footer Side.
    Port of angular/tedi/components/layout/footer/footer-side/footer-side.component.ts

    Angular derives `placement` (start|end) from the presence of a
    `tedi-footer-start` / `tedi-footer-end` host attribute (`HostAttributeToken`).
    That is a runtime host-attribute inspection with no Blade equivalent, so per
    the spirit of CONVENTIONS.md §5 it becomes the explicit prop `placement`.
    Place the result inside tedi:footer's `start` / `end` named slot to match
    the attribute it declares.

    `mobileLayout` (BreakpointService) is not ported (CONVENTIONS.md §7) — the
    `tedi-footer-side--mobile` modifier is never emitted.

    Angular's `hostClasses` unconditionally pushes `tedi-footer-side--vertical-${position}`,
    including for the default `"center"` value, but the vendored SCSS only
    defines `&--vertical-start` / `&--vertical-end` — `center` is already the
    base rule's `justify-content: center`, so `--vertical-center` has no CSS
    rule at all (verified against dist/tedi.css). Emitting it would fail
    IntegrityTest's "every class must be styled" guardrail for no visual gain,
    so this port only emits the vertical modifier for `start`/`end`.
--}}
@props([
    /** start|end — which named slot of tedi:footer this occupies. */
    'placement' => 'start',
    /** start|center|end */
    'position' => 'center',
])

<div {{ $attributes->class([
    'tedi-footer-side',
    'tedi-footer-side--vertical-'.$position => $position !== 'center',
    'tedi-footer-side--'.$placement,
]) }}>
    {{ $slot }}
</div>
