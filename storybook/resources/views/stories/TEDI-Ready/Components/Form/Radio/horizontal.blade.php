@storybook([
    'name' => 'Horizontal',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
])

<tedi:radio-group label="Tooraine">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="horizontal-demo" />
        Kartul
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="horizontal-demo" />
        Kapsas
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="horizontal-demo" :checked="true" />
        Peet
    </tedi:form.label>
</tedi:radio-group>
