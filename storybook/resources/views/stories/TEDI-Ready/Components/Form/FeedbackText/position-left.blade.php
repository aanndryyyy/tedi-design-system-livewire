@storybook([
    'name' => 'Position Left',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'text' => 'I am a hint text',
        'position' => 'left',
    ],
])

<tedi:feedback-text :text="$text" :position="$position" />
