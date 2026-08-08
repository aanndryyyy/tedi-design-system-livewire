{{--
    TEDI Header Toggle (mobile menu button).

    Divergence (document, don't fake): Angular's header stories place a
    `<button tedi-sidenav-toggle>` (SideNavToggleComponent, from
    layout/sidenav — out of this port's scope) in the header's mobile-toggle
    slot. That component toggles `SideNavService.isMobileOpen` and its
    accessible label uses the "sidenav.toggle" translation key (one static
    string, not an open/closed pair).

    Per explicit instruction, this port instead ships a self-contained toggle
    using the "header.toggle.true" / "header.toggle.false" translation keys
    (lang/en/tedi.php), which exist for exactly this purpose but are unused
    elsewhere in the current Angular component tree. It reuses
    `tedi-sidenav-toggle` / `tedi-sidenav-toggle__icon` — the only vendored
    classes for a header-mobile-toggle button — since "keep classes identical
    to Angular" means the classes this button actually renders with in every
    header story, not the Angular class of component that happens to own them.
    There is no sidenav service here: `open` is local Alpine state. A real
    sidenav integration should bind its own `x-on:click` via `$attributes` or
    replace this component outright.
--}}
@props([])

<button
    type="button"
    x-data="{ open: false }"
    x-on:click="open = ! open"
    x-bind:aria-label="open ? @js(__('tedi::tedi.header.toggle.true')) : @js(__('tedi::tedi.header.toggle.false'))"
    {{ $attributes->class(['tedi-sidenav-toggle']) }}
>
    <tedi:icon
        name="menu"
        class="tedi-sidenav-toggle__icon"
        x-text="open ? 'close' : 'menu'"
    />
</button>
