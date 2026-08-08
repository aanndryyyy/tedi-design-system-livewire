@storybook([
    'name' => 'Logged Out With Search',
    'order' => 3,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

{{--
    Drops the mobile sidenav (`<nav tedi-sidenav>`, unported — see
    default.blade.php's comment). Angular's `[showLogo]` on this story is
    bound to a custom `storyResponsive` directive that watches a 420px media
    query client-side; that behaviour has no static Blade equivalent, so the
    logo is always shown here (`showLogo` defaults to true).

    Angular shows a compact search bar in `header-actions` only in a
    `*showAt('md')`/`*hideAt('lg')` window and again below `md`; breakpoint
    gating is not ported (CONVENTIONS.md §7), so this port keeps a single
    always-visible search in header-actions.
--}}
<tedi:header>
    <x-slot:toggle>
        <tedi:header.toggle />
    </x-slot:toggle>

    <tedi:header.logo href="/">
        <img src="header-logo.svg" alt="Logo">
        <x-slot:darkLogo>
            <img src="header-logo-white.svg" alt="Logo (Dark Mode)">
        </x-slot:darkLogo>
    </tedi:header.logo>

    <tedi:header.content alignment="space-between">
        <div>
            <tedi:link href="#" :underline="false">Avaleht</tedi:link>
            <tedi:link href="#" :underline="false">Teenused</tedi:link>
            <tedi:link href="#" :underline="false">Blogi</tedi:link>
            <tedi:link href="#" :underline="false">Kontakt</tedi:link>
        </div>
        <tedi:header.search>
            <div style="width: 100%; max-width: 22.5rem;">
                <input type="search" class="tedi-input" placeholder="Otsi">
            </div>
        </tedi:header.search>
    </tedi:header.content>

    <tedi:header.actions>
        <tedi:header.language :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
        <tedi:separator axis="vertical" />
        <tedi:header.login />
    </tedi:header.actions>
</tedi:header>
