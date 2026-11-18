<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=4511-78722" target="_blank">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/35370f-toast" target="_BLANK">Zeroheight ↗</a><hr/>

## Usage

Angular injects `ToastService`. Here, place the toast container once in your
layout (with `@tediScripts`, which loads its behaviour), then call one of the
convenience methods on `window.TediToast`, the service's port:

```blade
<tedi:toast-container />
```

```js
TediToast.success('Title', 'Content text', { icon: 'icon_name' });
```

From a Livewire component, dispatch the `tedi-toast` browser event with the
same options. It calls `show()`, so pass `role: 'alert'` yourself for a danger
toast, which `danger()` does for you:

```php
$this->dispatch('tedi-toast', title: 'Title', content: 'Content text', type: 'success');
```

Toasts rendered on the server, such as flash messages, go in the container's
slot. They take a `position` and are timed and announced like the rest:

```blade
<tedi:toast-container>
    <tedi:toast title="Title" type="success" position="top-right">Content text</tedi:toast>
</tedi:toast-container>
```

## Accessibility

`ToastService` announces every toast through a dedicated visually hidden live
region, and the visible toast is not a live region itself — otherwise screen
readers read each toast twice. `role` picks the politeness of that announcement:
- `role="status"` (default): For non-critical notifications. Screen readers announce politely.
- `role="alert"` (default for danger): For critical errors. Screen readers announce immediately.
- `role="none"`: When no screen reader announcement is needed.

The auto-close timer pauses while the toast is hovered or holds keyboard focus.
