{{--
    Angular's `pseudo` map targets one id per row, so the custom map form is
    used (CONTRACT.md §5). The Selected and Disabled rows are ordinary markup —
    `:selected` / `disabled` — not pseudo-classes.
--}}
@storybook([
    'name' => 'Secondary',
    'order' => 7,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['#hover-secondary'],
        'focusVisible' => ['#focus-secondary'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php $states = ['Default', 'Hover', 'Selected', 'Focus', 'Disabled']; @endphp

<tedi:row :cols="1" :gap-y="2">
    @foreach ($states as $state)
        <tedi:col>
            <tedi:row :cols="12" :gap-y="1" align-items="center">
                <tedi:col :width="2">
                    <tedi:text as="p" modifiers="bold">{{ $state }}</tedi:text>
                </tedi:col>
                <tedi:col :width="10">
                    <tedi:button-group variant="secondary-button-group" :aria-label="'Teisene '.$state">
                        <tedi:button-group-button
                            :id="strtolower($state).'-secondary'"
                            value="1"
                            label="Tabel"
                            :selected="$state === 'Selected'"
                            :disabled="$state === 'Disabled'"
                        />
                        <tedi:button-group-button value="2" label="Loend" />
                        <tedi:button-group-button value="3" label="Kalender" />
                    </tedi:button-group>
                </tedi:col>
            </tedi:row>
        </tedi:col>
    @endforeach
</tedi:row>
