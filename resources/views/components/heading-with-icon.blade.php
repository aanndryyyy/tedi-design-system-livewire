{{--
    TEDI Heading with Icon.
    Port of react/src/tedi/components/content/heading-with-icon/heading-with-icon.tsx
    (CONVENTIONS.md §13).

    A heading that flexes an icon in front of its text. Upstream is a thin
    composer over `Heading` (itself a thin composer over `Text`) and `Icon`, and
    so is this: the root is `tedi:text` rendered as the heading element, with
    `tedi-heading-with-icon` merged into its class list.

    Two upstream details worth knowing, both kept:

    * The heading element is `h4` by default, and — because React's `Heading`
      passes `element` as the *tag* and only `modifiers` as the *class* — no
      `tedi-text--h4` typography modifier is emitted. The element is semantic;
      the visual size is the icon-flex rule plus whatever `modifiers` you pass
      through to `tedi:text`.
    * Upstream spreads its remaining props onto the icon as well as the heading,
      which is how `size` reaches the icon. Here the icon's props are explicit
      (`icon-size`, `icon-color`) so that a stray heading attribute cannot end
      up on the icon.

    The icon renders only when `name` is given, exactly as upstream.
--}}
@props([
    /** Material Symbols icon name. Omit to render the heading with no icon. */
    'name' => null,
    /** Heading tag: h1…h6. */
    'element' => 'h4',
    /** Colour of the heading text — any tedi:text colour. */
    'headingColor' => 'primary',
    /** Colour of the icon — any tedi:icon colour. */
    'iconColor' => 'primary',
    /** Icon size token: 8|12|16|18|22|24|36|48. */
    'iconSize' => 24,
    /** Typography modifier(s) forwarded to tedi:text, e.g. "h2" or "bold". */
    'modifiers' => null,
])

<tedi:text
    :as="$element"
    :color="$headingColor"
    :modifiers="$modifiers"
    {{ $attributes->class(['tedi-heading-with-icon']) }}
>@if ($name)<tedi:icon :name="$name" :color="$iconColor" :size="$iconSize" />@endif{{ $slot }}</tedi:text>
