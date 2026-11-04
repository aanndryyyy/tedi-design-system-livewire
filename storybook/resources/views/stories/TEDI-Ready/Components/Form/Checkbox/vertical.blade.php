@storybook([
    'name' => 'Vertical',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

<tedi:checkbox-group label="Vali tooraine" direction="vertical">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" />
        Kartul
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox :checked="true" />
        Porgand
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox />
        Kapsas
    </tedi:form.label>
</tedi:checkbox-group>
