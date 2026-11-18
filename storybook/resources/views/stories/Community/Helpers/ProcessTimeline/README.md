An informational process timeline: shows the stages of a business process and the status of
each stage. It is not a stepper: stages aren't clickable or focusable, only the buttons and
links inside them are.

> **Blade port note.** Upstream's second paragraph describes the Angular markup
> (`<ol tedi-process-timeline>` with `<li tedi-process-timeline-item>` children, numbered by
> their order). In Blade, use `tedi:process-timeline` with `tedi:process-timeline-item`
> children; they render the same native `ol` / `li`. Blade cannot count a slot's children, so
> each item takes its stage number from its 0-based `index` prop, and the final item sets
> `last`. Completed and upcoming stages get a visually hidden state text, and the active stage
> gets `aria-current="step"`. Indicators and connectors are decorative and hidden from screen
> readers. `orientation` is `horizontal` or `vertical`; upstream's breakpoint object is not
> ported.
