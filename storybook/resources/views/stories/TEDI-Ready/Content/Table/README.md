<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev" target="_BLANK">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/557b9f-table" target="_BLANK">Zeroheight ↗</a>

Data table markup. Supports sort affordances, a filter row, expansion, selection,
pagination chrome, sticky chrome and body row spanning.

**This is a documented subset.** The Angular component is a wrapper around
`@tanstack/angular-table`, and that engine — sorting, filtering, row selection,
expansion, column visibility / order / sizing, pagination state, drag-and-drop
reorder and `localStorage` persistence — has no Blade equivalent and is not
ported. What ships is the markup layer: a pure function of explicit `:columns`
and `:rows` array props, in the same spirit as `<tedi:select>` and
`<tedi:pagination>`. You perform the sort / filter / paging and render the result
back in; the component owns the class list, the control columns and the ARIA.

Cells render from each row's `cells` map. Scalars are escaped; anything
`Illuminate\Contracts\Support\Htmlable` is emitted raw, which is how this port
replaces Angular's per-column `cell` `TemplateRef` — see **Actions** and
**Custom**.

`resources/views/components/table.blade.php` carries the full per-prop rationale.

### Angular stories not ported

| Angular story | Why |
|---|---|
| `GroupedSelectableRows` | Its subject is table-level `groupRowsBy` **behaviour**: one checkbox per group, indeterminate propagation across a group's rows, group-wide selection. All of it is computed by the TanStack row model. The visual result (merged cells, group dividers) is covered by **Grouped Rows**. |
| `EditableValues` | Cells host Angular reactive-forms controls whose writes go back into the table's `data` signal. Both the form binding and the write-back are engine, not markup. |
| `Filters` | Demonstrates the built-in filter **popover** (`filterable: true` + a `filterTemplate`), whose body is a `TemplateRef` bound to per-column draft state owned by the engine. The plain filter **row** (`enableColumnFilters`) is ported and appears in **Default**. |
| `ReorderableRows` | Row drag-and-drop (CDK) plus the keyboard pick-up / move / drop / cancel state machine and its `aria-live` announcements. Not ported. |
| `ReorderableColumns` | Same, for columns, plus `ColumnReorderPhase` and the `drag` control column. Not ported. |
| `ServerSide` | `manualPagination` / `manualSorting` with a simulated server round-trip driving the table's state. There is no table-owned state here to drive. |
| `PaginationCustomResults` | Projects an `<ng-template tediPaginationResults>` into the table, which forwards it into whichever paginator slot shows results. Pagination is a plain slot in this port, so the forwarding mechanism does not exist — configure `<tedi:pagination>` directly instead. |
| `PaginationFullyConfigured` | Exists to show every `tedi-pagination` input being forwarded through the table's `pagination` options object. Nothing is forwarded here, so the story would only re-document `<tedi:pagination>`'s own props. |
| `Responsive` | Resolves the visible column set from the current breakpoint at runtime. Breakpoint props are not ported anywhere in this package (CONVENTIONS.md §7.1). |
