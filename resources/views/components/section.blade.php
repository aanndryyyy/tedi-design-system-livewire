{{--
    TEDI Section.
    Port of react/src/tedi/components/content/section/section.tsx (CONVENTIONS.md §13).

    A page-level content band: nothing but the responsive horizontal padding
    (0.5rem → 1.5rem at md → 2.5rem at lg) and `overflow: clip`. There are no
    modifier classes and no state, so the whole component is its root element.

    `as` is upstream's own prop, not this port's usual escape hatch — React
    declares `'section' | 'article' | 'aside' | 'div'` and defaults to
    `section`. Anything else you pass is emitted as written; the padding rule
    keys on the class, not the tag.

    `role` and `id` are upstream props too, but they need no declaration here —
    they arrive through `$attributes` and land on the root, which is where
    upstream puts them.
--}}
@props([
    /** section|article|aside|div — the element to render. */
    'as' => 'section',
])

<{{ $as }} {{ $attributes->class(['tedi-section']) }}>{{ $slot }}</{{ $as }}>
