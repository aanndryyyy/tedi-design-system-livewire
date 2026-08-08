@storybook([
    'name' => 'Title Only',
    'order' => 2,
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
        <x-slot:title>Taotluse esitamine</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="1">
        <x-slot:title>Menetlemine</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="2" last>
        <x-slot:title>Otsus</x-slot:title>
    </tedi:timeline-item>
</tedi:timeline>
