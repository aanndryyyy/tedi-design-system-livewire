@storybook([
    'name' => 'Global',
    'order' => 4,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:alert title="Pealkiri" variant="global">
        Sisu kirjeldus
    </tedi:alert>
    <tedi:alert variant="global">
        Sisu kirjeldus
    </tedi:alert>
</tedi:row>
