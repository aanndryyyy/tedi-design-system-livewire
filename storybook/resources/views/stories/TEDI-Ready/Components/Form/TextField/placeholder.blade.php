@storybook([
    'name' => 'Placeholder',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="example-placeholder">Label</tedi:form.label>
    </x-slot:label>
    <tedi:text-field id="example-placeholder" placeholder="Placeholder" />
</tedi:form-field>
