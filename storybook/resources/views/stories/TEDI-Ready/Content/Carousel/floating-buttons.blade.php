@storybook([
    'name' => 'Floating buttons',
    'order' => 8,
    'status' => 'subset',
    'args' => [
        'slidesPerView' => 3,
        'loop' => true,
    ],
    'argTypes' => [
        'slidesPerView' => [
            'control' => ['type' => 'number', 'min' => 1, 'step' => 0.25],
            'description' => 'Slides per view (minimum 1, can be fractional, e.g. 1.25 for peeking). Angular additionally accepts a per-breakpoint object; breakpoint props are not ported here (see README), so this control only sets the base value.',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'loop' => [
            'control' => 'boolean',
            'description' => 'Whether navigation wraps around at the ends. When `false`, it stops at the first and last reachable positions and disables the corresponding arrow.',
            'table' => ['category' => 'Carousel Content', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
    ],
])

{{--
    Angular passes `slidesPerView` as { xs: 1, sm: 2, md: 2.5, lg: 3, xl: 3.5, xxl: 4 };
    breakpoint props are not ported (README), so this story uses the `lg` value.
    Upstream writes `tedi-carousel-navigation overlay` directly inside
    `tedi-carousel`; here it goes in the content's `navigation` slot, which
    renders the `.tedi-carousel__body` it floats over (carousel-content.blade.php).
--}}
<tedi:carousel>
    <tedi:carousel-header>
        <tedi:text as="h2" modifiers="h1">Title</tedi:text>
    </tedi:carousel-header>
    <tedi:carousel-content :slides-per-view="(float) $slidesPerView" :loop="(bool) $loop">
        @for ($i = 0; $i < 5; $i++)
            <tedi:carousel-slide>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; border: 1px solid var(--general-border-primary); border-radius: 4px; height: 10rem; padding: 1rem 3.5rem; flex: 1;">
                    <tedi:icon name="spa" :size="36" color="tertiary" />
                    <tedi:text color="secondary" style="text-align: center;">Replace with your own content</tedi:text>
                </div>
            </tedi:carousel-slide>
        @endfor
        <x-slot:navigation>
            <tedi:carousel-navigation overlay />
        </x-slot:navigation>
    </tedi:carousel-content>
    <tedi:carousel-footer style="justify-content: center;">
        <tedi:carousel-indicators />
    </tedi:carousel-footer>
</tedi:carousel>
