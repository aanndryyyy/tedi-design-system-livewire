{{--
    TEDI Carousel Slide.
    Port of angular/tedi/components/content/carousel/carousel-slide.directive.ts
    (rendered slide markup comes from carousel-content.component.html's `@for`)

    Angular's `[tediCarouselSlide]` is a structural directive holding a
    `TemplateRef`, cloned by CarouselContentComponent into a buffered window
    of `.tedi-carousel__slide` divs for wrap-around. This port drops the
    clone/buffer window (see carousel.blade.php's divergence note) — each
    `<tedi:carousel-slide>` renders exactly one `.tedi-carousel__slide` div in
    document order, sized via the slidesPerView/gap inherited from the
    parent tedi-carousel-content.
--}}
@aware([
    'slidesPerView' => 1,
    'gap' => 16,
])
@props([])

@php
    $flexBasis = $slidesPerView > 1
        ? 'calc((100% - '.(($slidesPerView - 1) * $gap).'px) / '.$slidesPerView.')'
        : '100%';
@endphp

<div
    role="group"
    aria-roledescription="slide"
    style="flex: 0 0 {{ $flexBasis }}"
    {{ $attributes->class(['tedi-carousel__slide']) }}
>
    {{ $slot }}
</div>
