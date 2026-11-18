@storybook([
    'name' => 'Loading',
    'order' => 8,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="loading">Label</tedi:form.label>
    </x-slot:label>
    <tedi:file-upload
        input-id="loading"
        name="file"
        multiple
        :files="[
            ['id' => '1', 'name' => 'report.pdf', 'isLoading' => true],
            ['id' => '2', 'name' => 'report_1.pdf'],
        ]"
    />
</tedi:form-field>
