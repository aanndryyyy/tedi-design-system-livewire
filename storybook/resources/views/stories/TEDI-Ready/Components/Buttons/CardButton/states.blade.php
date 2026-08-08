{{--
    Angular spells the five rows out one by one; the card inside each is
    identical, so they are looped here. The Hover, Active and Focus rows are
    forced by storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`, and the
    `id` on each button is what it targets.
--}}
@storybook([
    'name' => 'States',
    'order' => 5,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus', 'Disabled'];
@endphp

<tedi:row :cols="1" :gap-y="3" align-items="center">
    @foreach ($states as $state)
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">{{ $state }}</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:card-button id="{{ $state }}" type="button" :disabled="$state === 'Disabled'">
                <tedi:card>
                    <tedi:card-row>
                        <tedi:card-icon><tedi:icon name="euro_symbol" /></tedi:card-icon>
                        <tedi:separator axis="vertical" size="auto" />
                        <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                            <div>
                                <tedi:text as="p" modifiers="bold">Isiku toetused</tedi:text>
                                <tedi:text as="p" modifiers="small" color="secondary">Toetused mis on isikule ette nähtud</tedi:text>
                            </div>
                            <tedi:icon name="arrow_right_alt" color="secondary" />
                        </tedi:card-content>
                    </tedi:card-row>
                </tedi:card>
            </tedi:card-button>
        </tedi:col>
    @endforeach
</tedi:row>
