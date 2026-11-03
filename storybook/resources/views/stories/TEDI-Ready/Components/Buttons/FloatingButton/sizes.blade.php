@storybook([
    'name' => 'Sizes',
    'order' => 4,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap-y="4">
    @foreach (['default', 'large'] as $size)
        <tedi:col>
            <tedi:text as="p" modifiers="bold">{{ $size === 'default' ? 'Default' : 'Large' }}</tedi:text>
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; padding-top: 0.5rem;">
                <tedi:floating-button position="static" variant="secondary" :size="$size">Jäta oma tagasiside</tedi:floating-button>
                <tedi:tooltip>
                    <tedi:tooltip-trigger>
                        <tedi:floating-button position="static" variant="secondary" :size="$size" icon-only icon-start="arrow_upward" aria-label="Mine üles" />
                    </tedi:tooltip-trigger>
                    <tedi:tooltip-content>Mine üles</tedi:tooltip-content>
                </tedi:tooltip>
            </div>
        </tedi:col>
    @endforeach
</tedi:row>
