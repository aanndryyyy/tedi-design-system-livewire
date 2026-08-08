@storybook([
    'name' => 'Error',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'text' => 'I am an error text',
        'type' => 'error',
    ],
])

<tedi:feedback-text :text="$text" :type="$type" />
