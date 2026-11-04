{{--
    TEDI Timeline.
    Port of angular/src/tedi/components/helpers/timeline/timeline.component.ts

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
    /**
     * Vertical padding of each card-variant item, in rems (Card's scale). The
     * gaps between items and the card's top/bottom edges resolve to twice this
     * value; horizontal item padding is fixed at the card default. Unset uses
     * the stylesheet default, 0.5 (`--card-padding-xs`).
     */
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
