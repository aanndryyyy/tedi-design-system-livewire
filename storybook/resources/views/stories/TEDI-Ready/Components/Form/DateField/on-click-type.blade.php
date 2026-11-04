@storybook([
    'name' => 'On Click Type',
    'order' => 7,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:row :gap="3" :cols="2">
    <tedi:col>
        <tedi:text as="p">Calendar button is clickable</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-trigger-button">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-trigger-button" calendar-trigger="button" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Input is clickable</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-trigger-input">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-trigger-input" calendar-trigger="input" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
