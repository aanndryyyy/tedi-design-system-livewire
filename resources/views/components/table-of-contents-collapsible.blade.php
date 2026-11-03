{{--
    TEDI Table of Contents — collapsible (mobile) variant.
    Port of angular/src/tedi/components/navigation/table-of-contents/table-of-contents-collapsible/table-of-contents-collapsible.component.{ts,html}

    A bottom bar that opens the list in a bottom sheet. Takes the same
    `tedi:table-of-contents-item` children as `tedi:table-of-contents`, which
    read `activeId` / `numbered` / `defaultOpen` from it through `@aware` —
    upstream's `providers: [{ provide: TableOfContentsComponent, useExisting }]`.
    Render it on small viewports (e.g. inside `tedi:hide-at breakpoint="lg"`).

    Behaviour is `Alpine.data('tediTableOfContentsCollapsible')` in
    resources/js/src/table-of-contents.js: the sheet's open state, Escape,
    backdrop click, closing when a link or button in the list is activated,
    focus to the first tabbable on open and back to the trigger on close, and
    `hide-on-scroll`. Both collapse buttons drive the shared `open` state
    through `tedi:collapse-button`'s `state` prop.

    DROPPED CLASS: `tedi-table-of-contents-collapsible` on the root. Angular's
    host carries it, but the vendored SCSS has no rule for it, so dropping it
    is behaviourally identical (CONVENTIONS.md §4). The root is still the
    literal `tedi-table-of-contents-collapsible` element.

    DIVERGENCES:
    - CDK Dialog re-parents the sheet into an overlay container at <body>,
      inside `.cdk-overlay-pane` with a `.cdk-overlay-backdrop`. Here the
      backdrop (`tedi-table-of-contents__backdrop`) and the panel
      (`tedi-table-of-contents__sheet-panel`, `role="dialog"`,
      `aria-modal="true"`) stay where they are written, always in the DOM
      (§8) and shown with `x-show`. The placement CDK writes — fixed to the
      viewport bottom, full width, above the page — is inline style, at
      `--z-index-modal`. Inside a `transform`ed ancestor the fixed sheet is
      positioned relative to that ancestor (CONVENTIONS.md §11).
    - The sheet lists the same items the bar would open; there is no second
      copy of the slot.
    - `scrollContainer` is an `HTMLElement` input upstream. Blade cannot pass
      an element, so `scroll-container` is a CSS selector, resolved with
      `document.querySelector` when Alpine starts.
    - The focus trap CDK Dialog applies inside the sheet is not ported.
    - `heading=""` falls back to the translated title here, as upstream's
      `heading() || title` does.
--}}
@props([
    /** Heading on the bar and the sheet. Defaults to the translated title. */
    'heading' => null,
    /** Id of the currently active item. */
    'activeId' => null,
    /** Whether nested items are expanded by default; false shows only the active trail. */
    'defaultOpen' => true,
    /** Show auto-generated hierarchical numbers (1., 2., 2.1, …). */
    'numbered' => false,
    /** Draws a divider under each item except the last. */
    'bordered' => false,
    /** Pin the bar to the bottom of the viewport. False renders it inline. */
    'sticky' => true,
    /** Hide the bar while scrolling down and reveal it on scroll up. */
    'hideOnScroll' => false,
    /** CSS selector of the element whose scroll drives `hide-on-scroll`. Defaults to the window. */
    'scrollContainer' => null,
    /** Accessible name for the sheet's nav and dialog. Defaults to the visible title. */
    'ariaLabel' => null,
])

@php
    $title = filled($heading) ? $heading : __('tedi::tedi.table-of-contents.title');
    $navLabel = filled($ariaLabel) ? $ariaLabel : $title;
    $openLabel = __('tedi::tedi.open');
    $closeLabel = __('tedi::tedi.close');

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
        'hideOnScroll' => (bool) $hideOnScroll,
        'scrollContainer' => $scrollContainer,
    ]);
@endphp

<tedi-table-of-contents-collapsible
    data-name="table-of-contents-collapsible"
    x-data="tediTableOfContentsCollapsible({{ $config }})"
    x-modelable="activeId"
    {{ $attributes }}
>
    <div
        x-ref="trigger"
        @class([
            'tedi-table-of-contents__bar',
            'tedi-table-of-contents__bar--static' => ! $sticky,
        ])
        x-bind:class="{
            'tedi-table-of-contents__bar--hidden': open,
            'tedi-table-of-contents__bar--tucked': barHidden,
        }"
    >
        <tedi:text modifiers="bold" class="tedi-table-of-contents__bar-title">{{ $title }}</tedi:text>
        <tedi:collapse-button
            aria-haspopup="dialog"
            state="open"
            :open-text="$openLabel"
            :close-text="$closeLabel"
            :underline="false"
        />
    </div>

    <div
        class="tedi-table-of-contents__backdrop"
        style="display: none; position: fixed; inset: 0; z-index: var(--z-index-modal);"
        x-show="open"
        x-on:click="close()"
    ></div>

    <div
        class="tedi-table-of-contents__sheet-panel"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $navLabel }}"
        style="display: none; position: fixed; inset-inline: 0; bottom: 0; z-index: var(--z-index-modal);"
        x-ref="sheet"
        x-show="open"
    >
        <div @class([
            'tedi-table-of-contents',
            'tedi-table-of-contents__sheet',
            'tedi-table-of-contents--bordered' => $bordered,
        ])>
            <div class="tedi-table-of-contents__sheet-header">
                <tedi:text modifiers="bold" class="tedi-table-of-contents__bar-title">{{ $title }}</tedi:text>
                <tedi:collapse-button
                    state="open"
                    :open="true"
                    :open-text="$openLabel"
                    :close-text="$closeLabel"
                    :underline="false"
                />
            </div>
            <nav
                class="tedi-table-of-contents__sheet-nav"
                aria-label="{{ $navLabel }}"
                x-on:click="onListClick($event)"
            >
                <ul class="tedi-table-of-contents__list tedi-table-of-contents__list--headless" role="list">{!! $items !!}</ul>
            </nav>
        </div>
    </div>
</tedi-table-of-contents-collapsible>
