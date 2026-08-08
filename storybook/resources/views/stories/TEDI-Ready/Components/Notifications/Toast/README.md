<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=4511-78722" target="_blank">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/35370f-toast" target="_BLANK">Zeroheight ↗</a><hr/>

## Usage

Angular's docs cover its `ToastService`, which spawns toasts into a CDK Overlay
container. There is no Blade equivalent — `<tedi:toast>` renders the toast's own
markup as a statically-positioned element and leaves placement to the consumer,
per CONVENTIONS.md §7. That section is therefore not ported.

## Accessibility

- `role="status"` (default): For non-critical notifications. Screen readers announce politely.
- `role="alert"` (default for danger): For critical errors. Screen readers announce immediately.
- `role="none"`: When no screen reader announcement is needed.
