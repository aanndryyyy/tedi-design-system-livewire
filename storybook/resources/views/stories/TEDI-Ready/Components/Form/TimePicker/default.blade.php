@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.41.64?node-id=42943-146292&m=dev',
    'args' => [
        'value' => '03:03',
        'variant' => 'scroll',
        'timeSlots' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30'],
        'columns' => 3,
        'showSlotIndicator' => false,
        'minuteStep' => 1,
        'disabled' => false,
        'border' => false,
    ],
    'argTypes' => [
        'value' => [
            'description' => 'Selected time in HH:mm format. Two-way bindable.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'variant' => [
            'description' => 'Visual variant — scroll wheels, predefined slot grid, or a dropdown list.',
            'control' => ['type' => 'radio'],
            'options' => ['scroll', 'slots', 'dropdown'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TimePickerVariant'],
                'defaultValue' => ['summary' => 'scroll'],
            ],
        ],
        'minuteStep' => [
            'description' => 'Minute increment for the scroll variant — e.g. 5 renders 00, 05, 10…',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'timeSlots' => [
            'description' => 'Predefined HH:mm strings rendered by the slots and dropdown variants.',
            'control' => ['type' => 'object'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string[]'],
                'defaultValue' => ['summary' => '[]'],
            ],
        ],
        'columns' => [
            'description' => 'Number of columns rendered by the slots variant.',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '3'],
            ],
        ],
        'showSlotIndicator' => [
            'description' => 'Show the radio indicator dot on each card in the slots variant. Has no effect on other variants.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'description' => 'Disables interaction with the picker.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'border' => [
            'description' => 'Render the picker with a surrounding border — useful when the picker is embedded inside other content and needs to visually stand apart.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

{{--
    Angular's argTypes also expose `trapFocus`, which this port drops: it is
    pure JS focus behaviour with no markup effect (CONVENTIONS.md §7 item 2,
    CONTRACT.md §5). Dropped, not faked as an inert control.

    Note the host element carries NO `tedi-time-picker--<variant>` class:
    TEDI ships no rule for any of the three, so they are dropped per
    CONVENTIONS.md §4. `variant` still selects the markup branch.
--}}
<div style="width: 178px;">
    <tedi:time-picker
        :value="$value ?: null"
        :variant="$variant"
        :time-slots="$timeSlots"
        :columns="(int) $columns"
        :show-slot-indicator="(bool) $showSlotIndicator"
        :minute-step="(int) $minuteStep"
        :disabled="(bool) $disabled"
        :border="(bool) $border"
    />
</div>
