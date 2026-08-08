@storybook([
    'name' => 'Field Without Picker',
    'order' => 11,
    'status' => 'subset',
])

<div style="max-width: 22rem;">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="no-picker">Aeg</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="no-picker" placeholder="tt:mm" picker-variant="none" />
    </tedi:form-field>
</div>
