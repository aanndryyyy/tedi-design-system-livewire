@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?node-id=115-11630&m=dev',
    'args' => [
        'ngContent' => 'Text',
        'modifiers' => [],
        'color' => 'primary',
    ],
    'argTypes' => [
        'ngContent' => [
            'name' => 'ng-content',
            'control' => 'text',
        ],
        'modifiers' => [
            'control' => 'multi-select',
            'options' => [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'normal', 'small', 'extra-small', 'bold', 'thin',
                'italic', 'center', 'left', 'right', 'nowrap', 'break-all', 'break-word', 'break-spaces',
                'uppercase', 'lowercase', 'capitalize', 'capitalize-first', 'inline-block', 'inline',
                'line-normal', 'line-condensed', 'subtitle',
            ],
            'description' => 'Single or multiple modifiers to change the text behavior',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TextModifiers[] | TextModifiers'],
            ],
        ],
        'color' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'white', 'disabled', 'brand', 'success', 'warning', 'danger', 'info', 'neutral', 'inherit'],
            'description' => 'Color of the text',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'TextColor'],
            ],
        ],
    ],
])

<tedi:text as="p" :modifiers="$modifiers" :color="$color">{{ $ngContent }}</tedi:text>
