@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.68?node-id=2405-53326&m=dev',
    'args' => [
        'type' => 'success',
        'size' => 'sm',
        'hasBorder' => false,
        'position' => 'default',
        'label' => '',
    ],
    'argTypes' => [
        'type' => [
            'control' => 'radio',
            'options' => ['success', 'danger', 'warning', 'inactive'],
            'description' => 'The status type, which determines the indicator color',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'StatusIndicatorType'],
                'defaultValue' => ['summary' => 'success'],
            ],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['sm', 'lg'],
            'description' => 'The size of the indicator',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'StatusIndicatorSize'],
                'defaultValue' => ['summary' => 'sm'],
            ],
        ],
        'hasBorder' => [
            'control' => 'boolean',
            'description' => 'Whether the indicator has a white border ring',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Accessible label. When provided, the indicator is exposed to assistive technology with role="img"; otherwise it is treated as decorative.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | undefined'],
                'defaultValue' => ['summary' => 'undefined'],
            ],
        ],
        'position' => [
            'control' => 'radio',
            'options' => ['default', 'top-right'],
            'description' => 'Controls positioning of the indicator',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'StatusIndicatorPosition'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
    ],
])

<div style="background: var(--general-surface-tertiary); padding: 16px; display: inline-block; width: 100%;">
    <tedi:status-indicator :type="$type" :size="$size" :has-border="(bool) $hasBorder" :position="$position" :label="$label ?: null" />
</div>
