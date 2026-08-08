{{--
    The Hover, Active and Focus rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`, and the
    `id` on each link is what it targets.

    Angular's outer row also carries `[xl]="{ cols: 2 }"`; breakpoint props are
    not ported (CONVENTIONS.md §7).
--}}
@storybook([
    'name' => 'Default No Underline',
    'order' => 5,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus'];
@endphp

<tedi:row :cols="1" :gap-y="5">
    <tedi:col class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
        <tedi:text modifiers="bold">Default size</tedi:text>
        @foreach ($states as $state)
            <tedi:row :cols="4">
                <tedi:text color="primary">{{ $state }}</tedi:text>
                <tedi:col>
                    <tedi:link href="#" id="{{ $state }}" :underline="false">View result</tedi:link>
                </tedi:col>
                <tedi:col>
                    <tedi:link href="#" id="{{ $state }}" :underline="false">
                        Continue
                        <tedi:icon name="arrow_forward" />
                    </tedi:link>
                </tedi:col>
                <tedi:col>
                    <tedi:link href="#" id="{{ $state }}" :underline="false">
                        <tedi:icon name="arrow_back" />
                        Back
                    </tedi:link>
                </tedi:col>
            </tedi:row>
        @endforeach
    </tedi:col>
    <tedi:col class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
        <tedi:text modifiers="bold">Small size</tedi:text>
        @foreach ($states as $state)
            <tedi:row :cols="4">
                <tedi:text color="primary">{{ $state }}</tedi:text>
                <tedi:col>
                    <tedi:link href="#" id="{{ $state }}" size="small" :underline="false">View result</tedi:link>
                </tedi:col>
                <tedi:col>
                    <tedi:link href="#" id="{{ $state }}" size="small" :underline="false">
                        Continue
                        <tedi:icon name="arrow_forward" />
                    </tedi:link>
                </tedi:col>
                <tedi:col>
                    <tedi:link href="#" id="{{ $state }}" size="small" :underline="false">
                        <tedi:icon name="arrow_back" />
                        Back
                    </tedi:link>
                </tedi:col>
            </tedi:row>
        @endforeach
    </tedi:col>
</tedi:row>
