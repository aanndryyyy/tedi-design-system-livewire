@storybook([
    'name' => 'With Descriptions',
    'order' => 5,
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

<div style="display: flex; flex-direction: column; gap: 16px;">
    <tedi:horizontal-stepper aria-label="Kirjeldustega sammud (samm 1)" :background="$background">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :selected="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" description="Ametnik täidab" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" description="Ametnik täidab" />
    </tedi:horizontal-stepper>
    <tedi:horizontal-stepper aria-label="Kirjeldustega sammud (samm 2)" :background="$background">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" description="Ametnik täidab" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" description="Ametnik täidab" />
    </tedi:horizontal-stepper>
    <tedi:horizontal-stepper aria-label="Kirjeldustega sammud (samm 3)" :background="$background">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :completed="true" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" :selected="true" description="Ametnik täidab" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" description="Ametnik täidab" />
    </tedi:horizontal-stepper>
    <tedi:horizontal-stepper aria-label="Kirjeldustega sammud (samm 4)" :background="$background">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :completed="true" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" :completed="true" description="Ametnik täidab" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" :selected="true" description="Ametnik täidab" />
    </tedi:horizontal-stepper>
</div>
