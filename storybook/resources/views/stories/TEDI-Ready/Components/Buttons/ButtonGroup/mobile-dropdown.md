Angular collapses the strip into a dropdown below `mobileBreakpoint` when
`enableMobileDropdown` is true. That decision is made in JavaScript against the
live viewport, which a server-rendered template cannot do (CONVENTIONS.md §7),
so this port takes an explicit `dropdown-mode` prop and the story sets it to
`true` to show the collapsed branch.
