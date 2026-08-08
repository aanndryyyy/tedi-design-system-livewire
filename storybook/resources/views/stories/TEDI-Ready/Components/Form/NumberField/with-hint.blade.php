@storybook([
    'name' => 'With Hint',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'inputId' => 'example-hint',
        'label' => 'Label',
        'feedbackText' => [
            'text' => 'Hint text',
            'type' => 'hint',
            'position' => 'left',
        ],
    ],
])

<tedi:number-field
    :input-id="$inputId"
    :label="$label"
    :feedback-text="$feedbackText"
/>
