@storybook([
    'name' => 'With Inline Search',
    'order' => 12,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

@php
    $representatives = [
        ['id' => '1', 'icon' => 'person', 'name' => 'Mari Maasikas', 'description' => '49504080934'],
        ['id' => '2', 'icon' => 'supervised_user_circle', 'name' => 'Juulia Sarapuu', 'description' => '62004122984'],
        ['id' => '3', 'icon' => 'supervised_user_circle', 'name' => 'Marta Sarapuu', 'description' => '62204115671'],
        ['id' => '4', 'icon' => 'supervised_user_circle', 'name' => 'Helgi Sarapuu', 'description' => '62407194692'],
    ];
    $currentRepresentative = ['id' => '1', 'icon' => 'person', 'name' => 'Mari Maasikas', 'description' => '49504080934'];
@endphp

{{--
    Angular splits the search input across three breakpoint-gated copies
    (`*showAt('md')`, `*hideAt('md')`, plus the desktop role selector at
    `*showAt('lg')`). Breakpoint gating is not ported (CONVENTIONS.md §7), so
    this keeps a single always-visible `header.search` alongside the role
    selector, and drops the mobile-profile role duplicate (same rule as
    logged-in.blade.php).
--}}
<tedi:header>
    <tedi:header.logo href="/">
        <img src="header-logo.svg" alt="Logo">
        <x-slot:darkLogo>
            <img src="header-logo-white.svg" alt="Logo (Dark Mode)">
        </x-slot:darkLogo>
    </tedi:header.logo>

    <tedi:header.actions>
        <tedi:header.search>
            <input type="search" class="tedi-input" placeholder="Otsi">
        </tedi:header.search>
        <tedi:separator axis="vertical" />

        <tedi:header.role
            label="Roll:"
            description="49504080934"
            show-search
            :representatives="$representatives"
            :current-representative="$currentRepresentative"
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
