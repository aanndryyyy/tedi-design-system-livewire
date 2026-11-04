@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.103.102?node-id=8769-68905&m=dev',
    'args' => [
        'inputId' => 'example-id',
        'formFieldSize' => 'default',
        'size' => '',
        'value' => '',
        'placeholder' => 'tt:mm',
        'invalid' => false,
        'disabled' => false,
        'formFieldClearable' => true,
        'clearable' => '',
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
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'value' => [
            'description' => 'Selected time in HH:mm format. Two-way bindable.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'placeholder' => [
            'description' => 'Placeholder shown when the input is empty.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'invalid' => [
            'description' => "Manually mark the field as invalid. Sets `aria-invalid` on the input and triggers the form-field's invalid styling. Combines with the form-control validity state from reactive forms.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'description' => 'Disables interaction. Combines with the form-control disabled state.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'size' => [
            'description' => "Overrides the wrapping `tedi-form-field`'s `size` for this field. Leave unset to inherit it — set `size` on the form field instead, so the label scales too. Use it for a standalone time field, where it defaults to `default`.",
            'control' => ['type' => 'radio'],
            'options' => ['', 'default', 'small'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'InputSize | undefined'],
                'defaultValue' => ['summary' => 'undefined'],
            ],
        ],
        'formFieldSize' => [
            'name' => 'size',
            'description' => 'Set on the wrapping `tedi-form-field` — the label and the time field scale together. The time field reads it unless its own `size` is set.',
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'InputSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'clearable' => [
            'description' => "Overrides the wrapping `tedi-form-field`'s `clearable` for this field. Leave unset to inherit it — set `clearable` on the form field instead. Use it for a standalone time field, where it defaults to `true`.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'boolean | undefined'],
                'defaultValue' => ['summary' => 'undefined'],
            ],
        ],
        'formFieldClearable' => [
            'name' => 'clearable',
            'description' => 'Set on the wrapping `tedi-form-field`. Shows a clear button once the field has a value; set `false` to opt out. The time field reads it unless its own `clearable` is set.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'pickerVariant' => [
            'description' => 'Picker variant. `none` renders just the input with no picker UI.',
            'control' => ['type' => 'radio'],
            'options' => ['scroll', 'slots', 'dropdown', 'none'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'TimeFieldPickerVariant'],
                'defaultValue' => ['summary' => 'scroll'],
            ],
        ],
        'pickerTrigger' => [
            'description' => 'What opens the picker: only the icon (`button`) or also clicking the input (`input`).',
            'control' => ['type' => 'radio'],
            'options' => ['button', 'input'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'TimeFieldPickerTrigger'],
                'defaultValue' => ['summary' => 'button'],
            ],
        ],
        'timeSlots' => [
            'description' => 'Predefined HH:mm strings for the slots and dropdown variants.',
            'control' => ['type' => 'object'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'string[]'],
                'defaultValue' => ['summary' => '[]'],
            ],
        ],
        'columns' => [
            'description' => 'Grid columns for the slots variant.',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '3'],
            ],
        ],
        'minuteStep' => [
            'description' => 'Minute step for the scroll variant — e.g. 5 renders 00, 05, 10…',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'Time Field inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'open' => [
            'description' => "Whether the picker panel is shown. Stands in for Angular's runtime popover open state, which a server render cannot hold.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Time Field inputs',
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
<tedi:form-field :size="$formFieldSize" :clearable="(bool) $formFieldClearable">
    <x-slot:label>
        <tedi:form.label :for="$inputId" :size="$formFieldSize">Aeg</tedi:form.label>
    </x-slot:label>

    <tedi:time-field
        :input-id="$inputId"
        :value="$value ?: null"
        :placeholder="$placeholder ?: null"
        :invalid="(bool) $invalid"
        :disabled="(bool) $disabled"
        :clearable="$clearable === '' ? null : (bool) $clearable"
        :size="$size ?: null"
        :picker-variant="$pickerVariant"
        :picker-trigger="$pickerTrigger"
        :time-slots="$timeSlots"
        :columns="(int) $columns"
        :minute-step="(int) $minuteStep"
        :open="(bool) $open"
    />
</tedi:form-field>
