@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.54.76?node-id=4620-85618&m=dev',
])

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
