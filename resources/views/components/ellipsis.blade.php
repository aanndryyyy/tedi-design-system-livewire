{{--
    TEDI Ellipsis.
    Port of angular/tedi/components/helpers/ellipsis/ellipsis.component.{ts,html}

    Angular measures overflow with a ResizeObserver and, when the text is
    truncated, wraps it in a tooltip showing the full string. That measurement is
    inherently client-side. Per CONVENTIONS.md §7 the Blade port renders the
    static clamped markup only — the CSS line-clamp works without JS; the
    reveal-on-hover tooltip does not.
--}}
@props([
    /** Number of lines before clamping (only applies to position="end"). */
    'lineClamp' => 2,
    /** start|end — which side the text truncates from. */
    'position' => 'end',
])

@php
    $clamp = $position === 'end' ? (string) $lineClamp : null;
    $clampStyle = $clamp !== null
        ? '-webkit-line-clamp: '.$clamp.'; line-clamp: '.$clamp.';'
        : null;
@endphp

<span {{ $attributes->class(['tedi-ellipsis']) }}>
    <span class="tedi-ellipsis__wrapper">
        <span
            @class([
                'tedi-ellipsis__content',
                'tedi-ellipsis__content--start' => $position === 'start',
            ])
            @if ($clampStyle) style="{{ $clampStyle }}" @endif
        >
            <span class="tedi-ellipsis__inner">{{ $slot }}</span>
        </span>
    </span>
</span>
