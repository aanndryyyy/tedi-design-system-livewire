@storybook([
    'name' => 'Logged Out',
    'order' => 2,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

{{--
    As with Default, the mobile sidenav menu (`<nav tedi-sidenav>`) is dropped
    — layout/sidenav is not ported in this phase (README "Not in this
    phase") — while the header's own mobile toggle button is kept via
    tedi:header.toggle (see header/toggle.blade.php).
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

    <tedi:header.content>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
    </tedi:header.content>

    <tedi:header.actions>
        <tedi:header.language :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
        <tedi:separator axis="vertical" />
        <tedi:header.login />
    </tedi:header.actions>
</tedi:header>
