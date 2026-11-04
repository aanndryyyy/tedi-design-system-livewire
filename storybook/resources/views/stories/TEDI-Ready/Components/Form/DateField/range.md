`mode='range'` builds a `{ from, to }` value across two clicks. It combines with the same constraint inputs as single mode (`minDate`/`maxDate`, `disablePast`) and with `numberOfMonths` for a multi-month view. All fields honour the shared controls (e.g. set `modal` to open them in a modal).

> `minDate` / `maxDate` / `disablePast` are matcher machinery with no server-side analogue; this port takes a flat `disabled-days` array of `'Y-m-d'` strings instead, so the open-ended "Range with min/max limits" field is omitted. The modal variant is not ported, so `modal` is not a control here.
