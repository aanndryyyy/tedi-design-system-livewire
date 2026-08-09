{{--
    Angular numbers the steps by walking contentChildren(); Blade can't
    introspect its slot, so every story passes an explicit `step-number`
    (CONVENTIONS.md §5).
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.68?node-id=11201-120695&m=dev',
    'args' => [
        'ariaLabel' => 'Vormi edenemine',
        'background' => 'default',
        'compact' => 'sm',
    ],
    'argTypes' => [
        'ariaLabel' => [
            'name' => 'ariaLabel',
            'control' => 'text',
            'description' => 'Accessible label for the navigation landmark.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
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
        'compact' => [
            'control' => 'select',
            'options' => [true, false, 'sm', 'md', 'lg', 'xl', 'xxl'],
            'description' => "Collapse labels (show only indicators + selected step's label). `true` = always; a breakpoint string = collapse below that breakpoint.",
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => "boolean | 'sm' | 'md' | 'lg' | 'xl' | 'xxl'"],
                'defaultValue' => ['summary' => "'sm'"],
            ],
        ],
    ],
])

<tedi:horizontal-stepper :aria-label="$ariaLabel" :background="$background" :compact="$compact">
    <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :selected="true" />
    <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" />
    <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" />
    <tedi:horizontal-stepper-item label="Vastus" :step-number="4" />
</tedi:horizontal-stepper>
