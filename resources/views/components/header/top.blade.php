{{--
    TEDI Header Top.
    Port of angular/tedi/components/layout/header/header-top/header-top.component.ts

    Breakpoint overrides ([xs]-[xxl]) are not ported (CONVENTIONS.md §7) — only
    the base `alignment` prop is supported.
--}}
@props([
    /** flex-start|center|flex-end|space-between|space-around|space-evenly */
    'alignment' => 'space-between',
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
    'tedi-header-top',
    $alignmentUtility[$alignment] ?? $alignmentUtility['space-between'],
]) }}>
    {{ $slot }}
</div>
