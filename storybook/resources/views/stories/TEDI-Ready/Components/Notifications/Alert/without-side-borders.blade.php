@storybook([
    'name' => 'Without Side Borders',
    'order' => 5,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:alert variant="noSideBorders" title="Pealkiri">
        Sisu kirjeldus
    </tedi:alert>
    <tedi:alert variant="noSideBorders">
        Sisu kirjeldus
    </tedi:alert>
</tedi:row>
