@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=9165-62054&m=dev',
    'args' => [
        'text' => 'I am a hint text',
        'type' => 'hint',
        'position' => 'left',
    ],
    'argTypes' => [
        'text' => [
            'control' => 'text',
            'description' => 'Helper text',
            'table' => ['category' => 'inputs'],
        ],
        'type' => [
            'control' => 'select',
            'options' => ['hint', 'valid', 'error'],
            'description' => 'Type of form-helper.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'FeedbackTextType'],
                'defaultValue' => ['summary' => 'hint'],
            ],
        ],
        'position' => [
            'control' => 'select',
            'options' => ['left', 'right'],
            'description' => 'Position of the helper.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'FeedbackTextPosition'],
                'defaultValue' => ['summary' => 'left'],
            ],
        ],
    ],
])

<tedi:feedback-text :text="$text" :type="$type" :position="$position" />
