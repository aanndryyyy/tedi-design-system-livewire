<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.81?node-id=3486-65554&m=dev" target="_BLANK">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/43adad-breadcrumbs" target="_blank">Zeroheight ↗</a><br/>
Breadcrumbs show the user's location within the page hierarchy.
- Pass the crumbs as the `items` array, in order from the root to the current page. (Angular marks each crumb with `*tediBreadcrumbItem`; Blade cannot introspect its own slot, so the trail is an explicit prop — see CONVENTIONS.md §5.)
- An entry with an `href` renders as a link and one without renders as a button. The last entry is always the current page and renders as a plain element with `aria-current="page"`.
- Crumb links are underlined by default; set `'underline' => false` on an entry for non-underlined crumbs (recommended for the `short` back-link). Crumbs collapsed into the ellipsis dropdown are always non-underlined.
- `long` shows the full trail; `short` shows only the parent crumb as a back-link (mobile).
- Set `maxItems` to collapse the middle of a long trail into an ellipsis dropdown.
