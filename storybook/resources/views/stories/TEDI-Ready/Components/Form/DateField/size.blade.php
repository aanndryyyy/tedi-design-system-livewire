@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="3" style="max-width: 22rem;">
    <tedi:col>
        <tedi:form-field size="default">
            <x-slot:label>
                <tedi:form.label for="date-size-default">Vaikimisi</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-size-default" size="default" display="05.06.2026" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field size="small">
            <x-slot:label>
                <tedi:form.label for="date-size-small" size="small">Väike</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-size-small" size="small" display="05.06.2026" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
