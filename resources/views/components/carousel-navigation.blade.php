{{--
    TEDI Carousel Navigation.
    Port of angular/src/tedi/components/content/carousel/carousel-navigation/carousel-navigation.component.{ts,html}

    The `tedi-carousel-navigation` element selector provides the layout, so the
    tag name is emitted literally. `next()`/`prev()` and `canPrev`/`canNext`
    come from the tedi-carousel ancestor's Alpine scope; the arrows' `disabled`
    is bound client-side (see carousel.blade.php for why it cannot be static).

    `overlay` renders the arrows as floating buttons over the slides. Upstream
    asks for it to be placed directly inside `tedi-carousel`, where the parent
    template projects it into `.tedi-carousel__body`; here it goes in
    `tedi:carousel-content`'s `navigation` slot, which renders that body (see
    carousel-content.blade.php). Upstream's `position="static"` on the floating
    buttons is its `[style.position]` host binding, emitted here as the same
    inline style.
--}}
@props([
    /**
     * Shows floating navigation buttons over the slides. Place the navigation in
     * the `navigation` slot of `tedi:carousel-content`, outside the header and footer.
     */
    'overlay' => false,
])

<tedi-carousel-navigation {{ $attributes->class(['tedi-carousel-navigation--overlay' => $overlay]) }}>
    @if ($overlay)
        <tedi:floating-button
            type="button"
            variant="secondary"
            :icon-only="true"
            style="position: static"
            :aria-label="__('tedi::tedi.carousel.moveBack')"
            x-bind:disabled="!canPrev"
            @click="prev()"
        >
            <tedi:icon name="arrow_back" :size="18" />
        </tedi:floating-button>
        <tedi:floating-button
            type="button"
            variant="secondary"
            :icon-only="true"
            style="position: static"
            :aria-label="__('tedi::tedi.carousel.moveForward')"
            x-bind:disabled="!canNext"
            @click="next()"
        >
            <tedi:icon name="arrow_forward" :size="18" />
        </tedi:floating-button>
    @else
        <tedi:button type="button" variant="secondary" :icon-only="true" :aria-label="__('tedi::tedi.carousel.moveBack')" x-bind:disabled="!canPrev" @click="prev()">
            <tedi:icon name="arrow_back" :size="18" />
        </tedi:button>
        <tedi:button type="button" variant="secondary" :icon-only="true" :aria-label="__('tedi::tedi.carousel.moveForward')" x-bind:disabled="!canNext" @click="next()">
            <tedi:icon name="arrow_forward" :size="18" />
        </tedi:button>
    @endif
</tedi-carousel-navigation>
