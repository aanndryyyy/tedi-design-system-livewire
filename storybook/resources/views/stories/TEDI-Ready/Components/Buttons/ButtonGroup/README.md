<a href="https://www.figma.com/design/ze9LXyoxEdGV8vpEdat7Oi/Button-group-buttons?node-id=136-19706&m=dev" target="_blank">Figma ↗</a><br>
<a href="https://www.tedi.ee/1ee8444b7/p/82e9cf-button-group" target="_blank">Zeroheight ↗</a><br>

Group of toggle buttons used as a view switcher (an alternative to tabs).
Selection lives on the group via `value`; set `multiple` to allow several
values at once. Angular collapses the group into a dropdown menu below its
`mobileBreakpoint` when `enableMobileDropdown` is true; that resolution happens
in JavaScript against the live viewport, so this port selects the branch with
the explicit `dropdown-mode` prop instead (CONVENTIONS.md §5 and §7).

Angular reads its items back through `contentChildren`, which Blade cannot do,
so the items are the `items` array prop. Writing
`<tedi:button-group-button>` children into the slot is also supported — that is
how the icon-only story wraps each item in a tooltip — but a slot-only group
has nothing to enumerate and so cannot build the mobile dropdown.
