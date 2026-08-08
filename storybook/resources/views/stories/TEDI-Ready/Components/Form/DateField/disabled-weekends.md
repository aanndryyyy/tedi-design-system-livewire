Grey out recurring weekdays so they cannot be selected.

> Angular passes a `{ dayOfWeek: number[] }` matcher to `disabledMatchers` (0 = Sunday … 6 = Saturday), and also accepts single dates, ranges and predicate functions. Matchers are JS callables with no server-side analogue, so this port takes a flat `disabled-days` array of `'Y-m-d'` strings — precompute the days you want disabled.
