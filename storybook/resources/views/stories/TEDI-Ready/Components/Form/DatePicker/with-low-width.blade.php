@storybook([
    'name' => 'With Low Width',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=9938-87564&m=dev',
    'args' => [
        'inputPlaceholder' => 'Select a date...',
        'inputSize' => 'default',
        'inputState' => 'default',
    ],
    'argTypes' => [
        'inputPlaceholder' => [
            'control' => 'text',
            'description' => 'Input placeholder',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'inputSize' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'Input size',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerInputSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'inputState' => [
            'control' => 'radio',
            'options' => ['default', 'error', 'valid'],
            'description' => 'Input state',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerInputState'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
    ],
])

<div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));">
    <tedi:date-picker :input-placeholder="$inputPlaceholder" :input-size="$inputSize" :input-state="$inputState" />
    <tedi:date-picker :input-placeholder="$inputPlaceholder" :input-size="$inputSize" :input-state="$inputState" />
    <tedi:date-picker :input-placeholder="$inputPlaceholder" :input-size="$inputSize" :input-state="$inputState" />
    <div>
        <tedi:form.label for="success" :required="true">Label</tedi:form.label>
        <tedi:date-picker input-id="success" :input-placeholder="$inputPlaceholder" :input-size="$inputSize" :input-state="$inputState" />
    </div>
    <tedi:date-picker :input-placeholder="$inputPlaceholder" :input-size="$inputSize" :input-state="$inputState" />
</div>
