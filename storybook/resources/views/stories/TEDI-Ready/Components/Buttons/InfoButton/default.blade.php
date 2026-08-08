@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4514-72997&m=dev',
    'args' => [
        'ariaLabel' => '',
        'color' => 'primary',
    ],
    'argTypes' => [
        'ariaLabel' => [
            'name' => 'aria-label',
            'control' => 'text',
            'description' => 'ARIA label',
        ],
        'color' => [
            'control' => 'radio',
            'options' => ['primary', 'inverted'],
            'description' => 'Color variant. Use `inverted` on dark or colored backgrounds.',
        ],
    ],
])

<tedi:info-button :color="$color" :aria-label="$ariaLabel ?: null" />
