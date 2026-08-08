@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=19071-105925&m=dev',
    'args' => [
        'inputId' => 'slider-example',
        'name' => '',
        'label' => 'Väärtus',
        'hideLabel' => false,
        'required' => false,
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'value' => 50,
        'disabled' => false,
        'invalid' => false,
        'minLabel' => '0%',
        'maxLabel' => '100%',
        'showCurrentValue' => false,
        'feedbackText' => null,
        'ariaLabel' => '',
        'ariaLabelledby' => '',
        'ariaValuetext' => '',
    ],
    'argTypes' => [
        'inputId' => [
            'control' => false,
            'description' => 'Identifier for the underlying range input; associates the label with the input.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'name' => [
            'control' => 'text',
            'description' => 'Name attribute of the underlying input.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Label rendered above the slider.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'hideLabel' => [
            'control' => 'select',
            'options' => [false, true, 'keep-space'],
            'description' => "Hide the label visually while keeping it for assistive tech; 'keep-space' also reserves its vertical space.",
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => "boolean | 'keep-space'"],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'required' => [
            'control' => 'boolean',
            'description' => 'Marks the field as required.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'min' => [
            'control' => 'number',
            'description' => 'Minimum allowed value.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '0'],
            ],
        ],
        'max' => [
            'control' => 'number',
            'description' => 'Maximum allowed value.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '100'],
            ],
        ],
        'step' => [
            'control' => 'number',
            'description' => 'Step size.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'value' => [
            'control' => 'number',
            'description' => 'Current value. Supports two-way binding and reactive forms.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '0'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Disables the slider.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'invalid' => [
            'control' => 'boolean',
            'description' => 'Marks the slider as invalid for validation purposes.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
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
        'showCurrentValue' => [
            'control' => 'boolean',
            'description' => 'Render the current value to the right of the track instead of maxLabel.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'feedbackText' => [
            'control' => 'object',
            'description' => 'FeedbackText component inputs, rendered below the slider.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ComponentInputs<FeedbackTextComponent>'],
            ],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible label used when no visible label is provided.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'ariaLabelledby' => [
            'control' => 'text',
            'description' => 'ID of an element that labels the slider, used when no visible label is provided.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'ariaValuetext' => [
            'control' => 'text',
            'description' => 'Human-readable text alternative of the current value.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
    ],
])

{{--
    The Angular meta also exposes `valueFormatter` (a function input) and
    `tooltip`. `valueFormatter` is a PHP callable in the Blade port and has no
    Storybook control, so it is passed inline below exactly as the Angular
    render function does (`(value) => `${value}%``). `tooltip` is not a prop at
    all — the thumb tooltip is CDK-Overlay based and out of scope
    (CONVENTIONS.md §7 item 3); see slider.blade.php's header.
--}}
<tedi:row>
    <tedi:col :width="6">
        <tedi:slider
            :input-id="$inputId"
            :name="$name ?: null"
            :label="$label ?: null"
            :hide-label="$hideLabel"
            :required="(bool) $required"
            :min="$min"
            :max="$max"
            :step="$step"
            :value="$value"
            :disabled="(bool) $disabled"
            :invalid="(bool) $invalid"
            :min-label="$minLabel ?: null"
            :max-label="$maxLabel ?: null"
            :show-current-value="(bool) $showCurrentValue"
            :value-formatter="fn ($v) => $v.'%'"
            :feedback-text="$feedbackText"
            :aria-label="$ariaLabel ?: null"
            :aria-labelledby="$ariaLabelledby ?: null"
            :aria-valuetext="$ariaValuetext ?: null"
        />
    </tedi:col>
</tedi:row>
