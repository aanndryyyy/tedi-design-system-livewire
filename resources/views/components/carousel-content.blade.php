{{--
    TEDI Carousel Content.
    Port of angular/src/tedi/components/content/carousel/carousel-content/carousel-content.component.{ts,html}

    See carousel.blade.php for the family's documented divergences. The
    behaviour is `tediCarousel` (resources/js/src/carousel.js): this element's
    `x-init` hands it the inputs that live here (`loop`, `slidesPerView`,
    `gap`, `swipeThreshold`, `transitionMs`) and registers this root as the
    viewport the pointer maths measures. `slidesPerView`/`gap` also reach each
    tedi-carousel-slide child via @aware so a slide computes its own
    flex-basis server-side.

    STRUCTURAL DIVERGENCE — the `.tedi-carousel__body` wrapper. Angular's
    `carousel.component.html` projects `tedi-carousel-content` and
    `tedi-carousel-navigation` into a `<div class="tedi-carousel__body">` by
    selector, whatever order the consumer wrote them in. Blade cannot pick
    children out of a slot by tag, so this component renders that wrapper
    around itself and takes the navigation through a `navigation` named slot:

        `tedi:carousel-content` … slides …
            `x-slot:navigation` → `tedi:carousel-navigation overlay`

    The rendered tree is Angular's exactly (body > content + navigation). The
    one consequence is that `$attributes` land on the content element (the
    Angular host), not on the outermost element this file emits. An overlay
    navigation written as a direct child of `tedi:carousel` instead would have
    no positioned `.tedi-carousel__body` ancestor and would cover the page.

    Pointer drag, the swipe threshold / flick detection and the keyboard
    handler (ArrowLeft/Right, PageUp/Down, Home, End) are ported in
    `tediCarousel`. The `role="presentation"` / `aria-hidden` swap upstream
    applies to off-screen slides, and the slide's `aria-label`, need the
    clone window's per-render indices and are not ported.
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
    /**
     * How far a drag must go to move on, as a fraction (0–1) of the distance to
     * the next position, which is one slide except for a shorter final step when
     * not looping. Each position fully dragged past counts; a quick flick moves
     * one position. Clamped to 0–1, as upstream's transform does.
     */
    'swipeThreshold' => 0.3,
    /**
     * Whether navigation wraps around at the ends. When `false`, it stops at the
     * first and last reachable positions and disables the corresponding arrow.
     */
    'loop' => true,
    /** Accessible label for the carousel region. Falls back to the translated "carousel" label. */
    'ariaLabel' => null,
])

@php
    $config = [
        'loop' => (bool) $loop,
        'slidesPerView' => max(1, (float) $slidesPerView),
        'gap' => (float) $gap,
        'swipeThreshold' => min(max(is_numeric($swipeThreshold) ? (float) $swipeThreshold : 0.3, 0), 1),
        'transitionMs' => (int) $transitionMs,
    ];
@endphp

<div class="tedi-carousel__body">
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
        x-init="configure({ ...{{ \Illuminate\Support\Js::from($config) }}, viewport: $el })"
        x-on:keydown="keydown($event)"
        x-on:pointerdown="pointerDown($event)"
        x-on:pointermove="pointerMove($event)"
        x-on:pointerup="pointerUp($event)"
        x-on:pointercancel="pointerCancel()"
        x-on:lostpointercapture="pointerCancel()"
    >
        <div
            x-ref="track"
            class="tedi-carousel__track"
            style="gap: {{ $config['gap'] }}px"
            x-bind:style="trackStyle()"
        >
            {{ $slot }}
        </div>
    </div>
    {{ $navigation ?? '' }}
</div>
