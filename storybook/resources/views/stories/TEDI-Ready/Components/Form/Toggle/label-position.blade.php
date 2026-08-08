@storybook([
    'name' => 'Label Position',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :cols="1" :gap-y="3">
    <div style="display: flex; align-items: center; gap: 8px;">
        <tedi:form.label for="example-toggle-3.1">Toggle button</tedi:form.label>
        <tedi:toggle input-id="example-toggle-3.1" />
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <tedi:toggle input-id="example-toggle-3.2" />
        <tedi:form.label for="example-toggle-3.2">Toggle button</tedi:form.label>
    </div>
</tedi:row>
