@storybook([
    'name' => 'With Mobile Bottom Search',
    'order' => 13,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

@php
    $representatives2 = [
        ['id' => '1', 'icon' => 'person', 'name' => 'Mari Maasikas', 'description' => '49504080934'],
    ];
    $currentRepresentative = ['id' => '1', 'icon' => 'person', 'name' => 'Mari Maasikas', 'description' => '49504080934'];
@endphp

{{--
    Demonstrates `header.bottom` — a secondary row rendered below the main
    header bar, hidden from `md` up purely by the vendored SCSS (no
    breakpoint prop needed, per header/bottom.blade.php). Angular's
    `mobileVariant="inline"` renders the search input directly rather than
    behind the modal toggle; `header.search`'s `mobile` prop defaults to
    false, which already gives that inline rendering here.

    Same breakpoint-driven role duplication drop as logged-in.blade.php, and
    the profile menu's theme toggle is dropped (form/toggle is not ported —
    see logged-in.blade.php's sibling stories).
--}}
<tedi:header>
    <tedi:header.logo href="/">
        <img src="header-logo.svg" alt="Logo">
        <x-slot:darkLogo>
            <img src="header-logo-white.svg" alt="Logo (Dark Mode)">
        </x-slot:darkLogo>
    </tedi:header.logo>

    <tedi:header.actions>
        <tedi:separator axis="vertical" />
        <tedi:header.role
            description="49504080934"
            show-search
            :representatives="$representatives2"
            :current-representative="$currentRepresentative"
        />
        <tedi:separator axis="vertical" />

        <tedi:header.language :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
        <tedi:separator axis="vertical" />

        <tedi:header.profile show-label label="Mari Maasikas">
            <tedi:link href="#" :underline="false">Minu andmed</tedi:link>
            <tedi:link href="#" :underline="false">Esindatavad</tedi:link>
            <tedi:link href="#" :underline="false">Kontaktid</tedi:link>
        </tedi:header.profile>
        <tedi:separator axis="vertical" />
        <tedi:header.logout href="#" />
    </tedi:header.actions>

    <x-slot:bottom>
        <tedi:header.bottom>
            <tedi:header.search mobile-variant="inline">
                <input type="search" class="tedi-input" placeholder="Otsi">
            </tedi:header.search>
        </tedi:header.bottom>
    </x-slot:bottom>
</tedi:header>
