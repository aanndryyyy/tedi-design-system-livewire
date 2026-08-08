<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=4620-82860&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/4013b4-search" target="_blank">Zeroheight ↗</a><br />

Search wraps `tedi-form-field` + `input[tedi-text-field]` with an optional trailing button.

<!--
The Angular description ends with "Works with Reactive forms and
Template-driven forms". Those are Angular form APIs with no equivalent here;
`{{ $attributes }}` lands on the `<input>`, so `wire:model` binds directly
instead.
-->
Bind a value with `wire:model` — the attribute reaches the `<input>` itself.
