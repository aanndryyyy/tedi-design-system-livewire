{{--
    TEDI Info tooltip.
    Port of angular/tedi/components/overlay/info-tooltip/info-tooltip.component.{ts,html}

    Pure composition: `<tedi:tooltip>` + `<tedi:tooltip-trigger>` +
    `<tedi:tooltip-content>` wrapped around `<tedi:info-button>`, exposing the
    tooltip's position/open behaviour and the info button's colour and
    accessible name. The Angular selector is the element `tedi-info-tooltip`
    (CONVENTIONS.md §4's element-selector rule), carrying the
    `.tedi-info-tooltip` host class.

    `description` is the one addition to Angular's five inputs, and it is the
    §5 translation of behaviour Angular *does* have here: `tedi-tooltip` reads
    its projected content's `textContent` at runtime to build the sr-only
    description that the info button's `aria-describedby` points at. Blade
    cannot read its own slot, so the text is spelled out. Unlike the standalone
    `<tedi:tooltip-trigger>`, the wiring closes here — this component renders
    the button, so it can set `aria-describedby` on it exactly as Angular does.

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
    /** sr-only description text for the info button (CONVENTIONS.md §5). */
    'description' => null,
])

@php
    $descriptionId = $description ? \Tedi\Livewire\Tedi::id('tedi-tooltip') : null;
@endphp

<tedi-info-tooltip {{ $attributes->class(['tedi-info-tooltip']) }}>
    <tedi:tooltip
        :position="$position"
        :open-with="$openWith"
        :description="$description"
        :description-id="$descriptionId"
    >
        <tedi:tooltip-trigger>
            <tedi:info-button
                :color="$color"
                :aria-label="$ariaLabel"
                :aria-describedby="$descriptionId"
            />
        </tedi:tooltip-trigger>

        <tedi:tooltip-content :max-width="$maxWidth">{{ $slot }}</tedi:tooltip-content>
    </tedi:tooltip>
</tedi-info-tooltip>
