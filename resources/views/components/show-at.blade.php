{{--
    TEDI Show At.
    Port of angular/tedi/directives/show-at/show-at.directive.ts

    Shows its content at and above a grid breakpoint. `breakpoint="md"` means
    visible from 48rem up, hidden below it — the inclusive boundary upstream's
    `BreakpointService.isAboveBreakpoint` computes (`currentIndex >= targetIndex`).
    It is the exact complement of `tedi:hide-at`; both share
    `resources/js/src/breakpoint.js`, which explains why one matchMedia query
    covers both.

    Every note on `tedi:hide-at` applies here — the wrapper-element port, why the
    behaviour is Alpine rather than a class, and the dropped structural (`*showAt`)
    usage. Read that file's header first.

    FALLBACK WITHOUT THE JS. Without `dist/tedi.js` (or Alpine) the content is
    VISIBLE at every width, since nothing sets `display: none`. For `show-at`
    that is the more conspicuous half of the same trade-off `hide-at` makes:
    content intended for wide viewports appears on narrow ones too. Upstream
    would have hidden it. The port prefers showing content it cannot place over
    hiding content it cannot restore.
--}}
@props([
    /** xs|sm|md|lg|xl|xxl — visible at and above this breakpoint. Required. */
    'breakpoint',
])

<div {{ $attributes }}
     x-data="tediBreakpoint({ breakpoint: '{{ $breakpoint }}', mode: 'show' })"
     x-show="visible">
    {{ $slot }}
</div>
