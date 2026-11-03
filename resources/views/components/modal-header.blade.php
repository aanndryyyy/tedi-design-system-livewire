{{--
    TEDI Modal header.
    Port of angular/tedi/components/overlay/modal/modal-header/modal-header.component.{ts,html}

    Angular projects three content slots: `h1..h6` into `__head`, then anything
    carrying the `tedi-modal-description` attribute, then the rest. Blade cannot
    select on tag name, so:

      - the default slot is the heading, rendered inside `__head`;
      - the `description` slot is rendered after `__head` (write the
        `tedi-modal-description` attribute on your own element, exactly as in
        Angular — that attribute is what the stylesheet indents on).

    `size` mirrors Angular's `inject(MODAL_SIZE)`: the close button shrinks with
    the surrounding modal. In Blade that is `@aware`, whose fallback must equal
    `tedi:modal`'s `@props` default (CONVENTIONS §3) — both are 'default'.
    `closeButtonSize` overrides it, as upstream.

    The heading in the default slot is what names the dialog (upstream #680):
    `tediModal` gives the first `h1`–`h6` here an id on open and points the
    dialog's `aria-labelledby` at it, unless `tedi:modal` was given its own
    `aria-label` / `aria-labelledby`.

    The close button calls `requestClose('close-button')` on the enclosing
    `tediModal` Alpine scope — upstream's `modalRef.requestClose("close-button")`,
    so a `tedi-modal-close` listener can keep the modal open.
    Used outside a `tedi:modal` it is an inert no-op, matching Angular's
    `inject(ModalComponent, { optional: true })`.
--}}
{{-- @aware fallback must equal tedi:modal's @props default — CONVENTIONS §3. --}}
@aware([
    'size' => 'default',
])
@props([
    /** Should show closing button? */
    'showClose' => true,
    /** default|small — overrides the size derived from the surrounding modal. */
    'closeButtonSize' => null,
    /** Read from the surrounding modal so the close button auto-shrinks. */
    'size' => 'default',
])

@php
    // effectiveCloseButtonSize()
    $effectiveCloseButtonSize = $closeButtonSize ?: ($size === 'small' ? 'small' : 'default');
@endphp

<tedi-modal-header {{ $attributes->class(['tedi-modal-header']) }}>
    <div class="tedi-modal-header__head">
        {{ $slot }}

        @if ($showClose)
            <tedi:closing-button
                class="tedi-modal-header__close"
                :size="$effectiveCloseButtonSize"
                x-on:click="requestClose('close-button')"
            />
        @endif
    </div>

    {{ $description ?? '' }}
</tedi-modal-header>
