{{--
    TEDI Table of Contents.
    Port of angular/src/tedi/components/navigation/table-of-contents/table-of-contents.component.{ts,html}

    Formerly ported from Angular's `community/` tree. Upstream re-wrote the
    component in `tedi/` (a different API and markup), which is the source now
    (CONVENTIONS.md §1). The mobile half is the separate
    `tedi:table-of-contents-collapsible`.

    A navigation aid that lists the sections of a page and highlights the one
    the reader is on. Compose it from `tedi:table-of-contents-item` elements;
    nest items (in an item's `sub-items` slot) to build a two-level hierarchy.

    CONTROLLED, like upstream: `active-id` marks the active item and nothing
    in the component moves it. Scroll-spy and click-to-scroll are the
    consumer's (see the StickyInLayout story). For client-side control the
    root is an Alpine scope (`tediTableOfContents`) whose `activeId` is
    `x-modelable`, so `x-model` / `wire:model` on the component reach it and
    the items follow; with Livewire, re-rendering with a new `active-id` works
    as well.

    Root is the literal `tedi-table-of-contents` element (§4) with
    `data-name="table-of-contents"` and the sticky geometry custom properties
    inline (upstream's host style bindings). `variant="transparent"` renders a
    plain container; the default renders `tedi:card` with zero-padding content,
    as upstream.

    `heading`: omitted (null), it is the translated `table-of-contents.title`;
    `heading=""` renders headless — upstream's `[heading]="null"`, which Blade
    cannot express (§3). The nav is then named by `aria-label` (the
    translated title, or `aria-label`), otherwise by `aria-labelledby` the
    heading.

    Numbering of the items happens here: each item leaves a placeholder that
    its parent has already turned into a relative number, and this component
    resolves the top level (see the item's docblock).

    `scroll-active-into-view` is ported in resources/js/src/table-of-contents.js.
--}}
@props([
    /** Heading above the list. Omitted: the translated title. "" renders it headless. */
    'heading' => null,
    /** h1|h2|h3|h4|h5|h6 — semantic level of the heading; the visual style stays h4. */
    'headingLevel' => 'h3',
    /** default|transparent */
    'variant' => 'default',
    /** Draws a divider under each item except the last. */
    'bordered' => false,
    /** Id of the currently active item. */
    'activeId' => null,
    /** Keep the active item visible inside the TOC's own scroll area. */
    'scrollActiveIntoView' => false,
    /** Whether nested items are expanded by default; false shows only the active trail. */
    'defaultOpen' => true,
    /** Show auto-generated hierarchical numbers (1., 2., 2.1, …). */
    'numbered' => false,
    /** Stick the container to the viewport while scrolling. */
    'sticky' => true,
    /** CSS length at which the sticky TOC pins (default 1.5rem). */
    'stickyOffset' => null,
    /** Overrides the sticky height cap. */
    'stickyMaxHeight' => null,
    /** Accessible name for the nav landmark. */
    'ariaLabel' => null,
])

@php
    $titleLabel = __('tedi::tedi.table-of-contents.title');
    $resolvedHeading = $heading ?? $titleLabel;
    $hasHeading = filled($resolvedHeading);
    $navLabel = filled($ariaLabel) ? $ariaLabel : $titleLabel;
    $headingId = \Tedi\Livewire\Tedi::id('tedi-table-of-contents-heading');
    $headingTag = in_array($headingLevel, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true) ? $headingLevel : 'h3';

    $index = 0;
    $items = preg_replace_callback(
        '/__TEDI_TOC_(NUMBER|PREFIX)__/',
        function ($match) use (&$index) {
            if ($match[1] === 'NUMBER') {
                return (++$index).'.';
            }

            return (string) $index;
        },
        (string) $slot,
    );

    $config = \Illuminate\Support\Js::from([
        'activeId' => (string) $activeId,
        'defaultOpen' => (bool) $defaultOpen,
        'scrollActiveIntoView' => (bool) $scrollActiveIntoView,
    ]);

    $styles = array_values(array_filter([
        filled($stickyOffset) ? '--tedi-table-of-contents-sticky-top: '.$stickyOffset : null,
        filled($stickyMaxHeight) ? '--tedi-table-of-contents-sticky-max-height: '.$stickyMaxHeight : null,
    ]));
    $rootAttributes = $attributes->class([
        'tedi-table-of-contents',
        'tedi-table-of-contents--bordered' => $bordered,
    ]);
@endphp

<tedi-table-of-contents
    data-name="table-of-contents"
    x-data="tediTableOfContents({{ $config }})"
    x-modelable="activeId"
    {{ $styles ? $rootAttributes->style($styles) : $rootAttributes }}
>
    @php
        ob_start();
    @endphp
    @if ($hasHeading)
        <tedi:text :as="$headingTag" modifiers="h4" class="tedi-table-of-contents__heading" :id="$headingId">{{ $resolvedHeading }}</tedi:text>
    @endif
    <nav
        @if (! filled($ariaLabel) && $hasHeading) aria-labelledby="{{ $headingId }}" @endif
        @if (filled($ariaLabel) || ! $hasHeading) aria-label="{{ $navLabel }}" @endif
    >
        <ul @class([
            'tedi-table-of-contents__list',
            'tedi-table-of-contents__list--headless' => ! $hasHeading,
        ]) role="list">{!! $items !!}</ul>
    </nav>
    @php
        $inner = new \Illuminate\Support\HtmlString(ob_get_clean());
    @endphp

    @if ($variant === 'transparent')
        <div @class([
            'tedi-table-of-contents__container',
            'tedi-table-of-contents--transparent',
            'tedi-table-of-contents--sticky' => $sticky,
        ])>{{ $inner }}</div>
    @else
        <tedi:card :class="\Illuminate\Support\Arr::toCssClasses([
            'tedi-table-of-contents__container',
            'tedi-table-of-contents--sticky' => $sticky,
        ])">
            <tedi:card-content :padding="0">{{ $inner }}</tedi:card-content>
        </tedi:card>
    @endif
</tedi-table-of-contents>
