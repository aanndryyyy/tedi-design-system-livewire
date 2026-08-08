Common field add-ons: a plain field, a field with a format hint via `tedi-feedback-text`, and shortcut buttons. Shortcuts are not a DateField input — project them into the `tedi-form-field` (its catch-all slot renders them in the field's column flow, below the input/feedback).

> **Port note.** Angular wires the shortcut buttons to the same reactive form control with a `(click)` handler. Reactive forms and `output()` events are not ported (CONVENTIONS.md §7), so the buttons here are markup only — bind your own `wire:click` and set the field's `display` / `value` props from the server.
