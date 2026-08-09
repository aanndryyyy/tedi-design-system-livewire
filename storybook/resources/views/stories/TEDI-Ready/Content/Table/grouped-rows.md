**Column-level grouping.** Consecutive rows that share a date are merged into one spanning cell.

Upstream the span is computed internally against the live (post sort / filter / pagination) row model via `groupBy: (row) => row.original.date`. This port has no row model, so the *result* is passed in explicitly: the group's first row carries `rowspan` on the merged cell and the rows it covers set `rowspan => 0`. `groupStart` marks each group's first row, which is what `rowGroupDividers` keys on.

Grouping here is **purely visual**: it merges that one column's cells and nothing else. It does *not* make the rows behave as a group — turn on `enableRowSelection` and you still get one checkbox per row.

Pair grouped columns with `'vAlign' => 'top'` so the merged cell's content sits at the top of the block rather than centered.
