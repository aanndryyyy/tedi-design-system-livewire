@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.20.28?node-id=26296-151359&m=dev',
    'args' => [
        'slidesPerView' => 3,
        'gap' => 16,
        'fade' => false,
        'withArrows' => false,
        'variant' => 'dots',
    ],
    'argTypes' => [
        'slidesPerView' => [
            'control' => ['type' => 'number', 'min' => 1, 'step' => 0.25],
            'description' => 'Slides per view (minimum 1, can be fractional, e.g. 1.25 for peeking). Angular additionally accepts a per-breakpoint object; breakpoint props are not ported here (see README), so this control only sets the base value.',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'gap' => [
            'control' => 'number',
            'description' => 'Gap between slides in px',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '16']],
        ],
        'fade' => [
            'control' => 'boolean',
            'description' => 'Should carousel have fade? In mobile both left and right, in desktop only right.',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'withArrows' => [
            'control' => 'boolean',
            'description' => "Should show indicators with arrows? If yes, don't use carousel-navigation component",
            'table' => ['category' => 'Carousel Indicators', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['dots', 'numbers'],
            'description' => 'Variant of indicators (dots and numbers)',
            'table' => ['category' => 'Carousel Indicators', 'type' => ['summary' => 'CarouselIndicatorsVariant'], 'defaultValue' => ['summary' => 'dots']],
        ],
    ],
])

{{--
    KNOWN DIVERGENCE (see carousel.blade.php): Angular's per-breakpoint
    slidesPerView/gap object isn't ported — breakpoint props aren't ported
    package-wide (README). This story exposes the plain scalar slidesPerView
    the base tedi:carousel-content prop accepts.
--}}
<tedi:carousel>
    <tedi:carousel-header>
        <div>
            <tedi:text as="h2" modifiers="h1">Title</tedi:text>
            <tedi:text as="p" color="secondary">Description</tedi:text>
        </div>
        <tedi:carousel-navigation />
    </tedi:carousel-header>
    <tedi:carousel-content :slides-per-view="(float) $slidesPerView" :gap="(int) $gap" :fade="(bool) $fade">
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
        <tedi:carousel-indicators :with-arrows="(bool) $withArrows" :variant="$variant" />
    </tedi:carousel-footer>
</tedi:carousel>
