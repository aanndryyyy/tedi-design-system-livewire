Items with `:close-on-select="false"` keep the dropdown open after activation. Useful for multi-select checkbox menus where the user toggles several options in a row — e.g. a column-visibility chooser.

Angular pairs this with an `(itemSelect)` output that fires for both mouse click and keyboard activation, and skips disabled items. Outputs are not re-emitted in this package, so the checkbox states below are rendered server-side and the toggling is left to the consumer's own `wire:click` / `x-on:click` on the item.
