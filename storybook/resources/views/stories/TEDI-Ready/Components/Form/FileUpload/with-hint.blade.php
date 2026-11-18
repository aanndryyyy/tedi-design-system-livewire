@storybook([
    'name' => 'With Hint',
    'order' => 6,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

<tedi:row :cols="1" :gap-y="3">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="hint-own">Label</tedi:form.label>
            </x-slot:label>
            <tedi:file-upload input-id="hint-own" name="file" accept=".jpg,.png,.pdf" :max-size="1024 ** 2" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="hint-extra">Label</tedi:form.label>
            </x-slot:label>
            <tedi:file-upload input-id="hint-extra" name="file" accept=".jpg,.png,.pdf" :max-size="1024 ** 2" hint="Lisa allkirjastatud avalduse koopia." />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="hint-projected">Label</tedi:form.label>
            </x-slot:label>
            <tedi:file-upload input-id="hint-projected" name="file" accept=".jpg,.png,.pdf" :max-size="1024 ** 2" :show-restrictions="false" />
            <x-slot:feedback>
                <tedi:feedback-text text="JPG, PNG, PDF suurusega kuni 1 MB." />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
</tedi:row>
