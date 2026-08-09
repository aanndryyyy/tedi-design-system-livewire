@storybook([
    'name' => 'With Errors',
    'order' => 4,
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
    <tedi:horizontal-stepper aria-label="Veaga vorm" :background="$background">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :error="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" />
    </tedi:horizontal-stepper>
    <tedi:horizontal-stepper aria-label="Vorm vea kirjeldusega" :background="$background">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :error="true" description="Sammus esinevad vead" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" :selected="true" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" />
    </tedi:horizontal-stepper>
</div>
