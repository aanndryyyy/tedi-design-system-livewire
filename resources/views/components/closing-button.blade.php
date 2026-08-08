{{--
    TEDI Closing button.
    Port of angular/tedi/components/buttons/closing-button/closing-button.component.{ts,html}
--}}
@props([
    /** default|small */
    'size' => 'default',
    /** 18|24 */
    'iconSize' => 24,
    /** Material Symbols name. */
    'icon' => 'close',
    /** Accessible label; falls back to the translated "close". */
    'ariaLabel' => null,
    /** Also expose the label as a title attribute. */
    'showTitle' => true,
])

@php
    $label = $ariaLabel ?: __('tedi::tedi.close');
@endphp

<button
    type="button"
    aria-label="{{ $label }}"
    @if ($showTitle) title="{{ $label }}" @endif
    {{ $attributes->class([
        'tedi-closing-button',
        'tedi-closing-button--small' => $size === 'small',
    ]) }}
>
    <tedi:icon :name="$icon" :size="$iconSize" aria-hidden="true" />
</button>
