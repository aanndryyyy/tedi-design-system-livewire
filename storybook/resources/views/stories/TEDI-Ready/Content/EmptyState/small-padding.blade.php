@storybook([
    'name' => 'Small Padding',
    'order' => 7,
    'status' => 'stable',
    'args' => [
        'type' => 'separate',
        'size' => 'small',
        'icon' => 'spa',
    ],
    'argTypes' => [
        'size' => [
            'control' => 'inline-radio',
            'options' => ['default', 'small'],
            'description' => 'Padding scale.',
        ],
    ],
])

<tedi:empty-state :type="$type" :size="$size" :icon="$icon ?: ''">
    You have no data to display
    <x-slot:actions>
        <tedi:button icon-start="add">Create new</tedi:button>
        <tedi:button variant="secondary" icon-end="arrow_forward">Read more</tedi:button>
    </x-slot:actions>
</tedi:empty-state>
