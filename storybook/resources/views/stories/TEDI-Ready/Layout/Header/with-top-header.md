
`HeaderTopComponent` (`tedi-header-top`) adds a secondary bar above the main header — language selection, top-level links or a theme toggle. Place it as a direct child of `<header tedi-header>`; the header projects it by selector, so its position in your template does not matter.

Unlike `HeaderBottomComponent` it is never hidden by breakpoint — use `*showAt` / `*hideAt` on its children instead.
        