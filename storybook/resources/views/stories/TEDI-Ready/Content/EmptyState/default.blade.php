@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=5784-114507&m=dev',
    'args' => [
        'type' => 'separate',
        'size' => 'default',
        'icon' => 'spa',
        'iconColor' => 'brand',
        'iconSize' => 36,
        'heading' => '',
    ],
    'argTypes' => [
        'type' => [
            'control' => 'inline-radio',
            'options' => ['separate', 'attached', 'inside'],
            'description' => 'Container variant.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => 'separate']],
        ],
        'size' => [
            'control' => 'inline-radio',
            'options' => ['default', 'small'],
            'description' => 'Padding scale.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => 'default']],
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Material icon name rendered above the text. Pass an empty string to hide.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => 'spa']],
        ],
        'iconColor' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'danger', 'inherit'],
            'description' => 'Icon color override.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => 'brand']],
        ],
        'iconSize' => [
            'control' => ['type' => 'number', 'min' => 8, 'max' => 72, 'step' => 1],
            'description' => 'Icon size in pixels.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => '36']],
        ],
        'heading' => [
            'control' => 'text',
            'description' => 'Optional heading above the description.',
            'table' => ['category' => 'inputs'],
        ],
    ],
])

<tedi:empty-state
    :type="$type"
    :size="$size"
    :icon="$icon ?: ''"
    :icon-color="$iconColor"
    :icon-size="$iconSize"
    :heading="$heading ?: null"
>You have no data to display</tedi:empty-state>
