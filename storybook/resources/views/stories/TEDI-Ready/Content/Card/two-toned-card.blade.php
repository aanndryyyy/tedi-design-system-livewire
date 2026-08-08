@storybook([
    'name' => 'Two Toned Card',
    'order' => 16,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:card>
    <tedi:card-row>
        <tedi:card-icon>
            <tedi:icon name="straighten" />
        </tedi:card-icon>
        <tedi:separator axis="vertical" size="auto" />
        <tedi:card-content>
            <tedi:text as="p" modifiers="bold">Statistika: x kg</tedi:text>
            <tedi:text as="p">Kirjeldus</tedi:text>
        </tedi:card-content>
    </tedi:card-row>
</tedi:card>
