@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=45-30752&mode=dev',
    'args' => [
        'name' => 'account_circle',
        'size' => 24,
        'color' => 'primary',
        'background' => '',
        'type' => 'outlined',
        'variant' => 'outlined',
        'label' => '',
    ],
    'argTypes' => [
        'name' => [
            'control' => 'text',
            'description' => 'Name of the Material Icon <br /> https://fonts.google.com/icons',
        ],
        'size' => [
            'control' => 'select',
            'options' => [8, 12, 16, 18, 24, 36, 48, 'inherit'],
            'description' => 'Size of the icon in pixels.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => '24'],
                'type' => ['summary' => 'IconSize'],
            ],
        ],
        'color' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'warning-dark', 'danger', 'white', 'inherit'],
            'description' => 'Color of the icon.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'IconColor'],
            ],
        ],
        'background' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'brand-primary', 'brand-secondary'],
            'description' => 'Background color for the icon (adds a circular background).',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'IconBackground'],
            ],
        ],
        'type' => [
            'control' => 'radio',
            'options' => ['outlined', 'sharp', 'rounded'],
            'description' => 'Type of Material Symbols icon style. <br /> It is recommended to only use one type throughout your app.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'outlined'],
                'type' => ['summary' => 'IconType'],
            ],
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['filled', 'outlined'],
            'description' => 'Whether the icon should be filled or outlined.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'outlined'],
                'type' => ['summary' => 'IconVariant'],
            ],
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Accessible label for screen readers. <br /> If omitted then the icon is hidden for screen-readers.',
        ],
    ],
])

<tedi:icon
    :name="$name"
    :size="$size"
    :color="$color"
    :background="$background ?: null"
    :type="$type"
    :variant="$variant"
    :label="$label ?: null"
/>
