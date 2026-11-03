@storybook([
    'name' => 'Vertical',
    'order' => 3,
    'status' => 'stable',
])

<style>
    .floating-vertical-example {
        display: flex;
        align-items: center;
        justify-content: center;
        width: var(--button-md-icon-size);
        height: 14rem;
    }
</style>

<tedi:row :cols="1" :gap-y="4">
    @foreach (['primary', 'secondary'] as $variant)
        <tedi:col>
            <tedi:text as="p" modifiers="bold">{{ $variant === 'primary' ? 'Primary' : 'Secondary' }}</tedi:text>
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; padding-top: 0.5rem;">
                <div class="floating-vertical-example">
                    <tedi:floating-button position="static" axis="vertical" :variant="$variant">Anna tagasiside</tedi:floating-button>
                </div>
                <div class="floating-vertical-example">
                    <tedi:floating-button position="static" axis="vertical" :variant="$variant" icon-end="news">Anna tagasiside</tedi:floating-button>
                </div>
                <div class="floating-vertical-example">
                    <tedi:floating-button position="static" axis="vertical" :variant="$variant" icon-start="news">Anna tagasiside</tedi:floating-button>
                </div>
            </div>
        </tedi:col>
    @endforeach
</tedi:row>
