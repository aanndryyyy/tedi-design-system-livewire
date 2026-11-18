Bind `files` two-way when the parent changes the list itself — after a
successful upload, say. A one-way binding seeds the field once and never
reaches it again.

<!--
Upstream's text is about Angular's two-way `[(files)]` binding. In Blade the
list is a Livewire property rendered through `:files`, so "binding it two-way"
means the component owns it: `wire:model` uploads the selection, the tag remove
buttons and "Clear files" change the property, and the field follows. The
Livewire round trip needs the host app's `web` middleware (a session for the
CSRF token); Blast's preview routes run without it, so in this Storybook the
buttons answer 419. The client-side pipeline works either way.
-->
