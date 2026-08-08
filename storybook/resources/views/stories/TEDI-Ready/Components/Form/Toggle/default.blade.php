@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.1.6--work-in-progress-?node-id=7123-152108&m=dev',
    'args' => [
        'inputId' => 'example-toggle-1',
        'variant' => 'primary',
        'type' => 'filled',
        'size' => 'default',
        'checked' => false,
        'required' => false,
        'disabled' => false,
        'icon' => false,
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'inputId' => [
            'control' => 'text',
            'description' => 'The unique identifier for the input element that is associated with label.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'checked' => [
            'control' => 'boolean',
            'description' => 'Is toggle checked? Supports two-way binding, use with form controls.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'required' => [
            'control' => 'boolean',
            'description' => 'Indicates whether the toggle field is required.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Is toggle disabled?',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['primary', 'colored'],
            'description' => 'Color variant of the toggle',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ToggleVariant'],
                'defaultValue' => ['summary' => 'primary'],
            ],
        ],
        'type' => [
            'control' => 'radio',
            'options' => ['filled', 'outlined'],
            'description' => 'Type of the toggle',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ToggleType'],
                'defaultValue' => ['summary' => 'filled'],
            ],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'large'],
            'description' => 'Size of the toggle',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ToggleSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'icon' => [
            'control' => 'boolean',
            'description' => 'Should the toggle show lock icon. Works only with large toggle.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible label for the toggle, used when there is no visible label.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

<label for="{{ $inputId }}" class="sr-only">Default toggle</label>
<tedi:toggle
    :input-id="$inputId"
    :variant="$variant"
    :type="$type"
    :size="$size"
    :checked="(bool) $checked"
    :required="(bool) $required"
    :disabled="(bool) $disabled"
    :icon="(bool) $icon"
    :aria-label="$ariaLabel ?: null"
/>
