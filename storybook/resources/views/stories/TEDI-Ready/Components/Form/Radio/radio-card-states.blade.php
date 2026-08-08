@storybook([
    'name' => 'Radio Card States',
    'order' => 14,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['#PrimaryHover', '#SecondaryHover'],
        'active' => ['#PrimaryActive', '#SecondaryActive'],
        'focusVisible' => ['#PrimaryFocus', '#SecondaryFocus'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    All visual states of the radio card component for both variants. The Hover,
    Active and Focus rows are forced by storybook-addon-pseudo-states: the
    `pseudoStates` arg is what .storybook/preview.js turns into the addon's
    `parameters.pseudo`. Each row needs two ids because the two variants sit
    side by side, so this story passes the selector map rather than the default
    `#Hover`/`#Active`/`#Focus` one.
--}}
<tedi:row :cols="3" :gap-y="3">
    <tedi:col></tedi:col>
    <tedi:col><strong>Primary</strong></tedi:col>
    <tedi:col><strong>Secondary</strong></tedi:col>

    <tedi:col><strong>Default</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-default" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-default" />
            Text
        </tedi:radio-card>
    </tedi:col>

    <tedi:col><strong>Hover</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-hover" id="PrimaryHover" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-hover" id="SecondaryHover" />
            Text
        </tedi:radio-card>
    </tedi:col>

    <tedi:col><strong>Selected</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-selected" :checked="true" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-selected" :checked="true" />
            Text
        </tedi:radio-card>
    </tedi:col>

    <tedi:col><strong>Active</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-active" :checked="true" id="PrimaryActive" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-active" :checked="true" id="SecondaryActive" />
            Text
        </tedi:radio-card>
    </tedi:col>

    <tedi:col><strong>Focus</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-focus" id="PrimaryFocus" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-focus" id="SecondaryFocus" />
            Text
        </tedi:radio-card>
    </tedi:col>

    <tedi:col><strong>Disabled</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-disabled" :disabled="true" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-disabled" :disabled="true" />
            Text
        </tedi:radio-card>
    </tedi:col>

    <tedi:col><strong>Disabled selected</strong></tedi:col>
    <tedi:col>
        <tedi:radio-card variant="primary">
            <tedi:radio name="card-state-p-disabled-selected" :checked="true" :disabled="true" />
            Text
        </tedi:radio-card>
    </tedi:col>
    <tedi:col>
        <tedi:radio-card variant="secondary">
            <tedi:radio name="card-state-s-disabled-selected" :checked="true" :disabled="true" />
            Text
        </tedi:radio-card>
    </tedi:col>
</tedi:row>
