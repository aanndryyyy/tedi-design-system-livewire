When you only need a typed time entry without any picker UI, set `pickerVariant="none"`. The input stays a plain text field.

> **Port note.** The Angular story also points at `useNativePicker` for the browser's `type="time"` UI. That input is a breakpoint prop and is not ported (CONVENTIONS.md §7 item 1), so this package's input is always `type="text"`.
