Set `size` on both the `tedi-form-field` wrapper and the `tedi-date-field` so the input height and the field chrome stay in sync.

> **Port note.** In this package the field height comes entirely from the wrapping `<tedi:form-field size>`. `size` on `<tedi:date-field>` is declared for API parity but emits no class of its own: `.tedi-date-field--small` has no rule in `dist/tedi.css`, and Angular only forwards the value to the form-field too.
