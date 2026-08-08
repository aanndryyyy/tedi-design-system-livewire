@storybook([
    'name' => 'With Top Header And Language Dropdown',
    'order' => 15,
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
    Same as with-top-header.blade.php, but the top bar's language switcher is
    `header.language` (dropdown) instead of plain links. The story-local
    theme toggle is dropped for the same reason (form/toggle not ported).
    Same breakpoint-driven role/profile duplication drop as
    logged-in.blade.php.
--}}
<tedi:header>
    <x-slot:top>
        <tedi:header.top alignment="space-between">
            <tedi:header.language label-position="left" :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
            <div style="display: flex; gap: var(--layout-grid-gutters-08); align-items: center;">
                <tedi:link href="#" :underline="false" aria-current="page" style="color: var(--link-primary-active); font-weight: var(--body-bold-weight);">
                    Eraisik
                </tedi:link>
                <tedi:link href="#">Ettevõtja</tedi:link>
            </div>
        </tedi:header.top>
    </x-slot:top>

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
            :representatives="$organizations"
            :current-representative="$currentOrganization"
            is-organization
            show-search
        />
        <tedi:separator axis="vertical" />

        <tedi:header.profile show-label label="Mari Maasikas">
            <tedi:link href="#" :underline="false">Minu andmed</tedi:link>
            <tedi:link href="#" :underline="false">Esindatavad</tedi:link>
            <tedi:link href="#" :underline="false">Kontaktid</tedi:link>
            <tedi:separator />
            <tedi:link href="#" :underline="false">
                <tedi:icon name="notifications" />
                Teated
            </tedi:link>
            <tedi:separator />
            <tedi:header.logout href="#" />
        </tedi:header.profile>
    </tedi:header.actions>
</tedi:header>
