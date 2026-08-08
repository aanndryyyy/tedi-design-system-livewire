@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.41.64?node-id=4662-91741&m=dev',
    'args' => [
        'inputId' => 'example-id',
        'value' => '',
        'placeholder' => 'tt:mm',
        'invalid' => false,
        'disabled' => false,
        'clearable' => true,
        'pickerVariant' => 'scroll',
        'pickerTrigger' => 'button',
        'timeSlots' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30'],
        'columns' => 3,
        'minuteStep' => 1,
        'open' => false,
    ],
    'argTypes' => [
        'inputId' => [
            'description' => 'Unique ID for label association and accessibility.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'value' => [
            'description' => 'Selected time in HH:mm format. Two-way bindable.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'placeholder' => [
            'description' => 'Placeholder shown when the input is empty.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'invalid' => [
            'description' => "Manually mark the field as invalid. Sets `aria-invalid` on the input and triggers the form-field's invalid styling. Combines with the form-control validity state from reactive forms.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'description' => 'Disables interaction. Combines with the form-control disabled state.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'clearable' => [
            'description' => 'Show a clear button when the field has a value.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'pickerVariant' => [
            'description' => 'Picker variant. `none` renders just the input with no picker UI.',
            'control' => ['type' => 'radio'],
            'options' => ['scroll', 'slots', 'dropdown', 'none'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TimeFieldPickerVariant'],
                'defaultValue' => ['summary' => 'scroll'],
            ],
        ],
        'pickerTrigger' => [
            'description' => 'What opens the picker: only the icon (`button`) or also clicking the input (`input`).',
            'control' => ['type' => 'radio'],
            'options' => ['button', 'input'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TimeFieldPickerTrigger'],
                'defaultValue' => ['summary' => 'button'],
            ],
        ],
        'timeSlots' => [
            'description' => 'Predefined HH:mm strings for the slots and dropdown variants.',
            'control' => ['type' => 'object'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string[]'],
                'defaultValue' => ['summary' => '[]'],
            ],
        ],
        'columns' => [
            'description' => 'Grid columns for the slots variant.',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '3'],
            ],
        ],
        'minuteStep' => [
            'description' => 'Minute step for the scroll variant — e.g. 5 renders 00, 05, 10…',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'open' => [
            'description' => "Whether the picker panel is shown. Stands in for Angular's runtime popover open state, which a server render cannot hold.",
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
    Angular's argTypes also expose `useNativePicker`, `modal` and `fullscreen`
    (breakpoint props, CONVENTIONS.md §7 item 1) and `closeOnSelect` (runtime
    only). All four are dropped rather than declared as inert controls
    (CONTRACT.md §5).

    `open` is added instead: overlay positioning is out of scope (§7 item 3), so
    the picker panel renders inline under an explicit prop. That is the only way
    the open state is reachable at all in a template-only port.
--}}
<tedi:form-field>
    <x-slot:label>
        <tedi:form.label :for="$inputId">Aeg</tedi:form.label>
    </x-slot:label>

    <tedi:time-field
        :input-id="$inputId"
        :value="$value ?: null"
        :placeholder="$placeholder ?: null"
        :invalid="(bool) $invalid"
        :disabled="(bool) $disabled"
        :clearable="(bool) $clearable"
        :picker-variant="$pickerVariant"
        :picker-trigger="$pickerTrigger"
        :time-slots="$timeSlots"
        :columns="(int) $columns"
        :minute-step="(int) $minuteStep"
        :open="(bool) $open"
    />
</tedi:form-field>
