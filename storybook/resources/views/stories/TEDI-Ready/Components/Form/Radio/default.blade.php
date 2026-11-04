@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65724-11106&m=dev',
    'args' => [
        'size' => 'default',
        'invalid' => false,
        'disabled' => false,
    ],
    'argTypes' => [
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'large'],
            'description' => 'Size of the radio.',
            'table' => [
                'category' => 'Radio',
                'type' => ['summary' => 'RadioSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'invalid' => [
            'control' => 'boolean',
            'description' => 'Show the radio in an invalid state.',
            'table' => [
                'category' => 'Radio',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Disable the radio.',
            'table' => [
                'category' => 'Radio',
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<tedi:form.label color="primary" class="flex align-items-center gap-2">
    <tedi:radio :size="$size" :invalid="(bool) $invalid" :disabled="(bool) $disabled" />
    Tekst
</tedi:form.label>
