{{--
    TEDI Carousel Indicators.
    Port of angular/tedi/components/content/carousel/carousel-indicators/carousel-indicators.component.{ts,html}

    Angular computes `indicatorsArray` from the actual slide count reactively
    (via contentChildren). Blade can't introspect a sibling's slot content at
    render time (§5), so the dot list is generated client-side with
    `x-for="i in count"`, reading `count` from the tedi-carousel ancestor's
    Alpine scope (populated by counting `.tedi-carousel__slide` children on
    init — see carousel.blade.php).
--}}
@props([
    /** Show arrow buttons alongside the indicators. */
    'withArrows' => false,
    /** dots|numbers */
    'variant' => 'dots',
])

<tedi-carousel-indicators {{ $attributes }}>
    @if ($withArrows)
        <tedi:button type="button" variant="neutral" :aria-label="__('tedi::tedi.carousel.moveBack')" @click="prev()">
            <tedi:icon name="arrow_back" :size="18" />
        </tedi:button>
    @endif

    @if ($variant === 'dots')
        <template x-for="i in count" :key="i">
            <button
                type="button"
                class="tedi-carousel__indicator"
                x-bind:class="{ 'tedi-carousel__indicator--active': isActive(i - 1) }"
                {{-- lang/en/tedi.php's carousel.showSlide.* keys are corrupted
                     placeholder strings from the Angular extraction (e.g.
                     "Show slide true"), not usable ICU messages — falls back
                     to a plain numbered label instead of __() here. --}}
                x-bind:aria-label="'Slide ' + i"
                @click="go(i - 1)"
            ></button>
        </template>
    @else
        <div x-show="count">
            <b class="tedi-text tedi-text--brand" x-text="index + 1"></b>
            <span class="tedi-text tedi-text--tertiary"> / <span x-text="count"></span></span>
        </div>
    @endif

    @if ($withArrows)
        <tedi:button type="button" variant="neutral" :aria-label="__('tedi::tedi.carousel.moveForward')" @click="next()">
            <tedi:icon name="arrow_forward" :size="18" />
        </tedi:button>
    @endif
</tedi-carousel-indicators>
