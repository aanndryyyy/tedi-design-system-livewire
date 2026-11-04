@storybook([
    'name' => 'Cards Has Border',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

<tedi:radio-card-group :bordered="true">
    <tedi:radio-card variant="primary">
        <tedi:radio name="card-bordered" />
        Kartul
    </tedi:radio-card>
    <tedi:radio-card variant="primary">
        <tedi:radio name="card-bordered" />
        Kapsas
    </tedi:radio-card>
    <tedi:radio-card variant="primary">
        <tedi:radio name="card-bordered" :checked="true" />
        Peet
    </tedi:radio-card>
</tedi:radio-card-group>
