{{--
    The Hover, Active and Focus rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`, and the
    `id` on each link is what it targets.

    Angular sets the brand backdrop with the story's `globals.backgrounds`;
    Blast has no passthrough for those, so it is a wrapper div here. The outer
    row's `[xl]="{ cols: 2 }"` is dropped — breakpoint props are not ported
    (CONVENTIONS.md §7).
--}}
@storybook([
    'name' => 'Inverted Underlined',
    'order' => 6,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus'];
@endphp

<div style="background: var(--general-icon-background-brand-primary); border-radius: 4px; padding: 1rem;">
    <tedi:row :cols="1" :gap-y="5">
        <tedi:col class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
            <tedi:text modifiers="bold" color="white">Default size</tedi:text>
            @foreach ($states as $state)
                <tedi:row :cols="4">
                    <tedi:text color="white">{{ $state }}</tedi:text>
                    <tedi:col>
                        <tedi:link href="#" id="{{ $state }}" variant="inverted">View result</tedi:link>
                    </tedi:col>
                    <tedi:col>
                        <tedi:link href="#" id="{{ $state }}" variant="inverted">
                            Continue
                            <tedi:icon name="arrow_forward" />
                        </tedi:link>
                    </tedi:col>
                    <tedi:col>
                        <tedi:link href="#" id="{{ $state }}" variant="inverted">
                            <tedi:icon name="arrow_back" />
                            Back
                        </tedi:link>
                    </tedi:col>
                </tedi:row>
            @endforeach
        </tedi:col>
        <tedi:col class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
            <tedi:text modifiers="bold" color="white">Small size</tedi:text>
            @foreach ($states as $state)
                <tedi:row :cols="4">
                    <tedi:text color="white">{{ $state }}</tedi:text>
                    <tedi:col>
                        <tedi:link href="#" id="{{ $state }}" variant="inverted" size="small">View result</tedi:link>
                    </tedi:col>
                    <tedi:col>
                        <tedi:link href="#" id="{{ $state }}" variant="inverted" size="small">
                            Continue
                            <tedi:icon name="arrow_forward" />
                        </tedi:link>
                    </tedi:col>
                    <tedi:col>
                        <tedi:link href="#" id="{{ $state }}" variant="inverted" size="small">
                            <tedi:icon name="arrow_back" />
                            Back
                        </tedi:link>
                    </tedi:col>
                </tedi:row>
            @endforeach
        </tedi:col>
    </tedi:row>
</div>
