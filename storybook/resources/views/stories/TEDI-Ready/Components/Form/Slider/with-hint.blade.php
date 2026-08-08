@storybook([
    'name' => 'With Hint',
    'order' => 5,
    'status' => 'subset',
    'args' => [
        'inputId' => 'slider-hint',
        'ariaLabel' => 'Väärtus',
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'value' => 50,
        'showCurrentValue' => true,
        'feedbackText' => [
            'text' => 'Hint text',
            'type' => 'hint',
            'position' => 'left',
        ],
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
        'showCurrentValue' => [
            'control' => 'boolean',
            'description' => 'Render the current value to the right of the track instead of maxLabel.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'feedbackText' => [
            'control' => 'object',
            'description' => 'FeedbackText component inputs, rendered below the slider.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'ComponentInputs<FeedbackTextComponent>']],
        ],
    ],
])

{{-- The feedback text gets `id="<inputId>-feedback"` and the range input an
     `aria-describedby` pointing at it. A feedbackText of type "error" also sets
     aria-invalid on the input. --}}
<tedi:row>
    <tedi:col :width="6">
        <tedi:slider
            :input-id="$inputId"
            :aria-label="$ariaLabel ?: null"
            :min="$min"
            :max="$max"
            :step="$step"
            :value="$value"
            :show-current-value="(bool) $showCurrentValue"
            :feedback-text="$feedbackText"
            :value-formatter="fn ($v) => $v.'%'"
        />
    </tedi:col>
</tedi:row>
