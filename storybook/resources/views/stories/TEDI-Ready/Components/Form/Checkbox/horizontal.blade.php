@storybook([
    'name' => 'Horizontal',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
])

<tedi:checkbox-group label="Vali tooraine">
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
