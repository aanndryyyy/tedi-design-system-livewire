{{--
    TEDI Info tooltip.
    Port of angular/tedi/components/overlay/info-tooltip/info-tooltip.component.{ts,html}

    Pure composition: `<tedi:tooltip>` + `<tedi:tooltip-trigger>` +
    `<tedi:tooltip-content>` wrapped around `<tedi:info-button>`, exposing the
    tooltip's position/open behaviour and the info button's colour and
    accessible name. The Angular selector is the element `tedi-info-tooltip`
    (CONVENTIONS.md §4's element-selector rule), carrying the
    `.tedi-info-tooltip` host class.

    Accessibility (Angular #585): the visible content carries `role="tooltip"`
    and a shared `description-id`; the info button's `aria-describedby` points
    at that id. There is no separate `.sr-only` mirror — the slot text is the
    description. This component always generates the shared id and wires both
    sides, because it owns the button and the content.

    The tooltip's `preventOverflow`, `timeoutDelay` and `offset` keep their
    defaults, matching Angular's template, which forwards only `position` and
    `openWith`.
--}}
@props([
    /** auto|auto-start|auto-end|top|top-start|top-end|bottom|…|left-end */
    'position' => 'top',
    /** hover|click|both|none */
    'openWith' => 'both',
    /** none|small|medium|large */
    'maxWidth' => 'medium',
    /** primary|inverted — use `inverted` on dark or coloured backgrounds. */
    'color' => 'primary',
    /** Accessible name for the info button; falls back to the translated label. */
    'ariaLabel' => null,
])

@php
    $descriptionId = \Tedi\Livewire\Tedi::id('tedi-tooltip');
@endphp

<tedi-info-tooltip {{ $attributes->class(['tedi-info-tooltip']) }}>
    <tedi:tooltip
        :position="$position"
        :open-with="$openWith"
        :description-id="$descriptionId"
    >
        <tedi:tooltip-trigger :described-by="$descriptionId">
            <tedi:info-button
                :color="$color"
                :aria-label="$ariaLabel"
                :aria-describedby="$descriptionId"
            />
        </tedi:tooltip-trigger>

        <tedi:tooltip-content :max-width="$maxWidth" :description-id="$descriptionId">{{ $slot }}</tedi:tooltip-content>
    </tedi:tooltip>
</tedi-info-tooltip>
