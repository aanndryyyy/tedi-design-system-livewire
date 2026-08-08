@storybook([
    'name' => 'Card Shortcut',
    'order' => 3,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                <div>
                    <tedi:text as="p" modifiers="bold">Töövõime</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Näiteks töövõimetuslehed, töövõime hindamine</tedi:text>
                </div>
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                <div>
                    <tedi:text as="p" modifiers="bold">Esindusõigus Terviseportaalis</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Võimaldab jagada ligipääsu sinu terviseandmetele</tedi:text>
                </div>
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                <div>
                    <tedi:text as="p" modifiers="bold">Mootorsõiduki juhiloa tõend</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Kehtib kuni 28.05.2024</tedi:text>
                </div>
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                <div>
                    <tedi:text as="p" modifiers="bold">Minu hammaste tervis</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Ülevaade sinu vastuvõttudest</tedi:text>
                </div>
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
</tedi:row>
