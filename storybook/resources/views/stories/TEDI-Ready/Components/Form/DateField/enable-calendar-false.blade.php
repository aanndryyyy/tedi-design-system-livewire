@storybook([
    'name' => 'Enable Calendar False',
    'order' => 18,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="date-no-calendar">Kuupäev</tedi:form.label>
    </x-slot:label>
    <tedi:date-field input-id="date-no-calendar" :enable-calendar="false" placeholder="pp.kk.aaaa" />
    <x-slot:feedback>
        <tedi:feedback-text text="Ainult käsitsi sisestus — valija puudub." />
    </x-slot:feedback>
</tedi:form-field>
