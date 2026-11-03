Use `arrowVariant` to apply any `tedi-button` variant to the prev/next buttons. Pair with `showArrowLabels` to render a regular labelled button — useful when the pager needs to read as a primary navigation action. Labels are hidden below `md` here (`[md]="{ showArrowLabels: true }"`) so narrow screens keep compact icon-only arrows.

**Blade port:** the `xs`–`xxl` breakpoint overrides are not ported (see the README's divergences), so this story sets `showArrowLabels` to `true` and shows the `md`-and-up appearance at every width.
