@storybook([
    'name' => 'With Info Button',
    'order' => 4,
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
    <tedi:timeline-item :index="0">
        <x-slot:title>
            Taotluse esitamine
            <tedi:info-button />
        </x-slot:title>
        <x-slot:description>Pärast taotluse esitamist võetakse see menetlusse.</x-slot:description>
    </tedi:timeline-item>
    <tedi:timeline-item :index="1">
        <x-slot:title>
            Menetlemine
            <tedi:info-button />
        </x-slot:title>
        <x-slot:description>Menetlemine võib võtta kuni 30 päeva.</x-slot:description>
    </tedi:timeline-item>
    <tedi:timeline-item :index="2" last>
        <x-slot:title>
            Otsus
            <tedi:info-button />
        </x-slot:title>
        <x-slot:description>Otsus tehakse teatavaks.</x-slot:description>
    </tedi:timeline-item>
</tedi:timeline>
