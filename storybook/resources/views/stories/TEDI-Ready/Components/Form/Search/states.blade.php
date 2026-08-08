@storybook([
    'name' => 'States',
    'order' => 3,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => '#search-states-Hover',
        'active' => '#search-states-Active',
        'focusVisible' => '#search-states-Focus',
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    The Hover, Active and Focus rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`. The
    selectors are Angular's own map, copied verbatim — they target the <input>
    through the id that `inputId` puts on it, not the default
    `#Hover`/`#Active`/`#Focus` map.

    Angular lays the rows out with <tedi-row [sm]="{ cols: 6 }">. Breakpoint
    props are not ported (CONVENTIONS.md §7 item 1), so the base `cols` is used.
--}}
@php $states = ['Default', 'Hover', 'Active', 'Focus']; @endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:row :cols="6" align-items="center">
            <tedi:col :width="1">
                <tedi:text modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:search
                    :input-id="'search-states-'.$state"
                    label="Otsing"
                    :aria-label="'Otsing – '.$state"
                />
            </tedi:col>
        </tedi:row>
    @endforeach

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1"><tedi:text modifiers="bold">Disabled</tedi:text></tedi:col>
        <tedi:col :width="5">
            <tedi:search
                input-id="search-states-disabled"
                label="Otsing"
                aria-label="Otsing – Disabled"
                :disabled="true"
            />
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1"><tedi:text modifiers="bold">Success</tedi:text></tedi:col>
        <tedi:col :width="5">
            <tedi:search
                input-id="search-states-success"
                label="Otsing"
                aria-label="Otsing – Success"
                :feedback-text="['text' => 'Tagasiside tekst', 'type' => 'valid']"
            />
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1"><tedi:text modifiers="bold">Error</tedi:text></tedi:col>
        <tedi:col :width="5">
            <tedi:search
                input-id="search-states-error"
                label="Otsing"
                aria-label="Otsing – Error"
                :feedback-text="['text' => 'Tagasiside tekst', 'type' => 'error']"
            />
        </tedi:col>
    </tedi:row>
</tedi:row>
