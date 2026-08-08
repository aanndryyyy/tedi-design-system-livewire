{{--
    TEDI Button.
    Port of angular/tedi/components/buttons/button/{button.component.ts,base-button.directive.ts}

    Angular's BaseButtonDirective inspects projected DOM (AfterContentChecked) to
    derive --icon-only / --pl / --pr. Blade cannot introspect its own slot, so per
    CONVENTIONS.md §5 those become the explicit props icon-start / icon-end /
    icon-only.
--}}
@props([
    /** primary|secondary|neutral|success|danger|danger-neutral|primary-inverted|secondary-inverted|neutral-inverted|primary-button-group|secondary-button-group */
    'variant' => 'primary',
    /** default|small */
    'size' => 'default',
    /** Renders as <a> when set. */
    'href' => null,
    /** submit|button|reset — ignored when href is set. */
    'type' => 'button',
    /** Material Symbols name rendered before the label. */
    'iconStart' => null,
    /** Material Symbols name rendered after the label. */
    'iconEnd' => null,
    /** Icon-only button; pass an accessible label via aria-label. */
    'iconOnly' => false,
    'disabled' => false,
])

@php
    $tag = $href ? 'a' : 'button';

    // Mirrors BaseButtonDirective.classes(): padding modifiers are dropped on the
    // side that starts/ends with an icon.
    $iconFirst = $iconOnly || (bool) $iconStart;
    $iconLast  = $iconOnly || (bool) $iconEnd;

    $iconSize = $size === 'small' ? 18 : 24;
@endphp

<{{ $tag }}
    @if ($href)
        href="{{ $disabled ? null : $href }}"
        @if ($disabled) aria-disabled="true" role="link" tabindex="-1" @endif
    @else
        type="{{ $type }}"
        @disabled($disabled)
    @endif
    {{ $attributes->class([
        'tedi-button',
        'tedi-button--'.$variant,
        'tedi-button--'.$size,
        'tedi-button--icon-only' => $iconOnly,
        'tedi-button--pl' => ! $iconFirst,
        'tedi-button--pr' => ! $iconLast,
    ]) }}
>
    @if ($iconStart)
        <tedi:icon :name="$iconStart" :size="$iconSize" color="inherit" />
    @endif

    {{ $slot }}

    @if ($iconEnd)
        <tedi:icon :name="$iconEnd" :size="$iconSize" color="inherit" />
    @endif
</{{ $tag }}>
