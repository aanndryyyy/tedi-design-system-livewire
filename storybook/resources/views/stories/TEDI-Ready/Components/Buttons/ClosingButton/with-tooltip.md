Wrap the button in a `tedi-tooltip` to show a custom tooltip on hover.
Set `[showTitle]="false"` so the native browser tooltip (the `title`
attribute) does not double the custom one — the `aria-label` is kept.
