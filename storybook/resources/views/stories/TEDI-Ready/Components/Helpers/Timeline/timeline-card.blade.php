@storybook([
    'name' => 'Timeline Card',
    'order' => 9,
    'status' => 'stable',
    'args' => [
        'activeIndex' => 1,
        'variant' => 'card',
        'cardPadding' => 1,
    ],
    'argTypes' => [
        'activeIndex' => [
            'control' => 'number',
            'description' => 'Index of active item',
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['default', 'card'],
            'description' => "Visual variant. 'card' wraps the timeline in the borders and paddings of a card.",
        ],
        'cardPadding' => [
            'control' => 'select',
            'options' => [0, 0.5, 0.75, 1, 1.5, 2, 2.5, 3],
            'description' => 'Item padding in rems for the card variant. Same values as the Card component.',
        ],
    ],
])

{{--
    Angular projects <tedi-collapse> for the "show more" body of each item;
    the collapse component isn't ported (not in resources/views/components/),
    so the extra info is rendered directly instead, mirroring how it's shown
    open by default in Angular's first item.
--}}
<tedi:timeline :active-index="$activeIndex" :variant="$variant" :card-padding="$cardPadding">
    <tedi:timeline-item :index="0" :timings="['11.01.2024 12:23', 'Kersti Ööviul']">
        <x-slot:timingsBottom>
            <small>Muudetud 08.02.2024 12:23</small>
        </x-slot:timingsBottom>
        <x-slot:title>Suhtlus isikuga</x-slot:title>
        <p>Lisainfo: Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </tedi:timeline-item>
    <tedi:timeline-item :index="1" :timings="['08.02.2024 12:23', 'Kersti Ööviul']" last>
        <x-slot:timingsBottom>
            <small>Muudetud 12.03.2024 12:23</small>
        </x-slot:timingsBottom>
        <x-slot:title>Suhtlus isikuga</x-slot:title>
    </tedi:timeline-item>
</tedi:timeline>
