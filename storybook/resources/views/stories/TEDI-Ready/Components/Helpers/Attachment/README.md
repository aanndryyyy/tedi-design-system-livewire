<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=30427-154342&m=dev" target="_blank">Figma ↗</a><br>
<a href="https://www.tedi.ee/1ee8444b7/p/9133f7-attachment" target="_blank">Zeroheight ↗</a>

Project a `<tedi-progress-bar>` inside the attachment to show upload progress.
Configure label, hint, and value formatting on the projected progress bar.

Action buttons (download, delete, …) are **not** built in. Project your own
neutral icon-only buttons into the actions slot with `tedi-attachment-actions`.
Always give each button an `aria-label`. Wrap a button in a `tedi-tooltip`
(put `tedi-attachment-actions` on the `tedi-tooltip`) to surface a tooltip.
