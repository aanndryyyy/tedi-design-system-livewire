@storybook([
    'name' => 'Placeholder',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="example-placeholder">Label</tedi:form.label>
    </x-slot:label>
    <tedi:textarea id="example-placeholder" :min-rows="5" placeholder="Placeholder" />
</tedi:form-field>
