@storybook([
    'name' => 'With Icon',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'icon' => 'search',
    ],
    'argTypes' => [
        'icon' => [
            'control' => 'text',
            'description' => 'Icon name or configuration shown at the end of the field.',
        ],
    ],
])

<tedi:form-field :icon="$icon ?: null">
    <x-slot:label>
        <tedi:form.label for="form-field-icon">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="form-field-icon" />
</tedi:form-field>
