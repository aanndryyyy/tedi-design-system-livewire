{{--
    TEDI Process Timeline Title (community).
    Port of angular/src/community/components/helpers/process-timeline/process-timeline-title/process-timeline-title.component.ts

    Angular's selector is the element `tedi-process-timeline-title`, so per
    CONVENTIONS.md §4 the root is that literal custom element, carrying the
    host class. `.tedi-process-timeline-title` supplies `display: block`.

    Use it through the `title` slot of `tedi:process-timeline-item`, which
    renders this component. Angular projects it with
    `<ng-content select="tedi-process-timeline-title" />` so that it lands
    before the visually hidden state text. Blade cannot pick children out of a
    slot, so this component placed in the item's default slot would land
    inside `.tedi-process-timeline-item__content` instead.
--}}
<tedi-process-timeline-title {{ $attributes->class(['tedi-process-timeline-title']) }}>{{ $slot }}</tedi-process-timeline-title>
