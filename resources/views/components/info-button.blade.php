{{--
    TEDI Info Button.
    Port of angular/tedi/components/buttons/info-button/info-button.component.ts

    Angular's selector is `button[tedi-info-button]`; `ariaLabel` is aliased
    to the literal `aria-label` DOM attribute (`input<string>(undefined, {
    alias: 'aria-label' })`), so it arrives here the same way — Blade's
    kebab-case attribute maps straight to the `ariaLabel` @props key. Falls
    back to the translated "info-button.label" string when omitted.
--}}
@props([
    /** primary|inverted */
    'color' => 'primary',
    /** Accessible label; falls back to the translated "info-button.label". */
    'ariaLabel' => null,
])

<button
    type="button"
    {{ $attributes->class([
        'tedi-info-button',
        'tedi-info-button--inverted' => $color === 'inverted',
    ])->merge([
        'aria-label' => $ariaLabel ?: __('tedi::tedi.info-button.label'),
    ]) }}
>
    <tedi:icon name="info" :size="18" />
</button>
