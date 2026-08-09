{{--
    TEDI SideNav Dropdown Item.
    Port of angular/tedi/components/layout/sidenav/sidenav-dropdown-item/sidenav-dropdown-item.component.{ts,html}

    Angular's host is `<tedi-sidenav-dropdown-item role="presentation"
    style="display: contents">` with the class list on an inner `<li>`. Both are
    reproduced literally: the vendored SCSS keys on the element for the tree
    connector lines — `tedi-sidenav-dropdown-item:last-child > .tedi-sidenav-dropdown-item::before`
    and `tedi-sidenav-dropdown-item:has(+ tedi-sidenav-dropdown-group) > …`
    (CONVENTIONS.md §4). `$attributes` goes on the host, which is where a
    consumer's `class` lands in Angular too — that is how
    `tedi-sidenav-dropdown-item--parent` is applied by `<tedi:sidenav.dropdown>`.

    `route` is Angular's `[routerLink]`; with no Angular router it renders as an
    ordinary `href`, and is kept as its own prop only to mirror the input list.
    With neither `href` nor `route`, the trigger is a `<div>`, exactly as
    upstream.

    Angular's `textContent` signal (read off the DOM after view init) exists
    only so `<tedi-sidenav-dropdown-group>` can re-render this item's label; the
    group takes an explicit `items` array here instead (§5), so no equivalent
    prop is needed on this component.
--}}
@props([
    /** Is navigation item selected? */
    'selected' => false,
    /** External link. */
    'href' => null,
    /** Router link upstream; rendered as a plain href here. */
    'route' => null,
])

@php
    $linkHref = $href ?: $route;
@endphp

<tedi-sidenav-dropdown-item {{ $attributes->merge(['role' => 'presentation'])->style(['display: contents']) }}>
    <li @class([
        'tedi-sidenav-dropdown-item',
        'tedi-sidenav-dropdown-item--selected' => $selected,
    ])>
        @if ($linkHref)
            <a href="{{ $linkHref }}" class="tedi-sidenav-dropdown-item__trigger">{{ $slot }}</a>
        @else
            <div class="tedi-sidenav-dropdown-item__trigger">{{ $slot }}</div>
        @endif
    </li>
</tedi-sidenav-dropdown-item>
