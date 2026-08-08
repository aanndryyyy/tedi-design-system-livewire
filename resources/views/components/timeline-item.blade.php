{{--
    TEDI Timeline Item.
    Port of angular/tedi/components/helpers/timeline/timeline-item/timeline-item.component.{ts,html}

    The vendored SCSS targets the literal `tedi-timeline-item` element (a
    type selector, e.g. `tedi-timeline-item { display: contents; }`), mirroring
    Angular's real `<tedi-timeline-item>` custom element — so the root tag
    below must be that same custom element, not a <div>.

    `index` / `last` replace Angular's contentChildren-based auto-registration
    (which derives each item's position and whether it's the last one) per
    CONVENTIONS.md §5. `activeIndex` is inherited from the parent
    <tedi:timeline> via @aware, mirroring `inject(TimelineComponent)`.

    Angular's `isMobile()` (BreakpointService) reorders the timingsBottom
    slot's DOM position between desktop/mobile layouts. That reorder is not
    portable (CONVENTIONS.md §7) — the slot always renders in the desktop
    position (inside `.tedi-timeline__timings`), and the mobile-only
    `tedi-timeline__item--has-bottom` class (which also depends on
    isMobile()) is not emitted, since the vendored CSS applies its effect
    unconditionally rather than behind a breakpoint media query.

    Angular's `<ng-content select="tedi-timeline-title/description" />`
    become the named `title` / `description` slots; anything else in the
    default slot renders as extra item content (buttons, collapse, ...).
--}}
@props([
    /** Item timings; first entry renders larger than the rest. */
    'timings' => [],
    /** This item's position in the timeline, compared against activeIndex. */
    'index' => null,
    /** Whether this is the last item (suppresses the connecting separator). */
    'last' => false,
])
@aware(['activeIndex' => null])

@php
    $state = 'future';

    if ($activeIndex !== null && $index !== null) {
        if ((int) $activeIndex === (int) $index) {
            $state = 'current';
        } elseif ((int) $activeIndex > (int) $index) {
            $state = 'past';
        }
    }

    $color = in_array($state, ['current', 'past'], true) ? 'accent' : 'secondary';
    $dotSize = $state === 'current' ? 'large' : 'medium';
    $dotFilled = $state !== 'future';
@endphp

<tedi-timeline-item {{ $attributes->class(['tedi-timeline-item']) }}>
    <div class="tedi-timeline__timings">
        @foreach ($timings as $timing)
            <tedi:text as="div" color="tertiary" class="tedi-timeline__time">{{ $timing }}</tedi:text>
        @endforeach

        @isset($timingsBottom)
            <div class="tedi-timeline__timings-bottom">{{ $timingsBottom }}</div>
        @endisset
    </div>

    <div @class(['tedi-timeline__marker', 'tedi-timeline__marker--large' => $state === 'current'])>
        <tedi:separator :color="$color" variant="dot-only" :dot-size="$dotSize" :dot-filled="$dotFilled" />

        @unless ($last)
            <tedi:separator axis="vertical" :color="$state === 'past' ? 'accent' : 'secondary'" />
        @endunless
    </div>

    <div class="tedi-timeline__info">
        <div>
            @isset($title)
                <tedi-timeline-title>{{ $title }}</tedi-timeline-title>
            @endisset

            @isset($description)
                <tedi-timeline-description>{{ $description }}</tedi-timeline-description>
            @endisset
        </div>

        <div class="tedi-timeline__content">
            {{ $slot }}
        </div>
    </div>
</tedi-timeline-item>
