@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'inputId' => 'example-id',
        'label' => 'Label',
        'value' => '',
        'disabled' => false,
        'required' => false,
        'min' => '',
        'max' => '',
        'step' => 1,
        'size' => 'default',
        'invalid' => false,
        'suffix' => '',
        'feedbackText' => '',
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'inputId' => [
            'control' => ['type' => 'text'],
            'description' => "The unique identifier for the input element that this label is associated with. This ID should match the input element's id attribute to ensure accessibility.",
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'label' => [
            'control' => ['type' => 'text'],
            'description' => 'The text content of the label that describes the input field.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'value' => [
            'control' => ['type' => 'number'],
            'description' => 'Value of the input field. Supports two-way binding, use with form controls.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'disabled' => [
            'control' => ['type' => 'boolean'],
            'description' => 'Is input disabled?',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'required' => [
            'control' => ['type' => 'boolean'],
            'description' => 'Indicates whether the input field is required. If set to true, the required indicator will be displayed next to the label.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'min' => [
            'control' => ['type' => 'number'],
            'description' => 'Minimum allowed value. Disables decrementing below this value and restricts manual input.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'max' => [
            'control' => ['type' => 'number'],
            'description' => 'Maximum allowed value. Disables incrementing above this value and restricts manual input.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'step' => [
            'control' => ['type' => 'number'],
            'description' => 'Step size for incrementing or decrementing the value.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'size' => [
            'control' => ['type' => 'select'],
            'options' => ['default', 'small'],
            'description' => 'Size of the number field.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'NumberFieldSize'], 'defaultValue' => ['summary' => 'default']],
        ],
        'invalid' => [
            'control' => ['type' => 'boolean'],
            'description' => 'Marks the field as invalid for validation purposes.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'suffix' => [
            'control' => ['type' => 'text'],
            'description' => 'Text displayed after the input value, typically a unit.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'feedbackText' => [
            'control' => ['type' => 'object'],
            'description' => '[FeedbackText](/?path=/docs/tedi-ready-components-form-feedbacktext--docs) component inputs.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'ComponentInputs<FeedbackTextComponent>']],
        ],
        'ariaLabel' => [
            'control' => ['type' => 'text'],
            'description' => 'Accessible label for the input, used when no visible label is provided.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
    ],
])

{{--
    `fullWidth` is a real prop of <tedi:number-field> but the Angular story's
    argTypes do not expose it, so neither does this one — the Full Width story
    demonstrates it instead.
--}}
<tedi:number-field
    :input-id="$inputId"
    :label="$label ?: null"
    :value="$value === '' ? null : $value"
    :disabled="(bool) $disabled"
    :required="(bool) $required"
    :min="$min === '' ? null : $min"
    :max="$max === '' ? null : $max"
    :step="$step"
    :size="$size"
    :invalid="(bool) $invalid"
    :suffix="$suffix ?: null"
    :feedback-text="$feedbackText ?: null"
    :aria-label="$ariaLabel ?: null"
/>
