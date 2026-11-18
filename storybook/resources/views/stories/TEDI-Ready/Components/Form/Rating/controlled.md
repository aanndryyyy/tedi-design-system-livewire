<!-- Upstream documents two-way binding with `[(value)]`, `ngModel` and reactive forms, which have no Blade equivalent. -->
The radios are native, so `wire:model` (or Alpine's `x-model`, as here) on the rating is copied onto
every radio. Pass `:value` too, so the server render matches the bound value.
