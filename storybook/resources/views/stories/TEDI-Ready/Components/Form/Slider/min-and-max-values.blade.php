@storybook([
    'name' => 'Min And Max Values',
    'order' => 3,
    'status' => 'subset',
    'args' => [
        'inputId' => 'slider-min-max',
        'ariaLabel' => 'Väärtus',
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'value' => 50,
        'minLabel' => '0%',
        'maxLabel' => '100%',
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
        'maxLabel' => [
            'control' => 'text',
            'description' => 'Text rendered to the right of the track. Ignored when showCurrentValue is true.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
    ],
])

{{-- Angular sets `label: undefined` here, so the slider is named by ariaLabel
     alone and the min/max labels flank the track. --}}
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
            :max-label="$maxLabel ?: null"
            :value-formatter="fn ($v) => $v.'%'"
        />
    </tedi:col>
</tedi:row>
