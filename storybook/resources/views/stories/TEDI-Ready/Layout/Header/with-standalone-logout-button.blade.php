@storybook([
    'name' => 'With Standalone Logout Button',
    'order' => 11,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

@php
    $organizations = [
        ['id' => 'org-1', 'name' => 'Pärnu linnavolikogu'],
        ['id' => 'org-2', 'name' => 'Tartu Linnavalitsus'],
    ];
    $currentOrganization = ['id' => 'org-1', 'name' => 'Pärnu linnavolikogu'];
@endphp

{{--
    Angular hides `header-role` and shows a `*hideAt('lg')` profile menu with
    its own role copy — the desktop/mobile split for the same content.
    Breakpoint gating is not ported (CONVENTIONS.md §7), so this keeps the
    header-actions role selector (matching every other story here) and drops
    the mobile-only profile-menu duplicate.
--}}
<tedi:header>
    <tedi:header.logo href="/">
        <img src="header-logo.svg" alt="Logo">
        <x-slot:darkLogo>
            <img src="header-logo-white.svg" alt="Logo (Dark Mode)">
        </x-slot:darkLogo>
    </tedi:header.logo>

    <tedi:header.actions>
        <tedi:link href="#" :underline="false">
            Ligipääsetavus
            <tedi:icon name="north_east" :size="16" />
        </tedi:link>
        <tedi:separator axis="vertical" />
        <tedi:header.role
            label="Asutus"
            show-search
            is-organization
            :representatives="$organizations"
            :current-representative="$currentOrganization"
        />
        <tedi:separator axis="vertical" />

        <tedi:header.language :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
        <tedi:separator axis="vertical" />
        <tedi:header.logout href="#" />
    </tedi:header.actions>
</tedi:header>
