@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.41.64?node-id=4228-72936&m=dev',
    'args' => [
        'size' => 'default',
        'invalid' => false,
        'disabled' => false,
    ],
    'argTypes' => [
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'large'],
            'description' => 'Size of the checkbox.',
            'table' => [
                'category' => 'Checkbox',
                'type' => ['summary' => 'CheckboxSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'invalid' => [
            'control' => 'boolean',
            'description' => 'Is checkbox invalid?',
            'table' => [
                'category' => 'Checkbox',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Is checkbox disabled?',
            'table' => [
                'category' => 'Checkbox',
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

{{-- Angular's "indeterminate" arg is dropped: it has no HTML attribute form,
     it can only be set at runtime via JS (`el.indeterminate = true`), which
     this template-only port doesn't wire up. --}}
<tedi:form.label color="primary" class="flex align-items-center gap-2">
    <tedi:checkbox :size="$size" :invalid="(bool) $invalid" :disabled="(bool) $disabled" />
    Text
</tedi:form.label>
