{{--
    TEDI Skeleton Block.
    Port of angular/src/tedi/components/loader/skeleton/skeleton-block/skeleton-block.component.ts

    Previously ported from React (CONVENTIONS.md §13); Angular now ships it in
    `tedi/`, which is the source from here on (§1).

    One shimmering bar: the literal `tedi-skeleton-block` element (§4),
    `aria-hidden="true"`. Resolution is upstream's:

      * height: a text style (`p`, `h1`…`h6`) emits `tedi-skeleton-block--<style>`
        and takes that style's line height; a number (or numeric string) is an
        inline `height` in px and emits no modifier.
      * width:  `auto` (default) writes no inline width — the stylesheet's
        `width: 100%` fills the container; a number (or numeric string) is a
        percentage; anything else (`36px`) is written verbatim.

    Note the asymmetry upstream has and this keeps: a numeric `height` is px,
    a numeric `width` is %.

    DIVERGENCE: the per-breakpoint `xs`–`xxl` override objects are resolved at
    runtime by `BreakpointService` and are not ported (CONVENTIONS.md §7 item 1).
--}}
@props([
    /** auto | number (percent) | '<n>px' */
    'width' => 'auto',
    /** p | h1…h6 | number (pixels) */
    'height' => 'p',
])

@php
    $isNumeric = fn ($value) => is_int($value) || is_float($value)
        || (is_string($value) && preg_match('/^\d+(\.\d+)?$/', $value));

    $widthStyle = match (true) {
        $width === null || $width === 'auto' => null,
        $isNumeric($width) => $width.'%',
        default => (string) $width,
    };
    $heightStyle = $isNumeric($height) ? $height.'px' : null;
@endphp

@php
    $styles = array_values(array_filter([
        $widthStyle !== null ? 'width: '.$widthStyle : null,
        $heightStyle !== null ? 'height: '.$heightStyle : null,
    ]));
    $attributes = $attributes->class([
        'tedi-skeleton-block',
        'tedi-skeleton-block--'.$height => is_string($height) && ! $isNumeric($height),
    ]);
@endphp

<tedi-skeleton-block aria-hidden="true" {{ $styles ? $attributes->style($styles) : $attributes }}></tedi-skeleton-block>
