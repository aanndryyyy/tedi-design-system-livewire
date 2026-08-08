<a href="https://www.tedi.ee/1ee8444b7/p/15bd6e-date-field" target="_blank">Zeroheight ↗</a>

The Calendar is the standalone date selection surface used inside DateField, and can also be
embedded directly. It supports `single`, `multiple` and `range` selection modes, three commit
levels (`days`, `months`, `years`), available/unavailable day predicates, ISO week numbers,
multi-month layouts, header dropdown vs. grid month-year selection, custom locales and a
footer projection slot (`tediCalendarFooter`).

<!--
Blade port notes (CONVENTIONS.md §7, CONTRACT.md §6): this port is a documented
subset. `monthYearSelectType="dropdown"` — the Angular default — renders the header
trigger only; the dropdown listbox panel needs an overlay component this package does
not ship. Custom locales are not supported: month and weekday names come from the
`date-picker.*` translation keys rather than `Intl`, so `localeCode` is dropped and
`firstDayOfWeek` is an explicit numeric prop. Matcher/predicate inputs
(`disabledMatchers`, `availableDays`, `unavailableDays`, `dayStatus`) become flat
`Y-m-d` arrays and a `Y-m-d`-keyed map. The footer slot is the Blade named slot
`<x-slot:footer>`.
-->
