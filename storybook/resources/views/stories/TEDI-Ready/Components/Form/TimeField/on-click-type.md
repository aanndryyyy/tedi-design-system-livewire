`pickerTrigger` controls how the picker opens: `button` (default) opens it only from the clock icon; `input` opens it from anywhere in the field.

> **Port note.** Angular also anchors the popover differently per trigger — to the icon for `button`, and to the field (left-aligned, matched to the input width) for `input`. Overlay positioning is out of scope for this template-only port (CONVENTIONS.md §7 item 3), so the picker panel renders inline instead and the two triggers differ only in which parts of the field are interactive.
