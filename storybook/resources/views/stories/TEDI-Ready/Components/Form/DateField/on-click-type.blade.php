@storybook([
    'name' => 'On Click Type',
    'order' => 7,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:row :gap="3" :cols="2">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-trigger-button">Kalendriikoon on klõpsatav</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-trigger-button" calendar-trigger="button" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-trigger-input">Sisestusväli on klõpsatav</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-trigger-input" calendar-trigger="input" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
