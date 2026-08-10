{{--
    TEDI Hash Trigger.
    Port of react/src/tedi/components/navigation/hash-trigger/hash-trigger.tsx
    (CONVENTIONS.md §13).

    Marks a region of the page as the target of a URL hash, and scrolls to it
    when the hash names it — on first load and on every later `hashchange`.

    It has no stylesheet and emits no classes: the component is entirely the
    `id` and the listener. The behaviour lives in `tediHashTrigger`
    (resources/js/src/hash-trigger.js), which documents the three upstream rules
    worth knowing — the hash is a slash-separated *list*, an element already
    fully in the viewport is not scrolled, and the first match jumps instantly
    while later ones animate.

    Upstream clones its first child to put the `id` on it, falling back to a
    wrapping `<div id>` when the child is not a valid element. Blade cannot
    introspect a slot (CONVENTIONS.md §5/§13.5), so the port always takes that
    fallback branch — which is also the branch upstream's own docblock tells
    consumers to rely on ("Child component has to inject id to DOM itself").

    `on-match` is not a prop: upstream's callback becomes a `tedi:hash-match`
    DOM event on the root (CONVENTIONS.md §7 item 2), so bind it the way you
    bind any other event — put `x-on:tedi:hash-match="open = true"` on the
    `tedi:hash-trigger` tag. (Written without angle brackets on purpose —
    CONVENTIONS.md §2.)
--}}
@props([
    /** The element id the hash must name. Required — without it nothing runs. */
    'id',
    /** False fires the event but leaves scrolling to you. */
    'scrollOnMatch' => true,
])

<div
    id="{{ $id }}"
    {{ $attributes }}
    x-data="tediHashTrigger(@js(['id' => $id, 'scrollOnMatch' => (bool) $scrollOnMatch]))"
>{{ $slot }}</div>
