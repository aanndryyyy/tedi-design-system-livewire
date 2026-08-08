
Disabled items keep their current expanded state but reject user interaction.
The header trigger renders as a native `<button disabled>` (or with
`aria-disabled` for the non-clickable-header variant), so browsers handle
focus, keyboard, and screen-reader announcements for free.

Use `disabled` for items whose content is locked behind a state the user
hasn't met yet (incomplete prerequisites, missing permissions, etc.).
        