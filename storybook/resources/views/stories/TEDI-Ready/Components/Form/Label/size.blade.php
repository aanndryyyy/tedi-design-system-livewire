@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :cols="2" :gap-y="3">
    <b>Default</b>
    <div style="display: flex; gap: 1rem;">
        <tedi:form.label>Label</tedi:form.label>
        <tedi:form.label><b>Label</b></tedi:form.label>
    </div>
    <b>Small</b>
    <div style="display: flex; gap: 1rem;">
        <tedi:form.label size="small">Label</tedi:form.label>
        <tedi:form.label size="small"><b>Label</b></tedi:form.label>
    </div>
</tedi:row>
