{{--
    TEDI Top Nav Group.
    Port of react/src/tedi/components/layout/top-nav/components/top-nav-group/top-nav-group.tsx
    (CONVENTIONS.md §13).

    One column of a mega-menu: an optional heading, then a list of
    `tedi:top-nav-subitem`s.

    The heading is omitted entirely when `title` is empty — upstream's `{title &&
    …}` — which is how a single-column menu of bare links is built. The icon is
    inside the heading, so it goes with it; upstream documents that explicitly
    ("Ignored when `title` is omitted") and this reproduces it rather than
    floating an orphan icon.

    `heading-level` picks the tag only. There is no typography modifier class:
    the group title's size and weight come from `.tedi-top-nav__group-title`,
    so the level is free to be whatever keeps the host page's outline correct.

    `icon` takes a Material Symbols name; upstream also accepts a full icon
    props object, and renders at size 16 with `color="inherit"`, which is what
    is fixed here (CONVENTIONS.md §5).
--}}
@props([
    /** Uppercase column heading. Omit for a headless column of links. */
    'title' => null,
    /** h2|h3|h4|h5|h6 — the heading tag. */
    'headingLevel' => 'h3',
    /** Material Symbols name rendered before the title. */
    'icon' => null,
])

<section {{ $attributes->class(['tedi-top-nav__group']) }}>
    @if (filled($title))
        <{{ $headingLevel }} class="tedi-top-nav__group-title">
            @if ($icon)
                <tedi:icon :name="$icon" class="tedi-top-nav__group-icon" color="inherit" :size="16" />
            @endif
            {{ $title }}
        </{{ $headingLevel }}>
    @endif

    <ul class="tedi-top-nav__group-list">
        {{ $slot }}
    </ul>
</section>
