@storybook([
    'name' => 'Third Step',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'background' => 'default',
    ],
    'argTypes' => [
        'background' => [
            'control' => 'select',
            'options' => ['default', 'transparent'],
            'description' => 'Background style of the stepper container.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => "'default' | 'transparent'"],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
    ],
])

<tedi:horizontal-stepper aria-label="Vormi edenemine" :background="$background">
    <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
    <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :completed="true" />
    <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" :selected="true" />
    <tedi:horizontal-stepper-item label="Vastus" :step-number="4" />
</tedi:horizontal-stepper>
