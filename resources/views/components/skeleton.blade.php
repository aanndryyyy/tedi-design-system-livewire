{{--
    TEDI Skeleton.
    Port of react/src/tedi/components/loaders/skeleton/skeleton.tsx (CONVENTIONS.md §13).

    A loading placeholder wrapper. It carries no visual of its own beyond
    `pointer-events: none` and the corner radius — the shimmer belongs to the
    `tedi:skeleton-block` children it wraps.

    Accessibility. React declares the loader through an AccessibilityProvider
    context when one is present, and otherwise announces `label` into an
    `aria-live` region on mount, swapping to `completedLabel` on unmount after
    `labelDelay` ms. There is no unmount and no provider on the server, so the
    port keeps the announcement half only: a `role="status"` live region holding
    `label`. `labelDelay` and `completedLabel` are runtime-only and dropped
    (CONVENTIONS.md §7 item 2) — a screen reader gets the "loading" message,
    and the "loaded" message is the consumer's to announce when they swap the
    skeleton out.

    Pass `label=""` to suppress the live region entirely, e.g. when several
    skeletons share one announcement from a wrapping region. `:label="null"`
    does NOT suppress it — Blade cannot distinguish an explicit null from an
    omitted prop (CONVENTIONS.md §3).
--}}
@props([
    /** Screen-reader message announced while the placeholder is on the page. Pass "" for none. */
    'label' => null,
])

@php
    $label = $label ?? __('tedi::tedi.skeleton.loading');
@endphp

<div {{ $attributes->class(['tedi-skeleton']) }}>
    @if (filled($label))
        <span class="sr-only" role="status" aria-live="assertive" aria-atomic="true">{{ $label }}</span>
    @endif

    {{ $slot }}
</div>
