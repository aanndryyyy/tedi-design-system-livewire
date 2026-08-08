{{--
    TEDI Header Mobile Button.
    Port of angular/tedi/components/layout/header/header-mobile-button/header-mobile-button.component.{ts,html}

    `classes()` has no `host:` binding in Angular — it is applied to the
    rendered `<a>`/`<button>` in the template, so (unlike most sub-components
    here) `$attributes` also lands on that same inner element, not a wrapper.
--}}
@props([
    /** Material Symbols icon name. Required. */
    'icon',
    'label' => null,
    /** Renders as <a> when set and not disabled. */
    'href' => null,
    'selected' => false,
    'disabled' => false,
    /** Accessible name override. */
    'ariaLabel' => null,
    /** Forwarded as aria-haspopup. Only applied when rendered as <button>. */
    'ariaHasPopup' => null,
    /** Forwarded as aria-expanded. Only applied when rendered as <button>. */
    'ariaExpanded' => null,
])

@php
    $renderAsLink = (bool) $href && ! $disabled;
@endphp

@if ($renderAsLink)
    <a
        href="{{ $href }}"
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        {{ $attributes->class([
            'tedi-header-mobile-button',
            'tedi-header-mobile-button--selected' => $selected,
            'tedi-header-mobile-button--disabled' => $disabled,
        ]) }}
    >
        <span class="tedi-header-mobile-button__inner">
            <tedi:icon :name="$icon" color="inherit" />
            @if ($label)
                <tedi:text as="span" modifiers="extra-small" class="tedi-header-mobile-button__text">
                    {{ $label }}
                </tedi:text>
            @endif
        </span>
    </a>
@else
    <button
        type="button"
        @disabled($disabled)
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if ($ariaHasPopup) aria-haspopup="{{ $ariaHasPopup }}" @endif
        @if (! is_null($ariaExpanded)) aria-expanded="{{ $ariaExpanded ? 'true' : 'false' }}" @endif
        {{ $attributes->class([
            'tedi-header-mobile-button',
            'tedi-header-mobile-button--selected' => $selected,
            'tedi-header-mobile-button--disabled' => $disabled,
        ]) }}
    >
        <span class="tedi-header-mobile-button__inner">
            <tedi:icon :name="$icon" color="inherit" />
            @if ($label)
                <tedi:text as="span" modifiers="extra-small" class="tedi-header-mobile-button__text">
                    {{ $label }}
                </tedi:text>
            @endif
        </span>
    </button>
@endif
