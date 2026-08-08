{{--
    The Hover, Active and Focus rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`, and the
    `id` on each button is what it targets.
--}}
@storybook([
    'name' => 'States',
    'order' => 6,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus'];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:col style="max-width: 200px; display: grid; grid-template-columns: repeat(2, 1fr); align-items: center;">
            <b>{{ $state }}</b>
            <tedi:closing-button id="{{ $state }}" />
        </tedi:col>
    @endforeach
</tedi:row>
