{{--
    TEDI Process Timeline (community).
    Port of angular/src/community/components/helpers/process-timeline/process-timeline.component.ts

    Ported from the `community/` tree (CONVENTIONS.md §12). An informational
    timeline of the stages of a business process. It is not a stepper: the
    stages are not clickable or focusable, only the buttons and links inside
    them are.

    Angular's selector is `ol[tedi-process-timeline]`, so per CONVENTIONS.md §4
    the root is a native `<ol>` carrying the literal attribute. `role="list"`
    is explicit, as upstream's is, because Safari drops list semantics from a
    list styled with `list-style: none`.

    Angular provides PROCESS_TIMELINE to its items, which read `vertical`,
    `connectorThickness` and `items` from it. Here:

    | Angular (token) | Blade |
    |---|---|
    | `vertical()` | `tedi:process-timeline-item` reads `orientation` through @aware |
    | `connectorThickness()` | read through @aware |
    | `items()` (index, isLast) | explicit `index` / `last` props on each item (§5) |

    The item's @aware fallbacks equal the defaults below (CONVENTIONS.md §3);
    tests/AwareTest.php pins this.

    DIVERGENCE: `orientation` takes `'horizontal'` or `'vertical'` only, and
    defaults to `'horizontal'`. Upstream also accepts a breakpoint object and
    defaults to `{ xs: "vertical", md: "horizontal" }`, which BreakpointService
    resolves at runtime. Breakpoint-valued props are not ported
    (CONVENTIONS.md §7.1). The default is `'horizontal'` because the item's
    @aware fallback must equal it, and Angular's item outside a timeline is
    horizontal (`vertical() ?? false`).
--}}
@props([
    /** horizontal|vertical — layout direction. */
    'orientation' => 'horizontal',
    /** 1|2 — thickness of the connectors between stages, in pixels. */
    'connectorThickness' => 1,
])

@php
    $vertical = $orientation === 'vertical';
@endphp

<ol tedi-process-timeline {{ $attributes->class([
    'tedi-process-timeline',
    'tedi-process-timeline--vertical' => $vertical,
    'tedi-process-timeline--horizontal' => ! $vertical,
])->merge(['role' => 'list']) }}>
    {{ $slot }}
</ol>
