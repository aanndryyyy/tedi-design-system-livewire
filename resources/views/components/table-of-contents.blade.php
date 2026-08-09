{{--
    TEDI Table of Contents (community).
    Port of angular/community/components/navigation/table-of-contents/table-of-contents.component.{ts,html}

    Ported from the `community/` tree — see CONVENTIONS.md §12. Note the class
    names are NOT `tedi-`-prefixed upstream (`table-of-contents`,
    `table-of-contents__item`), which ports verbatim; the two stylesheet
    guardrails in tests/IntegrityTest.php only harvest `tedi-*` tokens, so this
    component's classes are pinned by its parity test instead.

    Angular's selector is the element `tedi-table-of-contents`, so per §4 the
    root is that literal custom element, with the `<nav class="table-of-contents">`
    inside it exactly as upstream nests them.

    Behaviour lives in `Alpine.data('tediTableOfContents')` in
    resources/js/tedi.js — scroll spy, seek-on-click and the mobile panel, all
    documented at the function. Each `tedi:table-of-contents-item` reports its
    target through `data-toc-id`, which is how the engine finds the headings
    without a `contentChildren()` equivalent.

    SUBSET — the mobile panel. Upstream opens a CDK dialog holding a second copy
    of the nav (`Dialog.open(templateRef)`); there is no CDK here and the
    package's own `tedi:modal` is a different markup shape. Instead the single
    rendered nav gets `table-of-contents--modal-active` — the class upstream's
    copy carries, and the only thing the stylesheet keys on
    (`display: initial`). So the panel appears in place, below the trigger,
    rather than centred over a backdrop. Everything else about it — the footer
    trigger, which breakpoint it appears at, closing on item click — is ported.

    DIVERGENCES:
    - `onToggle` / `open` as a controlled pair, and `defaultOpen`, collapse into
      the single `default-open` prop: Angular's `open`/`onToggle` inputs are
      never read by the component (only `isOpen()` is), so there is nothing to
      port them to. State is Alpine's.
    - `TableOfContentsNestedWrapperComponent` is not ported. It exists solely to
      work around angular/angular#57345 — an `@if` inside a component that uses
      `ng-content`. Blade has no such bug; nest items in the `sub-items` slot.
    - `modal-breakpoint="never"` is faithful to upstream and probably not what
      you want: it emits no `--modal-breakpoint-*` class, and the base rule is
      `display: none`, so the nav never shows at any width while the footer
      trigger still does. Use `desktop` for "trigger up to the widest
      breakpoint" instead.
--}}
@props([
    /** Heading shown above the items, and inside the footer trigger. Required. */
    'heading',
    /** default|fixed|sticky */
    'position' => 'default',
    /** Accessible label for the navigation landmark. */
    'ariaLabel' => 'Table of contents',
    /** mobile|tablet|desktop|never — the width at or above which the list replaces the trigger. */
    'modalBreakpoint' => 'mobile',
    /** Whether scrolling the page updates the active item. */
    'scrollAware' => true,
    /** Whether clicking an item smooth-scrolls to its target. */
    'scrollOnClick' => true,
    /** Whether the mobile panel starts open. */
    'defaultOpen' => false,
    /** Id of the item to mark active before the first scroll-spy pass. */
    'activeId' => '',
])

@php
    $config = json_encode([
        'activeId' => (string) $activeId,
        'scrollAware' => (bool) $scrollAware,
        'scrollOnClick' => (bool) $scrollOnClick,
    ], JSON_THROW_ON_ERROR);
@endphp

<tedi-table-of-contents
    x-data="tediTableOfContents({{ $config }})"
    @if ($defaultOpen) x-init="open = true" @endif
    {{ $attributes }}
>
    <nav
        aria-label="{{ $ariaLabel }}"
        class="{{ collect([
            'table-of-contents',
            'table-of-contents--position-'.$position,
            $modalBreakpoint !== 'never' ? 'table-of-contents--modal-breakpoint-'.$modalBreakpoint : null,
        ])->filter()->implode(' ') }}"
        x-bind:class="{ 'table-of-contents--modal-active': open }"
    >
        <tedi:card>
            <tedi:card-content class="table-of-contents__content">
                <tedi:text as="h1" modifiers="h4" class="table-of-contents__header">{{ $heading }}</tedi:text>

                <div class="table-of-contents__items">
                    {{ $slot }}
                </div>
            </tedi:card-content>
        </tedi:card>
    </nav>

    <tedi:card
        class="table-of-contents__footer table-of-contents__footer--modal-breakpoint-{{ $modalBreakpoint }}"
        x-show="! open"
    >
        <tedi:card-content>
            <button type="button" class="table-of-contents__footer-trigger" x-on:click="open = true">
                <tedi:text as="h1" modifiers="normal" class="table-of-contents__header">
                    {{ $heading }}

                    {{--
                        Upstream is `<div tedi-button variant="neutral">`, a DIV
                        wearing the button classes — it sits inside the real
                        <button> above, so it cannot be one itself, and that is
                        why `tedi:button` (which renders <button> or <a>) is not
                        used here. The class list is BaseButtonDirective's for
                        this content: `--pl` but no `--pr`, because the icon is
                        the last child.
                    --}}
                    <div class="tedi-button tedi-button--neutral tedi-button--default tedi-button--pl">
                        {{ __('tedi::tedi.open') }}
                        <tedi:icon name="expand_more" color="inherit" />
                    </div>
                </tedi:text>
            </button>
        </tedi:card-content>
    </tedi:card>
</tedi-table-of-contents>
