@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'subset',
])

{{-- The date field inherits `size` from the wrapping form-field (Angular 8.0's field context). --}}
<tedi:row class="example-list" :cols="1" :gap-y="3">
    <tedi:row :cols="2" align-items="center" class="padding-14-16 border-bottom">
        <b>Default</b>
        <tedi:form-field size="default">
            <x-slot:label>
                <tedi:form.label for="date-size-default">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-size-default" />
        </tedi:form-field>
    </tedi:row>
    <tedi:row :cols="2" align-items="center" class="padding-14-16">
        <b>Small</b>
        <tedi:form-field size="small">
            <x-slot:label>
                <tedi:form.label for="date-size-small" size="small">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-size-small" />
        </tedi:form-field>
    </tedi:row>
</tedi:row>
