{{--
    The Hover, Active and Focus rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`, and the
    `id` on each trigger is what it targets. Selected is not a pseudo-class —
    it is driven by `default-value`, as in Angular.

    Angular also turns off the `aria-valid-attr-value` a11y rule here (the
    triggers deliberately have no panels). Blast's @storybook directive has no
    `parameters` passthrough for that, so the a11y panel will flag the dangling
    `aria-controls`; it is a demo artifact, not a defect.

    The inner row's `[sm]="{ cols: 6 }"` is dropped — breakpoint props are not
    ported (CONVENTIONS.md §7).
--}}
@storybook([
    'name' => 'States',
    'order' => 4,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus', 'Selected'];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:row cols="1" align-items="center">
            <tedi:col :width="1">
                <tedi:text modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:tabs default-value="{{ $state === 'Selected' ? $state : '' }}">
                    <tedi:tabs.list aria-label="Oleku näide">
                        <tedi:tabs.trigger id="{{ $state }}">Terviseteekond</tedi:tabs.trigger>
                    </tedi:tabs.list>
                </tedi:tabs>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:row>
