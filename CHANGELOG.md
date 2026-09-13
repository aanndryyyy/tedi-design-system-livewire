# Changelog

## Unreleased

### Upstream sync (Angular 8.0.1-rc.7, React 19.0.0, core 6.9.0)

Re-vendored component SCSS from `@tedi-design-system/angular@8.0.1-rc.7` (latest
published tag, includes the 8.0.0 stable release plus token/card/sidenav
fixes) and `@tedi-design-system/react@19.0.0`. Bumped `@tedi-design-system/core`
from 6.5.0 to 6.9.0.

Token names that core renamed while Angular community/header sheets still use
the old identifiers are aliased in `resources/scss/_core-compat.scss`.

### Angular 8 form-field surface

The form-field wrapper is optional. A bordered `tedi-form-field__box` (and
`tedi-field-surface`) is rendered only when `icon` or `clearable` is set;
otherwise `text-field`, `textarea`, `date-field` and `time-field` paint
`tedi-field-surface` themselves. Nested controls detect that box from the
wrapper's `icon` / `clearable` attributes (`Tedi::fieldOwnsSurface`).

Host modifiers `tedi-form-field--valid` / `--invalid` / `--disabled` are gone
(Angular 8); those states live on `tedi-field-surface`. Slider `hideLabel="keep-space"`
now uses `tedi-label--reserve-space`.

### Other API ports

- Pagination `align` (`left` / `right`; `between` is the unclassed default)
- Table `stickyLastColumn` and `controlColumnOrder` `"content"` sentinel
- Label `visuallyHidden="reserve-space"`
- Search host class `tedi-search--has-button` (replaces `__input--has-button`)
- File-upload `showRestrictions` / `maxSize` hint, plus `__container--{size}`
- Date-field / time-field size modifiers now emit classes (field-surface)

React 19.1.0-rc (TEDI-Ready vertical stepper, card-stepper, Sheet) is **not**
ported yet — those are new components, not deltas on existing Blade tags.
