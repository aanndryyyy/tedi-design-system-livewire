@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.37.57?m=dev&node-id=5784-114506',
    'args' => [
        'color' => 'neutral',
        'variant' => 'filled',
        'text' => 'Text',
        'size' => 'default',
        'icon' => '',
        'title' => '',
        'role' => '',
        'status' => '',
    ],
    'argTypes' => [
        'color' => [
            'control' => 'select',
            'options' => ['neutral', 'brand', 'accent', 'warning', 'danger', 'success', 'transparent'],
            'description' => 'Specifies the color scheme of the StatusBadge.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'StatusBadgeColor'],
                'defaultValue' => ['summary' => 'neutral'],
            ],
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['filled', 'filled-bordered', 'bordered'],
            'description' => 'Determines the style or visual type of the StatusBadge.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'StatusBadgeVariant'],
                'defaultValue' => ['summary' => 'filled'],
            ],
        ],
        'text' => [
            'control' => 'text',
            'description' => 'The text to be displayed inside the StatusBadge.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'The name of the icon to be displayed inside the StatusBadge. The icon is rendered using the `Icon` component.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'title' => [
            'control' => 'text',
            'description' => 'Provides the full text or description when the Badge represents an abbreviation. This is typically shown as a tooltip on hover.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'role' => [
            'control' => 'text',
            'description' => 'ARIA role attribute for accessibility.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'large'],
            'description' => 'Specifies the size of the StatusBadge.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'StatusBadgeSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'status' => [
            'control' => 'radio',
            'options' => ['inactive', 'success', 'warning', 'danger'],
            'description' => 'StatusBadge status indicator.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'StatusBadgeStatus']],
        ],
    ],
])

<tedi:status-badge
    :color="$color"
    :variant="$variant"
    :text="$text"
    :size="$size"
    :icon="$icon ?: null"
    :title="$title ?: null"
    :role="$role ?: null"
    :status="$status ?: null"
/>
