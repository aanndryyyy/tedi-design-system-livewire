{{--
    TEDI Heading with Icon.
    Port of angular/src/tedi/components/content/heading-with-icon/heading-with-icon.component.{ts,html}

    Previously ported from React (CONVENTIONS.md §13); Angular now ships it in
    `tedi/`, which is the source from here on (§1). The markup changed with
    it: the root is the literal `tedi-heading-with-icon` element (§4) holding a
    `tedi-heading-with-icon__icon` icon and a `tedi-heading-with-icon__heading`
    heading, where React put the block class on the heading itself.

    The icon is centred against the heading's first line through
    `--_tedi-heading-with-icon-line-height`, written inline on the root as
    upstream's host style binding does. It reads the visual level: the first
    heading modifier in `modifiers` (`h1`…`h6`) when there is one, otherwise
    `element` — a heading modifier wins over the tag in core's CSS, so
    `element="h2" modifiers="h4"` renders at h4's line height.

    The icon is decorative (`aria-hidden`); the slot is the accessible heading
    name. `icon` is required upstream; omitted here, the icon is not rendered.
--}}
@props([
    /** Material Symbols icon name. Required upstream. */
    'icon' => null,
    /** h1|h2|h3|h4|h5|h6 — semantic heading tag; also the typography unless a heading modifier overrides it. */
    'element' => 'h4',
    /** Typography modifier(s) forwarded to the heading's tedi:text, e.g. "h2" or ['h4', 'bold']. */
    'modifiers' => null,
    /** Colour of the heading text — any tedi:text colour. */
    'headingColor' => 'primary',
    /** Colour of the icon — any tedi:icon colour. */
    'iconColor' => 'primary',
    /** Icon size: 8|12|16|18|22|24|36|48|inherit. */
    'iconSize' => 24,
    /** outlined|filled */
    'iconVariant' => 'outlined',
])

@php
    $modifierList = is_array($modifiers) ? $modifiers : (filled($modifiers) ? [$modifiers] : []);
    $visualLevel = collect($modifierList)->first(fn ($m) => is_string($m) && preg_match('/^h[1-6]$/', $m)) ?? $element;
@endphp

<tedi-heading-with-icon {{ $attributes->class(['tedi-heading-with-icon'])->style([
    '--_tedi-heading-with-icon-line-height: var(--heading-'.$visualLevel.'-line-height)',
]) }}>
    @if ($icon)
        <tedi:icon
            class="tedi-heading-with-icon__icon"
            :name="$icon"
            :color="$iconColor"
            :size="$iconSize"
            :variant="$iconVariant"
        />
    @endif
    <tedi:text
        :as="$element"
        :color="$headingColor"
        :modifiers="$modifiers"
        class="tedi-heading-with-icon__heading"
    >{{ $slot }}</tedi:text>
</tedi-heading-with-icon>
