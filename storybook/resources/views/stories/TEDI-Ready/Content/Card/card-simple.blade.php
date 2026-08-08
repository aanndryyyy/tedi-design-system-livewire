@storybook([
    'name' => 'Card Simple',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:card>
        <tedi:card-content>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-content>
    </tedi:card>
    <tedi:card>
        <tedi:card-content>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
            <tedi:status-badge color="brand" text="Kinnitatud" />
        </tedi:card-content>
    </tedi:card>
    <tedi:card>
        <tedi:card-content>
            <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                <tedi:status-badge color="brand" text="Kinnitatud" />
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:card>
        <tedi:card-content>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <tedi:icon name="monitor_heart" />
                <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:card>
        <tedi:card-content>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <tedi:icon name="monitor_heart" />
                <div>
                    <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                    <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                </div>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:card>
        <tedi:card-content>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <tedi:icon name="monitor_heart" />
                    <div>
                        <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                        <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                    </div>
                </div>
                <tedi:button>Lisa uus</tedi:button>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:row cols="1">
        <tedi:col>
            <tedi:card>
                <tedi:card-content>
                    <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                    <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                    <tedi:separator :spacing="1.5" />
                    <div style="display: flex; justify-content: center;">
                        <tedi:button>Lisa uus</tedi:button>
                    </div>
                </tedi:card-content>
            </tedi:card>
        </tedi:col>
    </tedi:row>
</div>
