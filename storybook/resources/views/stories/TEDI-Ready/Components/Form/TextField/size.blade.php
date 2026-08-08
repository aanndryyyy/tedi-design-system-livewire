@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular lays this out with <tedi-row [sm]="{ cols: 3 }">. Breakpoint props
    are not ported (CONVENTIONS.md §7 item 1), so the rows use the base `cols`
    only.
--}}
<tedi:row :cols="3" :gap="3" align-items="center">
    <tedi:col><tedi:text>Default</tedi:text></tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="size-default">Label</tedi:form.label>
            </x-slot:label>
            <tedi:text-field id="size-default" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field icon="person">
            <x-slot:label>
                <tedi:form.label for="size-default-with-icon">Label</tedi:form.label>
            </x-slot:label>
            <tedi:text-field id="size-default-with-icon" />
        </tedi:form-field>
    </tedi:col>

    <tedi:col><tedi:text>Small</tedi:text></tedi:col>
    <tedi:col>
        <tedi:form-field size="small">
            <x-slot:label>
                <tedi:form.label for="size-small" size="small">Label</tedi:form.label>
            </x-slot:label>
            <tedi:text-field id="size-small" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field size="small" icon="person">
            <x-slot:label>
                <tedi:form.label for="size-small-with-icon" size="small">Label</tedi:form.label>
            </x-slot:label>
            <tedi:text-field id="size-small-with-icon" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
