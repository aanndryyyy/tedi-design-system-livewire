{{--
    TEDI Vertical Spacing.
    Port of angular/tedi/directives/vertical-spacing/vertical-spacing.directive.ts

    Upstream is an attribute DIRECTIVE with no template: `[tediVerticalSpacing]`
    goes on whatever element already contains the items, adds
    `.tedi-vertical-spacing` to it and writes
    `style="--vertical-spacing-internal: {n}em"`. Blade has no directives, so —
    following the `tedi:choicegroup` precedent (CONVENTIONS.md §12) — this ports
    as a WRAPPER element carrying exactly that class and that custom property,
    with the items projected into it.

    The wrapper is behaviourally equivalent here: the stylesheet only ever
    reaches the items as direct children (`.tedi-vertical-spacing > *`,
    `> :last-child`, `> :first-of-type`), and the slot's top-level elements are
    direct children of this wrapper just as they were of the directive's host.

    DIVERGENCE — style merging. Angular calls `setAttribute('style', …)`, which
    CLOBBERS any style already on the host. Here `$attributes->style()` merges,
    so a consumer's own `style="…"` survives alongside the custom property. That
    is what CONVENTIONS.md §6 requires of every attribute the component computes,
    and the merged declaration is the same one Angular would have written.

    DIVERGENCE — the wrapper is a `<div>`. Angular's host could be any element;
    here the level of DOM is fixed. Inside a flex or grid parent the wrapper, not
    the items, becomes the child — set layout on the wrapper in that case.
--}}
@props([
    /**
     * Spacing between the direct children, in em. The upstream union is
     * 0 | 0.25 | 0.5 | 0.75 | 1 | 1.25 | 1.5 | 1.75 | 2 | 2.5 | 3 | 4 | 5.
     */
    'size' => 0,
])

<div {{ $attributes->class(['tedi-vertical-spacing'])->style([
    '--vertical-spacing-internal: '.$size.'em',
]) }}>
    {{ $slot }}
</div>
