A documentation page with a sticky table-of-contents sidebar. The page scrolls
inside a fixed-height region, and the sticky TOC stays alongside it — its
heading pinned while the list scrolls on its own when it is taller than the
space available. The component is controlled via `activeId`, so the consumer
owns scroll behavior: this demo wires an `IntersectionObserver` to highlight the
section in view (scroll-spy) and smooth-scrolls the page to a section when its
item is clicked.

Tune the gap the sticky sidebar leaves above the viewport bottom with the
`--tedi-table-of-contents-sticky-bottom` CSS custom property (default `1.5rem`).
