@storybook([
    'name' => 'Multiple Content',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:card>
    <tedi:card-header background="brand-primary">
        <tedi:text as="h3" color="white">Pealkiri</tedi:text>
    </tedi:card-header>
    <tedi:card-content>
        <tedi:text as="p">Esimene sisuplokk</tedi:text>
    </tedi:card-content>
    <tedi:separator />
    <tedi:card-content>
        <tedi:text as="p">Teine sisuplokk</tedi:text>
    </tedi:card-content>
    <tedi:separator />
    <tedi:card-content>
        <tedi:text as="p">Kolmas sisuplokk</tedi:text>
    </tedi:card-content>
</tedi:card>
