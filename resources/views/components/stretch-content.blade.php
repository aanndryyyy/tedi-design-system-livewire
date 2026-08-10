{{--
    TEDI Stretch Content.
    Port of react/src/tedi/components/misc/stretch-content/stretch-content.tsx
    (CONVENTIONS.md §13).

    Makes a single child fill its container on one or both axes. The wrapper is
    `display: flex` and sets 100% width/height on itself and on its direct
    children, which is why the child does not need to cooperate.

    Note the class list has NO base `tedi-stretch-content` — upstream emits only
    the directional modifier, and the stylesheet only defines the three
    modifiers. Emitting a base class would be inventing one (CONVENTIONS.md §4).

    `role` is upstream's own prop; it needs no declaration here because it
    reaches the root through `$attributes` regardless.

    Breakpoint props (React's `BreakpointSupport`) are not ported —
    CONVENTIONS.md §7 item 1.
--}}
@props([
    /** both|horizontal|vertical — the axis (or axes) the child is stretched along. */
    'direction' => 'both',
])

<div {{ $attributes->class(['tedi-stretch-content--'.$direction]) }}>{{ $slot }}</div>
