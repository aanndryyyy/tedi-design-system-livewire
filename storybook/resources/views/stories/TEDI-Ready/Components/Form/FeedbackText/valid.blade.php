@storybook([
    'name' => 'Valid',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'text' => 'I am a valid text',
        'type' => 'valid',
    ],
])

<tedi:feedback-text :text="$text" :type="$type" />
