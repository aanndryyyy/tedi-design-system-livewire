<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=6060-65779&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/328d11-text-field" target="_blank">Zeroheight ↗</a>

<!--
The Angular description ends with "Can be used with Reactive forms and with
Template-driven forms". Those are Angular form APIs with no equivalent here;
this port is a native `<input tedi-text-field>` and `{{ $attributes }}` lands
on it, so `wire:model` binds directly instead.
-->
Bind a value with `wire:model` — the attribute reaches the `<input>` itself.
