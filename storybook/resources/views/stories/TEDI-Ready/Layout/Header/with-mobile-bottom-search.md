
`HeaderBottomComponent` (`tedi-header-bottom`) adds a secondary row below the main header bar — typically a compact search or mobile-only navigation. Place it as a direct child of `<header tedi-header>`; the header projects it by selector, so its position in your template does not matter.

It is hidden from the `md` breakpoint up in CSS, so you can include it unconditionally — no `*hideAt` needed.
        