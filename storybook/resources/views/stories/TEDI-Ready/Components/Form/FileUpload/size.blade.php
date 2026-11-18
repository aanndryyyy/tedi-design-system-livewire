@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

<tedi:row class="example-list" :cols="1" :gap-y="3">
    <tedi:row :cols="2" :gap="3" align-items="center" class="padding-14-16 border-bottom">
        <tedi:text as="p" modifiers="bold">Default</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="size-default">Label</tedi:form.label>
            </x-slot:label>
            <tedi:file-upload input-id="size-default" name="file" />
        </tedi:form-field>
    </tedi:row>
    <tedi:row :cols="2" :gap="3" align-items="center" class="padding-14-16">
        <tedi:text as="p" modifiers="bold">Small</tedi:text>
        <tedi:form-field size="small">
            <x-slot:label>
                <tedi:form.label for="size-small">Label</tedi:form.label>
            </x-slot:label>
            <tedi:file-upload input-id="size-small" name="file" />
        </tedi:form-field>
    </tedi:row>
</tedi:row>
