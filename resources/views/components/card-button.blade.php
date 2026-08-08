{{--
    TEDI Card Button.
    Port of angular/tedi/components/buttons/card-button/card-button.component.{ts,html}

    Angular's selector is `a[tedi-card-button], button[tedi-card-button]` — the
    host directive has no inputs, it only adds interaction semantics on top of
    a projected `tedi-card`. The `href`/`disabled` polymorphism mirrors
    button.blade.php since Angular relies on native `<a>`/`<button>` semantics
    for the same effect (disabled only applies to `<button>`).
--}}
@props([
    /** Renders as <a> when set. */
    'href' => null,
    /** submit|button|reset — ignored when href is set. */
    'type' => 'button',
    'disabled' => false,
])

@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href)
        href="{{ $disabled ? null : $href }}"
        @if ($disabled) aria-disabled="true" role="link" tabindex="-1" @endif
    @else
        type="{{ $type }}"
        @disabled($disabled)
    @endif
    {{ $attributes->class(['tedi-card-button']) }}
>
    {{ $slot }}
</{{ $tag }}>
