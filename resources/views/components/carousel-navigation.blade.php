{{--
    TEDI Carousel Navigation.
    Port of angular/tedi/components/content/carousel/carousel-navigation/carousel-navigation.component.{ts,html}

    No host bindings — the `tedi-carousel-navigation` element selector
    provides the layout, so the tag name is emitted literally. `next()`/
    `prev()` come from the tedi-carousel ancestor's Alpine scope.
--}}
<tedi-carousel-navigation {{ $attributes }}>
    <tedi:button type="button" variant="secondary" :aria-label="__('tedi::tedi.carousel.moveBack')" @click="prev()">
        <tedi:icon name="arrow_back" :size="18" />
    </tedi:button>
    <tedi:button type="button" variant="secondary" :aria-label="__('tedi::tedi.carousel.moveForward')" @click="next()">
        <tedi:icon name="arrow_forward" :size="18" />
    </tedi:button>
</tedi-carousel-navigation>
