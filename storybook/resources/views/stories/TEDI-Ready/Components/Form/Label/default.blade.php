@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=2137-19322&m=dev',
    'args' => [
        'ngContent' => 'Label',
        'size' => 'default',
        'color' => 'secondary',
    ],
    'argTypes' => [
        'ngContent' => [
            'name' => 'ng-content',
            'control' => 'text',
            'description' => 'Label text',
            'table' => ['type' => ['summary' => 'string']],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['small', 'default'],
            'description' => 'Defines the size of the label.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'default'],
                'type' => ['summary' => 'LabelSize'],
            ],
        ],
        'required' => [
            'control' => 'boolean',
            'description' => 'Marks the label as required.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
        'color' => [
            'control' => 'radio',
            'options' => ['primary', 'secondary'],
            'description' => 'Color of the label',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'LabelColor'],
                'defaultValue' => ['summary' => 'secondary'],
            ],
        ],
    ],
])

<tedi:form.label :size="$size" :color="$color">{{ $ngContent }}</tedi:form.label>
