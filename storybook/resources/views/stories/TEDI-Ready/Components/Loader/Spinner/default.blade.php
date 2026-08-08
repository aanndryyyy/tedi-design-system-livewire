@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2768-42334&mode=dev',
    'args' => [
        'size' => 16,
        'color' => 'primary',
        'label' => 'Loading...',
    ],
    'argTypes' => [
        'size' => [
            'control' => 'radio',
            'options' => [10, 16, 48],
            'description' => 'Size of the spinner in px.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => '16'],
                'type' => ['summary' => 'SpinnerSize'],
            ],
        ],
        'color' => [
            'control' => 'radio',
            'options' => ['primary', 'secondary'],
            'description' => 'Specifies the color theme of the spinner. The color should meet accessibility standards for color contrast.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'SpinnerColor'],
            ],
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Provides a text label for screen readers to announce the spinners purpose or status.',
            'table' => ['category' => 'inputs'],
        ],
    ],
])

<tedi:spinner :size="$size" :color="$color" :label="$label ?: null" />
