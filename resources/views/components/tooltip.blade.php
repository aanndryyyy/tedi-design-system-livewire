{{--
    TEDI Tooltip (root).
    Port of angular/tedi/components/overlay/tooltip/tooltip.component.{ts,html}

    Positioning is done by this package's own anchoring engine
    (`Alpine.data('tediOverlay')` in resources/js/tedi.js) rather than CDK
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
      - The sr-only description mirroring. Angular reads the projected
        content's `textContent` in `ngAfterContentChecked`; Blade cannot
        introspect its own slot, so it is an explicit `description` prop
        (CONVENTIONS.md §5). Angular then sets `aria-describedby` on whatever
        focusable element the trigger projected — also unreachable from here.
        See tooltip-trigger.blade.php's `described-by` prop.
      - Touch handling (`touchstart`/`touchend` toggling, with the 300ms guard
        that suppresses the synthetic click). Modern browsers fire
        `mouseenter` + `click` on tap, which `openWith="both"` already handles.

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
     * sr-only text describing the trigger. Angular derives this from the
     * projected content's textContent; Blade needs it spelled out (§5).
     */
    'description' => null,
    /** id of the sr-only description, for the trigger's `described-by`. */
    'descriptionId' => null,
])

@php
    $descriptionId = $descriptionId ?: \Tedi\Livewire\Tedi::id('tedi-tooltip');
@endphp

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
>
    {{ $slot }}

    @if ($description)
        <span id="{{ $descriptionId }}" class="sr-only">{{ $description }}</span>
    @endif
</tedi-tooltip>
