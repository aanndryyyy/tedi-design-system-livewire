Add `overlay` to `tedi-carousel-navigation` and place it directly inside `tedi-carousel`
to show the arrows as floating buttons over the slides.

**Blade port:** Blade cannot route a child by tag into the carousel's slide body, so put `tedi:carousel-navigation overlay` in the `navigation` slot of `tedi:carousel-content` instead — it renders the `.tedi-carousel__body` the buttons float over.
