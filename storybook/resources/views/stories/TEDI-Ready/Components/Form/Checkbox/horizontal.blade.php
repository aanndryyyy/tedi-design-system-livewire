@storybook([
    'name' => 'Horizontal',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
])

<tedi:checkbox-group label="Label">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" />
        Text
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" />
        Text
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox />
        Text
    </tedi:form.label>
</tedi:checkbox-group>
