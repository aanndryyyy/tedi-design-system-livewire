{{--
    TEDI Card Content.
    Port of angular/tedi/components/content/card/card-content/card-content.component.ts

    Inherits unset `background`/`padding` from the parent tedi-card via
    @aware (Angular: `inject(CardComponent)`).
--}}
@aware([
    'background' => null,
    'padding' => null,
])
@props([
    /** primary|secondary|tertiary|accent|brand-primary|brand-secondary|brand-tertiary|brand-quaternary|danger-primary|danger-secondary|success-primary|success-secondary|info-primary|info-secondary|warning-primary|warning-secondary|neutral-primary|neutral-secondary */
    'background' => null,
    /** rem number, or ['vertical'=>,'horizontal'=>] / ['top'=>,'right'=>,'bottom'=>,'left'=>] */
    'padding' => null,
    'backgroundImage' => null,
    'backgroundPosition' => null,
    'backgroundSize' => null,
    'backgroundRepeat' => null,
    'autoWidth' => false,
])

@php
    $blockClass = 'tedi-card-content';
    $resolvedBackground = $background ?? 'primary';
    $resolvedPadding = $padding ?? 1;

    // getPaddingCssVariables()
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
