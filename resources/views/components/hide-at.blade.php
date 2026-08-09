{{--
    TEDI Hide At.
    Port of angular/tedi/directives/hide-at/hide-at.directive.ts

    Hides its content at and above a grid breakpoint. `breakpoint="md"` means
    hidden from 48rem up, visible below it — the inclusive boundary upstream's
    `BreakpointService.isBelowBreakpoint` computes (`currentIndex < targetIndex`).
    See `resources/js/src/breakpoint.js` for the derivation.

    Upstream is an attribute/structural DIRECTIVE with no template, so this
    ports as a WRAPPER element per the `tedi:choicegroup` precedent
    (CONVENTIONS.md §12).

    WHY ALPINE AND NOT A CLASS. Upstream resolves this entirely in JavaScript,
    against a CDK BreakpointObserver. There is no class to emit instead: TEDI
    ships no display utilities — core's bootstrap utilities cover flex and gap
    only — and CONVENTIONS.md §4 forbids inventing class names TEDI does not
    ship. So the behaviour is layered on with Alpine, additively per §8: no class
    is involved, so there is no class parity to break. This is the documented
    exception to §7's "breakpoint props are not ported" — that ruling is about
    props whose value is *picked* per breakpoint with no CSS to express the
    choice; here the whole component is the breakpoint, and matchMedia expresses
    it exactly.

    FALLBACK WITHOUT THE JS. A consumer who does not load `dist/tedi.js` (or
    Alpine) gets the content VISIBLE at every width, because nothing ever sets
    `display: none`. That is the safe direction — content is shown rather than
    silently lost — but it is NOT what Angular does: upstream hides while its
    observer has not yet emitted. The port takes visible-by-default deliberately;
    a `tedi:hide-at` is a progressive enhancement here, not a guarantee.

    DIVERGENCE — structural usage is not ported. Angular's `*hideAt` removes the
    element from the DOM entirely, while attribute `hideAt` only sets
    `display: none`. Blade has no structural-directive equivalent, and §8
    requires the markup to exist statically anyway, so only the attribute
    behaviour is ported: the content is always in the DOM and `x-show` toggles
    `display`. Consumers who must not ship the markup at all should use a plain
    `@if` on the server instead.
--}}
@props([
    /** xs|sm|md|lg|xl|xxl — hidden at and above this breakpoint. Required. */
    'breakpoint',
])

<div {{ $attributes }}
     x-data="tediBreakpoint({ breakpoint: '{{ $breakpoint }}', mode: 'hide' })"
     x-show="visible">
    {{ $slot }}
</div>
