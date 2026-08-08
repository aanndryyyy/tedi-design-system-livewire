{{--
    TEDI Card Icon.
    Port of angular/tedi/components/content/card/card-icon/card-icon.component.ts

    Extends CardContentComponent: background is derived from `type` (not
    inherited from the card — `inheritedBackground()` is overridden to ignore
    the injected CardComponent), padding still inherits from the parent
    tedi-card when unset, falling back to a size-based default.
--}}
@aware([
    'padding' => null,
])
@props([
    /** default|brand */
    'type' => 'default',
    /** default|small */
    'size' => 'default',
    'background' => null,
    'padding' => null,
    'backgroundImage' => null,
    'backgroundPosition' => null,
    'backgroundSize' => null,
    'backgroundRepeat' => null,
    'autoWidth' => false,
])

@php
    $blockClass = 'tedi-card-icon';
    $resolvedBackground = $background ?? ($type === 'brand' ? 'brand-primary' : 'secondary');
    $defaultPadding = $size === 'small' ? 0.75 : 1;
    $resolvedPadding = $padding ?? $defaultPadding;

    if (is_numeric($resolvedPadding)) {
        $sides = ['top' => $resolvedPadding, 'right' => $resolvedPadding, 'bottom' => $resolvedPadding, 'left' => $resolvedPadding];
    } elseif (array_key_exists('vertical', $resolvedPadding) && array_key_exists('horizontal', $resolvedPadding)) {
        $sides = [
            'top' => $resolvedPadding['vertical'], 'bottom' => $resolvedPadding['vertical'],
            'right' => $resolvedPadding['horizontal'], 'left' => $resolvedPadding['horizontal'],
        ];
    } else {
        $sides = [
            'top' => $resolvedPadding['top'] ?? 0,
            'right' => $resolvedPadding['right'] ?? 0,
            'bottom' => $resolvedPadding['bottom'] ?? 0,
            'left' => $resolvedPadding['left'] ?? 0,
        ];
    }

    $styles = [
        '--card-content-padding-top: '.$sides['top'].'rem',
        '--card-content-padding-right: '.$sides['right'].'rem',
        '--card-content-padding-bottom: '.$sides['bottom'].'rem',
        '--card-content-padding-left: '.$sides['left'].'rem',
    ];

    if ($backgroundImage) {
        $styles[] = 'background-image: url('.$backgroundImage.')';
    }
    if ($backgroundPosition) {
        $styles[] = 'background-position: '.$backgroundPosition;
    }
    if ($backgroundSize) {
        $styles[] = 'background-size: '.$backgroundSize;
    }
    if ($backgroundRepeat) {
        $styles[] = 'background-repeat: '.$backgroundRepeat;
    }
@endphp

<div
    style="{{ implode('; ', $styles) }}"
    {{ $attributes->class([
        $blockClass,
        $blockClass.'--background--'.$resolvedBackground,
        $blockClass.'--auto-width' => (bool) $autoWidth,
    ]) }}
>
    {{ $slot }}
</div>
