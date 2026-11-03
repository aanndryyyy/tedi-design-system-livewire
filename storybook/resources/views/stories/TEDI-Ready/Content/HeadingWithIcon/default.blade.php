@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.75.90?node-id=5797-117045&m=dev',
    'args' => [
        'icon' => 'assignment_ind',
        'element' => 'h4',
        'modifiers' => '',
        'headingColor' => 'primary',
        'iconColor' => 'primary',
        'iconSize' => 24,
        'iconVariant' => 'outlined',
    ],
    'argTypes' => [
        'icon' => [
            'description' => 'Name of the Material Icon.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'element' => [
            'description' => 'Semantic heading tag. Also sets the typography unless a heading modifier overrides it.',
            'control' => 'select',
            'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'HeadingModifiers'],
                'defaultValue' => ['summary' => 'h4'],
            ],
        ],
        'modifiers' => [
            'description' => 'Single or multiple modifiers to change the heading behavior. A heading modifier styles the heading as that level while `element` keeps the semantics.',
            'control' => 'select',
            'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'bold', 'italic'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TextModifiers[] | TextModifiers'],
            ],
        ],
        'headingColor' => [
            'description' => 'Color of the heading text.',
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'white', 'disabled', 'brand', 'success', 'warning', 'danger', 'info', 'neutral', 'inherit'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TextColor'],
                'defaultValue' => ['summary' => 'primary'],
            ],
        ],
        'iconColor' => [
            'description' => 'Color of the icon.',
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'warning-dark', 'danger', 'white', 'inherit'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'IconColor'],
                'defaultValue' => ['summary' => 'primary'],
            ],
        ],
        'iconSize' => [
            'description' => 'Size of the icon in pixels.',
            'control' => 'select',
            'options' => [8, 12, 16, 18, 22, 24, 36, 48, 'inherit'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'IconSize'],
                'defaultValue' => ['summary' => '24'],
            ],
        ],
        'iconVariant' => [
            'description' => 'Whether the icon should be filled or outlined.',
            'control' => 'radio',
            'options' => ['outlined', 'filled'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'IconVariant'],
                'defaultValue' => ['summary' => 'outlined'],
            ],
        ],
    ],
])

<tedi:heading-with-icon
    :icon="$icon"
    :element="$element"
    :modifiers="$modifiers ?: null"
    :heading-color="$headingColor"
    :icon-color="$iconColor"
    :icon-size="$iconSize"
    :icon-variant="$iconVariant"
>My family physician</tedi:heading-with-icon>
