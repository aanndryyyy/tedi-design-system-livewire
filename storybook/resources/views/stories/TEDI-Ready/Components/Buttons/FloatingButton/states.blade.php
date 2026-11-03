{{--
    Angular's pseudo map targets `[data-state="…"]`; it is copied verbatim
    (storybook/CONTRACT.md §5). Disabled is ordinary markup.
--}}
@storybook([
    'name' => 'States',
    'order' => 5,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => '[data-state="Hover"]',
        'active' => '[data-state="Active"]',
        'focusVisible' => '[data-state="Focus"]',
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $pseudoState = ['Default', 'Hover', 'Active', 'Focus', 'Disabled'];
@endphp

<tedi:row :cols="3" :gap-y="3" align-items="center">
    <tedi:col></tedi:col>
    <tedi:col><tedi:text as="p" modifiers="bold">Primary</tedi:text></tedi:col>
    <tedi:col><tedi:text as="p" modifiers="bold">Secondary</tedi:text></tedi:col>
    @foreach ($pseudoState as $state)
        <tedi:col><tedi:text as="p" modifiers="bold">{{ $state }}</tedi:text></tedi:col>
        @foreach (['primary', 'secondary'] as $variant)
            <tedi:col style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem;">
                <tedi:floating-button position="static" :variant="$variant" :data-state="$state" :disabled="$state === 'Disabled'">Jäta oma tagasiside</tedi:floating-button>
                <tedi:tooltip>
                    <tedi:tooltip-trigger>
                        <tedi:floating-button position="static" :variant="$variant" :data-state="$state" :disabled="$state === 'Disabled'" icon-only icon-start="arrow_upward" aria-label="Mine üles" />
                    </tedi:tooltip-trigger>
                    <tedi:tooltip-content>Mine üles</tedi:tooltip-content>
                </tedi:tooltip>
            </tedi:col>
        @endforeach
    @endforeach
</tedi:row>
