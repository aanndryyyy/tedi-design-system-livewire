
The Header component renders the page header. It can contain a SideNav toggle, logo, content links, language and role selection, profile menu, login / logout buttons, and more.
Header is responsive and adapts to mobile layouts automatically, but some subcomponents move between breakpoints. For example, `HeaderRoleComponent` lives in the main header on desktop and inside the `HeaderProfileComponent` menu on mobile. Use the `*showAt` and `*hideAt` structural directives to render each subcomponent in the right place at each breakpoint.
To preview the mobile layout, resize the browser window or use Storybook's viewport tools.

Header consists of several sub-components:
- `HeaderTopComponent`: Optional secondary bar rendered above the main header bar (typically language selection, top-level links or a theme toggle).
- `HeaderLogoComponent`: Wraps the project logo. Project the light/default logo as direct content; optionally project a dark-theme variant marked with `tedi-header-logo-dark` for automatic swap when the active theme is `dark`.
- `HeaderContentComponent`: Used for showing links in desktop view.
- `HeaderActionsComponent`: Used for showing and styling actions in header (placed at the right side).
- `HeaderRoleComponent`: Used for showing role selection. Accepts an optional title element projected via the `[tedi-header-role-title]` slot when richer markup (e.g. a tag) is needed instead of the plain `label` text.
- `HeaderLanguageComponent`: Used for selecting language.
- `HeaderProfileComponent`: Used for showing profile menu. Projected children render inside the popover (desktop) and modal (mobile).
- `HeaderLoginComponent`: Used for showing login button.
- `HeaderLogoutComponent`: Used for showing logout button.
- `HeaderSearchComponent`: Used for the search input that adapts between an inline desktop field and a mobile modal/inline variant.
- `HeaderBottomComponent`: Optional secondary row rendered below the main header bar on mobile (typically a compact search bar or contextual nav).

Example with theme-aware logo:
```html
<tedi-header-logo href="/">
  <img src="logo.svg" alt="Logo" />
  <img tedi-header-logo-dark src="logo-white.svg" alt="Logo (dark mode)" />
</tedi-header-logo>
```

| Selector | Description |
|----------|------------|
| `[tedi-header-logo-dark]` | Dark-theme logo variant. The logo component swaps to this image when the active theme is `dark`. |
| `[tedi-header-role-title]` | Title content projected into the role header (e.g. a `<tedi-tag>`). Replaces the bold `label` text. |
| `[tedi-header-role-content]` | Custom content projected into the role selection popover (desktop) or accordion (mobile). Replaces the default representative list. |
| `[tedi-header-role-no-results]` | Custom "no results" content shown when the search filter produces an empty representative list. |
        