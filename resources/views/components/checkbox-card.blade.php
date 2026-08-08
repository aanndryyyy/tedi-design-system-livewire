{{--
    TEDI Checkbox Card.
    Port of angular/tedi/components/form/checkbox-card/{checkbox-card.component.ts,html}

    Angular's selector is `label[tedi-checkbox-card]` (attribute-based, not an
    element/class selector). The vendored SCSS targets that literal attribute,
    so it is emitted verbatim on the root <label> alongside the host `class`
    binding and its modifiers.

    Angular's <ng-content select="tedi-feedback-text" /> (content projected
    outside `.tedi-checkbox-card__content`) becomes the named `feedback` slot;
    everything else (the checkbox input, icon, label text) is the default slot.
--}}
@props([
    /** primary|secondary */
    'variant' => 'primary',
    /** Whether to show the checkbox indicator. */
    'showIndicator' => true,
])

<label
    tedi-checkbox-card
    {{ $attributes->class([
        'tedi-checkbox-card',
        'tedi-checkbox-card--primary' => $variant === 'primary',
        'tedi-checkbox-card--secondary' => $variant === 'secondary',
        'tedi-checkbox-card--hide-indicator' => ! $showIndicator,
    ]) }}
>
    <div class="tedi-checkbox-card__content">
        {{ $slot }}
    </div>

    {{ $feedback ?? '' }}
</label>
