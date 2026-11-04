{{--
    TEDI Carousel Indicators.
    Port of angular/src/tedi/components/content/carousel/carousel-indicators/carousel-indicators.component.{ts,html}

    Angular computes `indicatorsArray` from the carousel content's
    `positionCount()` — one per slide when looping, one per reachable stop when
    `loop` is off — and marks `activePosition()`. Blade can't introspect a
    sibling's slot at render time (§5), and `loop` lives on that sibling, so
    the dot list is generated client-side with `x-for` over the
    tedi-carousel ancestor's `positionCount` (see resources/js/src/carousel.js).
    The arrows' `disabled` (`!canPrev` / `!canNext`) is bound the same way.
--}}
@props([
    /** Show arrow buttons alongside the indicators. */
    'withArrows' => false,
    /** dots|numbers */
    'variant' => 'dots',
])

@php
    // `carousel.showSlide` takes the 1-based slide number; the number is only
    // known client-side, so the translated string carries a marker x-for fills in.
    $showSlideLabel = __('tedi::tedi.carousel.showSlide', ['slideNumber' => '__N__']);
@endphp

<tedi-carousel-indicators {{ $attributes }}>
    @if ($withArrows)
        <tedi:button type="button" variant="neutral" :icon-only="true" :aria-label="__('tedi::tedi.carousel.moveBack')" x-bind:disabled="!canPrev" @click="prev()">
            <tedi:icon name="arrow_back" :size="18" />
        </tedi:button>
    @endif

    @if ($variant === 'dots')
        <template x-for="i in positionCount" :key="i">
            <button
                type="button"
                class="tedi-carousel__indicator"
                x-bind:class="{ 'tedi-carousel__indicator--active': isActive(i - 1) }"
                x-bind:aria-label="{{ \Illuminate\Support\Js::from($showSlideLabel) }}.replace('__N__', i)"
                @click="go(i - 1)"
            ></button>
        </template>
    @else
        <div x-show="positionCount">
            <b class="tedi-text tedi-text--brand" x-text="activePosition + 1"></b>
            <span class="tedi-text tedi-text--tertiary"> / <span x-text="positionCount"></span></span>
        </div>
    @endif

    @if ($withArrows)
        <tedi:button type="button" variant="neutral" :icon-only="true" :aria-label="__('tedi::tedi.carousel.moveForward')" x-bind:disabled="!canNext" @click="next()">
            <tedi:icon name="arrow_forward" :size="18" />
        </tedi:button>
    @endif
</tedi-carousel-indicators>
