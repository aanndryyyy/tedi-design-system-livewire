@storybook([
    'name' => 'Character Count',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

{{--
    The counter is live in Angular (it reads the control's value as you type).
    Blade renders once: the textarea registers its value with the form field,
    so the counter shows the count of the initial value (here empty, 0/400).
--}}
<tedi:form-field :character-limit="400">
    <x-slot:label>
        <tedi:form.label for="example-only-char-count">Label</tedi:form.label>
    </x-slot:label>
    <tedi:textarea id="example-only-char-count" :min-rows="5" />
</tedi:form-field>
