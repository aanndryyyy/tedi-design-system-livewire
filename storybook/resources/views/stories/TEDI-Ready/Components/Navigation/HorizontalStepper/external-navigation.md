Step-by-step validation — the user advances with `Edasi`/`Tagasi`.
Past steps render as `completed` and are clickable for back-navigation;
future steps are `disabled` so the user can't skip ahead from the header.

Advancing is the consumer's job in this port: output events aren't re-emitted
(CONVENTIONS.md §7.2), so the story shows the initial state and the buttons are
decorative. Wire `wire:click` / `x-on:click` on the buttons and on
`<tedi:horizontal-stepper-item>`, then re-render with the new `selected` /
`completed` / `disabled` flags.
