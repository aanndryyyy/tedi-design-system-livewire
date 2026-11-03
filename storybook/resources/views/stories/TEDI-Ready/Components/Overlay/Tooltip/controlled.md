With `openWith="none"` the built-in triggers are disabled and visibility is driven
entirely by the `open` state.

Because focus never opens a controlled tooltip, its content cannot be announced via
the trigger's `aria-describedby` — so the anchor is marked `[interactive]="false"`
(a pure positioning origin, matching the slider) and accessibility lives on the real
control instead: here the toggle button conveys its own state with `aria-expanded`,
and the tooltip is a purely visual bubble. If the revealed content must be read by a
screen reader, use `tedi-popover` (a focus-managed dialog), not a tooltip.

<!--
    The Angular text continues: "Enable `trackPosition` when the origin can move
    (e.g. a dragging slider thumb) so the tooltip follows it."

    `trackPosition` is not ported (CONVENTIONS.md §7): it exists only to call
    CDK's `overlayRef.updatePosition()` every animation frame, and this package's
    anchoring engine exposes no equivalent hook. A consumer who needs it can call
    `position()` on the Alpine `tediOverlay` scope themselves.

    `open` is likewise the *initial* state rather than a two-way model — Blade's
    `@props` resolve with `isset()`, so an explicit `null` cannot be told apart
    from "not passed" (CONVENTIONS.md §3). Live control is Alpine's `toggle()`.
-->
