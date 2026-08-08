`mode='range'` builds a `{ from, to }` value across two clicks. It combines with the same constraint inputs as single mode and with `numberOfMonths` for a multi-month view.

> `minDate` / `maxDate` / `disablePast` are matcher machinery with no server-side analogue; this port takes a flat `disabled-days` array of `'Y-m-d'` strings instead. The modal variant is not ported.
