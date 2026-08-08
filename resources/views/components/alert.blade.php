{{--
    TEDI Alert.
    Port of angular/tedi/components/notifications/alert/alert.component.{ts,html}

    Angular emits a `closeClick` output and drives `open` as a two-way model
    that gets flipped to false (optionally after `closeDelay`) on close. Per
    CONVENTIONS.md §3 the Blade port doesn't re-emit events; instead it follows
    tag.blade.php's `closeAttributes` pattern so the consumer can bind
    wire:click (e.g. to hide the alert themselves). `closeDelay` is accepted
    for API parity but is inert — it only mattered to the JS click handler
    that no longer exists.

    The action slot vs. close-button precedence from the Angular doc comment
    ("action slot wins") is implemented purely in CSS via :has() selectors
    (see alert.component.scss), so both are simply rendered here whenever
    applicable and the stylesheet decides what's visible.
--}}
@props([
    'title' => null,
    /** info|success|warning|danger */
    'type' => 'info',
    /** Material Symbols icon name. */
    'icon' => '',
    /** Shows a close button. */
    'showClose' => false,
    /** alert|status|none */
    'role' => 'alert',
    /** default|global|noSideBorders */
    'variant' => 'default',
    /** default|small */
    'size' => 'default',
    /** h1|h2|h3|h4|h5|h6|div */
    'titleElement' => 'h2',
    /** Whether the alert is rendered visible. */
    'open' => true,
    /** Delay in ms before close, kept for API parity — inert without the JS click handler. */
    'closeDelay' => 0,
    /** Extra attributes forwarded to the close button (e.g. wire:click). */
    'closeAttributes' => [],
])

@php
    $ariaLive = match ($role) {
        'alert' => 'assertive',
        'status' => 'polite',
        default => 'off',
    };

    $ariaLabel = $title ? "{$type} alert: {$title}" : "{$type} alert";

    $titleTag = in_array($titleElement, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true)
        ? $titleElement
        : 'div';
@endphp

{{-- Root is <tedi-alert>, the Angular selector's element: toast.component.scss:13
     keys the drop shadow on `.tedi-toast__wrapper tedi-alert`. `.tedi-alert` sets
     its own `display: flex` (overridden inline below for `open`), so the
     unknown-element `inline` default never applies. --}}
<tedi-alert
    {{ $attributes->class([
        'tedi-alert',
        'tedi-alert--'.$type,
        'tedi-alert--size-'.$size,
        'tedi-alert--global' => $variant === 'global',
        'tedi-alert--no-side-borders' => $variant === 'noSideBorders',
    ])->merge(array_filter([
        'role' => $role !== 'none' ? $role : null,
        'aria-live' => $ariaLive,
        'aria-label' => $ariaLabel,
    ]))->style([
        'display: '.($open ? 'flex' : 'none'),
    ]) }}
>
    <div class="tedi-alert__body">
        @if ($title)
            <div class="tedi-alert__head">
                @if ($icon)
                    <tedi:icon :name="$icon" :size="18" />
                @endif
                <{{ $titleTag }} class="tedi-alert__title">{{ $title }}</{{ $titleTag }}>
            </div>
            <div>
                {{ $slot }}
            </div>
        @else
            <div class="tedi-alert__content">
                @if ($icon)
                    <tedi:icon :name="$icon" :size="18" class="tedi-alert__content-icon" />
                @endif
                <div>
                    {{ $slot }}
                </div>
            </div>
        @endif
    </div>

    <div class="tedi-alert__action">
        {{ $action ?? '' }}
    </div>

    @if ($showClose)
        <tedi:closing-button
            class="tedi-alert__close"
            size="small"
            :icon-size="18"
            {{ $attributes->only([])->merge($closeAttributes) }}
        />
    @endif
</tedi-alert>
