@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'type' => 'default',
        'layout' => 'horizontal',
        'selected' => false,
        'disabled' => false,
        'clipContent' => true,
    ],
    'argTypes' => [
        'type' => [
            'control' => 'radio',
            'options' => ['default', 'checkbox', 'radio'],
            'description' => 'Type of selection indicator',
            'table' => [
                'type' => ['summary' => 'DropdownItemValueType'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'layout' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => 'Layout of label and meta content',
            'table' => [
                'type' => ['summary' => 'DropdownItemValueLayout'],
                'defaultValue' => ['summary' => 'horizontal'],
            ],
        ],
        'selected' => [
            'control' => 'boolean',
            'description' => 'Whether the item is selected (controls checkbox/radio state)',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Whether the item is disabled',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'clipContent' => [
            'control' => 'boolean',
            'description' => '`tedi-dropdown-item-value-label` input. Whether the label clips overflowing content for text ellipsis. Set `false` when the label holds decorations that sit outside the line box (e.g. status indicator), so they are not cut off.',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
    ],
])

<tedi:dropdown-item-value
    :type="$type"
    :layout="$layout"
    :selected="(bool) $selected"
    :disabled="(bool) $disabled"
>
    <tedi:dropdown-item-value-label :clip-content="(bool) $clipContent">Option 1</tedi:dropdown-item-value-label>
</tedi:dropdown-item-value>
