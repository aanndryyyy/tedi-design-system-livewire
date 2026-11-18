@storybook([
    'name' => 'Read Only',
    'order' => 7,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="read-only">Label</tedi:form.label>
    </x-slot:label>
    <tedi:file-upload input-id="read-only" name="file" multiple read-only :files="[
        ['id' => '1', 'name' => 'taotlus_scan_lk_1.pdf'],
        ['id' => '2', 'name' => 'koolitused_erialane.zip'],
        ['id' => '3', 'name' => 'diplom_2024.pdf'],
        ['id' => '4', 'name' => 'diplom_ulikool_2020.pdf'],
    ]" />
</tedi:form-field>
