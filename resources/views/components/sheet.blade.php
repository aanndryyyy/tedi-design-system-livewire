{{--
    TEDI Sheet.
    Port of react/src/tedi/components/overlays/sheet/sheet.tsx (CONVENTIONS.md §13).

    A bottom sheet: a panel that slides up from the bottom edge over a dimmed
    backdrop, optionally collapsible to just its header ("docked peek") and
    draggable between snap points. It is React-only; Angular ships nothing
    like it.

    THE PIECES. `tedi:sheet` owns the state; inside it go
    `tedi:sheet-trigger` (wraps whatever opens it) and `tedi:sheet-content`
    (the backdrop and the panel), which holds `tedi:sheet-header`,
    `tedi:sheet-body` and `tedi:sheet-footer`. `tedi:sheet-closer` wraps
    anything that should close it.

    THE ROOT. Upstream's `Sheet` renders no element — it is a context
    provider. Blade needs one root (CONVENTIONS.md §6) and Alpine needs an
    element to hang the shared state on, so this is a classless `<div>`:
    inventing a class for it would break §4. `{{ $attributes }}` lands on it.

    BEHAVIOUR lives in `tediSheet` (resources/js/src/sheet.js — CONVENTIONS.md
    §8): open / close with upstream's transition statuses, Escape and
    outside-press dismissal, focus management, scroll lock, collapse, drag and
    snap points. Anything inside this element can drive it through the Alpine
    scope — `show()`, `hide()`, `toggle()`, `open`, `collapsed` — which is what
    stands in for upstream's controlled `open` / `onToggle` and `collapsed` /
    `onCollapsedChange` pairs: an `x-on:click="show(); collapsed = true"` on a
    button inside the sheet is the Controlled Collapse story. `onToggle` and
    `onCollapsedChange` themselves are not re-emitted (CONVENTIONS.md §7 item 2).

    `open` and `collapsed` are the initial state — upstream's `defaultOpen` /
    `defaultCollapsed`. As upstream does for an uncontrolled sheet, the sheet
    returns to the initial `collapsed` value each time it opens.
--}}
@props([
    /** Initial open state (upstream's `defaultOpen`). */
    'open' => false,
    /** Initial collapsed state (upstream's `defaultCollapsed`); restored on every open. */
    'collapsed' => false,
    /** Close when the backdrop — or, without a backdrop, the page — is pressed. */
    'closeOnBackdropClick' => true,
    /** Close when Escape is pressed. */
    'closeOnEscape' => true,
    /** dialog|alertdialog — the panel's ARIA role. Reaches tedi:sheet-content by `aware`. */
    'role' => 'dialog',
])

@php
    $config = [
        'open' => (bool) $open,
        'collapsed' => (bool) $collapsed,
        'closeOnBackdropClick' => (bool) $closeOnBackdropClick,
        'closeOnEscape' => (bool) $closeOnEscape,
    ];
@endphp

<div {{ $attributes }} x-data="tediSheet(@js($config))">{{ $slot }}</div>
