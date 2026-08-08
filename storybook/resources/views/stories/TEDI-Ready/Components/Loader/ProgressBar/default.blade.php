@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.49.74?node-id=25616-189000&m=dev',
    'args' => [
        'progressId' => '',
        'value' => 60,
        'size' => 'default',
        'label' => '',
        'labelPosition' => 'top',
        'required' => false,
        'showValue' => true,
        'valuePosition' => 'horizontal',
        'valueLabel' => '',
        'ariaLabel' => 'Edenemisriba pealkiri',
    ],
    'argTypes' => [
        'progressId' => [
            'control' => 'text',
            'description' => 'Optional id for the underlying `<progress>` element. Useful when an external `<label for>` should bind to it.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'value' => [
            'control' => ['type' => 'range', 'min' => 0, 'max' => 100, 'step' => 1],
            'description' => 'Progress value between 0 and 100. Clamped automatically.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '0']],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'Size of the bar. `small` renders a 4px bar height instead of the default 8px.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'ProgressBarSize'], 'defaultValue' => ['summary' => 'default']],
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Optional title rendered above (or to the left of) the bar.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'labelPosition' => [
            'control' => 'radio',
            'options' => ['top', 'horizontal'],
            'description' => 'Where to place the label relative to the bar. Ignored when `label` is not set.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'ProgressBarLabelPosition'], 'defaultValue' => ['summary' => 'top']],
        ],
        'required' => [
            'control' => 'boolean',
            'description' => 'Renders a red `*` after the label to mark the field required. Ignored when `label` is not set.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'showValue' => [
            'control' => 'boolean',
            'description' => 'Show or hide the percentage value.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'valuePosition' => [
            'control' => 'radio',
            'options' => ['horizontal', 'bottom'],
            'description' => 'Where to place the percentage value.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'ProgressBarValuePosition'], 'defaultValue' => ['summary' => 'horizontal']],
        ],
        'valueLabel' => [
            'control' => 'text',
            'description' => 'Override the rendered value text. Defaults to `"{value}%"`. Use for non-percentage progress (e.g. `value=20` with `valueLabel="1/5"`).',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible label for the progress bar. Falls back to `label()` when omitted.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
    ],
])

<tedi:progress-bar
    :progress-id="$progressId ?: null"
    :value="$value"
    :size="$size"
    :label="$label ?: null"
    :label-position="$labelPosition"
    :required="(bool) $required"
    :show-value="(bool) $showValue"
    :value-position="$valuePosition"
    :value-label="$valueLabel ?: null"
    :aria-label="$ariaLabel ?: null"
/>
