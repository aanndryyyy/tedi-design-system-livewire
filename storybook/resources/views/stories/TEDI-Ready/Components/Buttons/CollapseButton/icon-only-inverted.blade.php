{{--
    Angular's States / IconOnly / SecondaryButton / *Inverted stories share one
    StatesTemplate: a Default/Hover/Active/Focus matrix in a default-size and a
    small-size block, each row showing the collapsed and expanded button. The
    Hover/Active/Focus rows are forced by storybook-addon-pseudo-states — the
    `pseudoStates` arg is what .storybook/preview.js turns into the addon's
    `parameters.pseudo`, and the `id` on each button is what it targets.
    Angular's map (hover #Hover, active #Active, focusVisible #Focus) is the
    addon default, so `true` is enough (CONTRACT.md §5).
--}}
@storybook([
    'name' => 'Icon Only Inverted',
    'order' => 6,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus'];
@endphp

<div style="padding: 1.5rem; background: var(--general-icon-background-brand-primary);">
<div style="display: flex; flex-direction: column; gap: 3rem;">
    <div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
        <tedi:text as="p" modifiers="bold" color="white">Default</tedi:text>
        @foreach ($states as $state)
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: center; gap: 0.5rem;">
                <tedi:text as="p" color="white">{{ $state }}</tedi:text>
                <div style="grid-column: span 4; display: flex; gap: 1rem; align-items: center;">
                    <tedi:collapse-button id="{{ $state }}" :hide-text="true" arrow-type="default" :inverted="true" aria-label="Toggle details" />
                    <tedi:collapse-button id="{{ $state }}" :open="true" :hide-text="true" arrow-type="default" :inverted="true" aria-label="Toggle details" />
                </div>
            </div>
        @endforeach
    </div>
    <div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
        <tedi:text as="p" modifiers="bold" color="white">Small</tedi:text>
        @foreach ($states as $state)
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: center; gap: 0.5rem;">
                <tedi:text as="p" color="white">{{ $state }}</tedi:text>
                <div style="grid-column: span 4; display: flex; gap: 1rem; align-items: center;">
                    <tedi:collapse-button id="{{ $state }}" size="small" :hide-text="true" arrow-type="default" :inverted="true" aria-label="Toggle details" />
                    <tedi:collapse-button id="{{ $state }}" size="small" :open="true" :hide-text="true" arrow-type="default" :inverted="true" aria-label="Toggle details" />
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
