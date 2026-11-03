{{--
    TEDI Table of Contents Item.
    Port of angular/src/tedi/components/navigation/table-of-contents/table-of-contents-item/{table-of-contents-item.component.{ts,html},table-of-contents-item-suffix.directive.ts}

    Formerly ported from Angular's `community/` tree; upstream re-wrote the
    component in `tedi/`, which is the source now (CONVENTIONS.md §1). The
    community markup and its unprefixed `table-of-contents__*` classes are gone.

    Root is the literal `tedi-table-of-contents-item` element (§4), a
    `role="listitem"` carrying `tedi-table-of-contents__item`, plus
    `--selected` and `aria-current="location"` when `item-id` equals the parent
    table of contents' `active-id`. Inside it: the row (number, content,
    suffix), an optional separator, and the nested group.

    `activeId`, `numbered` and `defaultOpen` come from the parent
    `tedi:table-of-contents` / `tedi:table-of-contents-collapsible` through
    `@aware` — upstream's `inject(TableOfContentsComponent)`. The fallbacks
    equal the parents' `@props` defaults (§3). The selected class and
    `aria-current` are also bound to the parent's Alpine `activeId`, so a
    consumer driving it client-side (scroll-spy) updates the items in place.

    Three `contentChildren()` derivations, done on the server instead:

    * Sub-items go in the `sub-items` slot, not the default slot — Blade cannot
      split one slot by element type the way `<ng-content select>` does. They
      render in `<ul class="tedi-table-of-contents__group" role="list">`.
    * `level` (the row's `--level-N` modifier, capped at 2): every item
      renders a top-level row, and an item adds `--level-2` to every
      top-level row in its own `sub-items` HTML.

    DROPPED CLASS: `tedi-table-of-contents__row--level-1`. Angular emits
    `--level-` + level for every row, but the vendored SCSS styles only
    `--level-2`; level 1 is the base row, so dropping it is behaviourally
    identical (CONVENTIONS.md §4).
    * Hierarchical numbers (`1.`, `2.`, `2.1`, …, with `numbered`): an item
      cannot know its sibling index, so it emits a placeholder and each parent
      numbers its direct children's placeholders in document order, the root
      finishing the job. The rendered text is upstream's `numberLabel()`.

    `isOpen()`: with `default-open` (the default) the group always shows.
    Without it, upstream renders the group only on the active trail; here the
    group is always in the DOM (§8) and hidden with `x-show` — server-side
    from whether the sub-items HTML contains the active item, client-side from
    `isOpen()` in resources/js/src/table-of-contents.js. Each item reports its
    id through `data-toc-item-id` for that.

    The suffix (upstream's `[tediTocItemSuffix]` directive, which adds
    `tedi-table-of-contents__suffix` to the consumer's element) is the
    `suffix` slot, rendered in a `<span>` carrying that class — the wrapper
    shape this package uses for attribute directives.
--}}
@props([
    /** Marks the item active (via the parent's `active-id`) and parents nested items. */
    'itemId' => null,
    /** Render a separator below the item. */
    'separator' => false,
])

@aware([
    'activeId' => null,
    'numbered' => false,
    'defaultOpen' => true,
])

@php
    $isSelected = filled($itemId) && (string) $itemId === (string) $activeId;

    $subItemsHtml = isset($subItems) ? trim((string) $subItems) : '';
    $hasChildren = $subItemsHtml !== '';

    if ($hasChildren) {
        // level(): anything nested is level 2 (capped).
        $subItemsHtml = str_replace(
            'class="tedi-table-of-contents__row"',
            'class="tedi-table-of-contents__row tedi-table-of-contents__row--level-2"',
            $subItemsHtml,
        );

        // numberBase(): number the direct children; deeper placeholders were
        // already turned into "<prefix>.n" by their own parents.
        $index = 0;
        $subItemsHtml = preg_replace_callback(
            \Tedi\Livewire\Tedi::tocMarkerPattern(),
            function ($match) use (&$index) {
                if ($match[1] === 'NUMBER') {
                    $index++;
                }

                return \Tedi\Livewire\Tedi::tocMarker('PREFIX').'.'.$index;
            },
            $subItemsHtml,
        );
    }

    $containsActive = $isSelected || ($hasChildren && preg_match('/\saria-current="location"/', $subItemsHtml));
    $isOpen = $hasChildren && ($defaultOpen || $containsActive);
    $jsId = \Illuminate\Support\Js::from((string) $itemId);
@endphp

<tedi-table-of-contents-item
    role="listitem"
    data-name="table-of-contents-item"
    @if (filled($itemId)) data-toc-item-id="{{ $itemId }}" @endif
    {{ $attributes->class([
        'tedi-table-of-contents__item',
        'tedi-table-of-contents__item--selected' => $isSelected,
    ])->merge(array_filter([
        'aria-current' => $isSelected ? 'location' : null,
    ])) }}
    @if (filled($itemId))
        x-bind:class="{ 'tedi-table-of-contents__item--selected': activeId === {{ $jsId }} }"
        x-bind:aria-current="activeId === {{ $jsId }} ? 'location' : null"
    @endif
>
    <span class="tedi-table-of-contents__row">
        @if ($numbered)
            <span class="tedi-table-of-contents__number" aria-hidden="true">{{ \Tedi\Livewire\Tedi::tocMarker('NUMBER') }}</span>
        @endif
        <span class="tedi-table-of-contents__content">{{ $slot }}</span>
        @isset($suffix)
            <span class="tedi-table-of-contents__suffix">{{ $suffix }}</span>
        @endisset
    </span>

    @if ($separator)
        <div class="tedi-table-of-contents__separator">
            <tedi:separator />
        </div>
    @endif

    @if ($hasChildren)
        <ul
            class="tedi-table-of-contents__group"
            role="list"
            @unless ($isOpen) style="display: none;" @endunless
            x-show="isOpen($el)"
        >{!! $subItemsHtml !!}</ul>
    @endif
</tedi-table-of-contents-item>
