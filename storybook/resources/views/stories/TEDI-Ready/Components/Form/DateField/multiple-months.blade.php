@storybook([
    'name' => 'Multiple Months',
    'order' => 11,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="date-multiple-months">Kuupäev</tedi:form.label>
    </x-slot:label>
    <tedi:date-field input-id="date-multiple-months" :number-of-months="2" :open="true" />
    <x-slot:feedback>
        <tedi:feedback-text text="Kaks kuud kuvatakse kõrvuti igal ekraanilaiusel." />
    </x-slot:feedback>
</tedi:form-field>
