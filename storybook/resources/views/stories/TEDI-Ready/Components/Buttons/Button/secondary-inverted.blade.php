@storybook([
    'name' => 'Secondary Inverted',
    'order' => 5,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus', 'Disabled'];
@endphp

<div style="display: flex; flex-direction: column; gap: 3rem; background: var(--general-surface-brand-primary); padding: 1.5rem;">
    <div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em; overflow-x: auto;">
        <tedi:text as="p" modifiers="bold" color="white">Default</tedi:text>
        @foreach ($states as $state)
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: center; gap: 0.5rem;">
                <tedi:text as="p" color="white">{{ $state }}</tedi:text>
                <div style="grid-column: span 4; display: flex; gap: 1rem;">
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" :disabled="$state === 'Disabled'">Create</tedi:button>
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" :disabled="$state === 'Disabled'" icon-end="arrow_right_alt">Continue</tedi:button>
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" :disabled="$state === 'Disabled'" icon-start="edit">Edit</tedi:button>
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" :disabled="$state === 'Disabled'" icon-only icon-start="arrow_forward" aria-label="Edasi" />
                </div>
            </div>
        @endforeach
    </div>
    <div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1.4em; overflow-x: auto;">
        <tedi:text as="p" modifiers="bold" color="white">Small</tedi:text>
        @foreach ($states as $state)
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: center; gap: 0.5rem;">
                <tedi:text as="p" color="white">{{ $state }}</tedi:text>
                <div style="grid-column: span 4; display: flex; gap: 1rem;">
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" size="small" :disabled="$state === 'Disabled'">Create</tedi:button>
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" size="small" :disabled="$state === 'Disabled'" icon-end="arrow_right_alt">Continue</tedi:button>
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" size="small" :disabled="$state === 'Disabled'" icon-start="edit">Edit</tedi:button>
                    <tedi:button id="{{ $state }}" variant="secondary-inverted" size="small" :disabled="$state === 'Disabled'" icon-only icon-start="arrow_forward" aria-label="Edasi" />
                </div>
            </div>
        @endforeach
    </div>
</div>
