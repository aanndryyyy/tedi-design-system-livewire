# table-filter-popover: doc changes for the integrator

Stream: port of the Angular 10 table column filter popover and its footer actions
(`table.component.html` filter branch, `filterActionsFor` / `filterActionLabel` /
`handleFilterAction`, `TableFilterAction` incl. `cancel`). No JS module, SCSS
or lang changes: the popover runs on the existing `tediOverlay`. The
`table.filter-{apply,clear,cancel}` and `table.filter-button-aria` keys already
existed.

## README.md

### Divergences table (line ~177): change the `table` row

Old:

> | `table` | Markup layer only, the same spirit as `select`. `@tanstack/angular-table` is not ported, so sorting, filtering, selection and expansion **state** are the consumer's and come back in through `:columns` / `:rows`. With the engine go row virtualisation, drag reorder, column resizing, `state` persistence, the built-in filter popover, the small-viewport filter modal and `filterModalBreakpoint` / `filterModalFullscreen` / `filterPopoverWidth` |

New:

> | `table` | Markup layer only, the same spirit as `select`. `@tanstack/angular-table` is not ported, so sorting, filtering, selection and expansion **state** are the consumer's and come back in through `:columns` / `:rows`. With the engine go row virtualisation, drag reorder, column resizing and `state` persistence. The column filter popover is ported (`filterable` + an Htmlable `filterTemplate`, `filterActive`, `filter-popover-width`, `filter-actions`). Its draft state and `clearOnClose` are yours; the footer buttons run the column's `filterActionAttributes` and dispatch `tedi-table:filter-action` (detail `{column, action}`), then close. The small-viewport filter modal and `filterModalBreakpoint` / `filterModalFullscreen` are not ported: the popover is used at every width |

### Subset table (line ~515): change the `<tedi:table>` row

Old:

> | `<tedi:table>` | The full markup: toolbar, sortable headers, control columns, expandable and clickable rows, column menu | The `@tanstack/angular-table` engine — sorting/filtering/selection/expansion state, virtualisation, drag reorder, column resizing, persistence, the filter popover |

New:

> | `<tedi:table>` | The full markup: toolbar, sortable headers, column filter popovers with their footer actions, control columns, expandable and clickable rows, column menu | The `@tanstack/angular-table` engine — sorting/filtering/selection/expansion state, virtualisation, drag reorder, column resizing, persistence — and the small-viewport filter modal |

### Counts

- Line ~741: "661 stories across 87 components" → **663 stories** (+2: Table
  `Filters`, `FilterActions`). Component count unchanged.
- Line ~705: "188 stories have a per-story description" → **190** (+2:
  `filters.md`, `filter-actions.md`).

### Skipped-stories rows

None in README.md. The two rows (`Filters`, `FilterActions`) were in the story
directory's own README (`storybook/resources/views/stories/TEDI-Ready/Content/Table/README.md`)
and are already deleted on this branch.

## CHANGELOG.md, under "Unreleased"

- `table`: the column filter popover is ported (Angular 10 `filterable` +
  `filterTemplate`). A column with `filterable` (`true`, or
  `['popoverWidth' => …, 'actions' => […]]`) and an Htmlable `filterTemplate`
  gets the `filter_alt` header button in a `tedi:popover`
  (`.tedi-table__filter-popover`). `filterActive` selects and fills the
  button. New table props `filter-popover-width` (default `small`) and
  `filter-actions` (default `['clear', 'apply']`, plus `cancel`). Each footer
  button runs the column's `filterActionAttributes[action]` (e.g. `wire:click`),
  dispatches a bubbling `tedi-table:filter-action` event with detail
  `{column, action}`, and closes the popover. `filterTriggerAttributes` reaches
  the trigger. The small-viewport filter modal is not ported.
- Storybook: Table `Filters` and `FilterActions` are ported, and
  `SortableCustomHeader` gains its upstream filter popover.

## Upgrading

No entry. The change is additive. A column with only `filterable` (the filter-row
opt-in) renders exactly as before, because the popover also needs `filterTemplate`.

## CONVENTIONS.md

No §8 row (no new JS module).

One possible §11 note: `tediOverlay` has no `morph.updated` hook. If a filter
template uses `wire:model.live`, a Livewire morph while the popover is open could
strip the panel's client-set inline `position/top/left`. This is not verified
with a live Livewire component. The stories and docs use deferred `wire:model`
plus an Apply binding, which avoids a round-trip while the panel is open.
`overlay.js` is outside this stream's ownership.
