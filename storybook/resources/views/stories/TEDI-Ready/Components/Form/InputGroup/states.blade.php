@storybook([
    'name' => 'States',
    'order' => 5,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['#Hover-start', '#Hover-end'],
        'focusVisible' => ['#Focus-start', '#Focus-end'],
        'active' => ['#Active-start', '#Active-end'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    The Hover, Focus and Active rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`. Each row
    holds two inputs — a prefixed and a suffixed group — so this story passes
    the selector map rather than the default `#Hover`/`#Active`/`#Focus` one.

    Angular's breakpoint-responsive [xs]/[md] row/col widths are dropped per
    CONVENTIONS.md §7 item 1 — <tedi:row>/<tedi:col> only accept the base
    `cols`/`width`, rendered here at their widest (desktop) layout
    unconditionally.
--}}
@php
    $states = ['Default', 'Hover', 'Focus', 'Active'];
@endphp

<tedi:row :cols="1" :gap-y="2">
    @foreach ($states as $state)
        <tedi:row :cols="12" :gap-x="2" :gap-y="1" align-items="center">
            <tedi:col :width="2">
                <tedi:text as="p" modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:input-group>
                    <tedi:form.label for="{{ $state }}-start">Silt</tedi:form.label>
                    <x-slot:prefix>Tänav</x-slot:prefix>
                    <tedi:form-field>
                        <input type="text" id="{{ $state }}-start" />
                    </tedi:form-field>
                </tedi:input-group>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:input-group>
                    <tedi:form.label for="{{ $state }}-end">Silt</tedi:form.label>
                    <tedi:form-field>
                        <input type="text" id="{{ $state }}-end" />
                    </tedi:form-field>
                    <x-slot:suffix>EUR</x-slot:suffix>
                </tedi:input-group>
            </tedi:col>
        </tedi:row>
    @endforeach
    <tedi:row :cols="12" :gap-x="2" :gap-y="1" align-items="center">
        <tedi:col :width="2">
            <tedi:text as="p" modifiers="bold">Disabled</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:input-group :disabled="true">
                <tedi:form.label for="disabled-start">Silt</tedi:form.label>
                <x-slot:prefix>Tänav</x-slot:prefix>
                <tedi:form-field>
                    <input type="text" id="disabled-start" />
                </tedi:form-field>
            </tedi:input-group>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:input-group :disabled="true">
                <tedi:form.label for="disabled-end">Silt</tedi:form.label>
                <tedi:form-field>
                    <input type="text" id="disabled-end" />
                </tedi:form-field>
                <x-slot:suffix>EUR</x-slot:suffix>
            </tedi:input-group>
        </tedi:col>
    </tedi:row>
    <tedi:row :cols="12" :gap-x="2" :gap-y="1" align-items="start">
        <tedi:col :width="2">
            <tedi:text as="p" modifiers="bold">Error</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:input-group :invalid="true">
                <tedi:form.label for="error-start">Silt</tedi:form.label>
                <x-slot:prefix>Tänav</x-slot:prefix>
                <tedi:form-field>
                    <input type="text" id="error-start" />
                </tedi:form-field>
                <x-slot:feedback>
                    <tedi:feedback-text text="Tagasiside tekst" type="error" />
                </x-slot:feedback>
            </tedi:input-group>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:input-group :invalid="true">
                <tedi:form.label for="error-end">Silt</tedi:form.label>
                <tedi:form-field>
                    <input type="text" id="error-end" />
                </tedi:form-field>
                <x-slot:suffix>EUR</x-slot:suffix>
                <x-slot:feedback>
                    <tedi:feedback-text text="Tagasiside tekst" type="error" />
                </x-slot:feedback>
            </tedi:input-group>
        </tedi:col>
    </tedi:row>
</tedi:row>
