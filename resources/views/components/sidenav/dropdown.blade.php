{{--
    TEDI SideNav Dropdown.
    Port of angular/tedi/components/layout/sidenav/sidenav-dropdown/sidenav-dropdown.component.{ts,html}

    This is NOT the overlay `<tedi:dropdown>`. Angular's `SideNavDropdownComponent`
    imports nothing from `overlay/dropdown`: it is a `<ul class="tedi-sidenav-dropdown">`
    inside a `tedi-sidenav-dropdown-wrapper` grid, and the collapsed fly-out is
    positioned entirely by the vendored SCSS
    (`.tedi-sidenav--collapsed .tedi-sidenav-dropdown { position: absolute; left: calc(100% + …) }`).
    There is no CDK Overlay upstream, so there is no `tediOverlay` here either
    (CONVENTIONS.md §11 applies only to components that were anchored by CDK).

    Angular puts the wrapper class on the host and the `--open` class on the
    inner `<ul>`; both are reproduced, and the root is the literal
    `<tedi-sidenav-dropdown>` element per CONVENTIONS.md §4.

    Per CONVENTIONS.md §8 the `<ul>` is always in the DOM with its real class
    list — TEDI's own CSS does the hiding (`visibility: hidden` + a 0fr grid
    row), so no `x-show`/`x-cloak` is involved at all. Alpine only adds the
    `--open` class, written after the static list per §4.

    `open` and the collapsed parent-link's `href`/`route` reach this component
    from `<tedi:sidenav.item>` through `@aware` (CONVENTIONS.md §3), with the
    same defaults the item declares. The parent link's *text* cannot: Angular
    reads it off the DOM, so it is the explicit `parent-label` prop (§5). The
    parent link is what Angular renders in the collapsed fly-out so the parent
    page stays reachable when its trigger has become a dropdown toggle.
--}}
@props([
    /** Text of the generated parent link shown in the collapsed fly-out (§5). */
    'parentLabel' => null,
])
@aware([
    'collapsed' => false,
    'mobile' => false,
    'open' => false,
    'href' => null,
    'route' => null,
])

@php
    $isCollapsed = $collapsed && ! $mobile;
    $parentHref = $isCollapsed ? $href : null;
    $parentRoute = $isCollapsed && ! $href ? $route : null;
@endphp

<tedi-sidenav-dropdown {{ $attributes->class(['tedi-sidenav-dropdown-wrapper']) }}>
    <ul
        @class([
            'tedi-sidenav-dropdown',
            'tedi-sidenav-dropdown--open' => $open,
        ])
        x-bind:class="{ 'tedi-sidenav-dropdown--open': $data.sidenavOpen }"
    >
        @if ($parentHref)
            <tedi:sidenav.dropdown-item class="tedi-sidenav-dropdown-item--parent" :href="$parentHref">
                {{ $parentLabel }}
            </tedi:sidenav.dropdown-item>
        @elseif ($parentRoute)
            <tedi:sidenav.dropdown-item class="tedi-sidenav-dropdown-item--parent" :route="$parentRoute">
                {{ $parentLabel }}
            </tedi:sidenav.dropdown-item>
        @endif

        {{ $slot }}
    </ul>
</tedi-sidenav-dropdown>
