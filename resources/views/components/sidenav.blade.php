{{--
    TEDI SideNav (root).
    Port of angular/tedi/components/layout/sidenav/sidenav.component.{ts,html}

    Angular's selector is the attribute form `nav[tedi-sidenav]`, so the root is
    a literal `<nav tedi-sidenav>` (CONVENTIONS.md §4's element/attribute
    selector rule).

    STATE: Angular drives everything through the injectable `SideNavService`
    (`isCollapsed`, `isMobile`, `isMobileOpen`, `isMobileItemOpen`, plus a
    registry of every `tedi-sidenav-item`). A service is not portable, so per
    CONVENTIONS.md §5 each signal becomes an explicit prop here, and per §8 an
    inline Alpine layer is added on top so the collapse/mobile toggles actually
    work. A consumer who strips the JS still gets the correct static markup —
    every class the props imply is in the real `class` attribute, and the
    Alpine bindings (which per §4 are written *after* `$attributes->class()`)
    only flip them afterwards.

    - `collapsed`      ← SideNavService.isCollapsed()
    - `mobile`         ← SideNavService.isMobile()      (see §7 note below)
    - `mobileOpen`     ← SideNavService.isMobileOpen()
    - `mobileItemOpen` ← SideNavService.isMobileItemOpen()

    `mobile` is the §7 #1 translation of `isMobile()`, which Angular computes
    from `BreakpointService` at runtime. Blade has no viewport, so the mobile
    branch is an explicit opt-in prop. Angular's `desktopBreakpoint` input is
    therefore NOT declared at all (§7 #1's ruling on inert breakpoint props) —
    passing it leaves a visible stray attribute rather than a silent no-op.

    Angular's effect that forces `isCollapsed` back to false while `isMobile()`
    is ported as the `! $mobile` guard on the `--collapsed` class, in both the
    static list and the Alpine binding.

    `<tedi:sidenav.toggle>` and `<tedi:sidenav.overlay>` are DOM *siblings* of
    this element (that is how the Angular stories place them), so they cannot
    reach this scope through Alpine nesting or `@aware`. They talk to it with
    two window events, mirroring the fact that upstream's service is an
    application-wide singleton — several sidenavs on one page therefore share
    the toggle, exactly as they would in Angular:

    - `tedi-sidenav:toggle`    `{ open: bool }` — the mobile drawer state.
    - `tedi-sidenav:close-all`                  — collapse every item dropdown
                                                  (`handleGoToMainMenu()`).

    Items ARE descendants, so they read/write this scope directly through
    Alpine's merged data stack (`$data.tediSidenavItemToggled`, …).

    NOT PORTED: `handleBackToMainMenu()`'s focus restoration to the previously
    open item, and the item-registry itself — the same focus-management
    exclusion the overlay components make (CONVENTIONS.md §11).
--}}
@props([
    /** Show dividers between items. */
    'dividers' => true,
    /** small|medium|large — size of navigation item. */
    'size' => 'large',
    /** Is navigation collapsible in desktop? Renders the collapse button. */
    'collapsible' => false,
    /** SideNavService.isCollapsed() as an explicit prop (§5). Ignored while `mobile`. */
    'collapsed' => false,
    /** SideNavService.isMobile() as an explicit prop (§5/§7 — no viewport at render time). */
    'mobile' => false,
    /** SideNavService.isMobileOpen() — is the mobile drawer open? */
    'mobileOpen' => false,
    /** SideNavService.isMobileItemOpen() — is a sub-menu drilled into on mobile? */
    'mobileItemOpen' => false,
])

@php
    $isCollapsed = $collapsed && ! $mobile;
    // 'toggle' | tediTranslate: !isCollapsed(). Upstream's `toggle` key does not
    // exist in translations.ts, so Angular renders the raw key here; the port
    // uses the sidenav.toggle.{true,false} pair, which carries the intended text.
    $collapseLabelOpen = __('tedi::tedi.sidenav.toggle.false');
    $collapseLabelClose = __('tedi::tedi.sidenav.toggle.true');
@endphp

<nav
    tedi-sidenav
    {{ $attributes->class([
        'tedi-sidenav',
        'tedi-sidenav--'.$size,
        'tedi-sidenav--dividers' => $dividers,
        'tedi-sidenav--collapsed' => $isCollapsed,
        'tedi-sidenav--mobile' => $mobile,
        'tedi-sidenav--mobile-item-open' => $mobileItemOpen,
        'tedi-sidenav--hidden' => $mobile && ! $mobileOpen,
    ]) }}
    x-data="{
        tediSidenavCollapsed: {{ $isCollapsed ? 'true' : 'false' }},
        tediSidenavMobileOpen: {{ $mobileOpen ? 'true' : 'false' }},
        tediSidenavOpenCount: {{ $mobileItemOpen ? 1 : 0 }},
        get tediSidenavMobileItemOpen() {
            return {{ $mobile ? 'true' : 'false' }} && this.tediSidenavOpenCount > 0;
        },
        tediSidenavItemToggled(isOpen) {
            this.tediSidenavOpenCount = Math.max(0, this.tediSidenavOpenCount + (isOpen ? 1 : -1));
        },
        tediSidenavCloseAll() {
            this.tediSidenavOpenCount = 0;
            this.$dispatch('tedi-sidenav:close-all');
        },
    }"
    x-on:tedi-sidenav:toggle.window="tediSidenavMobileOpen = $event.detail.open"
    x-on:tedi-sidenav:close-all.window="tediSidenavOpenCount = 0"
    x-bind:class="{
        'tedi-sidenav--collapsed': {{ $mobile ? 'false' : 'true' }} && tediSidenavCollapsed,
        'tedi-sidenav--mobile-item-open': tediSidenavMobileItemOpen,
        'tedi-sidenav--hidden': {{ $mobile ? 'true' : 'false' }} && ! tediSidenavMobileOpen,
    }"
>
    @if (! $mobile && $collapsible)
        <button
            type="button"
            class="tedi-sidenav__collapse"
            aria-label="{{ $isCollapsed ? $collapseLabelOpen : $collapseLabelClose }}"
            x-on:click="tediSidenavCollapsed = ! tediSidenavCollapsed; tediSidenavCloseAll()"
            x-bind:aria-label="tediSidenavCollapsed ? @js($collapseLabelOpen) : @js($collapseLabelClose)"
        >
            <tedi:icon
                :name="$isCollapsed ? 'left_panel_open' : 'right_panel_open'"
                color="brand"
                x-text="tediSidenavCollapsed ? 'left_panel_open' : 'right_panel_open'"
            />
        </button>
    @endif

    @if ($mobile)
        {{--
            Angular structurally @ifs this button on isMobileItemOpen(). Keeping
            it in the DOM behind x-show is what CONVENTIONS.md §8 requires so its
            real class list is assertable; it is emitted only in the mobile
            branch, so the desktop DOM stays identical to Angular's.
        --}}
        <button
            type="button"
            class="tedi-sidenav-back"
            style="display: none"
            x-cloak
            x-show="tediSidenavMobileItemOpen"
            x-on:click="tediSidenavCloseAll()"
        >
            <tedi:icon name="arrow_back" color="white" />
            {{ __('tedi::tedi.sidenav.backToMainMenu') }}
        </button>
    @endif

    <ul class="tedi-sidenav__list">
        {{ $slot }}
    </ul>
</nav>
