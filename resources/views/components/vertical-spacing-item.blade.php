{{--
    TEDI Vertical Spacing Item.
    Port of angular/tedi/directives/vertical-spacing/vertical-spacing-item.directive.ts

    The single-item counterpart to `tedi:vertical-spacing`: upstream's
    `[tediVerticalSpacingItem]` adds `.tedi-vertical-spacing__item` to its host
    and writes `style="--vertical-spacing-internal: {n}em"`, and the rule is a
    flat `margin-bottom: var(--vertical-spacing-internal, 0)`. Use it to space
    one element without making its parent a spacing container.

    Ports as a WRAPPER element for the same reason as `tedi:vertical-spacing` —
    see that file's header for the choicegroup precedent and the style-merging
    divergence, which apply here unchanged.

    DIVERGENCE — margin collapse through the wrapper. Angular puts the class on
    the element itself, overriding e.g. an `<h1>`'s UA `margin-bottom`. Here the
    wrapper carries the margin and the child's own bottom margin collapses
    through it, so the effective gap is `max(child margin, size)` rather than
    exactly `size`. Where that matters, put the class on the element directly
    (`<h1 class="tedi-vertical-spacing__item" style="--vertical-spacing-internal: 1.5em">`)
    instead of using this component.
--}}
@props([
    /**
     * Spacing below this item, in em. The upstream union is
     * 0 | 0.25 | 0.5 | 0.75 | 1 | 1.25 | 1.5 | 1.75 | 2 | 2.5 | 3 | 4 | 5.
     */
    'size' => 0,
])

<div {{ $attributes->class(['tedi-vertical-spacing__item'])->style([
    '--vertical-spacing-internal: '.$size.'em',
]) }}>
    {{ $slot }}
</div>
