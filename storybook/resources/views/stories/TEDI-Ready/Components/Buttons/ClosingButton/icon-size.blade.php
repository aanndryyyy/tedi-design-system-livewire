@storybook([
    'name' => 'Icon Size',
    'order' => 3,
    'status' => 'stable',
])

<div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
    <tedi:row class="border-bottom" style="padding: 14px 16px;" :gap="3">
        <tedi:col>18px</tedi:col>
        <tedi:col>
            <tedi:closing-button size="small" :icon-size="18" />
        </tedi:col>
    </tedi:row>
    <tedi:row style="padding: 14px 16px;" :gap="3">
        <tedi:col>24px</tedi:col>
        <tedi:col style="display: flex; align-items: center; gap: 1rem;">
            <tedi:closing-button />
            <tedi:closing-button size="small" />
        </tedi:col>
    </tedi:row>
</div>
