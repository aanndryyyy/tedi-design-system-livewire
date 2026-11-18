# Changelog

## Unreleased

### Previously unported upstream features

Ports what the Angular 10 sync below left out, plus React's community
dock panel, resizer and split pane. **Breaking:** `number-field`. `file-upload`'s selection behaviour
also changes; the README's "Upgrading" section covers both.

`number-field`

- **BREAKING** the step buttons work (`tediNumberField`): ±step, clamped to
  min/max, `input` + `change` fired so `wire:model` follows, live disabled /
  invalid state, the `numberField.quantityUpdated` announcement and wrapper
  click-to-focus. A `wire:click` that stepped the value through
  `increment-attributes` / `decrement-attributes` now double-steps: remove it.

`file-upload`

- The client-side selection pipeline: `accept` / `max-size` / `validator`
  checks, duplicate skipping, appending for `multiple`, `keep-rejected-files`
  and the rejection summary run in `tediFileUpload` before `wire:model`
  uploads. New `validator` (a JavaScript expression) and `keep-rejected-files`
  props.
- Storybook: the five previously skipped stories (Validation: Discarding /
  Keeping Rejected Files, With Validator, Controlled Clearing, With Reactive
  Forms). The last two mount Livewire components.

`toast`, `toast-container`

- New: `tedi:toast-container`, upstream's `tedi-toast-container` plus
  `ToastService`. Place it once in the layout, then show toasts with
  `TediToast.info|success|warning|danger(title, content, options)` /
  `TediToast.show(config)` / `TediToast.close(id)`, or from Livewire with
  `$this->dispatch('tedi-toast', title: …, type: …)`. It provides the four
  positions, the `duration` auto-close with progress bar, pause on hover and
  focus (`pauseOnHover`), the slide-in/out animations, the close button, and
  screen-reader announcements through the shared announcer regions
  (`role` status → polite, alert → assertive, none → silent). Server-rendered
  `tedi:toast`s in its slot are adopted on load. Runs in the new
  `tediToastContainer` behaviour.
- `tedi:toast`: `role`, `duration` and `pauseOnHover` are live inside a
  container. New `position` prop (Blade extension). The toast now emits
  `data-duration`, `data-role`, `data-pause-on-hover` and, when set,
  `data-position`.
- Storybook: Positions, HoverBehavior, CustomTimerForAutoclose,
  PersistentToast and WCAGCompliance are ported, and the Docs page's Usage
  section is restored. Default and WithIcon are upstream's button demos again.

`table`

- The column filter popover (Angular 10 `filterable` + `filterTemplate`). A
  column with `filterable` (`true`, or `['popoverWidth' => …, 'actions' => […]]`)
  and an Htmlable `filterTemplate` gets the `filter_alt` header button in a
  `tedi:popover` (`.tedi-table__filter-popover`). `filterActive` selects and
  fills the button. New table props `filter-popover-width` (default `small`)
  and `filter-actions` (default `['clear', 'apply']`, plus `cancel`). Each
  footer button runs the column's `filterActionAttributes[action]` (e.g.
  `wire:click`), dispatches a bubbling `tedi-table:filter-action` event with
  detail `{column, action}`, and closes the popover. `filterTriggerAttributes`
  reaches the trigger. A column with only `filterable` renders as before. The
  small-viewport filter modal is not ported.
- Storybook: Filters and FilterActions are ported, and SortableCustomHeader
  gains its upstream filter popover.

`date-field`

- The modal branch (Angular `date-field-modal`). New props: `modal`
  (`true` | `false` | `sm`/`md`/`lg`/`xl`, a breakpoint resolved client-side by
  the new `tediDateField` behaviour), `calendar-full-width`, `draft`, and an
  inert `fullscreen` (upstream styles it only on the service-mode modal
  dialog, which is not ported). Confirm dispatches `tedi-date-field-confirm`;
  Cancel, Escape, the backdrop and the close button dispatch
  `tedi-modal-close`. `modal` defaults to `false`, whose output is unchanged;
  `modal`, `fullscreen`, `draft` and `calendar-full-width` no longer leak onto
  the root as stray attributes.
- Storybook: new DateField · Mobile Modal; Default gains the `modal`,
  `fullscreen` and `calendarFullWidth` controls.

`dock-panel`

- New: `tedi:dock-panel` and `tedi:dock-panel-layout`, ported from React's
  `community/` DockPanel on request: docked panel,
  collapse/restore, close, short title, dimensions, `minimize-counter` and the
  mobile Sheet presentation; the callbacks are the DOM events
  `tedi:collapsed-change`, `tedi:close` and `tedi:presentation-change`. Runs in
  `tediDockPanel` / `tediDockPanelSheet`. `resizable`, `min-width` and
  `min-height`: drag the workspace-facing edge, 8–92% default limits, clamped
  on workspace resize.
- Storybook: `Community/Layout/DockPanel`, 15 stories.

`resizer`, `split-pane`

- New: `tedi:resizer` and `tedi:split-pane`, ported from React's community
  map components. Resizer: mouse-drag width/height between limits (`tediResizer`).
  SplitPane: draggable, touch and keyboard divider, with `tedi:ratio-change`
  and `tedi:close` events (`tediSplitPane`). New lang key `splitPaneResize`.
- `resources/scss/_map-component-tokens.scss`: a temporary definition of
  `--drag-indicator-accent`, `--drag-indicator-button-background` and
  `--drag-indicator-vertical-width` until core ships them.
- Storybook: `Community/Map components/Resizer` and `…/SplitPane`, five
  stories each.

Fixes

- Alpine behaviours declare their private fields. An undeclared property
  written from a method lands on whichever enclosing `x-data` already has that
  key: `tediFormField`'s `_root` overwrote the dock panel's root element.
  Fixed in `form-field`, `dropdown`, `overlay`, `process-timeline` and
  `sheet`.
- Storybook: the app adds the `web` middleware to Blast's
  `/storybook_preview` routes (`storybook/bootstrap/app.php`), so stories that
  mount Livewire components no longer answer 419.

### Upstream sync (Angular 10.0.0-rc.21, React 21.0.0-rc.17, core 6.13.0)

Re-synced against Angular 10.0.0-rc.21 (`6ecb1ee5`), React 21.0.0-rc.17
(`917b2ce8`) and core 6.13.0 (`face373b`), recorded in the README baseline.
**Breaking changes**; the README's "Upgrading" section lists them per component.

- **BREAKING** `table-of-contents` / `-collapsible`: `bordered` → `divided`
  (class `tedi-table-of-contents--divided`), as upstream, with no alias.
- **BREAKING** the accessible-name series (upstream #888–#900): closing-button
  renders its label once; alert's live region moves to `__body` and the close
  button is described by the title; label drops the ", Required field" text;
  search's landmark is unnamed by default and gains `hide-label`; carousel,
  pagination, number-field, table, calendar day grid, progress-bar and sidenav
  shorten or stabilise their labels. Several translation keys are removed or
  reworded.
- **BREAKING** `file-upload` is re-ported from Angular 10's new TEDI-Ready
  file-upload (CONVENTIONS.md §13: Angular is the source wherever it has the
  component). New markup and props; `label`, `helper`, `has-clear-button`,
  `announcement` and `required` are gone. The add button, label naming and
  screen-reader announcements run in the new `tediFileUpload` behaviour. The
  client-side selection pipeline is listed under "Previously unported upstream
  features" above.
- New: `tedi:rating` (Angular TEDI-Ready Rating; native radios, so
  `wire:model` binds it, with the hover preview in `tediRating`) and the
  community `tedi:process-timeline` (`-item`, `-title`, `-description`; a
  horizontal timeline that overflows turns vertical in `tediProcessTimeline`).
- `table`: `rowHeader` columns (`<th scope="row">`), a column `label`, an
  icon-only sort button after a custom header, per-row `selectionLabel`, and
  the scoped stylesheet that keeps a nested table's look its own. New stories
  Sortable Custom Header and Nested Table.
- `calendar`: `full-width` and the Full Width Sizes story.
- `popover-content`: `padding` on the card scale.
- `time-field`: the input merges the form field's `aria-describedby` ids.
- `sheet`: grabbing cursor from the press, pointer capture on press, native
  drag blocked during a sheet drag, overlay inert while closing.
- Re-vendored stylesheets: closing-button, accordion, collapse, calendar,
  table, field-surface mixins, feedback-text, radio-card, timeline,
  horizontal-stepper, alert, dropdown-content, tag, and React's sheet.
- `scripts/generate-lang.cjs` handles optional interpolated parameters,
  Russian one/few/many plurals and counts compared as strings (which had
  lost their singular); `tests/LangTest.php` guards the lang files.

Not ported: React's mobile-nav changes (component not ported). Left out of this sync at first and since ported under "Previously
unported upstream features" above: the file upload's client-side selection
pipeline and its five skipped stories, the table's filter popover and its
footer (`filterActions`, `cancel`), the date field's `calendarFullWidth`
(modal branch), the `numberField.quantityUpdated` announcement, the toast
container, and React's dock-panel and split-pane.

Dependencies: `@tedi-design-system/core` 6.12.0 → 6.13.0 (additive tokens),
`sass` 1.107.1 → 1.108.2. Composer packages were current; the storybook app
stays on Laravel 12 (area17/blast 1.11 caps it).

### Upstream sync (Angular 9.0.1-rc.1, React 20.1.0-rc.1, core 6.12.0)

Supersedes the Angular 8 sync below. Every component is re-synced against
Angular 9.0.1-rc.1 (`d86bf8c7`), React 20.1.0-rc.1 (`e2c22c3c`) and core
6.12.0 (`452b66a9`), the baseline recorded in the README. This release has
**breaking changes**; the README's "Upgrading" section lists them per component.
**Upgrade note:** run `php artisan view:clear` after upgrading, and re-publish
any overridden form-field, text-field or date-* views (`$__tediFieldControl` and
`$__tediFieldId` are gone).

The most visible:

- `form-field` / `select` / `textarea` are `clearable` by default (Angular 9).
  The clear button is live through the bundled `tediFormField` behaviour.
- `dropdown-item-value{,-label,-meta}` is renamed `option-content{,-label,-meta}`
  (classes too), with no alias.
- floating-button, table-of-contents, skeleton, heading-with-icon, truncate
  and print now port from Angular `tedi/`. table-of-contents has a new
  controlled API, and truncate (`content` → `text`) and heading-with-icon
  (`name` → `icon`) rename props.
- New: search autocomplete; React `sheet`, `card-stepper` and `table-card`
  (with `text-group-list`, React's stylesheet loaded scoped under
  `.tedi-table-card` so it cannot restyle the Angular text-group).
- `resources/scss/_core-compat.scss` is removed. Core 6.12 defines
  `--layout-header-height` itself (responsive), and the Angular 9 stylesheets
  no longer use the other aliased names.
- `tedi:form-field` is a class component (`Tedi\Livewire\View\Components\FormField`;
  `<tedi:form-field>` and `<x-tedi::form-field>` are unchanged). The field
  context is a `Tedi\Livewire\View\FieldContext`, found through Blade's
  public `getConsumableComponentData()`. Nothing reads the view factory's
  protected state any more, and the app-wide `View::composer('*')` is gone.
  Removed: `Tedi::trackRender()`, `Tedi::dateInputContext()` and
  `Tedi::FIELD_DEFAULTS`; `Tedi::fieldContext()` replaces
  `Tedi::fieldOwnsSurface()`. date-field and date-time-field pass their child
  context down through a new `field-context` prop.
- `select`: the native `<select>` carries `tedi-select__trigger`, so it is a
  full-width field below its label, as upstream. `pagination`: the page-size
  `<select>` is dressed as upstream's small `tedi-select`.
- `number-field` renders one `<tedi-number-field>` root around label, field
  and feedback (Angular's host), so a grid parent lays it out as one item.
- `date-field` / `time-field`: an open panel is anchored below the field,
  start- or end-aligned by trigger as upstream, instead of sitting beside the
  input in the surface's flex row.
- Fixes found by a browser sweep of every story: button-group slot buttons
  rendered unselected despite `:selected`; file-dropzone's label threw an
  Alpine syntax error; the time-picker wheel now opens on the selected time;
  sheet focus returns after an outside press, and a fast handle drag is no
  longer lost.
- The Angular translation keys with arguments now take Laravel placeholders,
  generated by `scripts/generate-lang.cjs`.

Dependencies: the package now requires `laravel/framework` (`^11|^12|^13`)
instead of `illuminate/support` + `illuminate/view`. It calls Foundation helpers
(`config()`, `asset()`, `public_path()`, `resource_path()`), which ship only in
the framework (`illuminate/foundation` is abandoned). Every consumer is a
Laravel app that already has it. Tested on Laravel 13 with PHPUnit 13,
Testbench 11 and Livewire 4.4.

### Upstream sync (Angular 8.0.1-rc.7, React 19.0.0, core 6.9.0)

Re-vendored component SCSS from `@tedi-design-system/angular@8.0.1-rc.7` (latest
published tag, includes the 8.0.0 stable release plus token/card/sidenav
fixes) and `@tedi-design-system/react@19.0.0`. Bumped `@tedi-design-system/core`
from 6.5.0 to 6.9.0.

Token names that core renamed while Angular community/header sheets still use
the old identifiers are aliased in `resources/scss/_core-compat.scss`.

### Angular 8 form-field surface

The form-field wrapper is optional. A bordered `tedi-form-field__box` (and
`tedi-field-surface`) is rendered only when `icon` or `clearable` is set;
otherwise `text-field`, `textarea`, `date-field` and `time-field` paint
`tedi-field-surface` themselves. Nested controls detect that box from the
wrapper's `icon` / `clearable` attributes (`Tedi::fieldOwnsSurface`).

Host modifiers `tedi-form-field--valid` / `--invalid` / `--disabled` are gone
(Angular 8); those states live on `tedi-field-surface`. Slider `hideLabel="keep-space"`
now uses `tedi-label--reserve-space`.

### Other API ports

- Pagination `align` (`left` / `right`; `between` is the unclassed default)
- Table `stickyLastColumn` and `controlColumnOrder` `"content"` sentinel
- Label `visuallyHidden="reserve-space"`
- Search host class `tedi-search--has-button` (replaces `__input--has-button`)
- File-upload `showRestrictions` / `maxSize` hint, plus `__container--{size}`
- Date-field / time-field size modifiers now emit classes (field-surface)

React 19.1.0-rc (TEDI-Ready vertical stepper, card-stepper, Sheet) is **not**
ported yet — those are new components, not deltas on existing Blade tags.
