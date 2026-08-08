@storybook([
    'name' => 'States',
    'order' => 8,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    All visual states of the radio component. The Hover, Active and Focus rows
    are forced by storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`, and the
    `id` on each input is what it targets.
--}}
<tedi:row :cols="2" :gap-y="3">
    <strong>Default</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-default" />
        Text
    </tedi:form.label>

    <strong>Hover</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-hover" id="Hover" />
        Text
    </tedi:form.label>

    <strong>Selected</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-selected" :checked="true" />
        Text
    </tedi:form.label>

    <strong>Active</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-active" id="Active" />
        Text
    </tedi:form.label>

    <strong>Focus</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-focus" id="Focus" />
        Text
    </tedi:form.label>

    <strong>Error</strong>
    <div>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="state-error" :invalid="true" />
            Text
        </tedi:form.label>
        <tedi:feedback-text text="Feedback text" type="error" />
    </div>

    <strong>Disabled</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-disabled" :disabled="true" />
        Text
    </tedi:form.label>

    <strong>Disabled selected</strong>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="state-disabled-selected" :checked="true" :disabled="true" />
        Text
    </tedi:form.label>
</tedi:row>
