@storybook([
    'name' => 'Multiple',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="multiple">Label</tedi:form.label>
    </x-slot:label>
    <tedi:file-upload input-id="multiple" name="file" multiple :files="[
        ['id' => '1', 'name' => 'taotlus_scan_lk_1.pdf'],
        ['id' => '2', 'name' => 'koolitused_erialane.zip'],
        ['id' => '3', 'name' => 'diplom_2024.pdf'],
        ['id' => '4', 'name' => 'diplom_ulikool_2020.pdf'],
    ]" />
</tedi:form-field>
