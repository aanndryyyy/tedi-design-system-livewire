{{--
    Angular's Primary/Secondary/.../DangerNeutral stories share one
    Default/Hover/Active/Focus/Disabled matrix (ButtonTemplate). The Hover,
    Active and Focus rows are forced by storybook-addon-pseudo-states: the
    `pseudoStates` arg is what .storybook/preview.js turns into the addon's
    `parameters.pseudo`, and the `id` on each button is what it targets.
--}}
@storybook([
    'name' => 'Primary',
    'order' => 2,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus', 'Disabled'];
@endphp

<div style="display: flex; flex-direction: column; gap: 3rem;">
    <div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em; overflow-x: auto;">
        <tedi:text as="p" modifiers="bold">Default</tedi:text>
        @foreach ($states as $state)
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: center; gap: 0.5rem;">
                <tedi:text as="p">{{ $state }}</tedi:text>
                <div style="grid-column: span 4; display: flex; gap: 1rem;">
                    <tedi:button id="{{ $state }}" variant="primary" :disabled="$state === 'Disabled'">Create</tedi:button>
                    <tedi:button id="{{ $state }}" variant="primary" :disabled="$state === 'Disabled'" icon-end="arrow_right_alt">Continue</tedi:button>
                    <tedi:button id="{{ $state }}" variant="primary" :disabled="$state === 'Disabled'" icon-start="edit">Edit</tedi:button>
                    <tedi:button id="{{ $state }}" variant="primary" :disabled="$state === 'Disabled'" icon-only icon-start="arrow_forward" aria-label="Edasi" />
                </div>
            </div>
        @endforeach
    </div>
    <div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1.4em; overflow-x: auto;">
        <tedi:text as="p" modifiers="bold">Small</tedi:text>
        @foreach ($states as $state)
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: center; gap: 0.5rem;">
                <tedi:text as="p">{{ $state }}</tedi:text>
                <div style="grid-column: span 4; display: flex; gap: 1rem;">
                    <tedi:button id="{{ $state }}" variant="primary" size="small" :disabled="$state === 'Disabled'">Create</tedi:button>
                    <tedi:button id="{{ $state }}" variant="primary" size="small" :disabled="$state === 'Disabled'" icon-end="arrow_right_alt">Continue</tedi:button>
                    <tedi:button id="{{ $state }}" variant="primary" size="small" :disabled="$state === 'Disabled'" icon-start="edit">Edit</tedi:button>
                    <tedi:button id="{{ $state }}" variant="primary" size="small" :disabled="$state === 'Disabled'" icon-only icon-start="arrow_forward" aria-label="Edasi" />
                </div>
            </div>
        @endforeach
    </div>
</div>
