{{--
    TEDI List.
    Port of angular/tedi/components/content/list/list.component.{ts,html}

    Angular's selector is `ul[tedi-list], ol[tedi-list]` — the `as` prop picks
    the element, mirroring text.blade.php's polymorphic `as`.
--}}
@props([
    /** ul|ol — element to render as. */
    'as' => 'ul',
    /** Is list styled? */
    'styled' => true,
    /** primary|secondary|tertiary|brand|brand-dark|success|warning|warning-dark|danger|white */
    'color' => 'brand',
])

<{{ $as }} {{ $attributes->class([
    'tedi-list',
    'tedi-list--bullet-color-'.$color,
    'tedi-list--unstyled' => ! $styled,
]) }}>{{ $slot }}</{{ $as }}>
