@storybook([
    'name' => 'Alternative Cards',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <tedi:row cols="1" gap="4">
        <tedi:col>
            <tedi:card>
                <tedi:card-content>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <tedi:icon name="assignment_ind" color="brand" />
                            <tedi:text as="h3" color="brand">Minu tahteavaldus</tedi:text>
                        </div>
                        <tedi:text as="p" color="secondary">Näiteks elundidoonorlus ja vereülekanne</tedi:text>
                    </div>
                    <tedi:separator :spacing="1" />
                    <tedi:button variant="secondary">Vaata tahteavaldusi</tedi:button>
                </tedi:card-content>
            </tedi:card>
        </tedi:col>
        <tedi:col>
            <tedi:card>
                <tedi:card-content>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                            <tedi:text as="p" color="secondary">Näiteks elundidoonorlus ja vereülekanne</tedi:text>
                        </div>
                        <tedi:button variant="secondary">Vaata tahteavaldusi</tedi:button>
                    </div>
                </tedi:card-content>
            </tedi:card>
        </tedi:col>
        <tedi:col>
            <tedi:card>
                <tedi:card-header background="brand-primary">
                    <tedi:text as="h3" color="white">Lühike pealkiri</tedi:text>
                </tedi:card-header>
                <tedi:card-content>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <tedi:text as="p" color="secondary">Näiteks elundidoonorlus ja vereülekanne</tedi:text>
                        <tedi:button variant="secondary">Vaata tahteavaldusi</tedi:button>
                    </div>
                </tedi:card-content>
            </tedi:card>
        </tedi:col>
        <tedi:col>
            <tedi:card>
                <tedi:card-content>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <tedi:text as="p" color="secondary">Näiteks elundidoonorlus ja vereülekanne</tedi:text>
                        <tedi:button variant="secondary">Vaata tahteavaldusi</tedi:button>
                    </div>
                </tedi:card-content>
            </tedi:card>
        </tedi:col>
    </tedi:row>
    <tedi:card border="left-danger-secondary">
        <tedi:card-content>
            <tedi:text as="p">Tähtis kaart</tedi:text>
        </tedi:card-content>
    </tedi:card>
</div>
