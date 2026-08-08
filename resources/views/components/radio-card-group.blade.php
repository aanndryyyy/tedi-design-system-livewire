{{--
    TEDI Radio Card Group.
    Port of angular/tedi/components/form/radio-card-group/radio-card-group.component.ts

    A plain flex-wrap layout wrapper for <x-radio-card> children; Angular's
    selector `tedi-radio-card-group` is an element selector, so it maps to a
    <div> with the host class, no attribute needed.

    `grouped` renders the button-group style layout (shared borders, no gap)
    and is also exposed as ambient data for descendant <x-radio-card>
    components to pick up via @aware — see radio-card.blade.php.
--}}
@props([
    /** Renders children in a button-group style layout with shared borders and no gap. */
    'grouped' => false,
])

<div {{ $attributes->class([
    'tedi-radio-card-group',
    'tedi-radio-card-group--grouped' => (bool) $grouped,
]) }}>
    {{ $slot }}
</div>
