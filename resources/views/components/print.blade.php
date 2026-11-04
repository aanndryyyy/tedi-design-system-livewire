{{--
    TEDI Print.
    Port of angular/src/tedi/directives/print/print.directive.ts

    Previously ported from React's `misc/print` (CONVENTIONS.md §13); Angular
    now ships it as the `tediPrint` attribute directive, which is the source
    from here on (§1). The class logic is identical in both.

    Controls how its content behaves on paper: whether it prints at all, and
    where page/column/region breaks may fall around it.

    Every class this emits belongs to @tedi-design-system/core, not to a
    component sheet — `core/_print.scss` defines `.no-print:not(.show-print)`
    and the `break-{before,after,inside}-*` families inside a single
    `@media print` block. So there is nothing to vendor for this component, and
    nothing at all changes on screen.

    Note the class names are NOT `tedi-`-prefixed. That is upstream's naming
    (they are core utilities, like `sr-only`), so they port verbatim. The
    `tedi-*` guardrails in tests/IntegrityTest.php do not harvest them, so the
    parity test is their only cover.

    DIVERGENCE — a wrapper element, not a directive. Angular adds the classes to
    the element the attribute sits on (`<p tediPrint="hide">`). Blade cannot
    attach behaviour to a consumer's element, so this ports as a wrapper `<div>`
    carrying the classes — the shape `tedi:choicegroup` and
    `tedi:vertical-spacing` already use for directives. The break properties
    then apply to the wrapper rather than to the element inside it. The one
    case a wrapper cannot reproduce is `tediPrint="show"` cancelling a
    `no-print` on the SAME element (a button that hides itself when printing):
    core's rule is `.no-print:not(.show-print)`, so `show-print` on a wrapper
    does nothing for the button inside. Put `class="show-print"` on that
    component directly instead.

    `visibility` is Angular's `tediPrint` input under a name that says what it
    does — the directive's own name is not usable as a Blade prop, the same
    rename `hide-at` (`breakpoint`) and `vertical-spacing` (`size`) make. Its
    default is Angular's `""`: neither `no-print` nor `show-print` is emitted
    unless you ask for one. `null` and `""` both mean "neither".
--}}
@props([
    /** show|hide — Angular's `tediPrint`. hide: not printed. show: cancels a `no-print` on the same element. Empty: neither. */
    'visibility' => '',
    /** auto|avoid|avoid-column|avoid-page|avoid-region — break-before. */
    'breakBefore' => null,
    /** auto|avoid|avoid-column|avoid-page|avoid-region — break-after. */
    'breakAfter' => null,
    /** auto|avoid|avoid-column|avoid-page|avoid-region — break-inside. */
    'breakInside' => null,
])

<div {{ $attributes->class([
    'no-print' => $visibility === 'hide',
    'show-print' => $visibility === 'show',
    'break-before-'.$breakBefore => filled($breakBefore),
    'break-after-'.$breakAfter => filled($breakAfter),
    'break-inside-'.$breakInside => filled($breakInside),
]) }}>{{ $slot }}</div>
