{{--
    TEDI Skeleton Block.
    Port of react/src/tedi/components/loaders/skeleton/skeleton-block/skeleton-block.tsx
    (CONVENTIONS.md §13).

    One shimmering bar. Height comes from a typography token via the
    `tedi-skeleton-block--<h1…h6|p>` modifier, or from an explicit pixel number;
    width is always an inline style, as upstream.

    Width follows React's three-way resolution exactly:
      * a bare number      → percent   (`width="60"`  → `width: 60%`)
      * a `…px` string     → verbatim  (`width="80px"` → `width: 80px`)
      * `'auto'` (default) → `auto`
    Note the asymmetry that upstream has and this keeps: `height` as a number is
    px, `width` as a number is %.

    Breakpoint props (React's `BreakpointSupport`) are not ported — CONVENTIONS.md
    §7 item 1.
--}}
@props([
    /** 'auto' | number (percent) | '<n>px'. */
    'width' => 'auto',
    /** 'p' | 'h1'…'h6' | number (pixels). */
    'height' => 'p',
])

@php
    // React: number → `${width}%`, '…px' → verbatim, 'auto' → auto.
    if (is_numeric($width)) {
        $widthStyle = $width.'%';
    } elseif (is_string($width) && str_ends_with($width, 'px')) {
        $widthStyle = $width;
    } else {
        $widthStyle = 'auto';
    }

    $isNumericHeight = is_numeric($height);
@endphp

<span {{ $attributes
    ->class(array_filter([
        'tedi-skeleton-block',
        $isNumericHeight ? null : 'tedi-skeleton-block--'.$height,
    ]))
    ->style(array_filter([
        'width: '.$widthStyle,
        $isNumericHeight ? 'height: '.$height.'px' : null,
    ])) }}></span>
