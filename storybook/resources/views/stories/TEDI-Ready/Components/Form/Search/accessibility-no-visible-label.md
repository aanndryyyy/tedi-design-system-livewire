Always prefer a native `<label>` element for form controls.
If the label must not be visible in the UI, hide it visually using an `sr-only`
(or equivalent) class rather than removing it. This preserves correct semantics
and provides the most reliable experience for screen reader users.
Use `ariaLabel` only as a fallback when a real `<label>` cannot be rendered.
This follows WCAG 2.1 and EN 301 549 9.2.5.3.
