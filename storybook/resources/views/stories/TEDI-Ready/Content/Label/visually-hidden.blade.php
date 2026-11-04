@storybook([
    'name' => 'Visually Hidden',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:row :cols="1" :gap-y="3">
    <tedi:col>
        <tedi:form.label for="vh-visible">Nähtav silt</tedi:form.label>
        <tedi:text-field id="vh-visible" placeholder="Nähtav silt" />
    </tedi:col>
    <tedi:col>
        <tedi:form.label for="vh-hidden" :visually-hidden="true">Peidetud silt</tedi:form.label>
        <tedi:text-field id="vh-hidden" placeholder="Silt on ainult ekraanilugejale" />
    </tedi:col>
    <tedi:col>
        <tedi:form.label for="vh-reserved" visually-hidden="reserve-space">Peidetud silt</tedi:form.label>
        <tedi:text-field id="vh-reserved" placeholder="Silt peidetud, rida alles" />
    </tedi:col>
</tedi:row>
