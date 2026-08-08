@storybook([
    'name' => 'States',
    'order' => 3,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    The Hover, Active and Focus rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`. Angular's
    map is the default `#Hover` / `#Active` / `#Focus`, so `true` is enough —
    the ids land on the <input> via each row's `id`.

    Disabled is ordinary markup, not a pseudo-class (CONTRACT.md §5).
--}}
@php $states = ['Default', 'Hover', 'Active', 'Disabled', 'Focus']; @endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:row :cols="6" align-items="center">
            <tedi:col :width="1">
                <tedi:text modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:form-field :disabled="$state === 'Disabled'">
                    <x-slot:label>
                        <tedi:form.label :for="$state">Label</tedi:form.label>
                    </x-slot:label>
                    <tedi:text-field
                        :id="$state"
                        :value="$state === 'Disabled' ? 'Text value' : ''"
                        :disabled="$state === 'Disabled'"
                    />
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    @endforeach

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text modifiers="bold">Error</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:form-field :invalid="true">
                <x-slot:label>
                    <tedi:form.label for="error">Label</tedi:form.label>
                </x-slot:label>
                <tedi:text-field id="error" :invalid="true" />
                <x-slot:feedback>
                    <tedi:feedback-text text="Feedback text" type="error" />
                </x-slot:feedback>
            </tedi:form-field>
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text modifiers="bold">Success</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:form-field :valid="true">
                <x-slot:label>
                    <tedi:form.label for="success">Label</tedi:form.label>
                </x-slot:label>
                <tedi:text-field id="success" />
                <x-slot:feedback>
                    <tedi:feedback-text text="Feedback text" type="valid" />
                </x-slot:feedback>
            </tedi:form-field>
        </tedi:col>
    </tedi:row>
</tedi:row>
