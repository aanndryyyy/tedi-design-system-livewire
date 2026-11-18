<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/012bbe-file-upload" target="_blank">Zeroheight ↗</a>

### Angular stories not ported

| Angular story | Why |
|---|---|
| `DiscardingRejectedFiles` ("Validation: discarding rejected files") | Selects a file on load and shows the field rejecting it with its own `accept` / `maxSize` checks. That selection pipeline is not ported: the list is server state, so validate on the server and pass the message as `error`. |
| `KeepingRejectedFiles` ("Validation: keeping rejected files") | The same rejection, kept in the list by `keepRejectedFiles`, which is not ported for the same reason. A file the server marks `isValid => false` renders as the same danger tag (see **Multiple** and the component's tests). |
| `WithValidator` | Passes a JavaScript `validator` callback. Not ported: a Blade prop cannot carry a client-side function, and validation is the server's. |
| `ControlledClearing` | Two-way `[(files)]` binding to a signal the story clears from outside. Here `files` is rendered from server state, so clearing it is an ordinary Livewire property change. |
| `WithReactiveForms` | Angular reactive forms (`formControl`, `Validators.required`, the `rejectedFiles` error, touched / dirty) — use `wire:model` and server validation instead. |
