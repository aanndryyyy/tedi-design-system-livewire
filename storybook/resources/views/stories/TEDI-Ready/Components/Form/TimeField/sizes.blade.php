@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="3">
    <tedi:row :cols="2" align-items="center" class="padding-14-16 border-bottom">
        <b>Default</b>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="size-default">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field input-id="size-default" />
        </tedi:form-field>
    </tedi:row>
    <tedi:row :cols="2" align-items="center" class="padding-14-16">
        <b>Small</b>
        <tedi:form-field size="small">
            <x-slot:label>
                <tedi:form.label for="size-small" size="small">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field input-id="size-small" />
        </tedi:form-field>
    </tedi:row>
</tedi:row>
