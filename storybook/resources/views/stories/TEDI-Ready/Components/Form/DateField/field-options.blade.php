@storybook([
    'name' => 'Field Options',
    'order' => 4,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="3" style="max-width: 22rem;">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-opt-default">Vaikimisi kuupäevaväli</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-opt-default" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-opt-hint">Kuupäevaväli vihjega</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-opt-hint" placeholder="pp.kk.aaaa" />
            <x-slot:feedback>
                <tedi:feedback-text text="pp.kk.aaaa" type="hint" />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-opt-shortcuts">Kuupäevaväli otseteedega</tedi:form.label>
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
