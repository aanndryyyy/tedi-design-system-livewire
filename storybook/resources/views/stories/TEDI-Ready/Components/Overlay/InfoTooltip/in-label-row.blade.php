@storybook([
    'name' => 'In Label Row',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:label-row>
    <tedi:form.label for="city" :required="true">Linn</tedi:form.label>
    <tedi:info-tooltip>Sisestage linn, kus te praegu elate.</tedi:info-tooltip>
</tedi:label-row>
