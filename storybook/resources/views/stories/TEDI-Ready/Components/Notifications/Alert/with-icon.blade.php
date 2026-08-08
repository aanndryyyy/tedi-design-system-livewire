@storybook([
    'name' => 'With Icon',
    'order' => 6,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:alert title="Pealkiri" icon="check_circle">
        Sisu kirjeldus
    </tedi:alert>
    <tedi:alert icon="check_circle">
        Sisu kirjeldus
    </tedi:alert>
</tedi:row>
