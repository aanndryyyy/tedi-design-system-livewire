@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=6060-65779&m=dev',
    'args' => [
        'size' => 'default',
        'invalid' => false,
        'icon' => '',
        'clearable' => true,
        'readOnly' => false,
        'showClearOnInteraction' => false,
        'arrowsHidden' => true,
        'value' => '',
    ],
    'argTypes' => [
        'size' => [
            'description' => 'Size of the field. Falls back to the size of a wrapping form field.',
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small', 'large'],
            'table' => [
                'category' => 'Text Field inputs',
                'type' => ['summary' => 'InputSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'invalid' => [
            'description' => 'Forces the error state on. Combines with the state derived from reactive forms.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Text Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'icon' => [
            'description' => 'Icon name or configuration, shown at the end of the field.',
            'control' => ['type' => 'object'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'string | FormFieldIcon'],
            ],
        ],
        'clearable' => [
            'description' => 'Whether the field shows a clear button once it has a value. Set `false` to opt out. Hidden while the text field is read-only.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'readOnly' => [
            'description' => 'Makes the field read-only, like the native `readonly` attribute, which is also accepted. The value can still be selected and submitted, but not edited, and a wrapping form field hides its clear button.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Text Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'showClearOnInteraction' => [
            'description' => 'Show the clear button only on hover or focus when the field has a value. Requires clearable.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
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
    visible when the field is non-empty, and the server render only knows the
    value it was given. The text field registers it with the form-field.
--}}
<tedi:form-field
    :size="$size"
    :icon="$icon ?: null"
    :clearable="(bool) $clearable"
    :show-clear-on-interaction="(bool) $showClearOnInteraction"
>
    <x-slot:label>
        <tedi:form.label for="default">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field
        id="default"
        :value="$value"
        :invalid="(bool) $invalid"
        :readonly="(bool) $readOnly"
        :arrows-hidden="(bool) $arrowsHidden"
    />
</tedi:form-field>
