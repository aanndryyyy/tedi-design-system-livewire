Upstream: set `ModalRef.canClose` to keep the modal open until the user confirms. `ModalRef` exists only in service mode, which this package does not port.

Here the same decision point is the cancelable `tedi-modal-close` event on `tedi-modal`: call `$event.preventDefault()` in an `x-on:tedi-modal-close` listener to keep the modal open, and close it later with Alpine's unguarded `close()`. `detail.reason` is one of `backdrop`, `escape`, `close-button` or `programmatic`.
