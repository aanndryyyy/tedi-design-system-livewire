{{--
    TEDI Table of Contents Item (community).
    Port of angular/community/components/navigation/table-of-contents/table-of-contents-item/table-of-contents-item.component.{ts,html}

    Ported from the `community/` tree — see CONVENTIONS.md §12. Upstream's
    selector is the element `tedi-table-of-contents-item`, and its template's
    first node is a `<div [class]="classes()">` that carries the block class.
    That div is the root here, with no custom element around it: §4 asks for the
    literal element name when *a rule keys on it*, and nothing in the vendored
    SCSS does — every selector in this component's stylesheet keys on
    `.table-of-contents__item`, including the nested-level indent
    (`.table-of-contents__item .table-of-contents__item .table-of-contents__item-anchor`),
    which is a descendant combinator and matches either way.

    `selected` is a signal upstream, written by the parent's scroll spy and by
    item clicks. Here it comes from `Alpine.data('tediTableOfContents')` on the
    parent, which compares its `activeId` against this item's `id-to` — see the
    engine in resources/js/tedi.js. `data-toc-id` is how the engine enumerates
    the items, standing in for Angular's `contentChildren()`.

    `selected` is also accepted as a prop so the active item is correct in the
    server-rendered HTML before Alpine boots (and without JS at all). Per
    CONVENTIONS.md §8 the class is real markup, not something JS conjures.

    DIVERGENCE: the anchor is a real `<a href="#id">`. Upstream renders
    `<a tedi-button>` with no href and a click handler, which is neither
    focusable nor usable without JS; the href makes the same navigation work
    when Alpine is absent, and the engine's smooth scroll takes over when it is.
--}}
@props([
    /** Id of the heading this item points at. Required. */
    'idTo',
    /** Marks the item active in the server-rendered HTML. */
    'selected' => false,
])

<div
    data-toc-id="{{ $idTo }}"
    {{ $attributes->class([
        'table-of-contents__item',
        'table-of-contents__item--active' => (bool) $selected,
    ]) }}
    {{-- Object syntax, not `cond && 'class'`: only the object form also REMOVES
         the class, which matters because `selected` may have server-rendered it
         and the scroll spy then moves on. --}}
    x-bind:class="{ 'table-of-contents__item--active': activeId === '{{ $idTo }}' }"
>
    <a
        href="#{{ $idTo }}"
        class="tedi-button tedi-button--neutral tedi-button--default tedi-button--pl tedi-button--pr table-of-contents__item-anchor"
        x-on:click.prevent="select('{{ $idTo }}')"
    >{{ $slot }}</a>

    {{ $subItems ?? '' }}
</div>
