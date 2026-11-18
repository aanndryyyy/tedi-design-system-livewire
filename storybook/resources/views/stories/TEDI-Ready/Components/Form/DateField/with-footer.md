Anything passed in the `footer` slot renders below the calendar body. Two common patterns: a link to a sibling picker (icon on the left, per Figma) and a full-width Cancel + Save pair.

> Angular projects the footer with a `tediCalendarFooter` attribute and notes that the modal variant does not receive it. The same holds here: the `footer` slot reaches the popover's calendar, never the modal's (`modal`).
