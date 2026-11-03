@storybook([
    'name' => 'With Heading',
    'order' => 5,
    'status' => 'stable',
    'args' => [
        'type' => 'separate',
        'size' => 'default',
        'icon' => 'event_busy',
        'heading' => 'Choose new time',
    ],
    'argTypes' => [
        'icon' => [
            'control' => 'text',
            'description' => 'Material icon name rendered above the text.',
        ],
        'heading' => [
            'control' => 'text',
            'description' => 'Optional heading above the description.',
        ],
    ],
])

<tedi:empty-state :type="$type" :size="$size" :icon="$icon ?: ''" :heading="$heading">
    You have no data to display
    <x-slot:actions>
        <tedi:button>Choose time</tedi:button>
    </x-slot:actions>
</tedi:empty-state>
