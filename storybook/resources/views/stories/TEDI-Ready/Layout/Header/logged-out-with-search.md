
On the narrowest viewports the header has to fit a sidenav toggle, a search input and several action buttons in a single row, which leaves no room for the logo. This story binds `[showLogo]` to a media-query-driven signal (`storyResponsive` custom directive watching `(min-width: 420px)`) so the logo is hidden below that width and rendered again as soon as there is space.

```html
<tedi-header-logo
  storyResponsive
  #responsive="storyResponsive"
  [showLogo]="responsive.show()"
  href="/"
>
  <img src="header-logo.svg" alt="Logo" />
</tedi-header-logo>
```

Use `[showLogo]` whenever you need to hide the logo at a custom breakpoint that does not match the standard `xs`/`sm`/`md`/`lg`/`xl`/`xxl` tiers — wrap `HeaderLogoComponent` in `*showAt` / `*hideAt` for the standard ones, and use `[showLogo]` for the in-between cases.
