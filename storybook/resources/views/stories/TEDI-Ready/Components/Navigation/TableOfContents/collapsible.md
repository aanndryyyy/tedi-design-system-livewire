Responsive page usage. On desktop (`lg` and up) the table of contents is a
sticky sidebar card next to the content; below `lg` it collapses into
`tedi-table-of-contents-collapsible` — a bottom bar that opens the list in a
bottom-sheet overlay. Resize the canvas to switch between the two.

`tedi-table-of-contents-collapsible` shares the list-level inputs with the root
component — `heading`, `activeId`, `defaultOpen`, `numbered`, `bordered`,
`sticky` and `ariaLabel` — so the controls above apply here too. It leaves out
the desktop-card-only inputs (`variant`, `headingLevel`, `stickyOffset`,
`stickyMaxHeight`) and adds `hideOnScroll` + `scrollContainer` (see the
"Collapsible: hide on scroll" story).

<!-- In Blade, `scrollContainer` is a CSS selector rather than an element. -->
