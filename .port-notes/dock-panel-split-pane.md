# Port notes — `dock-panel-split-pane`

Stream: React `community/` DockPanel, SplitPane and Resizer.

## Decision reversal

The user explicitly asked for these React `community/` components. That reverses
the README decision in "From the React package" ("React's `community/` tree is
skipped wholesale …"). Update README and CONVENTIONS §13 as listed below.

## Feasibility gate (CONVENTIONS §13.1), installed core 6.12.0

| Component | Result | Missing tokens |
|---|---|---|
| `community/components/dock-panel` | **PASS** | none. All 12 `--dock-panel-*` are defined in the module itself. Every other `var(--…)` is in core 6.12 |
| `community/components/map-components/resizer` | **FAIL, blocked** | `--drag-indicator-accent`, `--drag-indicator-button-background`, `--drag-indicator-vertical-width` |
| `community/components/map-components/split-pane` | **FAIL, blocked** | `--drag-indicator-accent`, `--drag-indicator-vertical-width` |

The three `--drag-indicator-*` tokens are not in core: not in the installed 6.12,
and not in the reference clone (`core-6.13.0-1-gface373b`), which only has
`--drag-indicator-light`. They come from React's own
`src/community/styles/_map-component.variables.scss`, a top-level `:root` block
that also redefines core-owned tokens (`--button-primary-*`, `--button-neutral-*`,
`--button-neutral-inverted-*`, …).

The failure is not cosmetic. `--drag-indicator-vertical-width` sizes the
resizer's handle and the split-pane's separator (`flex: 0 0 var(--…)`), so
without it the drag target has zero width.

Per the brief, no vendored SCSS was edited to get past the gate. Neither
component is ported.

### Possible unblock paths (the parent decides)

1. **Wait for core.** If core starts shipping `--drag-indicator-accent`,
   `--drag-indicator-button-background` and `--drag-indicator-vertical-width`,
   both pass. Core already has `--drag-indicator-light`.
2. **A non-vendored glue file that defines only those three tokens.** The values
   would be copied from `_map-component.variables.scss`: `var(--general-border-accent)`,
   `var(--tedi-primary-200)` and `var(--tedi-dimensions-03)`, plus its dark-theme
   and responsive overrides. That is a new kind of exception to §13, so it is
   the maintainer's call.

Two other paths are ruled out:

- **Vendoring `_map-component.variables.scss` globally.** It would restyle every
  Angular button.
- **Scoping it under §13.7.** That is also ruled out, because §13.7 rule 4
  forbids a top-level `:root` block in a scoped module.

### What the brief assumed but upstream does not have

- **Resizer accessibility.** Upstream's `Resizer` is mouse-only: it has
  `mousedown`/`mousemove`, and no keyboard, pointer, touch or ARIA support. Only
  `SplitPane` has `role="separator"` with `aria-valuenow` and keyboard handling.
  A port of the resizer should not invent them.
- **Lang keys.** None are added. DockPanel takes its labels from the consumer
  (`labels: { collapse, expand, close }` is a required prop upstream), and
  `labels-map.ts` has no DockPanel keys. React's one relevant key,
  `splitPaneResize`, belongs to the blocked SplitPane.

### Consequence for DockPanel

DockPanel's `resizable` mode renders upstream's `Resizer`, so it is blocked with
it. DockPanel is ported **without** `resizable`, `minWidth` and `minHeight`. The
`ResizeWidth` and `ResizeHeight` stories are skipped. `maxWidth` and `maxHeight`
are ported, because without `resizable` they only set
`--dock-panel-max-width` / `--dock-panel-max-height`. The vendored stylesheet
still contains the `__resize-*` rules verbatim, and they are dead until the
resizer ports.
