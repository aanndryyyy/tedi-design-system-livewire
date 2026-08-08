@storybook([
    'name' => 'With Notification',
    'order' => 18,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:card :padding="0.75">
    <tedi:card-header background="primary">
        <tedi:text as="h3">Kaardi pealkiri</tedi:text>
    </tedi:card-header>
    <tedi:alert variant="noSideBorders">
        <tedi:text as="p">Kaardi teavitus</tedi:text>
    </tedi:alert>
    <tedi:card-content>
        <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
    </tedi:card-content>
</tedi:card>
