{{--
    TEDI SideNav Group Title.
    Port of angular/tedi/components/layout/sidenav/sidenav-group-title/sidenav-group-title.component.{ts,html}

    No inputs. The Angular host carries `class="tedi-sidenav-group-title"` and
    the template a single `<div class="tedi-sidenav-group-title__text">`.

    The root is the literal `<tedi-sidenav-group-title>` element per
    CONVENTIONS.md §4 — the class rule sets no `display`, and the collapsed and
    mobile-drill-down states are reached through descendant rules in
    sidenav.component.scss (`.tedi-sidenav--collapsed .tedi-sidenav-group-title`,
    `.tedi-sidenav--mobile-item-open .tedi-sidenav-group-title`), which match on
    the class either way. Neither TEDI nor this port sets a `display` on the
    element, but Angular renders the same custom element, so the computed value
    is identical to upstream's — no fallback is warranted.
--}}
@props([])

<tedi-sidenav-group-title {{ $attributes->class(['tedi-sidenav-group-title']) }}>
    <div class="tedi-sidenav-group-title__text">{{ $slot }}</div>
</tedi-sidenav-group-title>
