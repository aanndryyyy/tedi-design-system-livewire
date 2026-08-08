@storybook([
    'name' => 'On Click Type',
    'order' => 6,
    'status' => 'subset',
])

{{--
    Both fields are shown open so the difference is visible at all: overlay
    positioning is out of scope (CONVENTIONS.md §7 item 3), so the panel renders
    inline rather than anchored to the icon or to the field.
--}}
<tedi:row :cols="1" :gap-y="3">
    <tedi:col>
        <tedi:text as="p">Clock button is clickable</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="trigger-button">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field input-id="trigger-button" value="03:03" picker-trigger="button" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Input is clickable</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="trigger-input">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field input-id="trigger-input" value="03:03" picker-trigger="input" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
