{{--
    TEDI Radio Card Group.
    Port of angular/src/tedi/components/form/radio-card-group/radio-card-group.component.ts

    A plain flex-wrap layout wrapper for <x-radio-card> children; Angular's
    selector `tedi-radio-card-group` is an element selector, so it maps to a
    <div> with the host class, no attribute needed.

    `grouped` renders the button-group style layout (shared borders, no gap)
    and is also exposed as ambient data for descendant <x-radio-card>
    components to pick up via @aware — see radio-card.blade.php.

    `bordered` (added upstream in 9.0) is ambient data only: Angular's group
    host has no class for it, so none is emitted here. It is declared as a prop
    so it does not leak onto the <div> as an HTML attribute, and descendant
    cards read it through @aware.
--}}
@props([
    /** Renders children in a button-group style layout with shared borders and no gap. */
    'grouped' => false,
    /** Gives primary cards a border in the unselected state. Child cards inherit it; no effect on grouped cards, which always have a border. */
    'bordered' => false,
])

<div {{ $attributes->class([
    'tedi-radio-card-group',
    'tedi-radio-card-group--grouped' => (bool) $grouped,
]) }}>
    {{ $slot }}
</div>
