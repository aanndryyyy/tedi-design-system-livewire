@storybook([
    'name' => 'Value Type',
    'order' => 5,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="3" style="max-width: 22rem;">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="value-default">Aeg</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="value-default" />
    </tedi:form-field>
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="value-placeholder">Aeg</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="value-placeholder" placeholder="tt:mm" />
    </tedi:form-field>
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="value-set">Aeg</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="value-set" value="13:00" />
    </tedi:form-field>
</tedi:row>
