<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=4620-82915&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/15bd6e-date-field" target="_blank">Zeroheight ↗</a>

DateField is the form-control wrapper around the Calendar. It exposes a typed text input paired with a popover that renders the Calendar. It supports `single`, `multiple` and `range` modes, and the same selection-level/header options as Calendar.

> **Port notes.** Two of the Angular component's headline behaviours are not in this package. Angular defaults single-mode fields on phones to the **native OS date picker** and offers an **opt-in modal**; both are dropped here — `useNativePicker`, `modal` and `fullscreen` are breakpoint props (CONVENTIONS.md §7 item 1) and the modal composes four `tedi-modal*` components this package does not ship (§7 item 3). The field always takes the popover branch, and the popover itself is not *positioned*: `.tedi-date-field__overlay` renders inline under an explicit `open` prop, because overlay placement is out of scope for a template-only port.
>
> Angular's `formatDate` / `parseDate` callbacks are JS functions with no server-side analogue. Instead of accepting PHP callables, the strings Angular derived from them are explicit props — `display` for the input text and `tags` for the `multiple`-mode chips. Format the value however your application wants and pass the result.
