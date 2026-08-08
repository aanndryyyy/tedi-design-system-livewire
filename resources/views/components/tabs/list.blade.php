{{--
    TEDI Tabs List.
    Port of angular/tedi/components/navigation/tabs/tabs-list/tabs-list.component.{ts,html}

    Divergence (CONVENTIONS.md §7 style — runtime DOM measurement, not a
    breakpoint prop, but the same "no viewport/layout pass on the server"
    reasoning applies): `overflowMode="dropdown"` (the default) relies on
    `ResizeObserver` + `afterNextRender` to detect whether triggers overflow
    the available width, then needs the overlay-positioned `tedi-dropdown`
    (out of scope per CONVENTIONS.md §7.3) to show a "More" menu of the
    hidden triggers. Neither is portable to a single server render, so the
    "More" menu (`tedi-tabs-list__more`) and the `--overflow` collapse are
    dropped entirely — `dropdownLabel` is accepted for API parity but is
    inert (it only labels that unported menu).

    `overflowMode="scroll"` IS ported for its static class
    (`tedi-tabs-list__items--scroll`, which just sets `overflow-x: auto` — no
    measurement needed), so triggers genuinely scroll horizontally. Its fade
    indicators (`--fade-start` / `--fade-end`) still need scroll-position
    measurement and are dropped.
--}}
@props([
    /** Accessible label for the tablist. */
    'ariaLabel' => null,
    /** Id of the element labelling the tablist. */
    'ariaLabelledby' => null,
    /** dropdown|scroll — see the divergence note above; only "scroll" has a portable effect here. */
    'overflowMode' => 'dropdown',
    /** Label for the overflow dropdown trigger. Kept for API parity — inert since the "More" menu isn't ported. */
    'dropdownLabel' => null,
])

@php
    $itemsClass = 'tedi-tabs-list__items'.($overflowMode === 'scroll' ? ' tedi-tabs-list__items--scroll' : '');
@endphp

<div {{ $attributes->class(['tedi-tabs-list']) }}>
    <div
        role="tablist"
        data-name="tabs-list"
        class="{{ $itemsClass }}"
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if ($ariaLabelledby) aria-labelledby="{{ $ariaLabelledby }}" @endif
    >
        {{ $slot }}
    </div>
</div>
