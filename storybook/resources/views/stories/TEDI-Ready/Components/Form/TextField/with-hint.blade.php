@storybook([
    'name' => 'With Hint',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="example-hint">Label</tedi:form.label>
    </x-slot:label>
    <tedi:text-field id="example-hint" />
    <x-slot:feedback>
        <tedi:feedback-text text="Hint text" />
    </x-slot:feedback>
</tedi:form-field>
