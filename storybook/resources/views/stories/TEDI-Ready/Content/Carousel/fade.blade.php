@storybook([
    'name' => 'Fade',
    'order' => 13,
    'status' => 'subset',
    'args' => [
        'slidesPerView' => 3,
        'fade' => true,
    ],
    'argTypes' => [
        'slidesPerView' => [
            'control' => ['type' => 'number', 'min' => 1, 'step' => 0.25],
            'description' => 'Slides per view (minimum 1, can be fractional, e.g. 1.25 for peeking). Angular additionally accepts a per-breakpoint object; breakpoint props are not ported here (see README), so this control only sets the base value.',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'fade' => [
            'control' => 'boolean',
            'description' => 'Should carousel have fade? In mobile both left and right, in desktop only right.',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
    ],
])

<div style="display: flex; flex-direction: column; gap: 1.4rem;">
    <tedi:carousel>
        <tedi:carousel-header>
            <tedi:text as="h2" modifiers="h1">Title</tedi:text>
        </tedi:carousel-header>
        <tedi:carousel-content :slides-per-view="(float) $slidesPerView" :fade="(bool) $fade" aria-label="Karussell (mitu slaidi vaates)">
            @for ($i = 0; $i < 5; $i++)
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
    <tedi:carousel style="max-width: 400px;">
        <tedi:carousel-content :slides-per-view="1" :fade="(bool) $fade" aria-label="Karussell (üks slaid vaates)">
            @for ($i = 0; $i < 5; $i++)
                <tedi:carousel-slide>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; border: 1px solid var(--general-border-primary); border-radius: 4px; height: 10rem; padding: 1rem; flex: 1;">
                        <tedi:icon name="spa" :size="36" color="tertiary" />
                        <tedi:text color="secondary" style="text-align: center;">Replace with your own content</tedi:text>
                    </div>
                </tedi:carousel-slide>
            @endfor
        </tedi:carousel-content>
        <tedi:carousel-footer style="justify-content: center;">
            <tedi:carousel-indicators :with-arrows="true" />
        </tedi:carousel-footer>
    </tedi:carousel>
</div>
