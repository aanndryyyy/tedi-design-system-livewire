{{--
    TEDI Icon — Material Symbols ligature.
    Port of angular/tedi/components/base/icon/icon.component.{ts,html}

    Renders the icon name as a text ligature; the font is supplied by
    @tedi-design-system/core (dist/fonts/material-symbols-*.woff2).
--}}
@props([
    /** Material Symbols icon name, e.g. "add", "arrow_forward". Required. */
    'name',
    /** 8|12|16|18|22|24|36|48|'inherit'|null — null uses the host's contextual size. */
    'size' => null,
    /** primary|secondary|tertiary|brand|brand-dark|success|warning|warning-dark|danger|white|inherit */
    'color' => 'primary',
    /** primary|secondary|brand-primary|brand-secondary — adds a circular background. */
    'background' => null,
    /** filled|outlined */
    'variant' => 'outlined',
    /** outlined|sharp|rounded */
    'type' => 'outlined',
    /** Accessible label. When omitted the icon is aria-hidden. */
    'label' => null,
])

@php
    // ICON_SIZE_TOKENS from icon.component.ts
    $sizeTokens = [
        8 => 'icon-00', 12 => 'icon-01', 16 => 'icon-02', 18 => 'icon-03',
        22 => 'icon-04', 24 => 'icon-05', 36 => 'icon-06', 48 => 'icon-07',
    ];
    $iconWithBackground = [16, 24];

    $numericSize = is_numeric($size) ? (int) $size : null;

    // iconSizeVar()
    if ($background) {
        $bgSize = in_array($numericSize, $iconWithBackground, true) ? $numericSize : 24;
        $sizeVar = 'var(--'.$sizeTokens[$bgSize].')';
        $bgPadding = $bgSize === 16
            ? 'var(--icon-background-padding-sm)'
            : 'var(--icon-background-padding-lg)';
    } else {
        $bgSize = null;
        $bgPadding = null;
        $sizeVar = ($size === null || $size === 'inherit' || $numericSize === null)
            ? null
            : 'var(--'.$sizeTokens[$numericSize].')';
    }

    $styles = array_filter([
        $sizeVar ? '--_tedi-icon-size: '.$sizeVar : null,
        $bgPadding ? '--_tedi-icon-bg-padding: '.$bgPadding : null,
    ]);
@endphp

{{--
    Rendered as a literal <tedi-icon> custom element, NOT a <span>.

    Angular's selector is `tedi-icon`, and 19 rules in the vendored SCSS target
    it as an ELEMENT selector for contextual styling — `.tedi-button tedi-icon`
    sets the icon size and `color: inherit`, `label[tedi-radio-card] tedi-icon`
    sets `flex-shrink: 0`, and so on. A <span class="tedi-icon"> matches none of
    them, so icons inside buttons, cards and the header would silently lose their
    contextual size and colour.

    The class list is kept as well, so class-based rules (.tedi-icon, the colour
    and background modifiers) still apply. `.tedi-icon` sets display:inline-flex,
    so the unknown element needs no display fallback.
--}}
<tedi-icon
    role="img"
    @if ($label) aria-label="{{ $label }}" @else aria-hidden="true" @endif
    @if ($styles) style="{{ implode('; ', $styles) }}" @endif
    {{ $attributes->class([
        'notranslate',
        'material-symbols',
        'material-symbols--'.$type,
        'tedi-icon',
        'tedi-icon--color-'.$color,
        'tedi-icon--bg' => (bool) $background,
        'tedi-icon--bg-'.$background => (bool) $background,
        'tedi-icon--size-inherit' => ! $background && $size === 'inherit',
        'tedi-icon--filled' => $variant === 'filled',
    ]) }}
>{{ $name }}</tedi-icon>
