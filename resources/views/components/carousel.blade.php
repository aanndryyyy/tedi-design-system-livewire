{{--
    TEDI Carousel.
    Port of angular/tedi/components/content/carousel/carousel.component.{ts,html}

    Angular's host has no dynamic class bindings — `tedi-carousel` is styled
    purely via the vendored SCSS's element selector, so the custom tag name
    is emitted literally (see text-group-label.blade.php for the same
    rationale).

    KNOWN DIVERGENCE (documented once here for the whole carousel family, see
    CONVENTIONS.md §7-§8): Angular's CarouselContentComponent implements
    fractional `slidesPerView`, per-breakpoint `slidesPerView`/`gap`, pointer
    drag, wheel-to-scroll, a buffered clone window for seamless wrap-around,
    ResizeObserver-driven widths and LiveAnnouncer slide announcements. This
    port ships correct static markup/classes plus a minimal Alpine
    `tediCarousel` behaviour (index state, next/prev/goToIndex) from
    resources/js/tedi.js — no drag, no wheel, no wrap-around cloning, no
    live-region announcements. `slidesPerView`/`gap` are accepted as plain
    scalars (breakpoint props aren't ported, §7).
--}}
<tedi-carousel {{ $attributes }} x-data="tediCarousel()" x-init="count = $refs.track ? $refs.track.children.length : 0">
    {{ $slot }}
</tedi-carousel>
