@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=136-29124&m=dev',
    'args' => [
        'label' => 'Button',
        'variant' => 'primary',
        'size' => 'default',
        'iconStart' => '',
        'iconEnd' => '',
        'disabled' => false,
    ],
    'argTypes' => [
        'label' => [
            'control' => 'text',
            'description' => 'Button text. Angular projects this as ng-content.',
        ],
        'variant' => [
            'control' => 'radio',
            'options' => [
                'primary',
                'secondary',
                'neutral',
                'success',
                'danger',
                'danger-neutral',
                'primary-inverted',
                'secondary-inverted',
                'neutral-inverted',
                'primary-button-group',
                'secondary-button-group',
            ],
            'description' => 'Specifies the color theme of the button. The color should meet accessibility standards for color contrast.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'ButtonVariant'],
            ],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'Defines the size of the button.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'default'],
                'type' => ['summary' => 'ButtonSize'],
            ],
        ],
        'iconStart' => [
            'control' => 'text',
            'description' => 'Material Symbols name rendered before the label. Replaces Angular\'s projected <tedi-icon>, which Blade cannot introspect.',
        ],
        'iconEnd' => [
            'control' => 'text',
            'description' => 'Material Symbols name rendered after the label.',
        ],
        'disabled' => [
            'control' => 'boolean',
        ],
    ],
])

<tedi:button
    :variant="$variant"
    :size="$size"
    :icon-start="$iconStart ?: null"
    :icon-end="$iconEnd ?: null"
    :disabled="(bool) $disabled"
>{{ $label }}</tedi:button>
