{{--
    TEDI Process Timeline Description (community).
    Port of angular/src/community/components/helpers/process-timeline/process-timeline-description/process-timeline-description.component.ts

    Angular's selector is the element `tedi-process-timeline-description`, so
    per CONVENTIONS.md §4 the root is that literal custom element, carrying the
    host class. `.tedi-process-timeline-description` supplies `display: block`.

    Use it through the `description` slot of `tedi:process-timeline-item`,
    which renders this component after the visually hidden state text. Placed
    in the item's default slot it would land inside
    `.tedi-process-timeline-item__content` (see process-timeline-title).
--}}
<tedi-process-timeline-description {{ $attributes->class(['tedi-process-timeline-description']) }}>{{ $slot }}</tedi-process-timeline-description>
