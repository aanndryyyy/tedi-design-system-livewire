{{--
    TEDI Row (grid helper).
    Port of angular/tedi/components/helpers/grid/row/row.component.{ts,html}

    Angular's "grid" helper is actually two components — Row and Col, no
    single "grid" component exists (see index.ts). Ported here as separate
    row.blade.php / col.blade.php files.

    Per CONVENTIONS.md §7 the xs/sm/md/lg/xl/xxl breakpoint inputs (resolved
    at runtime by BreakpointService) are not ported — only the base props are
    supported. The vendored SCSS has no per-breakpoint variant classes to
    emit instead; it is the same `tedi-row--cols-N` / `g-N` / `gx-N` / `gy-N`
    classes at every viewport.
--}}
@props([
    /** 1-12|auto */
    'cols' => 'auto',
    /** Minimum column width (px) when cols is auto. */
    'minColWidth' => 200,
    /** start|end|center|stretch */
    'justifyItems' => null,
    /** start|end|center|stretch */
    'alignItems' => null,
    /** 0-5 */
    'gap' => null,
    /** 0-5 */
    'gapX' => null,
    /** 0-5 */
    'gapY' => null,
])

@php
    $gapRemMap = [0 => '0rem', 1 => '0.25rem', 2 => '0.5rem', 3 => '1rem', 4 => '1.5rem', 5 => '3rem'];
    $gridGap = $gapRemMap[$gap ?? $gapX ?? 0];

    $styles = $cols === 'auto'
        ? array_filter([
            '--_grid-gap: '.$gridGap,
            '--_grid-col-width: '.$minColWidth.'px',
        ])
        : [];
@endphp

<div
    role="presentation"
    @if ($styles) style="{{ implode('; ', $styles) }}" @endif
    {{ $attributes->class([
        'tedi-row',
        'tedi-row--cols-'.$cols,
        'tedi-row--justify-items-'.$justifyItems => filled($justifyItems),
        'tedi-row--align-items-'.$alignItems => filled($alignItems),
        'g-'.$gap => $gap !== null,
        'gx-'.$gapX => $gapX !== null,
        'gy-'.$gapY => $gapY !== null,
    ]) }}
>
    {{ $slot }}
</div>
