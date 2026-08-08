Persistent field states. The success/error states come from a `tedi-feedback-text` with `type="valid"`/`"error"`, which the surrounding `tedi-form-field` reflects on the input border.

> **Port note.** Angular drives the disabled row from the reactive form control (`ControlValueAccessor`), which is not ported (CONVENTIONS.md §7). Here the same state is set declaratively: `:input-disabled="true"` on the field, and `:disabled="true"` on the wrapping `<tedi:form-field>` for the chrome.
