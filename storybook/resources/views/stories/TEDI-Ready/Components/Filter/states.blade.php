@storybook([
    'name' => 'States',
    'order' => 6,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['.pseudo-hover .tedi-filter__button'],
        'active' => ['.pseudo-active .tedi-filter__button'],
        'focusVisible' => ['.pseudo-focus .tedi-filter__button'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    Every visual state of the filter, for both variants and for the multiselect
    dropdown. Hover, Active and Focus are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`. The
    selector map is copied from the Angular story, which targets the button
    inside a `.pseudo-*` wrapper rather than an id per row. Selected and
    Disabled are ordinary markup, not pseudo-classes.
--}}
@php
    $states = ['Default', 'Hover', 'Active', 'Focus', 'Selected', 'Disabled'];

    $options = [
        ['label' => 'Optometristi vastuvõtt', 'value' => '1'],
        ['label' => 'Silmaarsti vastuvõtt', 'value' => '2'],
        ['label' => 'Hambaarsti vastuvõtt', 'value' => '3'],
    ];
@endphp

<div style="overflow-x: auto; background: var(--general-surface-primary); padding: 24px;">
    <tedi:row :cols="6" :gap-y="3" align-items="center" style="min-width: 1200px;">
        <tedi:col><tedi:text as="p" modifiers="bold">State</tedi:text></tedi:col>
        <tedi:col><tedi:text as="p" modifiers="bold">Primary</tedi:text></tedi:col>
        <tedi:col><tedi:text as="p" modifiers="bold">Primary multiselect</tedi:text></tedi:col>
        <tedi:col><tedi:text as="p" modifiers="bold">Secondary</tedi:text></tedi:col>
        <tedi:col><tedi:text as="p" modifiers="bold">Secondary multiselect</tedi:text></tedi:col>
        <tedi:col><tedi:text as="p" modifiers="bold">Large</tedi:text></tedi:col>

        @foreach ($states as $state)
            @php
                $selected = $state === 'Selected';
                $disabled = $state === 'Disabled';
                $values = $selected ? ['1', '2'] : [];
                $pseudo = 'pseudo-'.strtolower($state);
            @endphp

            <tedi:col><tedi:text as="p">{{ $state }}</tedi:text></tedi:col>

            <tedi:col :class="$pseudo">
                <tedi:filter text="Filter" :selected="$selected" :disabled="$disabled" />
            </tedi:col>
            <tedi:col :class="$pseudo">
                <tedi:filter text="Filter" :allow-multiple="true" :options="$options" :value="$values" :disabled="$disabled" />
            </tedi:col>
            <tedi:col :class="$pseudo">
                <tedi:filter text="Filter" variant="secondary" :selected="$selected" :disabled="$disabled" />
            </tedi:col>
            <tedi:col :class="$pseudo">
                <tedi:filter text="Filter" variant="secondary" :allow-multiple="true" :options="$options" :value="$values" :disabled="$disabled" />
            </tedi:col>
            <tedi:col :class="$pseudo">
                <tedi:filter text="Filter" size="large" :selected="$selected" :disabled="$disabled" />
            </tedi:col>
        @endforeach
    </tedi:row>
</div>
