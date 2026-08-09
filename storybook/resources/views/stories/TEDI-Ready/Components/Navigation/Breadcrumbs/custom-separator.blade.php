@storybook([
    'name' => 'Custom Separator',
    'order' => 5,
    'status' => 'stable',
])

@php
    $trail = [
        ['label' => 'Töölaud', 'href' => '#'],
        ['label' => 'Dokumendid', 'href' => '#'],
        ['label' => 'Piirangud'],
    ];
@endphp

<div class="flex flex-column gap-3">
    <tedi:breadcrumbs separator="/" aria-label="Liikumistee (eraldatud kaldkriipsuga)" :items="$trail" />

    <tedi:breadcrumbs aria-label="Liikumistee (eraldatud noolega)" :items="$trail">
        <x-slot:separator-template>
            <tedi:icon name="arrow_forward" :size="16" color="brand" />
        </x-slot:separator-template>
    </tedi:breadcrumbs>
</div>
