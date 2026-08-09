{{--
    TEDI Modal.
    Port of angular/tedi/components/overlay/modal/{modal.component.ts,modal.component.html,modal.types.ts}

    Angular's ModalComponent has two modes. Only the **standalone / template**
    branch — the one driven by `[(open)]` — is ported. Upstream marks it
    `@deprecated` in favour of `ModalService.open()`, but it is the only branch
    that has markup: the service branch is an imperative Angular service that
    spawns a component into a CDK Dialog overlay, which has no Blade equivalent.
    So `.tedi-modal--service`, the whole `.tedi-modal-dialog*` class family,
    `cdk-dialog-container`, `ModalService`, `ModalRef`, `MODAL_DATA` and every
    `ModalConfig` key that exists only for them are NOT ported:
    `scrollBehavior` (its only rule is `.tedi-modal-dialog--scroll-page`),
    `fullscreen` / `fullscreen-<bp>` (also a breakpoint prop — CONVENTIONS §7 #1),
    `maxWidth`, `closeOnEscape`, `ariaLabel`, `ariaLabelledBy`, `data`.

    Divergences a consumer will notice:

    - **No re-parenting to `<body>`.** Angular's `ngAfterViewInit` appends the
      host element to `document.body`. Blade renders once on the server and the
      modal stays where it is written. `.tedi-modal` is `position: fixed; inset: 0`
      so it still covers the viewport — but inside a `transform`ed ancestor a
      fixed element is positioned relative to that ancestor, and the modal would
      be clipped/offset. Same spirit as CONVENTIONS §11's structural divergence;
      do not work around it by re-parenting.
    - **No focus trap.** Angular applies `cdkTrapFocus`. `tediModal` moves focus
      into the dialog on open and restores it on close, but does not cycle Tab.
    - **The backdrop is always in the DOM**, `x-show`n rather than `@if`d away,
      per CONVENTIONS §8 — a stripped-JS consumer still gets the real markup and
      the parity tests have something to assert against.

    Dropped class: `tedi-modal--bottom`. Angular emits it for `position="bottom"`
    but the vendored SCSS has no rule for it (`--center`, `--top`, `--left`,
    `--right` all do), so per CONVENTIONS §4 the stylesheet guardrail wins.
    `position="bottom"` remains a legal value; it simply emits no position class,
    which is behaviourally identical to what Angular renders. Restore it if TEDI
    ever ships the rule.
--}}
@props([
    /** Is the modal open? Angular's `[(open)]` model; here the Alpine initial state. */
    'open' => false,
    /** default|small — padding + heading scale. Inherited by modal-header via @aware. */
    'size' => 'default',
    /** xs|sm|md|lg|xl (preset → modifier class) or any CSS length (→ inline width on the dialog). */
    'width' => 'sm',
    /** center|top|bottom|left|right. `bottom` emits no class — see the header comment. */
    'position' => 'center',
    /** Whether clicking the backdrop closes the modal. */
    'closeOnBackdropClick' => true,
])

@php
    // isPresetWidth() / customWidth()
    $isPresetWidth = in_array($width, ['xs', 'sm', 'md', 'lg', 'xl'], true);
    $customWidth = $isPresetWidth ? null : $width;

    $isOpen = (bool) $open;

    // classes(): position 'top' emits BOTH --center and --top; 'bottom' emits
    // neither (dropped, no rule).
    $positionClasses = match ($position) {
        'top' => ['tedi-modal--center', 'tedi-modal--top'],
        'bottom' => [],
        default => ['tedi-modal--'.$position],
    };
@endphp

<tedi-modal
    {{ $attributes->class(array_merge([
        'tedi-modal',
        'tedi-modal--'.$size,
        'tedi-modal--'.$width => $isPresetWidth,
    ], array_fill_keys($positionClasses, true), [
        'tedi-modal--open' => $isOpen,
    ])) }}
    x-data="tediModal({ open: {{ $isOpen ? 'true' : 'false' }}, closeOnBackdropClick: {{ $closeOnBackdropClick ? 'true' : 'false' }} })"
    x-bind:class="{ 'tedi-modal--open': open }"
    x-on:keydown.window="onKeydown($event)"
>
    <div class="tedi-modal__backdrop" x-show="open" x-on:click="onBackdropClick()"></div>

    <div
        class="tedi-modal__dialog"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        @if ($customWidth) style="width: {{ $customWidth }}" @endif
        x-ref="dialog"
    >
        {{ $slot }}
    </div>
</tedi-modal>
