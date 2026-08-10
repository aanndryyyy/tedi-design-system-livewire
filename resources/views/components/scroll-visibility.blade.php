{{--
    TEDI Scroll Visibility.
    Port of react/src/tedi/components/misc/scroll-visibility/scroll-visibility.tsx
    (CONVENTIONS.md §13).

    Fades and slides its content out of the way once the page has been scrolled
    past a threshold — the usual home for a hide-on-scroll header or a
    back-to-top button.

    Upstream clones its single child and merges the classes onto it. Blade
    cannot introspect a slot (CONVENTIONS.md §5/§13.5), so this ports as the
    wrapper element upstream falls back to when the child is not a valid
    element — the same shape `tedi:choicegroup` already uses for an Angular
    directive. The transition is on the wrapper, so the visual is identical;
    what differs is one extra element in the tree.

    Behaviour lives in `tediScrollVisibility` (resources/js/src/scroll-visibility.js),
    a direct port of the component's effects, and is layered per CONVENTIONS.md
    §8: the markup ships with its real class list and is visible, so stripping
    the JS leaves permanently-visible content rather than nothing. The bound
    `--hidden` class comes after `$attributes->class()`, per §4.

    `scroll-container` takes a CSS selector rather than an element, since Blade
    has no element references to pass.
--}}
@props([
    /** left|right|up|down|center — the direction the content animates towards. */
    'animationDirection' => 'center',
    /** False disables the behaviour and leaves the content visible. */
    'enabled' => true,
    /** hide: hidden past the threshold. show: visible past it. */
    'visibility' => 'hide',
    /** Flip the state back when the user scrolls the opposite way. */
    'toggleVisibility' => false,
    /** How far, in px, the user must scroll before the state changes. */
    'scrollDistance' => 100,
    /** down: measured from the top of the page. up: from the bottom. */
    'scrollDirection' => 'down',
    /** CSS selector of the scrolling element. Null = the page itself. */
    'scrollContainer' => null,
])

@php
    $config = [
        'enabled' => (bool) $enabled,
        'visibility' => $visibility,
        'toggleVisibility' => (bool) $toggleVisibility,
        'scrollDistance' => (int) $scrollDistance,
        'scrollDirection' => $scrollDirection,
        'scrollContainer' => $scrollContainer,
    ];
@endphp

<div
    {{ $attributes->class([
        'tedi-scroll-visibility',
        'tedi-scroll-visibility--'.$animationDirection,
    ]) }}
    x-data="tediScrollVisibility(@js($config))"
    x-bind:class="{ 'tedi-scroll-visibility--hidden': hidden }"
>{{ $slot }}</div>
