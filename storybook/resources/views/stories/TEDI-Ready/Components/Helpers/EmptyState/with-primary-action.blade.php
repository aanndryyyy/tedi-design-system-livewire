@storybook([
    'name' => 'With Primary Action',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'type' => 'separate',
        'size' => 'default',
        'icon' => 'spa',
    ],
    'argTypes' => [
        'type' => [
            'control' => 'inline-radio',
            'options' => ['separate', 'attached', 'inside'],
            'description' => 'Container variant.',
        ],
        'size' => [
            'control' => 'inline-radio',
            'options' => ['default', 'small'],
            'description' => 'Padding scale.',
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Material icon name rendered above the text.',
        ],
    ],
])

<tedi:empty-state :type="$type" :size="$size" :icon="$icon ?: ''">
    You have no data to display
    <x-slot:actions>
        <tedi:button icon-start="add">Create new</tedi:button>
    </x-slot:actions>
</tedi:empty-state>
