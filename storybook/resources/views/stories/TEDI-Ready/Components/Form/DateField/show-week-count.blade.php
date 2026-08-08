@storybook([
    'name' => 'Show Week Count',
    'order' => 10,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="date-week-count">Kuupäev</tedi:form.label>
    </x-slot:label>
    <tedi:date-field input-id="date-week-count" :show-week-numbers="true" :open="true" />
    <x-slot:feedback>
        <tedi:feedback-text text="ISO nädalanumbrid kuvatakse vasakul." />
    </x-slot:feedback>
</tedi:form-field>
