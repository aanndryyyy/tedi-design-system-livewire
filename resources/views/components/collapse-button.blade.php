{{--
    TEDI Collapse Button.
    Port of angular/tedi/components/buttons/collapse-button/collapse-button.component.{ts,html}

    Angular's selector is `button[tedi-collapse-button]` — an ATTRIBUTE selector
    — so per CONVENTIONS.md §4 the root carries the literal `tedi-collapse-button`
    attribute in addition to the class list.

    STATE (CONVENTIONS.md §8). Angular's parent owns `open` and listens to
    `openChange`. Blade has no such channel, so the open state lives in Alpine:

      * standalone — the button declares its own `x-data` and toggles itself.
      * inside `<tedi:collapse>` — the parent owns the state and passes its
        Alpine expression as `state="tediCollapseOpen"`, so the button binds to
        that instead of shadowing it with a scope of its own.

    Everything state-dependent (the `--open` modifier, `aria-expanded`, the
    visible label, the icon-only `aria-label`) is rendered *statically* for the
    `open` prop's value and *additionally* bound with `x-bind` / `x-text`, so a
    consumer who strips the JS still gets correct markup.

    `output<boolean>()` (`openChange`) is not re-emitted (CONVENTIONS.md §3);
    a consumer needing server awareness binds `wire:click` via `$attributes`.

    Angular's `id` input is NOT declared as a prop: it exists there only because
    a host binding is the only way to reach the host element. In Blade `id=""`
    arrives on the root through `$attributes` already (CONVENTIONS.md §6).
--}}
@props([
    /** Current open state. Also the initial value when this button owns its own state. */
    'open' => false,
    /** Label shown when collapsed. Falls back to the translated "open". */
    'openText' => null,
    /** Label shown when expanded. Falls back to the translated "close". */
    'closeText' => null,
    /** Hide the label and render the chevron only. */
    'hideText' => false,
    /** default|secondary — chevron style. Only takes effect with hideText. */
    'arrowType' => 'default',
    /** default|small — visual size. */
    'size' => 'default',
    /** Light text/icon for a dark background. Ignored when arrowType is "secondary". */
    'inverted' => false,
    /** Underline the text label. No effect in icon-only mode. */
    'underline' => true,
    /** ID of the disclosed region, forwarded to aria-controls. */
    'ariaControls' => null,
    /** Accessible label. Required when hideText is true. */
    'ariaLabel' => null,
    /** Blade-only: Alpine expression owning the open state. Null = own x-data. */
    'state' => null,
])

@php
    $openExpr = $state ?? 'tediCollapseButtonOpen';

    $openLabel = $openText ?? __('tedi::tedi.open');
    $closeLabel = $closeText ?? __('tedi::tedi.close');
    $label = $open ? $closeLabel : $openLabel;

    // resolvedAriaLabel(): null while visible text is present, so the button's
    // own text content stays the accessible name (WCAG 2.5.3).
    $resolvedAriaLabel = $hideText ? ($ariaLabel ?? $label) : null;

    $iconSize = $hideText ? 24 : 16;
    $iconVariant = $hideText ? 'filled' : 'outlined';
@endphp

<button
    tedi-collapse-button
    @if ($state === null) x-data="{ {{ $openExpr }}: {{ $open ? 'true' : 'false' }} }" @endif
    x-on:click="{{ $openExpr }} = ! {{ $openExpr }}"
    {{ $attributes->class([
        'tedi-collapse-button',
        'tedi-collapse-button--open' => (bool) $open,
        'tedi-collapse-button--small' => $size === 'small',
        'tedi-collapse-button--inverted' => $inverted && $arrowType !== 'secondary',
        'tedi-collapse-button--icon-only' => (bool) $hideText,
        'tedi-collapse-button--secondary' => $hideText && $arrowType === 'secondary',
        'tedi-collapse-button--neutral' => $hideText && $arrowType !== 'secondary',
        'tedi-collapse-button--no-underline' => ! $underline && ! $hideText,
    ])->merge(array_filter([
        'type' => 'button',
        'aria-expanded' => $open ? 'true' : 'false',
        'aria-controls' => $ariaControls,
        'aria-label' => $resolvedAriaLabel,
    ])) }}
    x-bind:class="{ 'tedi-collapse-button--open': {{ $openExpr }} }"
    x-bind:aria-expanded="{{ $openExpr }}"
    @if ($hideText && ! $ariaLabel)
        x-bind:aria-label="{{ $openExpr }} ? @js($closeLabel) : @js($openLabel)"
    @endif
>
    @if (! $hideText)
        <span class="tedi-collapse-button__text" x-text="{{ $openExpr }} ? @js($closeLabel) : @js($openLabel)">{{ $label }}</span>
        <span class="tedi-collapse-button__icon-pad">
            <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$iconVariant" :size="$iconSize" />
        </span>
    @elseif ($arrowType === 'secondary')
        <span class="tedi-collapse-button__icon-wrapper">
            <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$iconVariant" :size="$iconSize" />
        </span>
    @else
        <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$iconVariant" :size="$iconSize" />
    @endif
</button>
