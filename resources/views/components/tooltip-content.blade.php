{{--
    TEDI Tooltip content (the positioned panel).
    Port of angular/tedi/components/overlay/tooltip/tooltip-content/tooltip-content.component.ts
    plus the `.tedi-tooltip__container` markup from tooltip.component.html.

    Angular splits these two: the container + arrow live in the tooltip's
    `<ng-template cdkConnectedOverlay>`, and `tedi-tooltip-content` is projected
    into it. Blade cannot project into a template, so the container travels with
    the content — this component owns the whole panel. It follows the §6
    wrapper/control precedent: the wrapper carries static classes and the
    Alpine refs, `{{ $attributes }}` goes on the inner `<tedi-tooltip-content>`,
    which is the element Angular's `host: { '[class]': 'classes()' }` targets
    and which the SCSS styles as `.tedi-tooltip-content`.

    Accessibility (Angular #585): this host carries `role="tooltip"` and the
    shared `description-id` that the trigger's `aria-describedby` points at.
    The visible content IS the accessible description — there is no duplicated
    `.sr-only` node, and the content is NOT aria-hidden. Only the decorative
    arrow is `aria-hidden`.

    `description-id` arrives through `@aware` from `<tedi:tooltip>` when the
    consumer (or a composer like info-tooltip) passed it on the root; otherwise
    a local id is generated so the role=tooltip element always has one.

    Per CONVENTIONS.md §8/§11 the panel is `x-show`n, not `x-if`ed: it exists in
    the DOM with its real class list while closed. `data-placement` is bound
    (the arrow's rotation keys off it) and therefore written after the real
    `class` attribute, per §4.

    The panel and arrow are `<span>`s, not `<div>`s, and the markup is written
    without whitespace between them. Both matter, and neither is cosmetic:

      - A tooltip is used inline inside running text (that is the whole point of
        the `text-trigger` story: "Tooltip works even inside a text"). `<p>` may
        only contain phrasing content, so an HTML parser that meets a `<div>`
        inside a `<p>` *auto-closes the paragraph* and hoists the div out — which
        pulls the panel clean out of `<tedi-tooltip>`, i.e. out of the Alpine
        scope. The tooltip then never opens. Custom elements
        (`<tedi-tooltip-content>`) are parsed as phrasing content, so only these
        two wrappers had to change. The SCSS keys on the classes, not the
        element, and both wrappers are `position: fixed`/`absolute`, which
        blockifies them regardless of the span default.
      - Whitespace between inline elements renders as a space. Inside a `<p>` a
        stray text node before the panel would add a visible gap after the
        trigger, so the tags are packed.

    `x-ref="panel"` and `x-ref="arrow"` are the two refs `tediOverlay` writes
    inline `position`/`top`/`left` to — TEDI ships no placement CSS at all (§11).

    Angular's mouseleave on the content host calls `hideTooltip()` immediately;
    here it goes through `tediOverlay`'s `contentLeave()`, which honours the
    tooltip's `timeoutDelay` the same way leaving the trigger does.
--}}
@aware([
    'descriptionId' => null,
])
@props([
    /** none|small|medium|large */
    'maxWidth' => 'medium',
    /** Id shared with the trigger's aria-describedby. Falls back to @aware, then generated. */
    'descriptionId' => null,
])

@php
    $descriptionId = $descriptionId ?: \Tedi\Livewire\Tedi::id('tedi-tooltip');
@endphp

<span
    class="tedi-tooltip__container"
    x-ref="panel"
    x-show="open"
    x-cloak
    x-bind:data-placement="side"
    x-on:mouseenter="contentEnter()"
    x-on:mouseleave="contentLeave()"
><span class="tedi-tooltip__arrow" aria-hidden="true" x-ref="arrow"></span><tedi-tooltip-content {{ $attributes->class([
        'tedi-tooltip-content',
        'tedi-tooltip-content--'.$maxWidth,
    ])->merge([
        'id' => $descriptionId,
        'role' => 'tooltip',
    ]) }}>{{ $slot }}</tedi-tooltip-content></span>
