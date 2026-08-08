@storybook([
    'name' => 'States',
    'order' => 3,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="3" style="max-width: 22rem;">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-state-default">Vaikimisi</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-state-default" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field :disabled="true">
            <x-slot:label>
                <tedi:form.label for="date-state-disabled">Mitteaktiivne</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-state-disabled" display="08.06.2026" value="2026-06-08" :input-disabled="true" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field :valid="true">
            <x-slot:label>
                <tedi:form.label for="date-state-valid">Õnnestumine</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-state-valid" display="08.06.2026" value="2026-06-08" />
            <x-slot:feedback>
                <tedi:feedback-text text="Tagasiside tekst" type="valid" />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field :invalid="true">
            <x-slot:label>
                <tedi:form.label for="date-state-error">Viga</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-state-error" />
            <x-slot:feedback>
                <tedi:feedback-text text="Tagasiside tekst" type="error" />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
</tedi:row>
