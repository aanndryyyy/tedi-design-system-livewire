@storybook([
    'name' => 'With Single Date',
    'order' => 7,
    'status' => 'stable',
    'args' => [
        'activeIndex' => 1,
    ],
    'argTypes' => [
        'activeIndex' => [
            'control' => 'number',
            'description' => 'Index of active item',
        ],
    ],
])

<tedi:timeline :active-index="$activeIndex">
    <tedi:timeline-item :index="0" :timings="['1990']">
        <x-slot:title>Taotluse esitamine</x-slot:title>
        <x-slot:description>Pärast taotluse esitamist võetakse see menetlusse.</x-slot:description>
    </tedi:timeline-item>
    <tedi:timeline-item :index="1" :timings="['1990']">
        <x-slot:title>Menetlemine</x-slot:title>
        <x-slot:description>Menetlemine võib võtta kuni 30 päeva.</x-slot:description>
    </tedi:timeline-item>
    <tedi:timeline-item :index="2" :timings="['1991']" last>
        <x-slot:title>Otsus</x-slot:title>
        <x-slot:description>Otsus tehakse teatavaks.</x-slot:description>
    </tedi:timeline-item>
</tedi:timeline>
