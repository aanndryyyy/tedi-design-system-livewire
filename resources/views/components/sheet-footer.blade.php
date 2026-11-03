{{--
    TEDI Sheet Footer.
    Port of react/src/tedi/components/overlays/sheet/components/sheet-footer/sheet-footer.tsx
    (CONVENTIONS.md §13).

    The action row. The default slot is the main actions, placed by `align`;
    the `end` slot (upstream's `endSlot`) switches to the split layout, which
    pins the main actions to the start and the end slot to the end, and makes
    `align` irrelevant — the class is upstream's either/or. Hidden while the
    sheet is collapsed, like the body.
--}}
@props([
    /** left|center|right — ignored when the `end` slot is used. */
    'align' => 'left',
    /** Stretch the main actions to equal shares of the footer width. */
    'fullWidth' => false,
])

@aware([
    /** Matches tedi:sheet's default. */
    'collapsed' => false,
])

@php
    $isSplit = isset($end) && trim((string) $end) !== '';
@endphp

<div
    @if ($collapsed) hidden @endif
    {{ $attributes->class([
        'tedi-sheet__footer',
        $isSplit ? 'tedi-sheet__footer--split' : 'tedi-sheet__footer--'.$align,
        'tedi-sheet__footer--full-width' => (bool) $fullWidth,
    ]) }}
    x-bind:hidden="collapsed"
>
    <div class="tedi-sheet__footer-side">{{ $slot }}</div>
    @if ($isSplit)
        <div class="tedi-sheet__footer-side tedi-sheet__footer-side--end">{{ $end }}</div>
    @endif
</div>
