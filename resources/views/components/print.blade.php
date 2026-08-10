{{--
    TEDI Print.
    Port of react/src/tedi/components/misc/print/print.tsx (CONVENTIONS.md §13).

    Controls how its content behaves on paper: whether it prints at all, and
    where page/column/region breaks may fall around it.

    Every class this emits belongs to @tedi-design-system/core, not to a
    component sheet — `core/_print.scss` defines `.no-print:not(.show-print)`
    and the `break-{before,after,inside}-*` families inside a single
    `@media print` block. So there is nothing to vendor for this component, and
    nothing at all changes on screen.

    Note the class names are NOT `tedi-`-prefixed. That is upstream's naming
    (they are core utilities, like `sr-only`), so they port verbatim — the same
    situation `table-of-contents` is in. The `tedi-*` guardrails in
    tests/IntegrityTest.php do not harvest them, so the parity test is their
    only cover.

    Upstream clones each child and merges the classes onto it, which also means
    it accepts an array of children and tags each one. Blade cannot introspect a
    slot (CONVENTIONS.md §5/§13.5), so this ports as a wrapper element carrying
    the classes — the shape `tedi:choicegroup` already uses. The break
    properties then apply to the wrapper rather than to each child; where you
    need per-child breaks, wrap each child in its own `tedi:print`.

    `visibility` has no default: upstream emits neither `no-print` nor
    `show-print` unless you ask for one, and `show-print` exists specifically to
    override an inherited `no-print`, so defaulting either way would be wrong.
--}}
@props([
    /** hide: omitted from print. show: forced into print, overriding an inherited hide. Null: neither. */
    'visibility' => null,
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
