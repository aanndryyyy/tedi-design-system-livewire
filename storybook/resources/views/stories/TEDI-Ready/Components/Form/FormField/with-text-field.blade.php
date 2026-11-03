@storybook([
    'name' => 'With Text Field',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'size' => 'default',
        'icon' => '',
        'clearable' => false,
        'showClearOnInteraction' => false,
        'characterLimit' => '',
    ],
    'argTypes' => [
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'small', 'large'],
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Icon name or configuration shown at the end of the field.',
        ],
        'clearable' => [
            'control' => 'boolean',
            'description' => 'Shows a clear button when the field has a value.',
        ],
        'showClearOnInteraction' => [
            'control' => 'boolean',
            'description' => 'Shows the clear button only while the filled field is hovered or focused. Requires clearable.',
        ],
        'characterLimit' => [
            'control' => 'number',
            'description' => 'Shows a live character counter and marks excess text as invalid.',
        ],
    ],
])

<tedi:form-field
    :size="$size"
    :icon="$icon ?: null"
    :clearable="(bool) $clearable"
    :show-clear-on-interaction="(bool) $showClearOnInteraction"
    :character-limit="$characterLimit !== '' ? (int) $characterLimit : null"
>
    <x-slot:label>
        <tedi:form.label for="form-field-text-field">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="form-field-text-field" />
</tedi:form-field>
