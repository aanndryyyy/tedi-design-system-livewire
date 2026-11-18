Each column supplies its own filter UI via `filterable: true` + `filterTemplate` — here a text field (Nimi), a `tedi-date-field` range (Tööalguse kuupäev) and a checkbox group (Tõendi staatus). 

`filterPopoverWidth` sets the popover width for every column, and `filterable: { popoverWidth }` overrides it for one — the date column uses `"medium"` here, since a range value needs more room than the default `"small"` panel. 

> **Not ported:** below `filterModalBreakpoint` the Angular table opens the filter in a **modal** (`filterModalFullscreen` controls its fullscreen behaviour). This port always uses the popover, positioned with `preventOverflow`; neither input exists here. In this port `filterable` is `true` or an array (`['popoverWidth' => '19rem']`), and `filterTemplate` is any `Htmlable`.

A **tag cloud** above the table surfaces the currently active filters. Each filter renders as one removable `tedi-tag` (labelled `Header: value`).

> **Port note:** the filter state is the consumer's. There is no table-owned `[state]` / `(stateChange)` or `clearFilters()`: the chips, the **Tühjenda filtrid** button and each column's `filterActive` flag are rendered from your own state, and the footer buttons reach it through `filterActionAttributes` (e.g. `wire:click`) or the `tedi-table:filter-action` event (`detail: { column, action }`). This story is seeded with the same two active filters as upstream and does not persist changes.

> **Port note:** pagination is a slot in this port, not a `pagination` options object, so the **"Näita kõiki"** page-size option of the Angular story is not part of this one — see **Pagination Top And Bottom**.
