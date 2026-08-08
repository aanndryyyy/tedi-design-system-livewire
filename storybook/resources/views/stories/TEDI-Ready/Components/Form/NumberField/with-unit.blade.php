@storybook([
    'name' => 'With Unit',
    'order' => 6,
    'status' => 'stable',
    'args' => [
        'inputId' => 'example-unit',
        'label' => 'Label',
        'suffix' => 'unit',
        'value' => 2,
    ],
])

<tedi:number-field
    :input-id="$inputId"
    :label="$label"
    :suffix="$suffix"
    :value="$value"
/>
