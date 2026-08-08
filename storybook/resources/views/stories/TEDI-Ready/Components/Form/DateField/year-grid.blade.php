@storybook([
    'name' => 'Year Grid',
    'order' => 12,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="date-year-grid">Aasta</tedi:form.label>
    </x-slot:label>
    <tedi:date-field
        input-id="date-year-grid"
        month-year-select-type="grid"
        selection-level="years"
        placeholder="aaaa"
        :display="date('Y')"
        :open="true"
    />
    <x-slot:feedback>
        <tedi:feedback-text text="Vali aasta — väli näitab ainult aastanumbrit." />
    </x-slot:feedback>
</tedi:form-field>
