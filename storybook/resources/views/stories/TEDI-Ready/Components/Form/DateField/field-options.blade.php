@storybook([
    'name' => 'Field Options',
    'order' => 4,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap="3">
    <tedi:col>
        <tedi:text as="p">Default date field</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-opt-default">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-opt-default" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Date field with helper text</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-opt-hint">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-opt-hint" placeholder="pp.kk.aaaa" />
            <x-slot:feedback>
                <tedi:feedback-text text="pp.kk.aaaa" type="hint" />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Date field with quick-select shortcuts</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-opt-shortcuts">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-opt-shortcuts" />
            <tedi:row :cols="2" :gap-x="2" style="max-width: 12rem;">
                <tedi:col>
                    <tedi:button variant="neutral" size="small">Täna</tedi:button>
                </tedi:col>
                <tedi:col>
                    <tedi:button variant="neutral" size="small">Homme</tedi:button>
                </tedi:col>
            </tedi:row>
        </tedi:form-field>
    </tedi:col>
</tedi:row>
