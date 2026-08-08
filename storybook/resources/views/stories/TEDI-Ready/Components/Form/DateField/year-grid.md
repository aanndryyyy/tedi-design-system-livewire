`monthYearSelectType="grid"` replaces the header dropdowns with a clickable label that drills into a year/month grid; `selectionLevel="years"` commits at year granularity.

> Angular collapses the committed `Date` to just the year number with a custom `formatDate`. JS callables are not ported — pass the already-formatted string as `display` instead.
>
> `selectionLevel` seeds which grid the calendar opens on, as it does in Angular. What it cannot do here is *drill*: clicking the header label to move between the year, month and day grids is a runtime interaction, so each level is reached by rendering it rather than by navigating to it.
