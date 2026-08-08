@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
])

<div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
    <tedi:row class="border-bottom" style="padding: 14px 16px;" :gap="3">
        <tedi:col>Default</tedi:col>
        <tedi:col>
            <tedi:closing-button />
        </tedi:col>
    </tedi:row>
    <tedi:row style="padding: 14px 16px;" :gap="3">
        <tedi:col>Small</tedi:col>
        <tedi:col>
            <tedi:closing-button size="small" />
        </tedi:col>
    </tedi:row>
</div>
