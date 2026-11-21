{{--
    TEDI Popover Content.
    Port of angular/tedi/components/overlay/popover/popover-content/popover-content.component.{ts,html}

    Angular's selector is the element `tedi-popover-content`, and the vendored
    SCSS targets `.tedi-popover__container--arrow .tedi-popover-content` — so per
    CONVENTIONS.md §4 the root is the literal `<tedi-popover-content>` element
    carrying the class list as well. `.tedi-popover-content` sets `display: flex`,
    so the unknown element needs no display fallback.

    The four-branch template (title + close / close / title / neither) is ported
    verbatim, including the `size="small"` closing button that only the
    close-without-title branch uses. `handleClose()` calls the popover's
    `hidePopover(true)`, which is `tediOverlay`'s `hide(true)` — hide and return
    focus to the trigger.

    `maxWidth="none"` emits no modifier: Angular suppresses the class for that
    value and the vendored SCSS has no `.tedi-popover-content--none` rule.
    Since Angular 9 any other non-preset value is a CSS length (`20rem`,
    `min(90vw, 30rem)`): it emits no modifier and is applied as an inline
    `width`, as upstream's `[style.width]` host binding does.

    `titleId` pairs the heading with the panel's `aria-labelledby`; it derives
    from `<tedi:popover>`'s `container-id` when that is set (see that
    component's header for the sibling-ARIA divergence), and is generated
    otherwise.

    In hover mode (`open-with="hover"` on the popover, read through `@aware`)
    the content keeps the popover open while hovered or focused and closes it
    when focus leaves both it and the trigger (Angular 10.0.0-rc.24, #879).
--}}
@aware([
    'containerId' => null,
    /** The popover's open-with; must equal its @props default — CONVENTIONS.md §3. */
    'openWith' => 'click',
])
@props([
    /** none|small|medium|large, or any CSS length ('20rem'), applied inline. */
    'maxWidth' => 'small',
    /** Heading title of the content. */
    'title' => '',
    /** Show the closing button. */
    'showClose' => false,
    /** Popover id used for ARIA pairing; normally inherited from the parent tedi-popover. */
    'containerId' => null,
    /** Id of the heading. Derived from container-id, or generated, when omitted. */
    'titleId' => null,
    /**
     * Inner padding on the `tedi:card` scale (Angular 9.1, #838): rems,
     * ['vertical' => …, 'horizontal' => …] or ['top' => …, 'right' => …, 'bottom' => …,
     * 'left' => …] (missing sides are 0). Null keeps the popover padding.
     */
    'padding' => null,
])

@php
    // Angular 9: a width outside the preset scale is a CSS length, set inline
    // with no preset class competing with it.
    $isPresetWidth = in_array($maxWidth, ['none', 'small', 'medium', 'large'], true);
    $rootAttributes = $attributes->class([
        'tedi-popover-content',
        'tedi-popover-content--'.$maxWidth => $isPresetWidth && $maxWidth !== 'none',
    ]);
    // customPadding(): the card scale's four sides as an inline `padding`.
    if ($padding !== null) {
        $sides = is_numeric($padding)
            ? array_fill_keys(['top', 'right', 'bottom', 'left'], $padding)
            : (array_key_exists('vertical', $padding) && array_key_exists('horizontal', $padding)
                ? ['top' => $padding['vertical'], 'right' => $padding['horizontal'], 'bottom' => $padding['vertical'], 'left' => $padding['horizontal']]
                : ['top' => $padding['top'] ?? 0, 'right' => $padding['right'] ?? 0, 'bottom' => $padding['bottom'] ?? 0, 'left' => $padding['left'] ?? 0]);
        $rootAttributes = $rootAttributes->style(['padding: '.implode(' ', array_map(fn ($v) => $v.'rem', $sides))]);
    }
    $headingId = $titleId
        ?: ($containerId ? $containerId.'_title' : \Tedi\Livewire\Tedi::id('popover-title'));
@endphp

<tedi-popover-content
    {{ $isPresetWidth ? $rootAttributes : $rootAttributes->style(['width: '.$maxWidth]) }}
    @if ($openWith === 'hover')
        x-on:mouseenter="contentEnter()"
        x-on:mouseleave="_contentHovered = false; scheduleHide()"
        x-on:focusin="cancelHide()"
        x-on:focusout="hoverFocusOut($event)"
    @endif
>
    @if ($title && $showClose)
        <div class="tedi-popover-content__head">
            <h4 class="tedi-popover-content__title" id="{{ $headingId }}">{{ $title }}</h4>
            <tedi:closing-button x-on:click="hide(true)" />
        </div>

        {{ $slot }}
    @elseif ($showClose)
        <div class="tedi-popover-content__head">
            <div>{{ $slot }}</div>
            <tedi:closing-button size="small" x-on:click="hide(true)" />
        </div>
    @elseif ($title)
        <h4 id="{{ $headingId }}">{{ $title }}</h4>

        {{ $slot }}
    @else
        {{ $slot }}
    @endif
</tedi-popover-content>
