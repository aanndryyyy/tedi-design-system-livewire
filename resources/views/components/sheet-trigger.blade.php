{{--
    TEDI Sheet Trigger.
    Port of react/src/tedi/components/overlays/sheet/components/sheet-trigger/sheet-trigger.tsx
    (CONVENTIONS.md §13).

    Upstream clones its single child and merges Floating UI's reference props
    into it — the click toggle and `aria-haspopup` / `aria-expanded` /
    `aria-controls`. Blade cannot clone a slot (CONVENTIONS.md §13.5), so this
    is a wrapper `<span>` carrying the click, and `tediSheet` writes the three
    ARIA attributes onto the wrapper's first element child — the element
    upstream would have cloned — once Alpine boots. Put one interactive element
    in it, as upstream requires. The wrapper carries `data-tedi-contents`
    (`display: contents`, resources/scss/_alpine.scss) so it generates no box
    and the child lays out exactly as the clone would — as a flex item, say.
--}}
<span {{ $attributes }} data-tedi-contents data-tedi-sheet-trigger x-ref="trigger" x-on:click="toggle()">{{ $slot }}</span>
