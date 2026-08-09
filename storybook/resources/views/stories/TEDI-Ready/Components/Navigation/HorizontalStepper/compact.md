Collapsed — only indicators plus the selected step's label are shown.
Use `:compact="true"` for always-on, or pass a breakpoint (e.g. `compact="md"`)
to collapse only below that viewport width.

Angular's story adds click-to-jump navigation via the `stepSelect` output.
Output events aren't re-emitted in this port (CONVENTIONS.md §7.2), so the
active step is fixed here; a consumer wires `wire:click` / `x-on:click` on
`<tedi:horizontal-stepper-item>` and re-renders with the new `selected` /
`completed` flags.
