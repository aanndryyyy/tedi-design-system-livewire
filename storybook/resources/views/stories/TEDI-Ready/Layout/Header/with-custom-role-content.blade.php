@storybook([
    'name' => 'With Custom Role Content',
    'order' => 16,
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
    Exercises header.role's `content` named slot ([tedi-header-role-content]
    in Angular). Same breakpoint-driven duplication drop as
    logged-in.blade.php.
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
            label="Roll:"
            description="49504080934"
            :representatives="$representatives"
            :current-representative="$currentRepresentative"
            show-role-switch
        >
            <x-slot:content>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                    <tedi:icon name="heart_check" :size="36" color="brand" />
                    <tedi:text color="secondary" modifiers="center">Sul puuduvad esindatavad</tedi:text>
                </div>
            </x-slot:content>
        </tedi:header.role>
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
