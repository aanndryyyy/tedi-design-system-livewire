@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular lays this out with <tedi-row [sm]="{ cols: 2 }">. Breakpoint props
    are not ported (CONVENTIONS.md §7 item 1), so the row uses the base `cols`.
    The label takes the small size from the form field.
--}}
<tedi:row :cols="2" :gap="3" align-items="center">
    <tedi:col><tedi:text modifiers="bold">Default</tedi:text></tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="size-default">Label</tedi:form.label>
            </x-slot:label>
            <tedi:textarea id="size-default" :min-rows="5" />
        </tedi:form-field>
    </tedi:col>

    <tedi:col><tedi:text modifiers="bold">Small</tedi:text></tedi:col>
    <tedi:col>
        <tedi:form-field size="small">
            <x-slot:label>
                <tedi:form.label for="size-small">Label</tedi:form.label>
            </x-slot:label>
            <tedi:textarea id="size-small" :min-rows="5" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
