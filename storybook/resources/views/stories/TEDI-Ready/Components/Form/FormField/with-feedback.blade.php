@storybook([
    'name' => 'With Feedback',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="form-field-feedback">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="form-field-feedback" />

    <x-slot:feedback>
        <tedi:feedback-text text="Helpful information about this field" />
    </x-slot:feedback>
</tedi:form-field>
