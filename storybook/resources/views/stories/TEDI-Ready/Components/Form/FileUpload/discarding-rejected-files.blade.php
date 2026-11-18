@storybook([
    'name' => 'Validation: discarding rejected files',
    'order' => 9,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

{{--
    Upstream's story-only `storyRejectedFile` directive: selects a file by this
    name once the field is up, so the story shows the rejection the field
    writes for itself.
--}}
<div x-data x-init="setTimeout(() => { const input = $el.querySelector('input[type=file]'); const transfer = new DataTransfer(); transfer.items.add(new File([''], 'taotlus_scan_lk_1.png')); input.files = transfer.files; input.dispatchEvent(new Event('change')); })">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="rejected-discarded">Label</tedi:form.label>
        </x-slot:label>
        <tedi:file-upload input-id="rejected-discarded" name="file" multiple accept=".pdf,.txt" :max-size="1024" :files="[['id' => '1', 'name' => 'taotlus_scan_lk_2.pdf']]" />
    </tedi:form-field>
</div>
