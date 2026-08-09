{{--
    TEDI SideNav Overlay.
    Port of angular/tedi/components/layout/sidenav/sidenav-overlay/sidenav-overlay.component.ts

    A dark scrim behind the mobile drawer. Angular's template is a bare
    `<ng-content />` and the whole class list is a host binding, so the root is
    the literal `<tedi-sidenav-overlay>` element (CONVENTIONS.md §4) carrying
    `tedi-sidenav-overlay` plus `--visible` when the mobile drawer is open.
    `.tedi-sidenav-overlay { display: none }` supplies the element's display, so
    no fallback is needed.

    The overlay is a DOM *sibling* of `<nav tedi-sidenav>` in every Angular
    story, so it cannot read the sidenav's state through `@aware` or Alpine
    nesting. `mobile` / `mobile-open` are therefore explicit props with the same
    defaults `<tedi:sidenav>` uses (§5), and the live state arrives over the
    same two window events `<tedi:sidenav>` documents:

    - listens to `tedi-sidenav:toggle` for the drawer state;
    - on click, dispatches `tedi-sidenav:toggle` `{ open: false }` and
      `tedi-sidenav:close-all`, which is Angular's
      `isMobileOpen.set(false)` + `handleGoToMainMenu()`.
--}}
@props([
    /** SideNavService.isMobile() as an explicit prop (§5/§7). */
    'mobile' => false,
    /** SideNavService.isMobileOpen(). */
    'mobileOpen' => false,
])

<tedi-sidenav-overlay
    {{ $attributes->class([
        'tedi-sidenav-overlay',
        'tedi-sidenav-overlay--visible' => $mobile && $mobileOpen,
    ]) }}
    x-data="{ tediSidenavOverlayOpen: {{ $mobileOpen ? 'true' : 'false' }} }"
    x-on:tedi-sidenav:toggle.window="tediSidenavOverlayOpen = $event.detail.open"
    x-on:click="tediSidenavOverlayOpen = false; $dispatch('tedi-sidenav:toggle', { open: false }); $dispatch('tedi-sidenav:close-all')"
    x-bind:class="{ 'tedi-sidenav-overlay--visible': {{ $mobile ? 'true' : 'false' }} && tediSidenavOverlayOpen }"
>{{ $slot }}</tedi-sidenav-overlay>
