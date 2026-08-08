{{--
    TEDI Card.
    Port of angular/tedi/components/content/card/{card.component.ts,card.utils.ts}

    `background` and `padding` are not classes on the card itself — they're
    defaults inherited by child `tedi-card-content`/`tedi-card-header`/
    `tedi-card-icon` blocks via @aware (mirrors Angular's `inject(CardComponent)`).
--}}
@props([
    /** Default background for child content blocks. See card-content for the value list. */
    'background' => null,
    /** Default padding (rem number, or ['vertical'=>,'horizontal'=>] / ['top'=>,'right'=>,'bottom'=>,'left'=>]) for child content blocks. */
    'padding' => null,
    /** false | ['top'=>bool,'right'=>bool,'bottom'=>bool,'left'=>bool,'topLeft'=>bool,'topRight'=>bool,'bottomRight'=>bool,'bottomLeft'=>bool] */
    'borderRadius' => null,
    /** Removes border from card. */
    'borderless' => false,
    /** primary|secondary|...|top-<background>|left-<background> — see card.utils.ts CardBackground. */
    'border' => null,
])

@php
    // getCardBorderPlacementColor()
    $borderPlacement = null;
    $borderColor = null;
    if ($border) {
        if (str_starts_with($border, 'top-')) {
            $borderPlacement = 'top';
            $borderColor = substr($border, 4);
        } elseif (str_starts_with($border, 'left-')) {
            $borderPlacement = 'left';
            $borderColor = substr($border, 5);
        } else {
            $borderColor = $border;
        }
    }

    // resolveCardBorderRadius() — corner keys override side keys; undefined config = all corners rounded.
    if ($borderRadius === false) {
        $corners = ['topLeft' => false, 'topRight' => false, 'bottomRight' => false, 'bottomLeft' => false];
    } else {
        $cfg = $borderRadius ?? [];
        $top = ($cfg['top'] ?? null) !== false;
        $right = ($cfg['right'] ?? null) !== false;
        $bottom = ($cfg['bottom'] ?? null) !== false;
        $left = ($cfg['left'] ?? null) !== false;

        $corners = [
            'topLeft' => $cfg['topLeft'] ?? ($top && $left),
            'topRight' => $cfg['topRight'] ?? ($top && $right),
            'bottomRight' => $cfg['bottomRight'] ?? ($bottom && $right),
            'bottomLeft' => $cfg['bottomLeft'] ?? ($bottom && $left),
        ];
    }
@endphp

<div {{ $attributes->class([
    'tedi-card',
    'tedi-card--border-'.$borderPlacement => $borderPlacement,
    'tedi-card--border--'.$borderColor => $borderColor,
    'tedi-card--borderless' => (bool) $borderless,
    'tedi-card--no-radius-tl' => ! $corners['topLeft'],
    'tedi-card--no-radius-tr' => ! $corners['topRight'],
    'tedi-card--no-radius-br' => ! $corners['bottomRight'],
    'tedi-card--no-radius-bl' => ! $corners['bottomLeft'],
]) }}>
    {{ $slot }}
</div>
