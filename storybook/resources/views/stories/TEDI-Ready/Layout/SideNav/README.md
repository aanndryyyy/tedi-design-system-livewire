<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.8.9--work-in-progress-?node-id=6367-171750&m=dev" target="_BLANK">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/136091-side-navigation" target="_BLANK">Zeroheight ↗</a>

The sidenav component is used to display the side navigation at the left side of the page. It can contain external links, router links, dropdowns, and more.
It consists of several sub-components:
- `SideNavItemComponent`: Used for showing item which can be text, external link or router link. And can contain a dropdown.
- `SideNavDropdownComponent`: Used for showing subitems in a dropdown.
- `SideNavDropdownItemComponent`: Dropdown item component. Subitems can be text, external link or router link.
- `SideNavDropdownGroup`: Used for grouping items in a dropdown. Grouping changes first item style, suggesting it is parent link.
- `SideNavGroupTitleComponent`: Used for showing title in menu and grouping similar items.
- `SideNavToggleComponent`: Used for toggling side navigation in mobile layout.
- `SideNavOverlayComponent`: Used for showing dark overlay when side navigation is open in mobile layout.

<!--
  PORT NOTE (storybook/CONTRACT.md §6). The Angular description continues:
  "To test the mobile layout, either resize your browser window or use
  Storybook's built-in viewport tools. … This component is responsive and adapts
  to mobile layout when at certain screen size."

  That is not true of this port and is not faked. Angular resolves the mobile
  branch at runtime with `BreakpointService` + the `desktopBreakpoint` input;
  breakpoint props are not ported (CONVENTIONS.md §7 #1), so `<tedi:sidenav>`
  takes an explicit `mobile` prop instead and `desktop-breakpoint` is not
  declared at all. Resizing the viewport therefore changes nothing here — pass
  `:mobile="true"` (plus `:mobile-open` / `:mobile-item-open`) to render the
  mobile branch, and drive it yourself from a media query if you need it live.

  The rest of the Angular state — `collapsed`, `open` on an item, the item
  registry — is likewise explicit props plus an inline Alpine layer
  (CONVENTIONS.md §5 and §8); see the header comment in
  resources/views/components/sidenav.blade.php.
-->
