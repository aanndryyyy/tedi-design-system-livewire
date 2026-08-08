{{--
    TEDI Accordion.
    Port of angular/tedi/components/content/accordion/accordion/accordion.component.ts

    `allowMultiple` and `defaultExpanded` are exposed to `tedi-accordion-item`
    children via @aware (Angular: `inject(AccordionComponent, {optional:true})`).

    KNOWN DIVERGENCE: Angular's `onItemToggled()` auto-collapses sibling items
    when `allowMultiple` is false, coordinated through the injected parent
    component instance. Blade items are independent Alpine components with no
    shared instance to inject into, so this port keeps each item's expanded
    state independent — `allowMultiple="false"` no longer auto-closes the
    other items. Documented per CONVENTIONS.md §7.
--}}
@props([
    /** Whether the accordion allows multiple items to be expanded at the same time. */
    'allowMultiple' => false,
    /** Group-level default for items' initial expanded state. */
    'defaultExpanded' => null,
    /** Vertical gap between sibling items, in rem. */
    'itemGap' => null,
])

<div
    @if ($itemGap !== null) style="--tedi-accordion-item-gap: {{ $itemGap }}rem" @endif
    {{ $attributes->class(['tedi-accordion']) }}
>
    {{ $slot }}
</div>
