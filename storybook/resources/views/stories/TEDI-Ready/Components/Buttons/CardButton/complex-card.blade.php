@storybook([
    'name' => 'Complex Card',
    'order' => 7,
    'status' => 'stable',
])

<tedi:card-button type="button">
    <tedi:card>
        <tedi:card-row>
            <tedi:card-icon>
                <tedi:icon name="prescriptions" />
            </tedi:card-icon>
            <tedi:separator axis="vertical" size="auto" />
            <tedi:card-content class="flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <tedi:text as="p" modifiers="bold">Amlodipiin 50mg</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Amlodipin-rathiopharm 50mg</tedi:text>
                </div>
                <div class="flex flex-wrap align-items-center gap-3">
                    <tedi:status-badge color="success" variant="bordered" text="Kehtiv" />
                    <tedi:text as="p" modifiers="small" color="secondary">Kehtiv kuni 12.05.2024</tedi:text>
                    <tedi:icon name="arrow_right_alt" color="secondary" />
                </div>
            </tedi:card-content>
        </tedi:card-row>
        <tedi:separator />
        <tedi:card-content>
            <tedi:row :cols="1" :gap="2">
                <tedi:col>
                    <tedi:text-group type="vertical">
                        <x-slot:label>Toimeaine</x-slot:label>
                        Amlodipiin
                    </tedi:text-group>
                </tedi:col>
                <tedi:col>
                    <tedi:text-group type="vertical">
                        <x-slot:label>Kogus</x-slot:label>
                        30 tk
                    </tedi:text-group>
                </tedi:col>
                <tedi:col>
                    <tedi:text-group type="vertical">
                        <x-slot:label>Välja ostmata</x-slot:label>
                        5 / 6 retsepti
                    </tedi:text-group>
                </tedi:col>
            </tedi:row>
        </tedi:card-content>
    </tedi:card>
</tedi:card-button>
