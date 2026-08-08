{{--
    TEDI Timeline.
    Port of angular/tedi/components/helpers/timeline/timeline.component.ts

    Angular auto-registers <tedi-timeline-item> children (contentChildren) to
    compute each item's index/isLast. Blade can't introspect its own slot, so
    per CONVENTIONS.md §5 those become explicit props on <tedi:timeline-item>:
    `index` (compared against this component's `activeIndex` to derive
    current/past/future) and `last` (replaces isLast auto-detection).

    `activeIndex` is exposed as ambient data so <tedi:timeline-item> can pick
    it up via @aware — see timeline-item.blade.php.
--}}
@props([
    /** Index of the active item. */
    'activeIndex' => null,
    /** default|card */
    'variant' => 'default',
    /** Item padding in rems, for the card variant. */
    'cardPadding' => null,
])

<div
    @if ($variant === 'card' && $cardPadding !== null)
        style="--_timeline-card-padding: {{ $cardPadding }}rem"
    @endif
    {{ $attributes->class([
        'tedi-timeline',
        'tedi-timeline--card' => $variant === 'card',
    ]) }}
>
    {{ $slot }}
</div>
