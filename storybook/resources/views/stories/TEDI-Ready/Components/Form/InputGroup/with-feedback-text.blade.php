@storybook([
    'name' => 'With Feedback Text',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
])

{{-- Feedback text is projected below the group. Pair an error message with `invalid` so the whole group — addon borders and the inner control — reflects the error state. --}}
<div class="flex flex-column gap-4">
    <tedi:input-group>
        <tedi:form.label for="feedback-hint">Summa</tedi:form.label>
        <tedi:form-field>
            <input type="text" id="feedback-hint" placeholder="0.00" />
        </tedi:form-field>
        <x-slot:suffix>EUR</x-slot:suffix>
        <x-slot:feedback>
            <tedi:feedback-text text="Sisesta summa eurodes" type="hint" />
        </x-slot:feedback>
    </tedi:input-group>

    <tedi:input-group :invalid="true">
        <tedi:form.label for="feedback-error">Summa</tedi:form.label>
        <tedi:form-field>
            <input type="text" id="feedback-error" placeholder="0.00" />
        </tedi:form-field>
        <x-slot:suffix>EUR</x-slot:suffix>
        <x-slot:feedback>
            <tedi:feedback-text text="See väli on kohustuslik" type="error" />
        </x-slot:feedback>
    </tedi:input-group>
</div>
