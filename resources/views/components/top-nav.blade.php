{{--
    TEDI Top Nav — DOCUMENTED SUBSET.
    Port of react/src/tedi/components/layout/top-nav/top-nav.tsx (CONVENTIONS.md §13).

    A horizontal primary navigation bar with an optional mega-menu panel. This
    is a genuinely new component for the port — Angular ships `header` and
    `sidenav`, and nothing shaped like this.

    THE PIECES. `tedi:top-nav` is the `<nav>`, the bar and the `<ul>`. Items are
    `tedi:top-nav-item` and `tedi:top-nav-separator`. A submenu's contents are
    `tedi:top-nav-group` wrapping `tedi:top-nav-subitem`s. Where that submenu
    is *rendered* depends on the fit:

    * `full` (default) — one full-width panel per submenu item, each a sibling
      of the bar. Upstream renders a single panel and hoists the open item's
      children into it; Blade cannot read a descendant's slot (CONVENTIONS.md
      §5/§13.5), so here each panel is written out as its own
      `tedi:top-nav-submenu for="…"` in this component's `submenu` slot. The
      DOM position, classes and inline max-width are upstream's; there are just
      N panels instead of one, which §8 wants anyway — a closed panel is in the
      document with its real class list rather than conjured by JS.
    * `content` — the panel belongs inside its item's own `<li>`, so it goes in
      `tedi:top-nav-item`'s `submenu` slot and the markup is upstream's exactly.

    INTERACTIVITY. One inline `x-data` on the `<nav>` holds `openKey` and
    nothing else; items toggle it, panels bind `x-show` to it. Escape and
    outside-click close, and Escape returns focus to the open trigger — that is
    upstream's dismissal, ported. What upstream additionally does on mount,
    opening the submenu of an item that is both a toggle and `isActive`, is the
    `open-key` prop here.

    `key` is how an item and its panel find each other, and it stands in for
    upstream's item *index*, which the server has no way to count
    (CONVENTIONS.md §5). It also builds the `aria-controls` / panel `id` pair.
    Give every submenu item one.

    NOT PORTED — the mobile branch. Below `mobileBreakpoint` upstream replaces
    the whole bar with `MobileNav`, which it builds by walking its children with
    `Children.map` and reconstructing every item and every submenu group as
    `SideNavItemProps` data. That reconstruction is precisely what a server
    render cannot do (§13.5), and the breakpoint that triggers it is a runtime
    measurement besides (§7 item 1). Render `tedi:sidenav` for small viewports
    and swap the two with `tedi:hide-at` / `tedi:show-at`. `mobileBreakpoint`,
    `isMobileOpen`, `onMenuToggle` and `showMobileOverlay` go with it and are
    deliberately NOT declared, per §7 item 1 — an undeclared prop shows up as a
    stray attribute instead of failing silently.
--}}
@props([
    /** Accessible name for the <nav> landmark. */
    'ariaLabel' => null,
    /** full|content — whether a submenu panel spans the nav or sits inside its item. */
    'submenuFit' => 'full',
    /** sm|md|lg|xl|xxl, a number of px, any CSS length, or 'none'/0 for no constraint. */
    'maxWidth' => 'xxl',
    /** Prefix for the submenu panel ids. Reaches the items and panels by `aware`. */
    'panelId' => 'top-nav-submenu',
    /** Key of the item whose submenu is open on first render. Null = none. */
    'openKey' => null,
])

@php
    $innerStyle = ($resolved = \Tedi\Livewire\Tedi::maxWidth($maxWidth))
        ? ['max-width: '.$resolved]
        : [];
@endphp

<nav
    {{ $attributes->class(['tedi-top-nav'])->merge(array_filter([
        'aria-label' => $ariaLabel,
    ])) }}
    x-data="{ openKey: {{ \Illuminate\Support\Js::from($openKey) }} }"
    x-on:keydown.escape.window="if (openKey !== null) { $el.querySelector('[aria-expanded=\'true\']')?.focus(); openKey = null }"
    x-on:click.outside="openKey = null"
>
    <div class="tedi-top-nav__bar">
        <ul @class(['tedi-top-nav__list']) @style($innerStyle)>
            {{ $slot }}
        </ul>
    </div>

    {{ $submenu ?? '' }}
</nav>
