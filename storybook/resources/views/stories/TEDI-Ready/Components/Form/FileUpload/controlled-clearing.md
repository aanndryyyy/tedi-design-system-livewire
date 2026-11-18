<!--
Upstream's description is about Angular's two-way `[(files)]` binding ("bind
`files` two-way … a one-way binding seeds the field once and never reaches it
again"). That is not true in Blade, where `:files` re-renders on every request,
so per CONTRACT.md §6 it is replaced with a note.
-->
The list is a Livewire property rendered through `:files`: `wire:model`
uploads what the field accepts, and the tag remove buttons and "Clear files"
change the property, so the field follows whichever side changes it. Emptying
the list from outside also clears the field's own state.

The round trip needs the host app's `web` middleware (a session for the CSRF
token). Blast registers its preview routes without it, so this Storybook's app
adds it (`storybook/bootstrap/app.php`).
