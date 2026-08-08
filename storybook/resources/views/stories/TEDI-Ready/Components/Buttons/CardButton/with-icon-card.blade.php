@storybook([
    'name' => 'With Icon Card',
    'order' => 4,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-row>
                <tedi:card-icon>
                    <tedi:icon name="euro_symbol" />
                </tedi:card-icon>
                <tedi:separator axis="vertical" size="auto" />
                <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                    <div>
                        <tedi:text as="p" modifiers="bold">Isiku toetused</tedi:text>
                        <tedi:text as="p" modifiers="small" color="secondary">Toetused mis on isikule ette nähtud</tedi:text>
                    </div>
                    <tedi:icon name="arrow_right_alt" color="secondary" />
                </tedi:card-content>
            </tedi:card-row>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-row>
                <tedi:card-icon>
                    <tedi:icon name="checklist" />
                </tedi:card-icon>
                <tedi:separator axis="vertical" size="auto" />
                <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                    <div>
                        <tedi:text as="p" modifiers="bold">Isiku hindamised</tedi:text>
                        <tedi:text as="p" modifiers="small" color="secondary">Hindamised toetuste saamiseks</tedi:text>
                    </div>
                    <tedi:icon name="arrow_right_alt" color="secondary" />
                </tedi:card-content>
            </tedi:card-row>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-row>
                <tedi:card-icon>
                    <tedi:icon name="contract" />
                </tedi:card-icon>
                <tedi:separator axis="vertical" size="auto" />
                <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                    <div>
                        <tedi:text as="p" modifiers="bold">Isiku teenused</tedi:text>
                        <tedi:text as="p" modifiers="small" color="secondary">Teenused mis on võimaldatud peale hinnagu andmist</tedi:text>
                    </div>
                    <tedi:icon name="arrow_right_alt" color="secondary" />
                </tedi:card-content>
            </tedi:card-row>
        </tedi:card>
    </tedi:card-button>
</tedi:row>
