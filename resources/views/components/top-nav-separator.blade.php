{{--
    TEDI Top Nav Separator.
    Port of react/src/tedi/components/layout/top-nav/components/top-nav-separator/top-nav-separator.tsx
    (CONVENTIONS.md §13).

    A vertical rule between groups of items in the bar. It is an `<li>`, because
    it lives in the item list, with `role="separator"` to keep it out of the
    list semantics — both upstream's, along with the aria-hidden inner span that
    draws the actual line.
--}}
<li
    role="separator"
    aria-orientation="vertical"
    {{ $attributes->class(['tedi-top-nav__separator']) }}
><span class="tedi-top-nav__separator-line" aria-hidden="true"></span></li>
