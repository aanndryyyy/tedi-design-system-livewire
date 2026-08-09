{{--
    Angular nests the sub-steps directly inside their parent item; in Blade they
    go in the parent's `sub-items` slot and each carries `:sub-item="true"`,
    because Blade cannot set that on its slot's children the way Angular's
    contentChildren() effect does (CONVENTIONS.md §5).

    The parent's `opened` defaults to false in both, so the sub-steps start
    collapsed behind the toggle — they are in the DOM either way (§8).
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'compact' => false,
        'enumerated' => false,
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'compact' => [
            'control' => 'boolean',
            'description' => "Whether it's the compact variant",
            'table' => [
                'category' => 'vertical-stepper',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'enumerated' => [
            'control' => 'boolean',
            'description' => 'Used for compact variant, displays step number infront of the step title',
            'table' => [
                'category' => 'vertical-stepper',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Aria label for stepper',
            'table' => [
                'category' => 'vertical-stepper',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

<tedi:vertical-stepper :compact="(bool) $compact" :enumerated="(bool) $enumerated" :aria-label="$ariaLabel ?: null">
    <tedi:vertical-stepper-item title="Default with description">
        <x-slot:description>
            <tedi:status-badge color="warning">Description</tedi:status-badge>
        </x-slot:description>
    </tedi:vertical-stepper-item>

    <tedi:vertical-stepper-item title="Completed" :completed="true" />
    <tedi:vertical-stepper-item title="Error" :error="true" />

    <tedi:vertical-stepper-item title="Selected with children" :selected="true">
        <x-slot:sub-items>
            <tedi:vertical-stepper-item title="Default child" :sub-item="true" />
            <tedi:vertical-stepper-item title="Completed child" :sub-item="true" :completed="true" />
            <tedi:vertical-stepper-item title="Error child" :sub-item="true" :error="true" />
            <tedi:vertical-stepper-item title="Selected child" :sub-item="true" :selected="true" />
            <tedi:vertical-stepper-item title="Disabled child" :sub-item="true" :disabled="true" />
            <tedi:vertical-stepper-item title="Informative child" :sub-item="true" :informative="true" />
        </x-slot:sub-items>
    </tedi:vertical-stepper-item>

    <tedi:vertical-stepper-item title="Disabled" :disabled="true" />
</tedi:vertical-stepper>
