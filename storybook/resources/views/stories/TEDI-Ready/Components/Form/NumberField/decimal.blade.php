@storybook([
    'name' => 'Decimal',
    'order' => 5,
    'status' => 'stable',
    'args' => [
        'inputId' => 'example-decimal',
        'label' => 'Label',
        'value' => 1.5,
    ],
])

<tedi:number-field
    :input-id="$inputId"
    :label="$label"
    :value="$value"
/>
