@storybook([
    'name' => 'Position Right',
    'order' => 5,
    'status' => 'stable',
    'args' => [
        'text' => 'I am a hint text',
        'position' => 'right',
    ],
])

<tedi:feedback-text :text="$text" :position="$position" />
