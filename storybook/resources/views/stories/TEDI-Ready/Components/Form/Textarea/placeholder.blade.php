@storybook([
    'name' => 'Placeholder',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
])

<tedi:form-field :textarea="true">
    <x-slot:label>
        <tedi:form.label for="example-placeholder">Label</tedi:form.label>
    </x-slot:label>
    <tedi:textarea id="example-placeholder" rows="5" placeholder="Placeholder" />
</tedi:form-field>
