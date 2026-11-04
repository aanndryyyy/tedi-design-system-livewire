{{--
    TEDI Sheet Header.
    Port of react/src/tedi/components/overlays/sheet/components/sheet-header/sheet-header.tsx
    (CONVENTIONS.md §13).

    The title bar: an optional `start` slot, the title, then an action group of
    the `end` slot, the collapse toggle, the `actions` slot and the close
    button, in upstream's order. Passing a default slot replaces that whole
    layout with your own content, as upstream's `children` does.

    The title is a real heading at `heading-level` (default h3) styled as h6
    whatever the level, and `tedi:sheet-content` names the dialog after it
    (`aria-labelledby`) — see the note there.

    The collapse toggle is `tedi:collapse-button`, bound to the sheet's state
    through `state="expanded"` (the inverse of `collapsed`), with upstream's
    `sheet.collapse` / `sheet.expand` labels and `inverted` on the brand
    header.

    DIVERGENCE — the close button on the brand header. Upstream renders its
    `ClosingButton` with `color="white"`, a React-only variant
    (`tedi-closing-button--color-white`, built on the inverted neutral button
    tokens). This package's closing button is Angular's, which has no colour
    variants, so on the brand header the close control is a
    `tedi:button variant="neutral-inverted"` icon-only button instead — the
    same tokens, as a button TEDI does ship. The default header keeps the
    `tedi:closing-button`. `closeButtonProps` is not ported — a Blade
    component tag cannot spread an arbitrary attribute bag onto a child; turn
    `close-button` off and put your own control in `actions` instead.
--}}
@props([
    /** Heading text. Ignored when the default slot replaces the layout. */
    'title' => null,
    /** h1–h6 — the semantic level; the visual style stays h6. */
    'headingLevel' => 'h3',
    /** brand|default — the colour scheme. */
    'variant' => 'brand',
    /** start|center — where the title sits. */
    'titlePosition' => 'start',
    /** Show a toggle that collapses the sheet to its header. */
    'collapsible' => false,
    /** Show the close button. */
    'closeButton' => true,
])

@aware([
    /** Matches tedi:sheet's default. */
    'collapsed' => false,
])

@php
    // Explicit checks rather than truthiness, so a title of `0` still renders.
    $hasTitle = $title !== null && $title !== false && $title !== '';
    $hasCustom = trim((string) $slot) !== '';
    $titleId = \Tedi\Livewire\Tedi::id('tedi-sheet-title');
    $isBrand = $variant === 'brand';
@endphp

<div {{ $attributes->class([
    'tedi-sheet__header',
    'tedi-sheet__header--'.$variant,
    'tedi-sheet__header--center' => $titlePosition === 'center',
]) }}>
    @if ($hasCustom)
        {{ $slot }}
    @else
        @if (isset($start) && trim((string) $start) !== '')
            <div class="tedi-sheet__header-start">{{ $start }}</div>
        @endif

        @if ($hasTitle)
            <tedi:text :as="$headingLevel" modifiers="h6" id="{{ $titleId }}" class="tedi-sheet__title">{{ $title }}</tedi:text>
        @endif

        <div class="tedi-sheet__header-actions">
            {{ $end ?? '' }}

            @if ($collapsible)
                <tedi:collapse-button
                    class="tedi-sheet__collapse-toggle"
                    hide-text
                    state="expanded"
                    :open="! $collapsed"
                    :inverted="$isBrand"
                    :open-text="__('tedi::tedi.sheet.expand')"
                    :close-text="__('tedi::tedi.sheet.collapse')"
                />
            @endif

            {{ $actions ?? '' }}

            @if ($closeButton)
                @if ($isBrand)
                    <tedi:button
                        variant="neutral-inverted"
                        icon-start="close"
                        icon-only
                        :aria-label="__('tedi::tedi.close')"
                        :title="__('tedi::tedi.close')"
                        x-on:click="hide()"
                    />
                @else
                    <tedi:closing-button x-on:click="hide()" />
                @endif
            @endif
        </div>
    @endif
</div>
