A crumb can be a button rather than an anchor — omit its `href` and it renders
as a `button tedi-link`, for crumbs that trigger an action (e.g. navigating a
wizard step) instead of following an `href`. The current page stays a plain
element with `aria-current="page"` — it should not be a button.
