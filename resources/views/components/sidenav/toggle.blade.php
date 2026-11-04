{{--
    TEDI SideNav Toggle.
    Port of angular/src/tedi/components/layout/sidenav/sidenav-toggle/sidenav-toggle.component.{ts,html}

    Angular's selector is `button[tedi-sidenav-toggle]`, so the root is a literal
    `<button tedi-sidenav-toggle>` (CONVENTIONS.md §4's attribute-selector rule).
    `tedi-sidenav-toggle--hidden` is emitted whenever the sidenav is NOT in its
    mobile branch, matching `if (!isMobile())` upstream — the button only exists
    for the mobile drawer.

    Like `<tedi:sidenav.overlay>`, this is a DOM sibling of `<nav tedi-sidenav>`,
    so `mobile` / `mobile-open` are explicit props (§5) and the live wiring is
    the pair of window events documented on `<tedi:sidenav>`: a click flips the
    drawer, dispatches `tedi-sidenav:toggle { open }` and
    `tedi-sidenav:close-all` (Angular's `handleGoToMainMenu()`), and the button
    also listens so a click on the overlay keeps its icon and label in sync.

    `aria-label` uses `sidenav.toggle.{true,false}`; upstream calls the same key
    with `isMobileOpen()`, and the generated lang files split parameterised keys
    into that `.true`/`.false` pair.

    DRAWER OFFSET (Angular 8+). Upstream registers the toggle with
    `SideNavService`, which measures the bottom edge of the toggle's closest
    `<header>` (or of the toggle itself) so the fixed mobile drawer and its
    overlay open below the header. The port measures the same edge and sends it
    as `top` on every `tedi-sidenav:toggle` it dispatches — on click, on init
    when the drawer starts open, and on window resize while it is open (upstream
    uses a `ResizeObserver` on the header instead; see sidenav.blade.php).

    NOTE: `header/toggle.blade.php` renders the same `tedi-sidenav-toggle`
    classes as a self-contained header button, written while sidenav was out of
    scope. This component is the real port; a header can slot it in instead.
--}}
@props([
    /** SideNavService.isMobile() as an explicit prop (§5/§7). */
    'mobile' => false,
    /** SideNavService.isMobileOpen(). */
    'mobileOpen' => false,
])

<button
    type="button"
    tedi-sidenav-toggle
    aria-label="{{ $mobileOpen ? __('tedi::tedi.sidenav.toggle.true') : __('tedi::tedi.sidenav.toggle.false') }}"
    {{ $attributes->class([
        'tedi-sidenav-toggle',
        'tedi-sidenav-toggle--hidden' => ! $mobile,
    ]) }}
    x-data="{
        tediSidenavToggleOpen: {{ $mobileOpen ? 'true' : 'false' }},
        tediSidenavToggleTop() {
            const anchor = this.$el.closest('header') ?? this.$el;
            return Math.max(0, anchor.getBoundingClientRect().bottom);
        },
    }"
    x-init="tediSidenavToggleOpen && $nextTick(() => $dispatch('tedi-sidenav:toggle', { open: true, top: tediSidenavToggleTop() }))"
    x-on:tedi-sidenav:toggle.window="tediSidenavToggleOpen = $event.detail.open"
    x-on:resize.window="tediSidenavToggleOpen && $dispatch('tedi-sidenav:toggle', { open: true, top: tediSidenavToggleTop() })"
    x-on:click="tediSidenavToggleOpen = ! tediSidenavToggleOpen; $dispatch('tedi-sidenav:toggle', { open: tediSidenavToggleOpen, top: tediSidenavToggleTop() }); $dispatch('tedi-sidenav:close-all')"
    x-bind:aria-label="tediSidenavToggleOpen ? @js(__('tedi::tedi.sidenav.toggle.true')) : @js(__('tedi::tedi.sidenav.toggle.false'))"
>
    <tedi:icon
        :name="$mobileOpen ? 'close' : 'menu'"
        class="tedi-sidenav-toggle__icon"
        x-text="tediSidenavToggleOpen ? 'close' : 'menu'"
    />
</button>
