Default `selectionMode` is `'multiple'` — a checkbox per row plus a select-all checkbox in the header.

The selection **state** is not ported: TanStack's `rowSelection` slice has no Blade equivalent. Each row's `selected` key drives the checkbox, `selectAllChecked` / `selectAllIndeterminate` drive the header control, and `selectAttributes` / `selectAllAttributes` forward `wire:model` (or any other attribute) straight onto the native `<input>`. Key your rows by a stable entity id via each row's `id` so the state stays correct as the data changes.
