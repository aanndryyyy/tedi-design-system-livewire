Always prefer a native `<label>` element for form controls.
If the label must not be visible in the UI, hide it with `hideLabel` rather than
removing it. This preserves correct semantics and provides the most reliable
experience for screen reader users.
`ariaLabel` names the search landmark, not the field. Set it only when the page has more than one search.
