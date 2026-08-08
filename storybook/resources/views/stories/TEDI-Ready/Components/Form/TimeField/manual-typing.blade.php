@storybook([
    'name' => 'Manual Typing',
    'order' => 13,
    'status' => 'subset',
])

<div style="max-width: 22rem;">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="input-formatting">Type a time and tab out</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="input-formatting" placeholder="tt:mm" picker-variant="none" />
        <x-slot:feedback>
            <tedi:feedback-text text="Try 1155, 930, 11.55, or 9:5" type="hint" position="left" />
        </x-slot:feedback>
    </tedi:form-field>
</div>
