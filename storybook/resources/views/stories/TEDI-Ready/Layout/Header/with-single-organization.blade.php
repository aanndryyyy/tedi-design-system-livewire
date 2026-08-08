@storybook([
    'name' => 'With Single Organization',
    'order' => 7,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

@php
    $organizations2 = [
        ['id' => 'org-2', 'name' => 'Tartu Linnavalitsus'],
    ];
    $currentOrganization2 = ['id' => 'org-2', 'name' => 'Tartu Linnavalitsus'];
@endphp

{{--
    A single representative makes header.role's own `count($representatives)
    > 1` heuristic hide the dropdown trigger and render the plain value —
    same rule Angular exercises via `showRoleSwitch` defaulting to false.
    Same breakpoint-driven duplication drop as logged-in.blade.php.
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
            :representatives="$organizations2"
            :current-representative="$currentOrganization2"
        />
        <tedi:separator axis="vertical" />

        <tedi:header.language :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
        <tedi:separator axis="vertical" />

        <tedi:header.profile>
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
