@storybook([
    'name' => 'With Character Limit',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'characterLimit' => 20,
    ],
    'argTypes' => [
        'characterLimit' => [
            'control' => 'number',
            'description' => 'Shows a live character counter and marks excess text as invalid.',
        ],
    ],
])

{{--
    The counter is live in Angular. Blade renders once: the text field
    registers its value with the form field, so the counter shows the count of
    the initial value.
--}}
<tedi:form-field :character-limit="(int) $characterLimit">
    <x-slot:label>
        <tedi:form.label for="form-field-character-limit">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="form-field-character-limit" value="Text value" />
</tedi:form-field>
