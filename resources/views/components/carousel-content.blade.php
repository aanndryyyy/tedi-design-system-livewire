{{--
    TEDI Carousel Content.
    Port of angular/tedi/components/content/carousel/carousel-content/carousel-content.component.{ts,html}

    See carousel.blade.php for the documented divergence (no drag/wheel/
    resize-observer/clone-window physics — minimal Alpine index state only).
    `slidesPerView`/`gap` are exposed to tedi-carousel-slide children via
    @aware so each slide can compute its own flex-basis.
--}}
@props([
    /** Slides visible at once (fractional allowed, e.g. 1.25 for peeking). */
    'slidesPerView' => 1,
    /** Gap between slides, in px. */
    'gap' => 16,
    /** Fade mask at the edges. */
    'fade' => false,
    /** Transition duration in ms. */
    'transitionMs' => 400,
    /** Accessible label for the carousel region. Falls back to the translated "carousel" label. */
    'ariaLabel' => null,
])

<div
    tabindex="0"
    role="region"
    aria-roledescription="carousel"
    aria-label="{{ $ariaLabel ?? __('tedi::tedi.carousel') }}"
    aria-live="off"
    {{ $attributes->class([
        'tedi-carousel__content',
        'tedi-carousel__content--fade-right' => $fade && $slidesPerView > 1,
        'tedi-carousel__content--fade-x' => $fade && $slidesPerView <= 1,
    ]) }}
>
    <div
        x-ref="track"
        class="tedi-carousel__track"
        x-bind:style="`gap: {{ $gap }}px; transform: translate3d(calc(-1 * ${index} * (100% + {{ $gap }}px) / {{ $slidesPerView }}), 0, 0); transition: transform {{ $transitionMs }}ms ease;`"
    >
        {{ $slot }}
    </div>
</div>
