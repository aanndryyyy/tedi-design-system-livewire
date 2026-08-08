{{--
    TEDI Separator.
    Port of angular/tedi/components/helpers/separator/separator.component.ts

    Angular's template is empty ("") — the component is host-classes + host
    styles only. Blade needs a real (empty) root element to carry them; a
    <span> is used since dot-only renders inline via ::before and the block
    axis is set purely through the class list, not the tag.
--}}
@props([
    /** horizontal|vertical */
    'axis' => 'horizontal',
    /** primary|secondary|accent */
    'color' => 'primary',
    /** dotted|dotted-small|dot-only|null */
    'variant' => null,
    /** extra-small|small|medium|large — only used when variant is dot-only. */
    'dotSize' => null,
    /** Only used when variant is dot-only. */
    'dotFilled' => true,
    /** 1|2 — ignored when variant is set. */
    'thickness' => 1,
    /**
     * Margins around the separator: a number (y-spacing on horizontal axis,
     * x+y on vertical), or an array with x/y/top/bottom/left/right keys.
     * An explicit side overrides its shorthand.
     */
    'spacing' => null,
    /** Width (horizontal axis) or height (vertical axis). */
    'size' => '100%',
])

@php
    $dashify = fn ($value) => str_replace('.', '-', (string) $value);

    $classes = [
        'tedi-separator',
        'tedi-separator--'.$color,
        'tedi-separator--'.$axis,
    ];

    if ($variant) {
        $classes[] = 'tedi-separator--'.$variant;
    }

    if ($variant && $dotSize) {
        $classes[] = 'tedi-separator--'.$variant.'-'.$dotSize;
    }

    if ($variant === 'dot-only') {
        $classes[] = 'tedi-separator--dot-only-'.($dotFilled ? 'filled' : 'outlined');
    }

    if ($thickness) {
        $classes[] = 'tedi-separator--thickness-'.$thickness;
    }

    if ($spacing !== null && is_array($spacing)) {
        $top = $spacing['top'] ?? $spacing['y'] ?? null;
        $bottom = $spacing['bottom'] ?? $spacing['y'] ?? null;
        $left = $spacing['left'] ?? $spacing['x'] ?? null;
        $right = $spacing['right'] ?? $spacing['x'] ?? null;

        if ($top) {
            $classes[] = 'tedi-separator--top-'.$dashify($top);
        }

        if ($bottom) {
            $classes[] = 'tedi-separator--bottom-'.$dashify($bottom);
        }

        if ($axis === 'vertical') {
            if ($left) {
                $classes[] = 'tedi-separator--left-'.$dashify($left);
            }

            if ($right) {
                $classes[] = 'tedi-separator--right-'.$dashify($right);
            }
        }
    } elseif (is_numeric($spacing)) {
        $classes[] = 'tedi-separator--spacing-'.$dashify($spacing);
    }

    $width = $variant === 'dot-only' ? null : ($axis === 'horizontal' ? $size : '0px');
    $height = $variant === 'dot-only' ? null : ($axis === 'vertical' ? $size : '0px');

    $styles = array_filter([
        $width !== null ? 'width: '.$width : null,
        $height !== null ? 'height: '.$height : null,
    ]);
@endphp

<span
    @if ($styles) style="{{ implode('; ', $styles) }}" @endif
    {{ $attributes->class($classes) }}
></span>
