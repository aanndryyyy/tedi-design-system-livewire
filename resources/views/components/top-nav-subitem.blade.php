{{--
    TEDI Top Nav Sub Item.
    Port of react/src/tedi/components/layout/top-nav/components/top-nav-subitem/top-nav-subitem.tsx
    (CONVENTIONS.md §13).

    A link inside a mega-menu column.

    Upstream reaches the TopNav context on click and closes the panel; the
    equivalent here is `openKey = null` on the same click, reading the `x-data`
    the nav already owns. That keeps the behaviour without a context, and it is
    additive — the navigation happens either way (CONVENTIONS.md §8).

    `as` exists because upstream is polymorphic (`as={Link}` for a router link).
    Here it picks the tag; with no `href` and no `as`, an `<a>` with no
    destination is still what upstream renders, so that is kept rather than
    silently promoted to a button.
--}}
@props([
    /** Destination URL. */
    'href' => null,
    /** Marks this link as the current page. */
    'isActive' => false,
    /** Element to render — upstream's polymorphic `as`. */
    'as' => 'a',
])

<li class="tedi-top-nav__subitem">
    <{{ $as }}
        @if ($href) href="{{ $href }}" @endif
        @if ($isActive) aria-current="page" @endif
        {{ $attributes->class([
            'tedi-top-nav__subitem-link',
            'tedi-top-nav__subitem-link--active' => $isActive,
        ]) }}
        x-on:click="openKey = null"
    >{{ $slot }}</{{ $as }}>
</li>
