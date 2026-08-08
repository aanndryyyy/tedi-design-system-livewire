@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:text modifiers="bold">Default</tedi:text>
    <tedi:alert type="info" size="default">
        Sisu kirjeldus
    </tedi:alert>
    <tedi:text modifiers="bold">Small</tedi:text>
    <tedi:alert type="info" size="small">
        Sisu kirjeldus
    </tedi:alert>
</tedi:row>
