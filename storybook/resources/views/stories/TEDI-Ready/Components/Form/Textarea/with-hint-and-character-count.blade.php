@storybook([
    'name' => 'With Hint And Character Count',
    'order' => 5,
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
        <tedi:form.label for="example-char-count">Label</tedi:form.label>
    </x-slot:label>
    <tedi:textarea id="example-char-count" rows="5">Kaksteist tk</tedi:textarea>
    <x-slot:feedback>
        <tedi:feedback-text text="Vihjetekst" />
    </x-slot:feedback>
</tedi:form-field>
