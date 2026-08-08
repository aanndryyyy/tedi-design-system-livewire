{{--
    TEDI Status badge.
    Port of angular/tedi/components/tags/status-badge/status-badge.component.{ts,html}

    Angular exposes a `class` input purely so consumers can append extra
    classes to `classes()`. Blade's `$attributes->class()` already merges any
    consumer-supplied `class="..."` the same way, so it isn't ported as a
    separate prop (see CONVENTIONS.md §4).
--}}
@props([
    'text' => '',
    /** Shown as a tooltip via the native title attribute; switches the root tag to <abbr>. */
    'title' => null,
    /** ARIA role, e.g. "status" or "alert". */
    'role' => null,
    /** neutral|brand|accent|success|danger|warning|transparent */
    'color' => 'neutral',
    /** filled|filled-bordered|bordered */
    'variant' => 'filled',
    /** default|large */
    'size' => 'default',
    /** danger|success|warning|inactive — shows a status-indicator dot. */
    'status' => null,
    /** Material Symbols icon name. */
    'icon' => '',
])

@php
    $uniqueId = \Tedi\Livewire\Tedi::id('tedi-status-badge');

    $ariaLive = match ($role) {
        'alert' => 'assertive',
        'status' => 'polite',
        default => null,
    };

    $hasText = trim((string) $text) !== '';
    $hasIcon = trim((string) $icon) !== '';

    $classes = [
        'tedi-status-badge',
        'tedi-status-badge--color-'.$color,
        'tedi-status-badge--variant-'.$variant,
        'tedi-status-badge--large' => $size === 'large',
        'tedi-status-badge__icon-only' => $hasIcon && ! $hasText,
    ];
@endphp

@php
    $rootAttributes = $attributes->class($classes)->merge(array_filter([
        'id' => $uniqueId,
        'role' => $role,
        'aria-live' => $ariaLive,
    ]));
@endphp

@if ($title)
    <abbr title="{{ $title }}" {{ $rootAttributes }}>
        @if ($status)
            <tedi:status-indicator :type="$status" :size="$size === 'large' ? 'lg' : 'sm'" :has-border="true" position="top-right" />
        @endif
        @if ($hasIcon)
            <tedi:icon :name="$icon" :size="16" class="tedi-status-badge__icon" />
        @endif
        @if ($hasText)
            <span class="tedi-status-badge__text">{{ $text }}</span>
        @endif
    </abbr>
@else
    <div {{ $rootAttributes }}>
        @if ($status)
            <tedi:status-indicator :type="$status" :size="$size === 'large' ? 'lg' : 'sm'" :has-border="true" position="top-right" />
        @endif
        @if ($hasIcon)
            <tedi:icon :name="$icon" :size="16" class="tedi-status-badge__icon" />
        @endif
        @if ($hasText)
            <span class="tedi-status-badge__text">{{ $text }}</span>
        @endif
    </div>
@endif
