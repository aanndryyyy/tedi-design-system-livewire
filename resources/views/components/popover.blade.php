{{--
    TEDI Popover (root).
    Port of angular/tedi/components/overlay/popover/popover.component.{ts,html}

    Angular projects by selector — `select="[tedi-popover-trigger]"` before the
    CDK overlay template and `select="tedi-popover-content"` inside its panel.
    Blade cannot select slot content, so per CONVENTIONS.md §3 the trigger is a
    named `trigger` slot and the content is the default slot:

        <tedi:popover container-id="pop-1">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Ava</tedi:popover-trigger>
            </x-slot:trigger>
            <tedi:popover-content title="Pealkiri" :show-close="true">…</tedi:popover-content>
        </tedi:popover>

    Positioning is `tediOverlay` (CONVENTIONS.md §11), a port of upstream's
    overlay-position.util.ts. `withArrow` adds 12px on top of the engine's 8px
    base gap, exactly as Angular's `arrowOffset` computed does. Unlike dropdown
    and tooltip, `preventOverflow` defaults to FALSE here, matching Angular.
    The panel stays where it is written rather than being re-parented into a
    `.cdk-overlay-container`, so a popover inside a `transform`ed ancestor is
    positioned relative to that ancestor — see CONVENTIONS.md §11.

    The panel and arrow are `<span>`s, not `<div>`s, and carry no whitespace
    between their tags. A `<div>` is not phrasing content, so a popover placed
    inline in running text makes the HTML parser auto-close the surrounding
    `<p>` and hoist the panel out of `<tedi-popover>` — and therefore out of
    the Alpine scope, leaving a popover that flips `open` but never appears.
    Server-rendered HTML is well-formed either way, so no class assertion can
    see this; only the browser parser rearranges it. Nothing is lost by the
    swap: the vendored SCSS keys on classes, and both elements are positioned
    (`relative` / `absolute`), which blockifies them regardless of the span
    default. Whitespace between the tags would render as a visible gap inside
    a paragraph, hence the run-together markup. See CONVENTIONS.md §11.

    That protects the popover's own chrome. Content that itself contains block
    elements — `<tedi:popover-content :show-close="true">` renders a `<div>`
    head, as Angular's does — still breaks a surrounding `<p>`, because the
    parser closes an open paragraph on any `<div>` start tag at any depth.
    Inline-in-text usage should therefore stick to phrasing content.

    KNOWN DIVERGENCE — ARIA pairing across siblings. Angular shares
    `containerId` through DI (`inject(PopoverComponent)` in the trigger
    directive and the content component). Blade siblings have no such channel,
    so `container-id` is an explicit prop read by the children through
    `@aware` (same pattern as `<tedi:accordion-item>`'s `item-id`). Pass it and
    the port is ARIA-identical to Angular: the panel gets `id`, the trigger gets
    `{container-id}_trigger` plus `aria-controls`, the content's title gets
    `{container-id}_title`. Omit it and the panel still gets a generated `id`,
    but the trigger renders no `id`/`aria-controls` and the panel no
    `aria-labelledby` — an unpaired id is worse than none.

    Angular's `ariaLabelledBy` computed points at the content's title when the
    content has one, and at the trigger otherwise. This component cannot inspect
    its slot (CONVENTIONS.md §5), so it always resolves to the trigger; a
    consumer whose content has a title passes `labelled-by="{container-id}_title"`.

    Deliberately not ported (CONVENTIONS.md §11): focus trapping inside the
    panel and the Tab-out-of-popover handling (`focusElementAfterTrigger` /
    `focusElementBeforeTrigger`). Escape-to-close, outside-click dismissal and
    focus return to the trigger are ported by `tediOverlay`.

    `output()`-style events are not re-emitted (CONVENTIONS.md §3); a consumer
    binds `wire:click` / `x-on:` on the trigger via `$attributes`.
--}}
@props([
    /** auto|auto-start|auto-end|top|top-start|top-end|bottom|bottom-start|bottom-end|right|right-start|right-end|left|left-start|left-end */
    'position' => 'top',
    /** Flip to the opposite side when the preferred one overflows the screen. */
    'preventOverflow' => false,
    /** Dismiss by clicking outside of the content. */
    'dismissible' => true,
    /** Hide the content when the page scrolls. */
    'hideOnScroll' => false,
    /** Illustrative prominent border on the arrow side. */
    'withBorder' => false,
    /** Render the arrow (and add its 12px to the offset). */
    'withArrow' => true,
    /** Lock scrolling on the rest of the page while open. */
    'lockScroll' => false,
    /** Delay (ms) before a hover-closed overlay hides. */
    'timeoutDelay' => 100,
    /** Stable id shared with the trigger and content for ARIA pairing. Generated when omitted. */
    'containerId' => null,
    /** Overrides the computed aria-labelledby. */
    'labelledBy' => null,
])

@php
    $panelId = $containerId ?: \Tedi\Livewire\Tedi::id('tedi-popover');
    $ariaLabelledBy = $labelledBy ?: ($containerId ? $containerId.'_trigger' : null);

    // tediOverlay resolves 'auto' to 'bottom' for its initial `side`; mirror that
    // statically so the arrow's data-placement rule matches before Alpine boots.
    $initialSide = strtok($position, '-');
    if (! in_array($initialSide, ['top', 'bottom', 'left', 'right'], true)) {
        $initialSide = 'bottom';
    }

    $overlayConfig = [
        'placement' => $position,
        'offset' => $withArrow ? 12 : 0,
        'preventOverflow' => (bool) $preventOverflow,
        'dismissible' => (bool) $dismissible,
        'hideOnScroll' => (bool) $hideOnScroll,
        'lockScroll' => (bool) $lockScroll,
        'hoverDelay' => (int) $timeoutDelay,
        'openWith' => 'click',
    ];
@endphp

<tedi-popover
    {{ $attributes->class(['tedi-popover']) }}
    x-data="tediOverlay(@js($overlayConfig))"
>
    {{ $trigger ?? '' }}<span
        id="{{ $panelId }}"
        role="dialog"
        tabindex="-1"
        @if ($ariaLabelledBy) aria-labelledby="{{ $ariaLabelledBy }}" @endif
        data-placement="{{ $initialSide }}"
        @class([
            'tedi-popover__container',
            'tedi-popover__container--border' => (bool) $withBorder,
            'tedi-popover__container--arrow' => (bool) $withArrow,
        ])
        x-ref="panel"
        x-show="open"
        x-cloak
        x-bind:data-placement="side"
    >@if ($withArrow)<span class="tedi-popover__arrow" x-ref="arrow"></span>@endif{{ $slot }}</span>
</tedi-popover>
