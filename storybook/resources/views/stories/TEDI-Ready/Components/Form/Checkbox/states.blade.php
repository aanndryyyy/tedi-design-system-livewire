@storybook([
    'name' => 'States',
    'order' => 8,
    'status' => 'subset',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    All visual states of the checkbox component. The Hover, Active and Focus
    rows are forced by storybook-addon-pseudo-states: the `pseudoStates` arg is
    what .storybook/preview.js turns into the addon's `parameters.pseudo`, and
    the `id` on each input is what it targets.

    Indeterminate stays dropped — it has no static markup (see
    default.blade.php), which is what keeps this story a subset.
--}}
<tedi:row :cols="2" :gap-y="3">
    <strong>Default</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox />
        Text
    </tedi:form.label>

    <strong>Hover</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox id="Hover" />
        Text
    </tedi:form.label>

    <strong>Selected</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" />
        Text
    </tedi:form.label>

    <strong>Active</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" id="Active" />
        Text
    </tedi:form.label>

    <strong>Focus</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox id="Focus" />
        Text
    </tedi:form.label>

    <strong>Error</strong>
    <div>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox :invalid="true" />
            Text
        </tedi:form.label>
        <tedi:feedback-text text="Feedback text" type="error" />
    </div>

    <strong>Disabled</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :disabled="true" />
        Text
    </tedi:form.label>

    <strong>Disabled selected</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" :disabled="true" />
        Text
    </tedi:form.label>
</tedi:row>
