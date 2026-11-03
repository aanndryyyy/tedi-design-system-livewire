@storybook([
    'name' => 'Vertical',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

<tedi:radio-group label="Tooraine" direction="vertical">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="vertical-demo" />
        Kartul
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="vertical-demo" />
        Kapsas
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="vertical-demo" :checked="true" />
        Peet
    </tedi:form.label>
</tedi:radio-group>
