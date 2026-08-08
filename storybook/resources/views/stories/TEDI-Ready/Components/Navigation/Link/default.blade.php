@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2160-25385&m=dev',
    'args' => [
        'ngContent' => 'Link',
        'variant' => 'default',
        'size' => 'default',
        'underline' => true,
        'target' => '',
    ],
    'argTypes' => [
        'ngContent' => [
            'control' => 'text',
            'description' => 'Link text.',
        ],
        'variant' => [
            'control' => 'select',
            'options' => ['default', 'inverted'],
            'description' => 'Variant of the link.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'default'],
                'type' => ['summary' => 'LinkVariant'],
            ],
        ],
        'size' => [
            'control' => 'select',
            'options' => ['default', 'small'],
            'description' => 'Size of the link.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'default'],
                'type' => ['summary' => 'LinkSize'],
            ],
        ],
        'underline' => [
            'control' => 'boolean',
            'description' => 'Does link have underline?',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'target' => [
            'control' => 'text',
            'description' => 'Target attribute for the link',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

<tedi:link
    href="#"
    :variant="$variant"
    :size="$size"
    :underline="(bool) $underline"
    :target="$target ?: null"
>{{ $ngContent }}</tedi:link>
