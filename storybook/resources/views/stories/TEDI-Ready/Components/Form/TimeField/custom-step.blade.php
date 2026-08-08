@storybook([
    'name' => 'Custom Step',
    'order' => 9,
    'status' => 'subset',
])

<div style="max-width: 22rem;">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="scroll-picker-step">Time with 15-min steps</tedi:form.label>
        </x-slot:label>
        <tedi:time-field
            input-id="scroll-picker-step"
            value="14:30"
            picker-variant="scroll"
            :minute-step="15"
            :open="true"
        />
    </tedi:form-field>
</div>
