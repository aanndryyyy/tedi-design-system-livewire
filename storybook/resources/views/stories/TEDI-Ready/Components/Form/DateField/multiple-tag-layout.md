`multiRow` controls how `multiple`-mode tags lay out. `true` (default) wraps them across rows and grows the field height — like the React MultiValueField. `false` keeps a single row and collapses the overflow into a `+N` counter.

> Angular measures the overflow count from the available width at runtime. This port takes it as the explicit `visible-tag-count` prop; when it is unset every tag renders and the field carries `tedi-date-input--tags-measuring`, matching Angular's first paint.
