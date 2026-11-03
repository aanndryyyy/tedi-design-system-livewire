@storybook([
    'name' => 'States',
    'order' => 3,
    'status' => 'subset',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    The Hover, Active and Focus rows are forced by storybook-addon-pseudo-states
    (CONTRACT.md §5): the `pseudoStates` arg is what .storybook/preview.js turns
    into the addon's `parameters.pseudo`, and Angular's map is the default
    `#Hover` / `#Active` / `#Focus` one. `inputId` is what puts those ids on the
    <input>, exactly as in the Angular story. Disabled, Success and Error are
    ordinary markup: Angular disables a form control holding 18.06.2026, and
    derives Success / Error from the feedback text, which in Blade is the
    form-field's explicit `valid` / `invalid`.
--}}
@php
    $states = ['Default', 'Hover', 'Focus', 'Active'];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:row :cols="6" align-items="center">
            <tedi:col :width="1">
                <tedi:text as="p" modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label :for="$state">Kuupäev</tedi:form.label>
                    </x-slot:label>
                    <tedi:date-field :input-id="$state" />
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    @endforeach

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Disabled</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:form-field>
                <x-slot:label>
                    <tedi:form.label for="date-state-disabled">Kuupäev</tedi:form.label>
                </x-slot:label>
                <tedi:date-field input-id="date-state-disabled" value="2026-06-18" display="18.06.2026" :input-disabled="true" />
            </tedi:form-field>
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Success</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:form-field :valid="true">
                <x-slot:label>
                    <tedi:form.label for="date-state-valid">Kuupäev</tedi:form.label>
                </x-slot:label>
                <tedi:date-field input-id="date-state-valid" />
                <x-slot:feedback>
                    <tedi:feedback-text text="Tagasiside tekst" type="valid" />
                </x-slot:feedback>
            </tedi:form-field>
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Error</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:form-field :invalid="true">
                <x-slot:label>
                    <tedi:form.label for="date-state-error">Kuupäev</tedi:form.label>
                </x-slot:label>
                <tedi:date-field input-id="date-state-error" />
                <x-slot:feedback>
                    <tedi:feedback-text text="Tagasiside tekst" type="error" />
                </x-slot:feedback>
            </tedi:form-field>
        </tedi:col>
    </tedi:row>
</tedi:row>
