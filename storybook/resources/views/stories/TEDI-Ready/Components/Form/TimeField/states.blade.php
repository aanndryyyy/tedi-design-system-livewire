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
    <input>, exactly as in the Angular story. Disabled is ordinary markup, not a
    pseudo-class.
--}}
@php
    $states = ['Default', 'Hover', 'Active', 'Disabled', 'Focus'];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:row :cols="6" align-items="center">
            <tedi:col :width="1">
                <tedi:text as="p" modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:form-field :disabled="$state === 'Disabled'">
                    <x-slot:label>
                        <tedi:form.label :for="$state">Aeg</tedi:form.label>
                    </x-slot:label>
                    <tedi:time-field
                        :input-id="$state"
                        :value="$state === 'Disabled' ? '12:00' : null"
                        :disabled="$state === 'Disabled'"
                    />
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    @endforeach

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Error</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:form-field :invalid="true">
                <x-slot:label>
                    <tedi:form.label for="state-error">Aeg</tedi:form.label>
                </x-slot:label>
                <tedi:time-field input-id="state-error" :invalid="true" value="12:00" />
                <x-slot:feedback>
                    <tedi:feedback-text text="Tagasiside tekst" type="error" />
                </x-slot:feedback>
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
                    <tedi:form.label for="state-success">Aeg</tedi:form.label>
                </x-slot:label>
                <tedi:time-field input-id="state-success" value="12:00" />
                <x-slot:feedback>
                    <tedi:feedback-text text="Tagasiside tekst" type="valid" />
                </x-slot:feedback>
            </tedi:form-field>
        </tedi:col>
    </tedi:row>
</tedi:row>
