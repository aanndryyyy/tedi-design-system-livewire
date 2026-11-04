{{--
    TEDI Carousel.
    Port of angular/src/tedi/components/content/carousel/carousel.component.{ts,html}

    Angular's host has no dynamic class bindings — `tedi-carousel` is styled
    purely via the vendored SCSS's element selector, so the custom tag name
    is emitted literally (see text-group-label.blade.php for the same
    rationale).

    Angular's template wraps the content and the navigation in
    `.tedi-carousel__body`. Blade cannot route slot children by selector, so
    `tedi:carousel-content` renders that wrapper itself and takes an overlay
    navigation through its `navigation` slot — see carousel-content.blade.php.

    KNOWN DIVERGENCE (documented once here for the whole carousel family, see
    CONVENTIONS.md §7-§8): the behaviour is the bundled `tediCarousel`
    (resources/js/src/carousel.js). It ports bounded navigation (`loop`),
    fractional `slidesPerView`, pointer drag with `swipeThreshold` and flick
    detection, and the keyboard handler. NOT ported:
      • the buffered clone window upstream renders around a looping track, so
        a looping carousel rewinds from the last slide to the first instead of
        continuing seamlessly, and cannot be dragged past either end;
      • wheel-to-scroll, and the ResizeObserver re-clamp on resize;
      • per-breakpoint `slidesPerView`/`gap` (breakpoint props aren't ported,
        §7) — both are plain scalars;
      • the LiveAnnouncer slide announcement after navigation.
    Indicator and arrow state (`canPrev`/`canNext`, the active dot, the dot
    count) is client-side only: `loop` lives on the content element, a sibling
    the indicators and navigation cannot read server-side, so arrows render
    enabled until Alpine boots.
--}}
<tedi-carousel {{ $attributes }} x-data="tediCarousel()" x-init="count = $refs.track ? $refs.track.children.length : 0">
    {{ $slot }}
</tedi-carousel>
