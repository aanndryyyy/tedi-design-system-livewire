@storybook([
    'name' => 'Separate',
    'order' => 8,
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
    ],
])

<tedi:empty-state :type="$type" :size="$size" :icon="$icon ?: ''">You have no data to display</tedi:empty-state>
