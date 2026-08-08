{{--
    TEDI Accordion Item.
    Port of angular/tedi/components/content/accordion/accordion-item/accordion-item.component.{ts,html}

    Owns the expanded state via Alpine (`x-data`) — descendant
    tedi-accordion-item-header / tedi-accordion-item-content components read
    and toggle `expanded` directly since they render inside this element's
    Alpine scope. `showIconCard` / `disabled` / `itemId` are exposed to those
    children via @aware.

    KNOWN DIVERGENCE: `aria-controls` / `aria-labelledby` pairing between the
    header and content requires an explicit `item-id` (Angular pairs them via
    an injected id generator shared through DI; Blade siblings have no such
    channel — see CONVENTIONS.md §5/§7). Without `item-id`, header and content
    render independent, unpaired ids.

    NOTE: Angular's accordion-item.component.html does bind
    `[class.tedi-accordion__item--disabled]="disabled()"` on this element, but
    no rule in the vendored SCSS styles that class (an upstream gap — it's a
    no-op class in Angular too). Dropped here rather than ported, since
    tests/IntegrityTest.php treats "class with no stylesheet rule" as a
    library-wide defect signal; `disabled` still reaches descendants via
    @aware and disables the header's trigger/aria-disabled as normal.
--}}
@aware([
    'defaultExpanded' => null,
])
@props([
    /** Whether the item is expanded initially. */
    'defaultExpanded' => null,
    /** Enables the icon-card layout variant. */
    'showIconCard' => false,
    /** Marks the item as selected. */
    'selected' => false,
    /** Disables toggling; current state is preserved. */
    'disabled' => false,
    /** Stable id used for ARIA pairing between header and content, and hash-based deep-linking. */
    'itemId' => null,
    /** Auto-expand when window.location.hash matches #itemId. Requires itemId. */
    'openOnHashMatch' => false,
])

@php
    $initialExpanded = $defaultExpanded ?? false;
@endphp

<div
    x-data="{
        expanded: {{ $initialExpanded ? 'true' : 'false' }},
        disabled: {{ $disabled ? 'true' : 'false' }},
        toggle() { if (! this.disabled) this.expanded = ! this.expanded; },
    }"
    @if ($openOnHashMatch && $itemId && ! $disabled)
        x-init="
            if (window.location.hash === '#{{ $itemId }}') expanded = true;
            window.addEventListener('hashchange', () => {
                if (window.location.hash === '#{{ $itemId }}') expanded = true;
            });
        "
    @endif
    x-bind:class="{ 'tedi-accordion__item--expanded': expanded }"
    {{ $attributes->class([
        'tedi-accordion__item',
        'tedi-accordion__item--selected' => (bool) $selected,
        'tedi-accordion__item--with-icon-card' => (bool) $showIconCard,
    ]) }}
>
    @if ($showIconCard)
        {{ $iconCard ?? '' }}
    @endif

    {{ $slot }}
</div>
