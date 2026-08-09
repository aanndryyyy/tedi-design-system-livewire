{{--
    Angular's EnumeratedCompact story. `enumerated` only shows in the compact
    variant — the counter rule is nested under `--compact` in the vendored SCSS.
--}}
@storybook([
    'name' => 'Enumerated Compact',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'compact' => true,
        'enumerated' => true,
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'compact' => ['table' => ['disable' => true]],
        'enumerated' => ['table' => ['disable' => true]],
        'ariaLabel' => ['control' => 'text', 'description' => 'Aria label for stepper'],
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
