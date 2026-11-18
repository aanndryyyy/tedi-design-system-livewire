<!--
Upstream's description documents Angular reactive forms (`FormControl`,
`Validators.required`, the `rejectedFiles` error, touched / dirty), which this
package does not have, so per CONTRACT.md §6 it is replaced with a note.
-->
The Livewire equivalent of a bound form control: `wire:model` uploads what the
field accepts, and the component validates it on the server (`required`,
`extensions:pdf,txt`, `max:1024`). With `keep-rejected-files`, files the field
rejects itself stay listed as invalid but are never uploaded. "Reset form"
resets the property and the validation, and the field clears with it — the
rejected files and the rejection summary too.

The round trip needs the host app's `web` middleware (a session for the CSRF
token). Blast registers its preview routes without it, so this Storybook's app
adds it (`storybook/bootstrap/app.php`). That works in the deployed
Storybook, which the app serves itself. In the local `:6006` dev server the
request is cross-origin and has no session, so it fails there.
