<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=3486-37618&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/25f281-text-area" target="_blank">Zeroheight ↗</a>

<!--
The Angular description ends with "Can be used with Reactive forms and with
Template-driven forms". Those are Angular form APIs with no equivalent here;
this port is a native `<textarea tedi-textarea>` and `{{ $attributes }}` lands
on it, so `wire:model` binds directly instead.
-->
Bind a value with `wire:model` — the attribute reaches the `<textarea>` itself.
