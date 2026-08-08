@storybook([
    'name' => 'Checkbox Card States',
    'order' => 12,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['#PrimaryHover', '#SecondaryHover'],
        'active' => ['#PrimaryActive', '#SecondaryActive'],
        'focusVisible' => ['#PrimaryFocus', '#SecondaryFocus'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    All visual states of the checkbox card component for both variants. The
    Hover, Active and Focus rows are forced by storybook-addon-pseudo-states:
    the `pseudoStates` arg is what .storybook/preview.js turns into the addon's
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
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox />
            Text
        </tedi:checkbox-card>
    </tedi:col>

    <tedi:col><strong>Hover</strong></tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox id="PrimaryHover" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox id="SecondaryHover" />
            Text
        </tedi:checkbox-card>
    </tedi:col>

    <tedi:col><strong>Selected</strong></tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox :checked="true" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox :checked="true" />
            Text
        </tedi:checkbox-card>
    </tedi:col>

    <tedi:col><strong>Active</strong></tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox :checked="true" id="PrimaryActive" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox :checked="true" id="SecondaryActive" />
            Text
        </tedi:checkbox-card>
    </tedi:col>

    <tedi:col><strong>Focus</strong></tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox id="PrimaryFocus" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox id="SecondaryFocus" />
            Text
        </tedi:checkbox-card>
    </tedi:col>

    <tedi:col><strong>Disabled</strong></tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox :disabled="true" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox :disabled="true" />
            Text
        </tedi:checkbox-card>
    </tedi:col>

    <tedi:col><strong>Disabled selected</strong></tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="primary">
            <tedi:checkbox :checked="true" :disabled="true" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
    <tedi:col>
        <tedi:checkbox-card variant="secondary">
            <tedi:checkbox :checked="true" :disabled="true" />
            Text
        </tedi:checkbox-card>
    </tedi:col>
</tedi:row>
