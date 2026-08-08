@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=6060-65779&m=dev',
    'args' => [
        'size' => 'default',
        'icon' => '',
        'clearable' => false,
        'inputClass' => '',
        'arrowsHidden' => true,
        'value' => '',
    ],
    'argTypes' => [
        'size' => [
            'description' => 'Input field size.',
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small', 'large'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'InputSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'icon' => [
            'description' => 'Icon name or configuration for the input field.',
            'control' => ['type' => 'object'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'string | TextFieldIcon'],
            ],
        ],
        'clearable' => [
            'description' => 'Whether the input includes a clear button.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'inputClass' => [
            'control' => 'text',
            'description' => 'Custom CSS classes for the input.',
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'arrowsHidden' => [
            'description' => 'Whether to hide arrows for number inputs.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Text Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'value' => [
            'control' => 'text',
            'description' => 'Current value. Emitted as the `value` attribute only when non-empty.',
            'table' => [
                'category' => 'Text Field inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => ''],
            ],
        ],
    ],
])

{{--
    Angular's argTypes also expose the `clear` output(). output()s are not
    re-emitted by this port (CONVENTIONS.md §7 item 2, CONTRACT.md §5), so it
    is dropped rather than faked as an action.

    `value` is added as a control because the form-field clear button is only
    visible when the field is non-empty — Angular reads that off the live
    control at runtime, which the server render cannot do.
--}}
<tedi:form-field
    :size="$size"
    :icon="$icon ?: null"
    :clearable="(bool) $clearable"
    :input-class="$inputClass ?: null"
    :value="$value"
>
    <x-slot:label>
        <tedi:form.label for="default">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="default" :value="$value" :arrows-hidden="(bool) $arrowsHidden" />
</tedi:form-field>
