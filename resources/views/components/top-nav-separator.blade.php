{{--
    TEDI Top Nav Separator.
    Port of react/src/tedi/components/layout/top-nav/components/top-nav-separator/top-nav-separator.tsx
    (CONVENTIONS.md §13).

    A vertical rule between groups of items in the bar. It is a plain `<li>`,
    because it lives in the item list; the inner span that draws the line
    carries `role="separator"` and `aria-orientation="vertical"`. That is
    upstream's 20.x markup — the role used to sit on the `<li>` itself, with an
    aria-hidden span, which overrode the list-item semantics (an a11y fix,
    #823).
--}}
<li {{ $attributes->class(['tedi-top-nav__separator']) }}><span role="separator" aria-orientation="vertical" class="tedi-top-nav__separator-line"></span></li>
