The value type follows `mode`: `single` → `Date`, `multiple` → `Date[]` (rendered as removable tags), `range` → `{ from, to }`.

> In this port the value is a `'Y-m-d'` string, an array of them, or `['from' => …, 'to' => …]`. The text shown in the input comes from the `display` prop and the tags from `tags`, because Angular's `formatDate` callback is not ported.
