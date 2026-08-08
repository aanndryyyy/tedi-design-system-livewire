@storybook([
    'name' => 'Card Icon',
    'order' => 13,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:row cols="1" gap="4">
    <tedi:card>
        <tedi:card-row>
            <tedi:card-icon>
                <tedi:icon name="monitor_heart" />
            </tedi:card-icon>
            <tedi:card-content>
                <tedi:text as="p" modifiers="bold">Vaikimisi</tedi:text>
                <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
            </tedi:card-content>
        </tedi:card-row>
    </tedi:card>
    <tedi:card>
        <tedi:card-row>
            <tedi:card-icon type="brand">
                <tedi:icon name="monitor_heart" />
            </tedi:card-icon>
            <tedi:card-content>
                <tedi:text as="p" modifiers="bold">Bränd</tedi:text>
                <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
            </tedi:card-content>
        </tedi:card-row>
    </tedi:card>
    <tedi:card>
        <tedi:card-row>
            <tedi:card-icon size="small">
                <tedi:icon name="monitor_heart" :size="16" />
            </tedi:card-icon>
            <tedi:card-content>
                <tedi:text as="p" modifiers="bold">Väike</tedi:text>
                <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
            </tedi:card-content>
        </tedi:card-row>
    </tedi:card>
    <tedi:card>
        <tedi:card-row>
            <tedi:card-icon type="brand" size="small">
                <tedi:icon name="monitor_heart" :size="16" />
            </tedi:card-icon>
            <tedi:card-content>
                <tedi:text as="p" modifiers="bold">Väike bränd</tedi:text>
                <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
            </tedi:card-content>
        </tedi:card-row>
    </tedi:card>
</tedi:row>
