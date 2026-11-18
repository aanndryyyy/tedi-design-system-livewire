# Port notes — `dock-panel-split-pane`

Stream: React `community/` DockPanel, SplitPane and Resizer.

## Decision reversal

The user explicitly asked for these React `community/` components. That reverses
the README decision in "From the React package" ("React's `community/` tree is
skipped wholesale …"). Update README and CONVENTIONS §13 as listed below.

## Feasibility gate (CONVENTIONS §13.1)

The gate was run twice:

- against the `node_modules` symlinked from the main checkout, which holds
  core **6.12.0**, even though `package-lock.json` pins 6.13.0 (that install is
  stale);
- against the core 6.13 sources in `.reference/core`.

Both runs give the same result.

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

## What was ported

| React | Blade | Notes |
|---|---|---|
| `community/components/dock-panel` `DockPanel` | `<tedi:dock-panel>` | Everything except `resizable` / `minWidth` / `minHeight` |
| `community/components/dock-panel` `DockPanelLayout` | `<tedi:dock-panel-layout>` | `panel` named slot |

Files:

- `resources/scss/components/layout/dock-panel/dock-panel.module.scss`, vendored verbatim, with its `@use` in `index.scss`
- `resources/views/components/dock-panel.blade.php`
- `resources/views/components/dock-panel-layout.blade.php`
- `resources/js/src/dock-panel.js`, registered in `tedi.js`
- `tests/Components/DockPanelComponentsTest.php` (19 tests)
- `tests/fixtures/matrices/dock-panel.blade.php`
- `tests/IntegrityTest.php`: a required-props entry for `dock-panel`
- `storybook/resources/views/stories/Community/Layout/DockPanel/`: 13 stories, `README.md` and 12 story `.md` files

Behaviour:

- Docked panel markup matches upstream attribute for attribute (`role="region"`,
  `data-placement/size/collapsed/resizable`, `--dock-panel-*` vars, h3/h4 title
  with `tabindex="-1"` and `text-small` while minimized).
- Collapse and restore, the close button (`closable`), `description`, the
  `header-actions` slot, the `footer` slot, presets and px/CSS dimensions,
  `max-width` / `max-height`, and `short-title` (ResizeObserver measurement).
- Mobile sheet presentation (`mobile` null/true/false). The content nodes move
  between the shells, so their state survives, as upstream's portals do.
- `<html data-tedi-dock-panel-sheet="minimized">`.
- `minimize-counter`.
- The callbacks are DOM events: `tedi:collapsed-change` `{collapsed}`,
  `tedi:close` and `tedi:presentation-change` `{presentation}`.
- After a Livewire morph, the server layout is restored and the client state is
  re-applied (`morph` / `morphed` hooks). A changed server `collapsed` is adopted,
  which gives the controlled mode.

## Divergences to document

1. **More than one root element (CONVENTIONS §6).** The component renders the
   docked panel, a `display: contents` sheet wrapper and the sheet spacer as
   siblings. Upstream returns a fragment, and the vendored layout CSS selects
   `.tedi-dock-panel-layout > .tedi-dock-panel[…]` and
   `> .tedi-dock-panel__sheet-spacer`, so a wrapper root would break the
   minimized rail. `$attributes` go on the docked panel. `class` and `style` are
   also forwarded to the sheet panel, as upstream does with `className` / `style`.
2. **The first render is always docked.** This is upstream's own behaviour, from
   `hasMounted`. With `mobile=true` the docked panel is visible until Alpine boots.
3. **The sheet stays where it is written.** It is not portalled to `body` (as in
   §11). `.tedi-dock-panel-layout { isolation: isolate }` therefore confines the
   sheet's z-index to the layout's stacking context.
4. **`defaultCollapsed` is folded into `collapsed`.** `collapsed` is the
   server-rendered state, and the client toggles it immediately. A different
   server value on a later render is adopted.
5. **Labels are a required `:labels` array** (`collapse`, `expand`, `close`), as
   upstream requires. React's labels map has no keys for them, so no lang keys
   are added.
6. **Toggle and close are Angular `tedi:button` icon-only buttons** named by
   `aria-label` + `title`. React's `Button` puts the name in a visually hidden
   `tedi-btn__text` span instead.
7. **The sheet uses the existing sheet port**: `tedi:sheet-content`, `-header`,
   `-body` and `-footer`, driven by `tediDockPanelSheet`. That is `tediSheet`
   with Escape, drag-dismiss and collapse rerouted to the panel. `sheet.js`
   itself is not modified. The composition overrides `hide`, `_onOpen` and
   `_onClose`, which are private to `sheet.js`.

## Story docs

- `README.md` is upstream's `componentDescription`, verbatim apart from two
  changes: a mapping note at the top (React props to Blade slots, props and
  events), and the `resizable` paragraph replaced by a not-ported note.
- `position.md` has one sentence changed: `defaultCollapsed` becomes `collapsed`.
- The Default story's argType descriptions for `collapsed`, `mobile`, `width`
  and `maxWidth` are reworded, not copied, because the Blade semantics differ
  (server-rendered state; no number-as-pixels control; no resizable default).
- Stories with choice buttons render each choice in its own `x-if`, because a
  Blade panel's props are server-rendered. Switching choices therefore
  remounts the content, where upstream keeps it.

## Tooling trap affecting every stream: symlinked `vendor/` tests the MAIN checkout

If `vendor/` is a symlink to the main checkout's, Composer's
`vendor/composer/autoload_*.php` resolve `$baseDir` through the symlink to the
**main checkout**. PHPUnit then loads the main repo's `src/TediServiceProvider.php`,
and with it the main repo's `resources/views`.

- A **new** component fails loudly: "Unable to locate a class or view".
- An **edit to an existing** component is silently not tested. The suite runs
  the main checkout's copy.

Other streams that symlinked `vendor/` and edited existing components may have
reported "green" against the wrong files.

The workaround used here is a real `vendor/` directory:

- `autoload.php`, `composer/` and `bin/` are **copied** from the main
  checkout's `vendor/`;
- every other entry is **symlinked**.

`vendor/bin/phpunit` then resolves the worktree's `src/` and views.
`node_modules` can stay a symlink.

Related: the main checkout's `node_modules` has `@tedi-design-system/core`
**6.12.0**, while `package-lock.json` pins **6.13.0**. Every stream's local
`dist/` build used 6.12.

## Convention-worthy finding (suggest a CONVENTIONS §8 note)

Alpine's merged data proxy writes an assignment to an undeclared property into
the **nearest ancestor scope that has that property**. `tediFormField.init()`
does `this._root = this.$el.closest(…)` without declaring `_root`. Inside the
dock panel, whose scope had a `_root`, that call overwrote the panel's root
element with the form field. The panel then wrote `data-collapsed` and its `id`
onto the `tedi-form-field`.

`dock-panel.js` now keeps all private state in a per-instance closure. Two
fixes are worth considering:

- a CONVENTIONS rule: private state is either declared in the returned object
  or kept in a closure, never assigned ad hoc on `this`;
- a separate fix to `form-field.js` (declare `_root: null`). That file is
  outside this stream, so it was not changed.

## Doc changes for the parent

### README.md

- "What's implemented": replace
  > `community/` and 23 from React, that is **189** in total.

  with
  > `community/` and 25 from React, that is **191** in total.

  The +2 are `dock-panel` and `dock-panel-layout`.
- "From the React package": replace "Eleven React-only components are ported:"
  with "Twelve React-only components are ported:", and add this row to its
  table:
  > | `community/dock-panel` | `<tedi:dock-panel>`, `<tedi:dock-panel-layout>` ⁸ |
- Same section: replace
  > React's `community/` tree is skipped
  > wholesale: it is the older generation Angular's `community/` also carries, and
  > the map-component suite in it is a domain-specific application, not a design
  > system.

  with something like:
  > React's `community/` tree is ported on request, component by component, under
  > the same token gate. `dock-panel` is ported. Its `resizable` mode is not, and
  > neither are `map-components/resizer` and `map-components/split-pane`: they need
  > `--drag-indicator-accent`, `--drag-indicator-button-background` and
  > `--drag-indicator-vertical-width`, which core does not ship. React defines them
  > in `community/styles/_map-component.variables.scss`, a global `:root` block that
  > also redefines core's button tokens.
- Subset table: add
  > | `<tedi:dock-panel>` | Docked panel, layout, collapse/restore, close, short title, dimensions, the mobile Sheet presentation with content preserved, minimizeCounter, and the three callbacks as `tedi:collapsed-change` / `tedi:close` / `tedi:presentation-change` | `resizable` / `minWidth` / `minHeight` (Resizer fails the token gate); renders sibling elements rather than one root; the sheet is not portalled to `body`; `defaultCollapsed` is folded into `collapsed` |
- "Docs pages": replace "All 87 components have a `README.md`; 188 stories
  have a per-story description" with "All 88 components have a `README.md`;
  200 stories have a per-story description". That is +1 README, plus 12 story
  descriptions; `Mobile` has none upstream.
- "Angular stories with no Blade equivalent": replace "661 stories across 87
  components are ported" with "674 stories across 88 components are ported"
  (+13).
- "Structure", in the `Community/` sentence: the community-only components with
  upstream stories now also include `Layout/DockPanel`, which is React's.
- "React stories" skipped table: add
  > | `DockPanel`: ResizeWidth, ResizeHeight | Need `resizable`, which renders React's community Resizer; its stylesheet fails the core-token gate |

  Also note that React `community/` stories keep their `Community/…` title
  verbatim (`Community/Layout/DockPanel`), as §12.2 does for Angular's.
- No skipped-stories rows need deleting.
- If README/ReadmeTest counts components or stories mechanically, the numbers
  above are the deltas. ReadmeTest passed unchanged in this branch.

### CHANGELOG.md (Unreleased)

- Added: `<tedi:dock-panel>` and `<tedi:dock-panel-layout>`, ported from React's
  `community/` DockPanel (docked panel, collapse/restore, close, short title,
  mobile Sheet presentation; callbacks as `tedi:collapsed-change`, `tedi:close`
  and `tedi:presentation-change` DOM events). `resizable` is not ported.
- Not ported: React community `Resizer` and `SplitPane`, because core lacks the
  `--drag-indicator-*` tokens their stylesheets use.

No Upgrading or breaking entry is needed; the change is additive only.

### CONVENTIONS.md

- §8: add `tediDockPanel` and `tediDockPanelSheet` to the list of modules, and
  add this row to the JS-layout table:
  > | `src/dock-panel.js` | `dockPanel`, `dockPanelSheet` | The community dock panel: collapse state, events, and the mobile presentation — moves the content/footer/action slot nodes between the docked panel and a `tediSheet`-based sheet (`dockPanelSheet` composes `sheet()` by property descriptors and reroutes Escape / drag-dismiss to the panel). Restores the server layout on Livewire `morph` and re-applies on `morphed`; private state lives in a closure, not on the Alpine object |
- §6: record the exception. A component whose upstream returns a fragment, and
  whose vendored CSS addresses the parts as direct children of a parent
  component, may render sibling roots. `tedi:dock-panel` is the case.
- §13: React's `community/` tree is an additive source on request (the
  reversal). Its stylesheets may depend on `community/styles/_map-component.variables.scss`
  tokens, so they fail the §13.1 gate. Story titles stay `Community/…` verbatim.

### dist/

`dist/` is not committed. Run `npm run build` before merging:
`IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet` and
`test_emitted_classes_exist_in_stylesheet` fail against the committed
`dist/tedi.css`, which lacks the dock-panel rules. With a fresh build, all
1442 tests pass.
