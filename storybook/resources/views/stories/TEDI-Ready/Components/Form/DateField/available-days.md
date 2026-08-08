Restrict selection to specific days — every other day is disabled.

> Angular takes `availableDays` (a `Date[]` or a predicate) and its inverse `unavailableDays`. Both are matcher machinery with no server-side analogue, so this port collapses them into the flat `disabled-days` array of `'Y-m-d'` strings.
