<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=4511-78722" target="_blank">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/35370f-toast" target="_BLANK">Zeroheight ↗</a><hr/>

## Usage

Angular's docs cover its `ToastService`, which spawns toasts into a CDK Overlay
container. There is no Blade equivalent — `<tedi:toast>` renders the toast's own
markup as a statically-positioned element and leaves placement to the consumer,
per CONVENTIONS.md §7. That section is therefore not ported.

## Accessibility

Upstream's `ToastService` announces every toast through a dedicated visually
hidden live region, and the visible toast is not a live region itself —
otherwise screen readers read each toast twice. `role` picks the politeness of
that announcement:

- `role="status"` (default): For non-critical notifications. Screen readers announce politely.
- `role="alert"` (default for danger): For critical errors. Screen readers announce immediately.
- `role="none"`: When no screen reader announcement is needed.

The service and its announcer are not ported, so on `<tedi:toast>` the `role`
prop is accepted for API parity but inert: the rendered toast is never a live
region. Announce from a live region that already exists on the page.

Upstream's auto-close timer pauses while the toast is hovered or holds keyboard
focus; the timer is not ported either.
