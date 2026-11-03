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
    <tedi:textarea id="example-hint" :min-rows="5" />
    <x-slot:feedback>
        <tedi:feedback-text text="Vihjetekst" />
    </x-slot:feedback>
</tedi:form-field>
