@storybook([
    'name' => 'Horizontal',
    'order' => 2,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap-y="4">
    @foreach (['primary', 'secondary'] as $variant)
        <tedi:col>
            <tedi:text as="p" modifiers="bold">{{ $variant === 'primary' ? 'Primary' : 'Secondary' }}</tedi:text>
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; padding-top: 0.5rem;">
                <tedi:floating-button position="static" :variant="$variant">Jäta oma tagasiside</tedi:floating-button>
                <tedi:floating-button position="static" :variant="$variant" icon-end="arrow_upward">Mine üles</tedi:floating-button>
                <tedi:floating-button position="static" :variant="$variant" icon-start="chat">Vestle meiega</tedi:floating-button>
                <tedi:tooltip>
                    <tedi:tooltip-trigger>
                        <tedi:floating-button position="static" :variant="$variant" icon-only icon-start="arrow_upward" aria-label="Mine üles" />
                    </tedi:tooltip-trigger>
                    <tedi:tooltip-content>Mine üles</tedi:tooltip-content>
                </tedi:tooltip>
            </div>
        </tedi:col>
    @endforeach
</tedi:row>
