{{--
    TEDI Tooltip (root).
    Port of angular/tedi/components/overlay/tooltip/tooltip.component.{ts,html}

    Positioning is done by this package's own anchoring engine
    (`Alpine.data('tediOverlay')` in resources/js/src/overlay.js) rather than CDK
    Overlay — CONVENTIONS.md §11. `offset` is passed straight through: the
    engine adds it on top of its 8px base gap, exactly as upstream's
    POSITION_MAP does.

    Structure differs from Angular in one place, deliberately. Angular renders
    the panel from a `<ng-template cdkConnectedOverlay>` here and projects
    `tedi-tooltip-content` into it; CDK then re-parents the pane to `<body>`.
    Blade has no template projection, so `<tedi:tooltip-content>` owns the
    `.tedi-tooltip__container` / `.tedi-tooltip__arrow` markup itself and stays
    where it was written, positioned `fixed` in viewport coordinates
    (CONVENTIONS.md §11, "one structural divergence"): a tooltip inside a
    `transform`ed ancestor is positioned relative to that ancestor.
    `tedi-tooltip { display: contents }` keeps the extra element out of layout.

    Not ported:
      - `trackPosition` — rAF repositioning against an origin that moves while
        open (a dragging slider thumb). It exists only to drive CDK's
        `overlayRef.updatePosition()` every frame; there is no equivalent hook
        on `tediOverlay`, and per CONVENTIONS.md §7 an inert prop is worse than
        an omitted one. A consumer who needs it can call `position()` on the
        Alpine component themselves.
      - Touch handling (`touchstart`/`touchend` toggling, with the 300ms guard
        that suppresses the synthetic click). Modern browsers fire
        `mouseenter` + `click` on tap, which `openWith="both"` already handles.

    Screen-reader wiring follows Angular 9 (#45 / #585): there is no sr-only
    copy of the content any more. `tedi-tooltip-content` itself carries
    `role="tooltip"` and the description id, and the trigger's interactive
    element points `aria-describedby` at it. The id is `description-id` when
    given (read by both children through `@aware`), otherwise Alpine's
    `$id('tedi-tooltip')`, scoped by `x-id` on this element. Angular's panel
    only exists while open; here it is always in the DOM (`x-show`), which
    `aria-describedby` resolves either way. Escape closes the tooltip through
    `tediOverlay`'s document-level listener, which upstream now has too.
    BREAKING: the old `description` prop (the sr-only text) is removed, because
    the content is now the description.

    The aria-describedby wiring needs Alpine: when the trigger is not a
    `:text="true"` span, the element it lands on is resolved in the browser
    (see tooltip-trigger.blade.php). Without JS no description is wired, which
    matches Angular's SSR output (its content is not rendered while closed).

    `open` is the initial open state, not a two-way model: Angular's `open`
    input distinguishes `undefined` (uncontrolled) from a boolean (controlled),
    which Blade's `isset()`-based `@props` cannot represent (CONVENTIONS.md §3).
    Pair `open-with="none"` with Alpine (`$refs` / `x-on`) for external control.
--}}
@props([
    /** auto|auto-start|auto-end|top|top-start|top-end|bottom|…|left-end */
    'position' => 'top',
    /** Flip to the opposite side when the preferred one overflows the viewport. */
    'preventOverflow' => true,
    /** hover|click|both|none */
    'openWith' => 'both',
    /** Initial open state. Use with open-with="none" for external control. */
    'open' => false,
    /** ms before a hover-opened tooltip closes once the pointer leaves. */
    'timeoutDelay' => 100,
    /** Extra px between tooltip and trigger, on top of the 8px base gap. */
    'offset' => 4,
    /**
     * id of the tooltip content (role="tooltip"), which the trigger's
     * aria-describedby points at. Generated client-side by Alpine when omitted.
     */
    'descriptionId' => null,
])

<tedi-tooltip
    {{ $attributes }}
    x-data="tediOverlay({
        placement: @js($position),
        offset: {{ (int) $offset }},
        preventOverflow: {{ $preventOverflow ? 'true' : 'false' }},
        openWith: @js($openWith),
        hoverDelay: {{ (int) $timeoutDelay }},
        open: {{ $open ? 'true' : 'false' }},
    })"
    @unless ($descriptionId) x-id="['tedi-tooltip']" @endunless
>
    {{ $slot }}
</tedi-tooltip>
