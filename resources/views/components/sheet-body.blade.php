{{--
    TEDI Sheet Body.
    Port of react/src/tedi/components/overlays/sheet/components/sheet-body/sheet-body.tsx
    (CONVENTIONS.md §13).

    The scrolling middle of the sheet. Hidden — not removed — while the sheet
    is collapsed, so form values survive a collapse, exactly as upstream.
--}}
@props([
    /** default|none — `none` for edge-to-edge content that brings its own padding. */
    'padding' => 'default',
])

@aware([
    /** Matches tedi:sheet's default. */
    'collapsed' => false,
])

<div
    @if ($collapsed) hidden @endif
    {{ $attributes->class([
        'tedi-sheet__body',
        'tedi-sheet__body--no-padding' => $padding === 'none',
    ]) }}
    x-bind:hidden="collapsed"
>{{ $slot }}</div>
