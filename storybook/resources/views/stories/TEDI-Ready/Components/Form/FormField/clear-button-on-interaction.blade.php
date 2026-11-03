@storybook([
    'name' => 'Clear Button On Interaction',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'clearable' => true,
        'showClearOnInteraction' => true,
    ],
    'argTypes' => [
        'clearable' => [
            'control' => 'boolean',
            'description' => 'Shows a clear button when the field has a value.',
        ],
        'showClearOnInteraction' => [
            'control' => 'boolean',
            'description' => 'Shows the clear button only while the filled field is hovered or focused. Requires clearable.',
        ],
    ],
])

<tedi:form-field
    :clearable="(bool) $clearable"
    :show-clear-on-interaction="(bool) $showClearOnInteraction"
>
    <x-slot:label>
        <tedi:form.label for="form-field-clear-on-interaction">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="form-field-clear-on-interaction" value="Text value" />
</tedi:form-field>
