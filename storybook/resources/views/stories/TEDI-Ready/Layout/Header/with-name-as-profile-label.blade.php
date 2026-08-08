@storybook([
    'name' => 'With Name As Profile Label',
    'order' => 10,
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
    Angular passes `[md]="{ label: 'Mari Maasikas' }"` — a per-breakpoint
    input override — to show the representative's name as the profile label
    from `md` up. Breakpoint-override props are not ported (CONVENTIONS.md
    §7), so `label` is set directly to the same value here, applying at every
    width rather than only from `md` up. Same breakpoint-driven role
    duplication drop as logged-in.blade.php (the `*hideAt('lg')` role copy
    inside the profile menu is dropped since header-actions already renders
    one).
--}}
<tedi:header>
    <tedi:header.actions>
        <tedi:header.language :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']" />
        <tedi:separator axis="vertical" />

        <tedi:header.profile show-label label="Mari Maasikas">
            <tedi:header.role
                description="49504080934"
                show-search
                :representatives="$representatives2"
                :current-representative="$currentRepresentative"
            />
            <tedi:link href="#" :underline="false">
                Ligipääsetavus
                <tedi:icon name="north_east" :size="16" />
            </tedi:link>

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
