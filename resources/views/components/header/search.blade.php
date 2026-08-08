{{--
    TEDI Header Search.
    Port of angular/tedi/components/layout/header/header-search/header-search.component.{ts,html}

    Angular's mobile/modal branch is gated on `isMobile()` (BreakpointService),
    not ported per CONVENTIONS.md §7. It becomes the explicit prop `mobile`
    (default false, matching the non-mobile branch) — pass `:mobile="true"` to
    force the compact toggle + native `<dialog>` regardless of viewport.

    The mobile modal is a native `<dialog>`, not a CDK/floating-ui overlay, so
    it's in scope for minimal Alpine (CONVENTIONS.md §8) — the toggle button
    calls `showModal()` / `close()` directly on the dialog element.
--}}
@props([
    /** modal|inline */
    'mobileVariant' => 'modal',
    /** ['button' => ..., 'modalTitle' => ...] — falls back to header.search translation. */
    'mobileLabels' => [],
    'disabled' => false,
    /** Explicit replacement for Angular's isBelowBreakpoint('md'). */
    'mobile' => false,
])

@php
    $buttonLabel = $mobileLabels['button'] ?? __('tedi::tedi.header.search');
    $modalTitle = $mobileLabels['modalTitle'] ?? __('tedi::tedi.header.search');
    $closeLabel = __('tedi::tedi.close');
    $showModal = $mobile && $mobileVariant === 'modal';
@endphp

<div {{ $attributes->class(['tedi-header-search']) }} @if ($showModal) x-data="{ open: false }" @endif>
    @if ($showModal)
        <tedi:header.mobile-button
            icon="search"
            :label="$buttonLabel"
            :aria-label="$buttonLabel"
            aria-has-popup="dialog"
            :disabled="$disabled"
            x-on:click="open = true; $refs.dialog.showModal()"
            x-bind:aria-expanded="open.toString()"
            x-bind:class="{ 'tedi-header-mobile-button--selected': open }"
        />
        <dialog
            x-ref="dialog"
            class="tedi-header-search__modal"
            aria-label="{{ $modalTitle }}"
            x-on:close="open = false"
        >
            <div class="tedi-header-search__modal-heading">
                <tedi:text as="span" modifiers="h3" color="secondary">
                    {{ $modalTitle }}
                </tedi:text>
                <button
                    type="button"
                    class="tedi-header-search__button-close"
                    aria-label="{{ $closeLabel }}"
                    x-on:click="open = false; $refs.dialog.close()"
                >
                    <tedi:icon name="close" :size="24" color="inherit" />
                </button>
            </div>
            <div class="tedi-header-search__modal-body">
                {{ $slot }}
            </div>
        </dialog>
    @else
        {{ $slot }}
    @endif
</div>
