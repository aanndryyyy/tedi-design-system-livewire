@storybook([
    'name' => 'As Link',
    'order' => 6,
    'status' => 'stable',
])

<tedi:card-button href="#">
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
