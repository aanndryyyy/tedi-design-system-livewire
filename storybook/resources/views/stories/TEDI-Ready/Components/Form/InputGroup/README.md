<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY?node-id=4968-94396&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/18b6b5-input-group" target="_blank">Zeroheight ↗</a>

InputGroup is a flexible wrapper that composes a form control with prefixes
and suffixes. Project a `label[tedi-label]`, a control (`tedi-form-field`,
`tedi-select`, …), optional `[tediInputGroupPrefix]` / `[tediInputGroupSuffix]`
addons, and an optional `tedi-feedback-text`.

<!--
Angular's description goes on: "The projected label names a native control
through `[for]`. `tedi-select`'s trigger is not a labelable element, so give the
label an `id` and pass it to the select's `ariaLabelledby`". That applies to
Angular's combobox only. This port's select is a native `<select>`, which a
`<label for>` names directly, so the paragraph is left out.
-->
