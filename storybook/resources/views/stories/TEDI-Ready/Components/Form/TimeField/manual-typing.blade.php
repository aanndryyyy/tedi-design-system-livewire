@storybook([
    'name' => 'Manual Typing',
    'order' => 13,
    'status' => 'subset',
])

<div style="max-width: 22rem;">
    <tedi:form-field>
        <x-slot:label>
            <tedi:form.label for="input-formatting">Aeg</tedi:form.label>
        </x-slot:label>
        <tedi:time-field input-id="input-formatting" placeholder="tt:mm" picker-variant="none" />
        <x-slot:feedback>
            <tedi:feedback-text text="Type a time and tab out — try 1155, 930, 11.55, or 9:5" type="hint" position="left" />
        </x-slot:feedback>
    </tedi:form-field>
</div>
