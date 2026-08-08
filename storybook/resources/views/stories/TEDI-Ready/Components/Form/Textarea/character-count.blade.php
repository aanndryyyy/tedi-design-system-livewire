@storybook([
    'name' => 'Character Count',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

{{--
    The counter is live in Angular (it reads the control's value). Blade renders
    once, so `characterCount` is an explicit prop (CONVENTIONS.md §5) and shows
    the count of the initial value.
--}}
<tedi:form-field :textarea="true" :character-limit="400" :character-count="12">
    <x-slot:label>
        <tedi:form.label for="example-only-char-count">Label</tedi:form.label>
    </x-slot:label>
    <tedi:textarea id="example-only-char-count" rows="5">Kaksteist tk</tedi:textarea>
</tedi:form-field>
