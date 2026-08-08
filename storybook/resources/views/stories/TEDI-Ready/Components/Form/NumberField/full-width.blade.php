@storybook([
    'name' => 'Full Width',
    'order' => 7,
    'status' => 'stable',
    'args' => [
        'inputId' => 'example-full-width',
        'label' => 'Label',
        'suffix' => 'unit',
        'fullWidth' => true,
    ],
])

<tedi:number-field
    :input-id="$inputId"
    :label="$label"
    :suffix="$suffix"
    :full-width="(bool) $fullWidth"
/>
