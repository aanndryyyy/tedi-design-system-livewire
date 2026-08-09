Combines expandable rows with `interactive` = true for clickable rows. Rows that can expand show a chevron and respond to clicks in two ways: clicking the chevron toggles expansion (its click is stopped from bubbling), while clicking anywhere else on the row activates it — shown here via `activeRowId`.

Upstream also borders the status badge for the *hovered* row by reading the table's `hoveredRowId()` signal. That signal is part of the unported TanStack layer, so this story shows the active-row highlight only.
