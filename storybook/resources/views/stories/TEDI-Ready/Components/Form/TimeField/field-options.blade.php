@storybook([
    'name' => 'Field Options',
    'order' => 4,
    'status' => 'subset',
])

<tedi:row :cols="3">
    <tedi:col>
        <tedi:row :cols="1" :gap-y="3">
            <tedi:col>
                <tedi:text as="p">Default time field</tedi:text>
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label for="opts-default">Aeg</tedi:form.label>
                    </x-slot:label>
                    <tedi:time-field input-id="opts-default" />
                </tedi:form-field>
            </tedi:col>
            <tedi:col>
                <tedi:text as="p">Time field with hint</tedi:text>
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label for="opts-hint">Aeg</tedi:form.label>
                    </x-slot:label>
                    <tedi:time-field input-id="opts-hint" />
                    <x-slot:feedback>
                        <tedi:feedback-text text="Vihjetekst" type="hint" />
                    </x-slot:feedback>
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    </tedi:col>
</tedi:row>
