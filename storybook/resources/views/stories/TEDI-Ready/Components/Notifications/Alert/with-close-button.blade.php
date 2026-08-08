@storybook([
    'name' => 'With Close Button',
    'order' => 7,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:alert title="Pealkiri" :show-close="true">
        Sisu kirjeldus
    </tedi:alert>
    <tedi:alert :show-close="true">
        Sisu kirjeldus
    </tedi:alert>
</tedi:row>
