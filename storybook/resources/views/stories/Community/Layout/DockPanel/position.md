Choose **Left**, **Right**, **Top** or **Bottom** to open or move the same panel.
This example uses `md` (50%) on the docking axis: side panels fill the workspace height;
top/bottom panels fill its width. Expanded panels overlay the workspace. Minimized strips occupy a separate grid track, so no workspace content is covered. The background remains interactive.

| Position | Expanded | Minimized |
| --- | --- | --- |
| Left | 50% width, full height, attached to the left edge | Vertical strip at the left edge |
| Right | 50% width, full height, attached to the right edge | Vertical strip at the right edge |
| Top | Full width, 50% height, attached to the top edge | Horizontal strip at the top edge |
| Bottom | Full width, 50% height, attached to the bottom edge | Horizontal strip at the bottom edge |

**Minimize and restore here** using the header button. Restore and Close stay grouped:
stacked above the smaller, bottom-to-top title on side strips, or adjacent at the end of horizontal strips. Inward arrows minimize; outward arrows restore the configured size.
The same panel and children stay mounted while switching positions or minimizing, so edited
form values and the content counter are preserved. Switching position buttons restores the
expanded view by default; set `defaultCollapsed` before opening to start minimized.
Closing is separate: it unmounts this example, and reopening starts with fresh content.
Custom side heights and top/bottom heights use the existing `height` prop;
see **Custom dimensions** and **Content height** rather than separate per-edge examples.
