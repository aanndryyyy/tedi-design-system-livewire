{{--
    TEDI Tag.
    Port of angular/tedi/components/tags/tag/tag.component.{ts,html}

    Angular emits a `closed` output; per CONVENTIONS.md §3 the Blade port instead
    lets the consumer bind their own listener to the close button via the
    `close-attributes` prop, e.g.
        <tedi:tag closable :close-attributes="['wire:click' => 'removeTag(1)']">
--}}
@props([
    /** primary|secondary|danger */
    'type' => 'primary',
    /** Shows a spinner instead of the close button. */
    'loading' => false,
    /** Shows a close button. */
    'closable' => false,
    /** false|'start'|'end' — truncation side for the label. */
    'ellipsis' => false,
    /** Extra attributes forwarded to the close button (e.g. wire:click). */
    'closeAttributes' => [],
])

@php
    $uniqueId = \Tedi\Livewire\Tedi::id('tedi-tag');
    $ellipsisPosition = $ellipsis === 'start' ? 'start' : 'end';
@endphp

<span {{ $attributes->class([
    'tedi-tag',
    'tedi-tag--'.$type,
    'tedi-tag--loading' => (bool) $loading,
    'tedi-tag--closable' => (bool) $closable,
    'tedi-tag--ellipsis' => $ellipsis !== false,
]) }}>
    @if ($type === 'danger')
        <span class="tedi-tag__icon-wrapper">
            <tedi:icon name="error" color="danger" :size="16" />
        </span>
    @endif

    <span class="tedi-tag__content" id="{{ $uniqueId }}">
        @if ($ellipsis !== false)
            <tedi:ellipsis :position="$ellipsisPosition" :line-clamp="1">{{ $slot }}</tedi:ellipsis>
        @else
            {{ $slot }}
        @endif
    </span>

    @if ($loading)
        <span class="tedi-tag__spinner-wrapper">
            <tedi:spinner :size="48" />
        </span>
    @elseif ($closable)
        <tedi:closing-button
            size="small"
            :icon-size="18"
            :aria-label="__('tedi::tedi.remove')"
            aria-describedby="{{ $uniqueId }}"
            {{ $attributes->only([])->merge($closeAttributes) }}
        />
    @endif
</span>
