@storybook([
    'name' => 'Vertical',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular repeats name="vertical-demo" on every input; the Blade port hoists
    it to the group, which propagates it to each <tedi:radio> via @aware for
    the same rendered DOM.
--}}
<tedi:radio-group label="Label" direction="vertical" name="vertical-demo">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio />
        Text
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio />
        Text
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio :checked="true" />
        Text
    </tedi:form.label>
</tedi:radio-group>
