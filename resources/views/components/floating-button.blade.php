{{--
    TEDI Floating Button.
    Port of angular/src/tedi/components/buttons/floating-button/floating-button.component.ts

    Formerly ported from Angular's `community/` tree. Upstream promoted it to
    `tedi/` (8.1) and deleted the community copy, so `tedi/` is the source now
    (CONVENTIONS.md §1).

    Angular's selector is the ATTRIBUTE `[tedi-floating-button]`, so per §4 the
    root carries the literal `tedi-floating-button` attribute as well as the
    class list. Upstream's selector fits any element, and its stylesheet now
    keys on the class alone; the Blade port always renders a `<button>` (every
    upstream story does too), so there is no `href` prop — wrap the button in a
    form or bind a click handler instead.

    The component composes `BaseButtonDirective` with its class prefix set to
    `tedi-floating-button`, which means the same runtime DOM introspection
    `tedi:button` has. Per CONVENTIONS.md §5 that becomes the explicit
    `icon-start` / `icon-end` / `icon-only` props, exactly as there. Unlike
    `tedi:button` they render at no explicit size: `button-main-styles()` sets
    `tedi-icon { --_tedi-icon-size: var(--icon-03) }` inside the button, and an
    explicit size would emit an inline custom property that overrides it.
    `BaseButtonDirective` also adds core's `no-print`, so the button is left
    off the printed page; add `class="show-print"` to keep it.

    Positioning is inline style, as upstream's host style bindings:
    `position` (default `fixed` — upstream's default, so a floating button
    floats unless you pass `position="static"`), `z-index` from `z-index` (the
    stylesheet's `--z-index-feedback` otherwise), and `top`/`bottom`/`left`/
    `right`/`translate` from `placement` + `offset`. `placement` is an array
    `['vertical' => 'top|bottom|center', 'horizontal' => 'left|right|center']`;
    `offset` is `['top' => …, 'bottom' => …, 'left' => …, 'right' => …]` where a
    number is px and a string is any CSS length. Only the edges `placement`
    names are written, and `position="static"` ignores `placement` entirely.

    DROPPED CLASS: `tedi-floating-button--horizontal`. Angular emits an axis
    class for both values, but the vendored SCSS styles only `&--vertical`;
    horizontal is the unstyled base appearance, so dropping the class is
    behaviourally identical (CONVENTIONS.md §4). `--vertical` is emitted as
    normal.
--}}
@props([
    /** primary|secondary */
    'variant' => 'primary',
    /** default|large */
    'size' => 'default',
    /** horizontal|vertical — `vertical` rotates the button 90° counter-clockwise. */
    'axis' => 'horizontal',
    /** fixed|absolute|sticky|relative|static — CSS positioning mode. */
    'position' => 'fixed',
    /** ['vertical' => top|bottom|center, 'horizontal' => left|right|center] — where to pin the button. */
    'placement' => null,
    /** ['top' => …, 'bottom' => …, 'left' => …, 'right' => …] — distance from the pinned edges; numbers are px. */
    'offset' => null,
    /** Overrides the CSS z-index; `--z-index-feedback` when omitted. */
    'zIndex' => null,
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

    // FloatingButtonComponent.placementStyles().
    $pinned = $position === 'static' ? [] : (array) ($placement ?? []);
    $vertical = $pinned['vertical'] ?? null;
    $horizontal = $pinned['horizontal'] ?? null;
    $offset = (array) ($offset ?? []);
    $edge = function (bool $isPinned, $value) {
        if (! $isPinned) {
            return null;
        }
        $value ??= 0;

        return is_int($value) || is_float($value) ? $value.'px' : (string) $value;
    };
    $centered = $horizontal === 'center' || $vertical === 'center';

    $styles = array_filter([
        'position' => $position,
        'z-index' => $zIndex !== null ? (string) $zIndex : null,
        'top' => $vertical === 'center' ? '50%' : $edge($vertical === 'top', $offset['top'] ?? null),
        'bottom' => $edge($vertical === 'bottom', $offset['bottom'] ?? null),
        'left' => $horizontal === 'center' ? '50%' : $edge($horizontal === 'left', $offset['left'] ?? null),
        'right' => $edge($horizontal === 'right', $offset['right'] ?? null),
        'translate' => $centered
            ? ($horizontal === 'center' ? '-50%' : '0').' '.($vertical === 'center' ? '-50%' : '0')
            : null,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

<button
    tedi-floating-button
    type="{{ $type }}"
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-floating-button',
        'no-print',
        'tedi-floating-button--'.$variant,
        'tedi-floating-button--'.$size,
        'tedi-floating-button--vertical' => $axis === 'vertical',
        'tedi-floating-button--icon-only' => $iconOnly,
        'tedi-floating-button--pl' => ! $iconFirst,
        'tedi-floating-button--pr' => ! $iconLast,
    ])->style(array_map(fn ($property, $value) => $property.': '.$value, array_keys($styles), $styles)) }}
>
    @if ($iconStart)
        <tedi:icon :name="$iconStart" color="inherit" />
    @endif

    {{ $slot }}

    @if ($iconEnd)
        <tedi:icon :name="$iconEnd" color="inherit" />
    @endif
</button>
