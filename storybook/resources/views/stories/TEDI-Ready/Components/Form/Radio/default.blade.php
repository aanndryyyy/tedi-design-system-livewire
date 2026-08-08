@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.41.64?node-id=6149-138013&m=dev',
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
            'description' => 'Is radio invalid?',
            'table' => [
                'category' => 'Radio',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Is radio disabled?',
            'table' => [
                'category' => 'Radio',
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<tedi:form.label color="primary" class="flex align-items-center gap-2">
    <tedi:radio :size="$size" :invalid="(bool) $invalid" :disabled="(bool) $disabled" />
    Text
</tedi:form.label>
