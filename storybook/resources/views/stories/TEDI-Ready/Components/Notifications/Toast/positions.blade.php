@storybook([
    'name' => 'Positions',
    'order' => 5,
    'status' => 'stable',
])

<tedi:toast-container />

<tedi:vertical-spacing :size="0.5" x-data>
    <tedi:row :gap="2">
        <tedi:col>
            <tedi:button variant="secondary" x-on:click="TediToast.info('Top Left', 'Positioned at top-left corner.', { position: 'top-left' })">
                Top Left
            </tedi:button>
        </tedi:col>
        <tedi:col>
            <tedi:button variant="secondary" x-on:click="TediToast.info('Top Right', 'Positioned at top-right corner.', { position: 'top-right' })">
                Top Right
            </tedi:button>
        </tedi:col>
    </tedi:row>
    <tedi:row :gap="2">
        <tedi:col>
            <tedi:button variant="secondary" x-on:click="TediToast.info('Bottom Left', 'Positioned at bottom-left corner.', { position: 'bottom-left' })">
                Bottom Left
            </tedi:button>
        </tedi:col>
        <tedi:col>
            <tedi:button variant="secondary" x-on:click="TediToast.info('Bottom Right', 'Positioned at bottom-right corner.', { position: 'bottom-right' })">
                Bottom Right
            </tedi:button>
        </tedi:col>
    </tedi:row>
</tedi:vertical-spacing>
