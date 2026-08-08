{{--
    TEDI Header Content.
    Port of angular/tedi/components/layout/header/header-content/header-content.component.ts
--}}
@props([
    /** flex-start|center|flex-end|space-between|space-around|space-evenly */
    'alignment' => 'center',
])

@php
    // headerAlignmentUtility from header-alignment.ts
    $alignmentUtility = [
        'flex-start' => 'justify-content-start',
        'center' => 'justify-content-center',
        'flex-end' => 'justify-content-end',
        'space-between' => 'justify-content-between',
        'space-around' => 'justify-content-around',
        'space-evenly' => 'justify-content-evenly',
    ];
@endphp

<div {{ $attributes->class([
    'tedi-header-content',
    $alignmentUtility[$alignment] ?? $alignmentUtility['center'],
]) }}>
    {{ $slot }}
</div>
