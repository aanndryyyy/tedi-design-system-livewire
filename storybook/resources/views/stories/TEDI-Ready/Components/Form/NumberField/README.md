<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/90c693-number-field" target="_blank">Zeroheight ↗</a>

<!--
The Angular description ends "Can be used with Reactive forms and with
Template-driven forms" — Angular forms APIs this package does not have, so per
CONTRACT.md §6 that sentence is replaced rather than ported. In Blade the
`<input type="number">` is the attribute-merge target, so `wire:model` binds the
value directly; the increment/decrement buttons are template-only and take their
handlers through `decrement-attributes` / `increment-attributes`.
-->
Bind the value with `wire:model` on the component; it lands on the underlying
`<input type="number">`. The buttons carry no behaviour of their own — attach
`wire:click` (or `x-on:click`) via the `decrement-attributes` /
`increment-attributes` props.
