@storybook([
    'name' => 'Single File',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="single">Label</tedi:form.label>
    </x-slot:label>
    <tedi:file-upload input-id="single" name="file" :files="[['id' => '1', 'name' => 'taotlus_scan_lk_2.pdf']]" />
</tedi:form-field>
