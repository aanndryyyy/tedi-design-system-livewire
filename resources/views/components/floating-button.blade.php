{{--
    TEDI Floating Button (community).
    Port of angular/community/components/buttons/floating-button/floating-button.component.ts

    Ported from the `community/` tree — see CONVENTIONS.md §12.

    Angular's selector is the ATTRIBUTE `[tedi-floating-button]`, so per §4 the
    root carries the literal `tedi-floating-button` attribute as well as the
    class list. It is always a `<button>`: the vendored SCSS opens with
    `button.tedi-floating-button { … }`, so the whole block — including every
    `button-main-styles()` declaration — applies to no other element. Unlike
    `tedi:button` there is therefore no `href` prop; wrap the button in a form
    or bind a click handler instead.

    The component composes `BaseButtonDirective` with its class prefix set to
    `tedi-floating-button`, which means the same runtime DOM introspection
    `tedi:button` has. Per CONVENTIONS.md §5 that becomes the explicit
    `icon-start` / `icon-end` / `icon-only` props, exactly as there. Unlike
    `tedi:button` they render at no explicit size: `button-main-styles()` sets
    `tedi-icon { --_tedi-icon-size: var(--icon-03) }` inside the button, and an
    explicit size would emit an inline custom property that overrides it.

    DROPPED CLASS: `tedi-floating-button--horizontal`. Angular emits an axis
    class for both values, but the vendored SCSS styles only `&--vertical`
    (`rotate(-90deg)` plus the squared-off corners); horizontal is the unstyled
    base appearance, so dropping the class is behaviourally identical
    (CONVENTIONS.md §4). `--vertical` is emitted as normal.
--}}
@props([
    /** primary|secondary */
    'variant' => 'primary',
    /** default|large */
    'size' => 'default',
    /** horizontal|vertical — `vertical` rotates the button 90°. */
    'axis' => 'horizontal',
    /** submit|button|reset */
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
    // BaseButtonDirective.classes(), with classNamePrefix "tedi-floating-button".
    $iconFirst = $iconOnly || (bool) $iconStart;
    $iconLast = $iconOnly || (bool) $iconEnd;
@endphp

<button
    tedi-floating-button
    type="{{ $type }}"
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-floating-button',
        'tedi-floating-button--'.$variant,
        'tedi-floating-button--'.$size,
        'tedi-floating-button--vertical' => $axis === 'vertical',
        'tedi-floating-button--icon-only' => $iconOnly,
        'tedi-floating-button--pl' => ! $iconFirst,
        'tedi-floating-button--pr' => ! $iconLast,
    ]) }}
>
    @if ($iconStart)
        <tedi:icon :name="$iconStart" color="inherit" />
    @endif

    {{ $slot }}

    @if ($iconEnd)
        <tedi:icon :name="$iconEnd" color="inherit" />
    @endif
</button>
