@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.17.25?node-id=136-14995&m=dev&focus-id=25945-119670',
    'args' => [
        'activeIndex' => 2,
        'variant' => 'default',
    ],
    'argTypes' => [
        'activeIndex' => [
            'control' => 'number',
            'description' => 'Index of active item',
            'table' => ['category' => 'timeline', 'type' => ['summary' => 'number']],
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['default', 'card'],
            'description' => "Visual variant. 'card' wraps the timeline in the borders and paddings of a card.",
            'table' => ['category' => 'timeline', 'defaultValue' => ['summary' => 'default'], 'type' => ['summary' => 'TimelineVariant']],
        ],
    ],
])

<tedi:timeline :active-index="$activeIndex" :variant="$variant">
    <tedi:timeline-item :index="0" :timings="['1990', '14. detsember']">
        <x-slot:title>Staaži kogumise algus (I sammas)</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="1" :timings="['2002', '04. oktoober']">
        <x-slot:title>II sambaga liitumine</x-slot:title>
        <x-slot:description>Aktiivne fond: LHV XL (loositud)</x-slot:description>
    </tedi:timeline-item>
    <tedi:timeline-item :index="2" :timings="['2007', '07. aprill']">
        <x-slot:title>Minimaalse staaži täitumine (I sammas)</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="3" :timings="['2021', '13. mai']">
        <x-slot:title>III sambaga liitumine</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="4" :timings="['2022', '14. juuni']">
        <x-slot:title>II sambast lahkumine</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="5" :timings="['2024', '16. detsember']">
        <x-slot:title>Taotluse esitamine</x-slot:title>
        <x-slot:description>Menetlemine võib võtta kuni 30 p</x-slot:description>
        <tedi:button size="small" style="width:fit-content">Alusta taotlust</tedi:button>
    </tedi:timeline-item>
    <tedi:timeline-item :index="6" :timings="['2032']">
        <x-slot:title>II sambaga uuesti liitumise võimalus</x-slot:title>
    </tedi:timeline-item>
    <tedi:timeline-item :index="7" :timings="['2035']" last>
        <x-slot:title>Vanaduspensioniiga</x-slot:title>
    </tedi:timeline-item>
</tedi:timeline>
