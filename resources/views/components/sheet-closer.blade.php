{{--
    TEDI Sheet Closer.
    Port of react/src/tedi/components/overlays/sheet/components/sheet-closer/sheet-closer.tsx
    (CONVENTIONS.md §13).

    Upstream clones its child and wraps its `onClick` so the child's own
    handler runs first and the sheet then closes. Blade cannot clone a slot
    (CONVENTIONS.md §13.5), so this is a wrapper `<span>` whose click handler
    closes the sheet — the child's own handlers (`wire:click`, `x-on:click`)
    still run first, because the click reaches them before it bubbles here.
    It carries `data-tedi-contents` (`display: contents`) so the child is the
    box that lays out — a full-width footer stretches the button, not the
    wrapper, exactly as with upstream's clone.
--}}
<span {{ $attributes }} data-tedi-contents x-on:click="hide()">{{ $slot }}</span>
