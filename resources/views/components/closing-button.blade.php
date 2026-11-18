{{--
    TEDI Closing button.
    Port of angular/src/tedi/components/buttons/closing-button/closing-button.component.{ts,html}

    Angular 10 (#900): the label is rendered once — as `title` (which also names
    the button) while `showTitle` is on, otherwise as `aria-label`. The icon is
    `color="inherit"` so it follows the button's per-state text colour (#814).
--}}
@props([
    /** default|small */
    'size' => 'default',
    /** 18|24 */
    'iconSize' => 24,
    /** Material Symbols name. */
    'icon' => 'close',
    /** Accessible label overriding the translated "close". Rendered as `title` or `aria-label` depending on `showTitle`. */
    'ariaLabel' => null,
    /** Render the label as a native `title` (which also names the button); when false, as `aria-label` instead. Set false inside a tooltip. */
    'showTitle' => true,
])

@php
    $label = $ariaLabel ?: __('tedi::tedi.close');
@endphp

<button
    type="button"
    {{ $attributes->class([
        'no-print',
        'tedi-closing-button',
        'tedi-closing-button--small' => $size === 'small',
    ])->merge([
        $showTitle ? 'title' : 'aria-label' => $label,
    ]) }}
>
    <tedi:icon :name="$icon" :size="$iconSize" color="inherit" aria-hidden="true" />
</button>
