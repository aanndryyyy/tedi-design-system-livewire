@storybook([
    'name' => 'With Action',
    'order' => 6,
    'status' => 'stable',
    'args' => [
        'activeIndex' => 3,
    ],
    'argTypes' => [
        'activeIndex' => [
            'control' => 'number',
            'description' => 'Index of active item',
        ],
    ],
])

{{--
    Angular's third item uses <tedi-collapse> for a "show more" toggle; the
    collapse component isn't ported (not in resources/views/components/), so
    that item is omitted here — the first two items still demonstrate the
    action-button composition this story exists for.
--}}
<tedi:timeline :active-index="$activeIndex">
    <tedi:timeline-item :index="0">
        <x-slot:title>Taotluse esitamine</x-slot:title>
        <x-slot:description>Pärast taotluse esitamist võetakse see menetlusse.</x-slot:description>
        <tedi:button size="small" variant="secondary" icon-start="add" style="width:fit-content">Lisa kaastaotleja</tedi:button>
    </tedi:timeline-item>
    <tedi:timeline-item :index="1" last>
        <x-slot:title>Menetlemine</x-slot:title>
        <x-slot:description>Menetlemine võib võtta kuni 30 päeva.</x-slot:description>
        <tedi:button size="small" style="width:fit-content">Vaata menetlust</tedi:button>
    </tedi:timeline-item>
</tedi:timeline>
