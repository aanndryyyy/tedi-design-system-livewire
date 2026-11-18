@storybook([
    'name' => 'With Validator',
    'order' => 11,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

{{--
    Upstream's story-only `storyRejectedFile` directive: selects a file by this
    name once the field is up, so the story shows the rejection the field
    writes for itself.
--}}
{{--
    Upstream passes a TypeScript function; here `validator` is a JavaScript
    expression evaluated with `file` in scope (see the component's docblock).
--}}
<div x-data x-init="setTimeout(() => { const input = $el.querySelector('input[type=file]'); const transfer = new DataTransfer(); transfer.items.add(new File([''], 'taotlus scan lk 1.png')); input.files = transfer.files; input.dispatchEvent(new Event('change')); })">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="validator">Label</tedi:form.label>
        </x-slot:label>
        <tedi:file-upload
            input-id="validator"
            name="file"
            multiple
            accept=".pdf,.txt"
            :max-size="100 * 1024"
            validator="(file) => file.name.includes(' ') ? 'Failinimes ei tohi olla tühikuid' : null"
            hint="Failinimes ei tohi olla tühikuid."
        />
    </tedi:form-field>
</div>
