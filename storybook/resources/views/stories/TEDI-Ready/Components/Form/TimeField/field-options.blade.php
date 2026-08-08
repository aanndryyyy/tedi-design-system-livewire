@storybook([
    'name' => 'Field Options',
    'order' => 4,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="3" style="max-width: 22rem;">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="opts-default">Default time field</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="opts-default" />
    </tedi:form-field>
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="opts-hint">Time field with hint</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="opts-hint" />
        <x-slot:feedback>
            <tedi:feedback-text text="Vihjetekst" type="hint" />
        </x-slot:feedback>
    </tedi:form-field>
</tedi:row>
