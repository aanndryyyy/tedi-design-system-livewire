{{--
    TEDI Collapse.
    Port of angular/tedi/components/buttons/collapse/collapse.component.{ts,html}

    ROOT ELEMENT. Angular's selector is `tedi-collapse` (an element selector), so
    per CONVENTIONS.md §4 the root is the literal `<tedi-collapse>` element. The
    Angular template then puts `.tedi-collapse` on an inner `<div>`; the two are
    merged into one root here, which CONVENTIONS.md §6 requires anyway. The merge
    is safe: nothing in the vendored SCSS targets `tedi-collapse` as an element
    (`collapse.component.scss`'s only element-ish rule is `:host { display: block }`,
    which — under `ViewEncapsulation.None` — is emitted verbatim into
    `dist/tedi.css` and matches nothing in the light DOM, in Angular too), and the
    `.tedi-collapse--open > .tedi-collapse__content` direct-child relationship the
    open/close animation depends on is preserved.

    STATE (CONVENTIONS.md §8). Angular owns `isOpen` as a signal. Here the open
    state is an Alpine variable declared on this root (`tediCollapseOpen`) and
    handed to `<tedi:collapse-button>` via its `state` prop, so the button binds
    to this scope rather than shadowing it.

    No `x-show` is needed: the content is always in the DOM with its real class
    list, and TEDI's own CSS does the collapsing (`.tedi-collapse__content` is a
    `grid-template-rows: 0fr` grid that becomes `1fr` under `--open`).

    `defaultOpen` is applied on first render. Angular defers it to
    `ngAfterViewInit` purely so the CSS transition plays on mount; the resolved
    state is identical.
--}}
@props([
    /** Label shown on the toggle button while collapsed. */
    'openText' => null,
    /** Label shown on the toggle button while expanded. */
    'closeText' => null,
    /** Open on first render. */
    'defaultOpen' => false,
    /** Hide openText/closeText and render the chevron only. */
    'hideCollapseText' => false,
    /** default|secondary — chevron style. Only takes effect with hideCollapseText. */
    'arrowType' => 'default',
    /** default|small — visual size of the toggle button. */
    'size' => 'default',
    /** Light text/icon for a dark background. Ignored when arrowType is "secondary". */
    'inverted' => false,
])

@php
    $contentId = \Tedi\Livewire\Tedi::id('collapse-content');
@endphp

<tedi-collapse
    x-data="{ tediCollapseOpen: {{ $defaultOpen ? 'true' : 'false' }} }"
    {{ $attributes->class([
        'tedi-collapse',
        'tedi-collapse--open' => (bool) $defaultOpen,
        'tedi-collapse--inverted' => $inverted && $arrowType !== 'secondary',
    ]) }}
    x-bind:class="{ 'tedi-collapse--open': tediCollapseOpen }"
>
    <tedi:collapse-button
        state="tediCollapseOpen"
        :open="(bool) $defaultOpen"
        :open-text="$openText"
        :close-text="$closeText"
        :hide-text="(bool) $hideCollapseText"
        :arrow-type="$arrowType"
        :size="$size"
        :inverted="(bool) $inverted"
        :aria-controls="$contentId"
    />

    <div class="tedi-collapse__content" id="{{ $contentId }}">
        <div class="tedi-collapse__extender">
            {{ $slot }}
        </div>
    </div>
</tedi-collapse>
