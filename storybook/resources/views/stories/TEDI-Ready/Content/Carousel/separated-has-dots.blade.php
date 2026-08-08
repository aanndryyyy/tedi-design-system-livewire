@storybook([
    'name' => 'Separated - has dots',
    'order' => 10,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:carousel style="max-width: 400px;">
    <tedi:carousel-header>
        <tedi:text as="h2" modifiers="h1">Title</tedi:text>
    </tedi:carousel-header>
    <tedi:carousel-content :slides-per-view="1">
        @for ($i = 0; $i < 3; $i++)
            <tedi:carousel-slide>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; border: 1px solid var(--general-border-primary); border-radius: 4px; height: 10rem; padding: 1rem; flex: 1;">
                    <tedi:icon name="spa" :size="36" color="tertiary" />
                    <tedi:text color="secondary" style="text-align: center;">Replace with your own content</tedi:text>
                </div>
            </tedi:carousel-slide>
        @endfor
    </tedi:carousel-content>
    <tedi:carousel-footer>
        <tedi:carousel-indicators />
        <tedi:carousel-navigation />
    </tedi:carousel-footer>
</tedi:carousel>
