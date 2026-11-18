{{--
    TEDI Process Timeline Item (community).
    Port of angular/src/community/components/helpers/process-timeline/process-timeline-item/process-timeline-item.component.{ts,html}

    Angular's selector is `li[tedi-process-timeline-item]`, so per
    CONVENTIONS.md §4 the root is a native `<li>` carrying the literal
    attribute, with an explicit `role="listitem"` as upstream has.

    What Angular reads from the PROCESS_TIMELINE token:

    | Angular | Blade |
    |---|---|
    | `index()`: position in the timeline's `contentChildren()` | explicit `index` prop, 0-based; the indicator shows `index + 1` (§5) |
    | `isLast()`: last of `contentChildren()` | explicit `last` prop (§5) |
    | `vertical()` | `orientation` through @aware from `tedi:process-timeline` |
    | `connectorThickness()` | `connectorThickness` through @aware |

    The @aware fallbacks equal `tedi:process-timeline`'s defaults
    (CONVENTIONS.md §3). The item declares neither name itself, so @aware
    resolves the parent's value. That is the server render only: inside a
    horizontal timeline, upstream's overflow fallback runs in the browser
    (resources/js/src/process-timeline.js), which can add `--vertical` here
    and flip the connector separator's axis class and inline size.

    `<ng-content select="tedi-process-timeline-title" />` and
    `select="tedi-process-timeline-description"` become the `title` and
    `description` slots. They render `tedi:process-timeline-title` /
    `-description` around their content, forwarding the slot's attributes, so
    the order upstream gets from projection is kept: title, the visually
    hidden state text, description, then the default slot inside
    `__content`. `__content` is written on one line so that an empty default
    slot leaves it `:empty` and the vendored `display: none` applies.

    DIVERGENCE: `last` defaults to false. Upstream's item outside a timeline
    computes `isLast` as true and draws no connector. In a timeline only the
    final item is last, so false is the useful default; set `last` on the final
    item, and on a standalone item.
--}}
@aware([
    'orientation' => 'horizontal',
    'connectorThickness' => 1,
])
@props([
    /** completed|active|upcoming — status of the stage. */
    'state' => 'upcoming',
    /** 0-based position of this stage in its timeline; the indicator shows index + 1. */
    'index' => 0,
    /** Whether this is the final stage (no connector after it). */
    'last' => false,
])

@php
    $vertical = $orientation === 'vertical';

    $stateLabelKey = [
        'completed' => 'process-timeline.completed',
        'upcoming' => 'process-timeline.upcoming',
    ][$state] ?? null;
@endphp

<li tedi-process-timeline-item {{ $attributes->class([
    'tedi-process-timeline-item',
    'tedi-process-timeline-item--completed' => $state === 'completed',
    'tedi-process-timeline-item--active' => $state === 'active',
    'tedi-process-timeline-item--upcoming' => $state === 'upcoming',
    'tedi-process-timeline-item--vertical' => $vertical,
    'tedi-process-timeline-item--last' => (bool) $last,
])->merge(array_filter([
    'role' => 'listitem',
    'aria-current' => $state === 'active' ? 'step' : null,
])) }}>
    <span class="tedi-process-timeline-item__indicator" aria-hidden="true">{{ (int) $index + 1 }}</span>

    @unless ($last)
        <span class="tedi-process-timeline-item__connector" aria-hidden="true">
            <tedi:separator :axis="$vertical ? 'vertical' : 'horizontal'" :thickness="$connectorThickness" color="primary" />
        </span>
    @endunless

    <div class="tedi-process-timeline-item__body">
        @isset($title)
            <tedi:process-timeline-title :attributes="$title->attributes">{{ $title }}</tedi:process-timeline-title>
        @endisset

        @if ($stateLabelKey)
            <span class="sr-only">{{ __('tedi::tedi.'.$stateLabelKey) }}</span>
        @endif

        @isset($description)
            <tedi:process-timeline-description :attributes="$description->attributes">{{ $description }}</tedi:process-timeline-description>
        @endisset

        <div class="tedi-process-timeline-item__content">{{ $slot }}</div>
    </div>
</li>
