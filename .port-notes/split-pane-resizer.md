# Port notes — `split-pane-resizer`

Stream: React `community/components/map-components/resizer` and `split-pane`,
plus the DockPanel parts that the resizer blocked (`resizable`, `minWidth`,
`minHeight`, and the ResizeWidth and ResizeHeight stories). This stream
follows `dock-panel-split-pane.md`. Every delta below is relative to that
stream's doc changes, applied first.

Source: React `react-21.0.0-rc.17` (map-components last touched in `a1d8e34b`,
17.11.2026). Core 6.13.0.

## The unblock: `resources/scss/_map-component-tokens.scss`

The integrator decided on this file. It is not vendored and defines exactly
three tokens with React's values:

| Token | `:root` | `.tedi-theme--dark` |
|---|---|---|
| `--drag-indicator-accent` | `var(--general-border-accent)` | `var(--general-border-accent)` |
| `--drag-indicator-button-background` | `var(--tedi-primary-200)` | `var(--tedi-alpha-80)` |
| `--drag-indicator-vertical-width` | `var(--tedi-dimensions-03)` | (not in React's dark block) |

- **Dark-theme selector.** React scopes its dark block as `:root.dark-mode`.
  Core scopes its dark theme as `.tedi-theme--dark`, and the file follows core.
- **The accent is restated in the dark block** even though its value is the
  same. A `var()` inside a custom property resolves on the element that
  declares it, so a `.tedi-theme--dark` subtree would otherwise inherit the
  value computed against the light `:root`.
- **The breakpoint overrides are not replicated.** React restates
  `--drag-indicator-vertical-width` at `width <= 62rem` and `<= 48rem` (lines
  212 and 265) with the same value.
- **The gate was run mechanically.** Every `var(--…)` in both vendored modules
  was grepped against the built `dist/tedi.css`, and all 12 are defined.
  Core 6.13 defines `--general-border-accent`, `--tedi-primary-200`,
  `--tedi-alpha-80` and `--tedi-dimensions-03`.
- **The header comment says to delete the file** once core ships the tokens,
  and to drop its `@use` from `index.scss`.
- **Placement in `index.scss`.** `@use 'map-component-tokens'` sits right
  before `resizer.module`, after core.

## What was ported

| React | Blade | JS |
|---|---|---|
| `map-components/resizer` `Resizer` | `<tedi:resizer>` | `src/resizer.js`, registered as `tediResizer` |
| `map-components/split-pane` `SplitPane` | `<tedi:split-pane>` (`first` / `second` named slots) | `src/split-pane.js`, registered as `tediSplitPane` |
| `community/dock-panel` `resizable`, `minWidth`, `minHeight` | `resizable`, `min-width`, `min-height` on `<tedi:dock-panel>` | `src/dock-panel.js` |

Files:

- Vendored verbatim:
  - `resources/scss/components/layout/resizer/resizer.module.scss`
  - `resources/scss/components/layout/split-pane/split-pane.module.scss`
  - The group is `layout`, per §12.1.
- New:
  - `resources/scss/_map-component-tokens.scss`
  - `resources/views/components/resizer.blade.php`
  - `resources/views/components/split-pane.blade.php`
  - `resources/js/src/resizer.js`
  - `resources/js/src/split-pane.js`
- Edited:
  - `resources/views/components/dock-panel.blade.php`
  - `resources/js/src/dock-panel.js`
  - `resources/js/tedi.js` (the header list and two registrations)
  - `resources/scss/index.scss`
- Lang: `splitPaneResize` was added to the React block in `en`, `et` and `ru`,
  copied verbatim from `labels-map.ts`. `close` already existed.
- Tests:
  - New: `tests/Components/MapComponentsTest.php` (16 tests).
  - Five resizable tests added to `DockPanelComponentsTest.php`.
  - New fixture: `tests/fixtures/matrices/map-components.blade.php`.
  - Resizable panels added to `fixtures/matrices/dock-panel.blade.php`.
  - IntegrityTest has a required-props entry `split-pane => direction="horizontal"`.
- Stories (§13.4: the titles are verbatim, including the space in `Map components`):
  - `Community/Map components/Resizer/`: five stories and `README.md`.
  - `Community/Map components/SplitPane/`: five stories and `README.md`.
  - `Community/Layout/DockPanel/`:
    - `resize-width` (order 11) and `resize-height` (order 12), each with a
      `.md` from upstream's `description.story`. Orders 11 and 12 were
      already reserved, so no other story was renumbered.
    - Its README gets upstream's resizable paragraph back, and the "not in
      this port" note is removed.
    - The Default story gets the `resizable`, `minWidth` and `minHeight`
      argTypes, and upstream's `maxWidth` / `maxHeight` wording.

## Behaviour

### Resizer

- **Markup** is upstream's, attribute for attribute:
  - the inline style is `width: Npx; height: auto|Npx; position: relative; display: block`;
  - the unprefixed `direction-*` class;
  - `data-show-indicator`;
  - the indicator tab only while `show-indicator` is false. That inversion is
    upstream's.
- **Input is mouse only, as upstream.** There is no keyboard, touch or ARIA
  support, and none was invented. Document listeners are added on mousedown
  and removed on mouseup. Upstream keeps them for the component's life and
  ignores moves when not resizing, which behaves the same.
- **The size is client state.** It is re-applied after a Livewire morph.
- **No events**, because upstream has no callbacks.

### SplitPane

- **Markup.** Upstream's, including the close button as a sibling of the
  separator (#1156). `data-testid` attributes are not ported.
- **Input.**
  - Mouse and touch drag across the document.
  - Keyboard: arrows move by 2 (Left/Right horizontal, Up/Down vertical), and
    Home/End jump to the limits.
  - `initial-ratio` is clamped on the server.
- **Events.**
  - `tedi:ratio-change` `{ratio}` fires when a drag ends (even without
    movement, as upstream's commitRatio) and after every handled key.
  - `tedi:close` is shown when `closable`.
- **Morph.** The client ratio (both `flex-grow` values and `aria-valuenow`) is
  re-applied after a Livewire morph. `initial-ratio` is initial-only.

### DockPanel `resizable`

- **Structure.** `__resize-layout` > `__resize-frame` > `tedi:resizer.tedi-dock-panel__resizer` + the panel.
- **Server render.** The server renders upstream's first commit, the
  measurement state:
  - `__resize-measurement`;
  - the panel expanded, with `data-resizable="false"`;
  - the resizer with `display: none`;
  - the layout with `visibility: hidden` when collapsed.
- **Requested state.** `data-tedi-dock-panel-collapsed` on the panel carries
  the requested state (resizable only). The controlled-mode morph check reads
  it, because `data-collapsed` is the measurement state's `false`.
- **Measurement.** JS measures the panel and its workspace (the layout's
  `offsetParent`), applies upstream's `getResizeLimits` (8% / 92% defaults,
  capped by the workspace, max wins), starts the resizer at the clamped
  measured size, then applies the real collapsed state and the layout's
  height / max-height.
- **Workspace resize.** A ResizeObserver on the workspace updates the limits.
  It clamps the saved size only when it is outside them, as upstream's
  remount with `key` does.
- **Sheet round trip.** Upstream unmounts the Resizer, and the panel
  re-measures on its return to docked. The port mirrors that, so the size
  starts again from the configured value.
- **Morph.** `_applyResize()` restores the measured state over the server's.
- **`maxWidth`.** It no longer emits `--dock-panel-max-width` while resizable,
  as upstream does (`!resizable &&`). `maxHeight` still emits its variable.

## Divergences to document

1. **Icons are `<span>`s, not `tedi:icon`.** This is new; it needs a §13 note
   and has no precedent.
   - Both vendored modules style the icon as `… > span`, which is React's
     `Icon` element. The rules rotate `drag_handle` on the left/right handles
     and on the indicator, and colour the handle icons.
   - `tedi:icon` renders `<tedi-icon>`, which none of those rules match.
   - So the icon is a raw `<span>` carrying exactly the class list, style and
     ARIA that `tedi:icon` emits for the same props (`size` 18 →
     `--_tedi-icon-size: var(--icon-03)`, `color` primary/brand).
   - Every `.tedi-icon*` rule in the Angular icon sheet is class-based, so
     nothing is lost.
2. **The split-pane close button** is an Angular `tedi:button variant="secondary"`
   icon-only, named by `aria-label`. React's `Button` puts the name in a
   visually hidden text span. This is the same as dock-panel divergence 6.
3. **DockPanel `resizable` and `style`.** Upstream moves the consumer's
   `zIndex` and `--dock-panel-gap` / `-rail-size` / `-layer` from `style` onto
   the resize layout. In the port the consumer's `style` stays on the panel,
   unparsed. This is documented in the docblock and the story README.
4. **Without JavaScript, a resizable panel stays in the measurement state.**
   It renders expanded, or invisible when `collapsed`. That is upstream's own
   first commit.
5. **Resizer stories use `tedi:empty-state`** where upstream uses the
   deprecated community `Placeholder`, which is not ported. Its own
   deprecation notice points to EmptyState. This is noted in the Resizer
   story README.

## Left out

- **Nothing is skipped.** All 5 Resizer stories, 5 SplitPane stories and
  both DockPanel Resize stories are ported.
- **SplitPane and Resizer have no per-story descriptions upstream**, so no
  `.md` files were written.
- **`split-pane.spec.tsx` behaviour** (drag, keys, events, clamping) is
  covered by the browser check below, not by PHPUnit. The repo has no JS test
  runner. The markup cases are in `MapComponentsTest`.

## Verification

- **Tests.** `npm run build` then `vendor/bin/phpunit`: **OK, 1512 tests, 5634 assertions**.
  The baseline in this worktree before any change was 1493.
- **Worktree setup.** `vendor/` is a real directory: `autoload.php`,
  `composer/` and `bin/` are copied, everything else is symlinked, and the
  autoloader resolves to the worktree's `src/`. `node_modules` is a symlink to
  the main checkout, which now has core **6.13.0**.
- **Browser** (Chromium preview tab). The test page was rendered from the
  components into static HTML, with built `dist/tedi.css` / `dist/tedi.js`
  and Livewire's bundled Alpine. It was served from the scratchpad by
  `php -S` and stopped afterwards. Synthetic events were driven with
  `preview_evaluate`.
  - **Resizer.**
    - The handle is 4px wide, so `--drag-indicator-vertical-width` resolves.
    - The accent background shows with `show-indicator`.
    - The right-handle icon is rotated 90°, and the top indicator −90°.
    - A drag of +100px gives 350 → 450px, and dragging far clamps to 600px.
    - `body` `user-select` is `none` during the drag and cleared afterwards.
    - A move after mouseup does nothing.
    - Top handle: a drag of −100px gives 118 → 218px, and the clamp gives
      back 118px.
  - **SplitPane.**
    - Dragging to 30% gives `flex-grow` 30/70, `aria-valuenow` 30, and a
      first pane 239px wide.
    - Dragging past the minimum clamps to 20.
    - Keys: ArrowRight gives 22, End 80, Home 20. ArrowUp is ignored when
      horizontal.
    - Touch start/move/end gives 60.
    - Events received: `[30, 20, 22, 80, 80, 20, 60, "close"]`.
    - `:focus-visible` gives a solid outline.
  - **DockPanel `resizable`** (right, `width="md"`, `height="70%"`, workspace 998×574).
    - It measures to 499px (50%) and leaves the measurement state, with the
      layout at 70% height.
    - The handle sits on the panel's left edge.
    - A drag of −200px gives 699px, and dragging far clamps to 918px (92%).
    - Minimizing gives a 54px rail of full height. Restoring gives back 918px.
    - Shrinking the workspace to 598px clamps the size to 550px.
      Restoring the workspace keeps 550px.
    - Bottom placement, `height="auto"`: it measures to 186px (the content
      height), and a −100px drag gives 286px.
    - With the automatic `mobile`, the viewport went 1169 → 500px (sheet) →
      1100px (docked). The panel re-measured to 50% (549 of 1098px), and the
      events were `["sheet", "docked"]`.
- **Not verified in a browser.**
  - The Livewire morph restore: there was no Livewire component on a static
    page. That code path is the `morphed` hook re-running the same
    `_apply()` / `configure()` that the checks above exercise.
  - The dark theme token values.
  - Blast/Storybook generation of the `Map components` directory. It was
    not run, because `blast:generate-stories` would write into the main
    checkout's `vendor/`. Blast derives the title as `ucwords($path, '/')`
    and the view name from the path, so the space should pass through. Run
    `storybook/start.sh` once to confirm.
- **Story compile check.** All 25 DockPanel / Resizer / SplitPane story files
  were rendered with their `@storybook` args through `Blade::render` (a
  temporary test, since removed), and none failed.

## Doc changes for the parent

All deltas are on top of `dock-panel-split-pane.md`'s.

### README.md

- "What's implemented": React 25 → **27**, total 191 → **193** (`resizer`,
  `split-pane`).
- "From the React package": add two to the count of React-only components
  ported ("Twelve" → "Fourteen", if the sentence counts components), and add
  this row:
  > | `community/map-components/resizer`, `community/map-components/split-pane` | `<tedi:resizer>`, `<tedi:split-pane>` |
- Same section: replace the community paragraph that dock-panel's notes
  proposed with something like:
  > React's `community/` tree is ported on request, component by component,
  > under the same token gate. `dock-panel`, `map-components/resizer` and
  > `map-components/split-pane` are ported. The last two need
  > `--drag-indicator-accent`, `--drag-indicator-button-background` and
  > `--drag-indicator-vertical-width`, which core 6.13 does not ship. Until it
  > does, `resources/scss/_map-component-tokens.scss` defines exactly those
  > three with React's values. React's own `_map-component.variables.scss` is
  > not loaded, because it also redefines core's button tokens.
- Subset table:
  - Dock-panel row: drop "`resizable` / `minWidth` / `minHeight` (Resizer
    fails the token gate)". Add: "with `resizable`, the consumer's `style`
    stays on the panel (upstream moves `zIndex` / `--dock-panel-gap|rail-size|layer`
    to the resize layout); without JS a resizable panel stays in its
    measurement state". Add "resizing (resizable, min/max limits, workspace
    clamping)" to its implemented column.
  - Add:
    > | `<tedi:resizer>` | Drag to resize width or height between limits, indicator and handle styles | Mouse only, as upstream; icons are `<span>`s with `tedi:icon`'s classes (the vendored CSS targets `> span`) |
    > | `<tedi:split-pane>` | Two panes, drag/touch/keyboard divider, ratio limits, highlighted side, close button; `onRatioChange` / `onClose` as `tedi:ratio-change` / `tedi:close` | Close button is an icon-only `tedi:button` named by `aria-label` |
- "Docs pages": 88 → **90** components with a `README.md`, and 200 → **202**
  stories with a per-story description (`resize-width.md`, `resize-height.md`;
  Resizer and SplitPane have none upstream).
- "Angular stories with no Blade equivalent": 674 → **686** stories across
  88 → **90** components (+5 Resizer, +5 SplitPane, +2 DockPanel).
- "React stories" skipped table: **delete** the row
  "`DockPanel`: ResizeWidth, ResizeHeight" that dock-panel's notes added.
- "Structure", `Community/` sentence: add `Map components/Resizer` and
  `Map components/SplitPane` (React's titles, verbatim, with the space).
- ReadmeTest passed unchanged in this branch. If it counts mechanically, use
  the deltas above.

### CHANGELOG.md (Unreleased)

- Added: `<tedi:resizer>` and `<tedi:split-pane>`, ported from React's
  community map components. Resizer: mouse-drag width/height between limits.
  SplitPane: draggable, touch and keyboard divider, with `tedi:ratio-change` and
  `tedi:close` events.
- Added: `<tedi:dock-panel>` `resizable`, `min-width` and `min-height`
  (drag the workspace-facing edge; 8–92% default limits; clamped on
  workspace resize).
- Added: `resources/scss/_map-component-tokens.scss`, a temporary definition
  of `--drag-indicator-accent`, `--drag-indicator-button-background` and
  `--drag-indicator-vertical-width` until core ships them.
- Remove the dock-panel stream's "Not ported: React community Resizer and
  SplitPane" bullet, and its "`resizable` is not ported" clause.

No breaking change. With `resizable` unset, `maxWidth` behaves as before.

### CONVENTIONS.md

- §8: add `tediResizer` and `tediSplitPane` to the module list, plus rows:
  > | `src/resizer.js` | `resizer` | The community resizer: mouse drag of the wrapper's inline width/height between limits; re-applies the size after a Livewire morph. `configure()` / `size()` let `tediDockPanel` drive it |
  > | `src/split-pane.js` | `splitPane` | The community split pane: mouse/touch drag and keyboard on the `role="separator"`, live `flex-grow` / `aria-valuenow`, `tedi:ratio-change` / `tedi:close`; re-applies the ratio after a Livewire morph |

  Update the `src/dock-panel.js` row to add "and resizable mode: measures the
  panel and workspace, drives `tediResizer` with upstream's limits, clamps on
  workspace resize".
- §13.1: a sentence after the sheet example. When a React stylesheet needs
  tokens that only React's global `community/styles/_map-component.variables.scss`
  defines, a small non-vendored token file may define exactly those tokens
  with React's values, scoped as core scopes themes (`:root` /
  `.tedi-theme--dark`), with a header saying to delete it once core ships
  them. `_map-component-tokens.scss` is the one case. Loading React's file
  itself is still forbidden, because it redefines core tokens.
- §13 (new subsection, or §13.3's neighbour): **React icons are `<span>`s.**
  When a vendored React module styles an icon through `> span` (or another
  element selector that matches React's `Icon`), render the icon as a
  `<span>` carrying exactly `tedi:icon`'s class list, style and ARIA for the
  same props, not as `tedi:icon` (whose `<tedi-icon>` those rules miss).
  `tedi:resizer` and `tedi:split-pane` are the cases. The Angular icon sheet's
  `.tedi-icon*` rules are class-based, so nothing is lost.
- §12/§13 story titles: React community titles may contain spaces
  (`Community/Map components/…`); keep them verbatim.

### dist/

`dist/` is not committed. Run `npm run build` before merging, because
IntegrityTest needs the resizer and split-pane rules in `dist/tedi.css`.
