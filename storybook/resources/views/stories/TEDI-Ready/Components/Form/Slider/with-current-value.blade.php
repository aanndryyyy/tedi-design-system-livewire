@storybook([
    'name' => 'With Current Value',
    'order' => 4,
    'status' => 'subset',
    'args' => [
        'inputId' => 'slider-current-value',
        'ariaLabel' => 'Silt',
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'value' => 50,
        'minLabel' => '0%',
        'showCurrentValue' => true,
    ],
    'argTypes' => [
        'inputId' => [
            'control' => false,
            'description' => 'Identifier for the underlying range input; associates the label with the input.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible label used when no visible label is provided.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'min' => [
            'control' => 'number',
            'description' => 'Minimum allowed value.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '0']],
        ],
        'max' => [
            'control' => 'number',
            'description' => 'Maximum allowed value.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '100']],
        ],
        'step' => [
            'control' => 'number',
            'description' => 'Step size.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'value' => [
            'control' => 'number',
            'description' => 'Current value. Supports two-way binding and reactive forms.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '0']],
        ],
        'minLabel' => [
            'control' => 'text',
            'description' => 'Text rendered to the left of the track (e.g. the minimum value).',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'showCurrentValue' => [
            'control' => 'boolean',
            'description' => 'Render the current value to the right of the track instead of maxLabel.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
    ],
])

{{-- `maxLabel` is unset here: showCurrentValue replaces the right-hand label
     with the formatted current value (aria-live="polite"). --}}
<tedi:row>
    <tedi:col :width="6">
        <tedi:slider
            :input-id="$inputId"
            :aria-label="$ariaLabel ?: null"
            :min="$min"
            :max="$max"
            :step="$step"
            :value="$value"
            :min-label="$minLabel ?: null"
            :show-current-value="(bool) $showCurrentValue"
            :value-formatter="fn ($v) => $v.'%'"
        />
    </tedi:col>
</tedi:row>
