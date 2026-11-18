<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=4620-82915&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/15bd6e-date-field" target="_blank">Zeroheight ↗</a>

DateField is the form-control wrapper around the Calendar. It exposes a typed text input paired with a popover that renders the Calendar. It supports `single`, `multiple` and `range` modes, and the same selection-level/header options as Calendar.

> **Port notes.** Angular defaults single-mode fields on phones to the **native OS date picker**. That is dropped here, because `useNativePicker` is a breakpoint prop (CONVENTIONS.md §7 item 1). The **opt-in modal** is ported. `modal` takes `true`, `false` or a breakpoint name, and the name is resolved in the browser by `tediDateField`. `calendar-full-width` is ported too. `fullscreen` is accepted but has no effect, since the standalone `tedi:modal` has no fullscreen styles. The popover is not *positioned*: `.tedi-date-field__overlay` renders under an explicit `open` prop, anchored below the field with inline styles. The modal uses the same `open` prop and also takes a `draft` value, with Confirm / Cancel reported as DOM events (see Mobile Modal).
>
> Angular's `formatDate` / `parseDate` callbacks are JS functions with no server-side analogue. Instead of accepting PHP callables, the strings Angular derived from them are explicit props — `display` for the input text and `tags` for the `multiple`-mode chips. Format the value however your application wants and pass the result.
