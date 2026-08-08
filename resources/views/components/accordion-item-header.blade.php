{{--
    TEDI Accordion Item Header.
    Port of angular/tedi/components/content/accordion/accordion-item-header/accordion-item-header.component.{ts,html}

    Slots mirror Angular's attribute selectors: start-action, before-title,
    title, start-description, after-title, end-description, end-action.

    `expanded` is read/toggled via the enclosing tedi-accordion-item's Alpine
    scope (`expanded` / `toggle()`), since this component renders inside it.

    The non-clickable branch (`headerClickable=false`) renders a
    `tedi-collapse-button` in Angular. That component isn't part of this
    port's scope, so its markup/classes are inlined here (duplicated once for
    the start position, once for end — the two are mutually exclusive at
    render time since `expandActionPosition` is single-valued) from
    buttons/collapse-button/collapse-button.component.{ts,html,scss} —
    documented per CONVENTIONS.md §3.
--}}
@aware([
    'itemId' => null,
    'disabled' => false,
    'showIconCard' => false,
])
@props([
    /** If false, disables header toggling and allows interactive elements inside the header. */
    'headerClickable' => true,
    /** hug|fill */
    'titleLayout' => 'hug',
    'openText' => null,
    'closeText' => null,
    'showExpandLabel' => true,
    'showDefaultExpandAction' => true,
    /** start|end */
    'expandActionPosition' => 'end',
    'headerClass' => null,
    /** 1|2|3|4|5|6|null — wraps the trigger in a semantic heading. */
    'headingLevel' => null,
    /** default|secondary */
    'expandActionArrowType' => 'default',
    /** default|small|null — derived from showExpandLabel when omitted. */
    'expandActionSize' => null,
    'expandActionInverted' => false,
    'expandActionUnderline' => false,
])

@php
    $headerId = $itemId ? $itemId.'-header' : \Tedi\Livewire\Tedi::id('tedi-accordion-item-header');
    $contentId = $itemId ? $itemId.'-content' : null;

    $openLabel = $openText ?? __('tedi::tedi.open');
    $closeLabel = $closeText ?? __('tedi::tedi.close');

    $showStartExpandAction = $showDefaultExpandAction && $expandActionPosition === 'start';
    $showEndExpandAction = $showDefaultExpandAction && $expandActionPosition === 'end';
    $resolvedExpandActionSize = $expandActionSize ?? ($showExpandLabel ? 'default' : 'small');

    $headingLevelInt = is_numeric($headingLevel) ? (int) $headingLevel : null;
    $headingTag = in_array($headingLevelInt, [1, 2, 3, 4, 5, 6], true) ? 'h'.$headingLevelInt : null;

    // Inlined tedi-collapse-button classes (headerClickable=false branch).
    $ccClasses = ['tedi-collapse-button'];
    if ($expandActionInverted && $expandActionArrowType !== 'secondary') {
        $ccClasses[] = 'tedi-collapse-button--inverted';
    }
    if ($resolvedExpandActionSize === 'small') {
        $ccClasses[] = 'tedi-collapse-button--small';
    }
    $ccIconOnly = ! $showExpandLabel;
    if ($ccIconOnly) {
        $ccClasses[] = 'tedi-collapse-button--icon-only';
        $ccClasses[] = $expandActionArrowType === 'secondary' ? 'tedi-collapse-button--secondary' : 'tedi-collapse-button--neutral';
    }
    if (! $expandActionUnderline && ! $ccIconOnly) {
        $ccClasses[] = 'tedi-collapse-button--no-underline';
    }
    $ccIconSize = $ccIconOnly ? 24 : 16;
    $ccIconVariant = $ccIconOnly ? 'filled' : 'outlined';
@endphp

<div
    x-bind:class="{
        'tedi-accordion-item-header--hoverable': {{ $headerClickable ? 'true' : 'false' }} && ! disabled,
        'tedi-accordion-item-header--expanded': expanded,
    }"
    {{ $attributes->class([
        'tedi-accordion-item-header',
        'tedi-accordion-item-header--with-icon-card' => (bool) $showIconCard,
        'tedi-accordion-item-header--disabled' => (bool) $disabled,
        $headerClass => (bool) $headerClass,
    ]) }}
>
@if ($headingTag)
<{{ $headingTag }} class="tedi-accordion-item-header__heading-wrapper">
@endif

@if ($headerClickable)
<button
    id="{{ $headerId }}"
    type="button"
    class="tedi-accordion-item-header__trigger"
    @click="toggle()"
    @disabled($disabled)
    x-bind:aria-expanded="expanded"
    @if ($contentId) aria-controls="{{ $contentId }}" @endif
>
@else
<div id="{{ $headerId }}" class="tedi-accordion-item-header__trigger">
@endif

    <div class="tedi-accordion-item-header__start">
        {{ $startAction ?? '' }}
        {{ $beforeTitle ?? '' }}

        <span class="tedi-accordion-item-header__title {{ $titleLayout === 'fill' ? 'tedi-accordion-item-header__title--grow' : '' }}">
            <span class="tedi-accordion-item-header__title-main">
                @if ($showStartExpandAction)
                    @if ($headerClickable)
                        <div
                            class="tedi-text tedi-accordion-item-header__expand-indicator"
                            x-bind:class="{ 'tedi-accordion-item-header__expand-indicator--with-label': {{ $showExpandLabel ? 'true' : 'false' }} }"
                        >
                            @if ($showExpandLabel)
                                <span x-text="expanded ? @js($closeLabel) : @js($openLabel)"></span>
                            @endif
                            <tedi:icon
                                name="expand_more"
                                class="tedi-accordion-item-header__icon"
                                x-bind:class="{ 'tedi-accordion-item-header__icon--expanded': expanded, 'tedi-accordion-item-header__icon--no-label': {{ $showExpandLabel ? 'false' : 'true' }} }"
                            />
                        </div>
                    @else
                        <button
                            type="button"
                            class="{{ implode(' ', $ccClasses) }}"
                            x-bind:class="{ 'tedi-collapse-button--open': expanded }"
                            @click="toggle()"
                            x-bind:aria-expanded="expanded"
                            @if ($contentId) aria-controls="{{ $contentId }}" @endif
                            @if ($ccIconOnly) x-bind:aria-label="expanded ? @js($closeLabel) : @js($openLabel)" @endif
                            @disabled($disabled)
                        >
                            @if (! $ccIconOnly)
                                <span class="tedi-collapse-button__text" x-text="expanded ? @js($closeLabel) : @js($openLabel)"></span>
                                <span class="tedi-collapse-button__icon-pad">
                                    <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$ccIconVariant" :size="$ccIconSize" />
                                </span>
                            @elseif ($expandActionArrowType === 'secondary')
                                <span class="tedi-collapse-button__icon-wrapper">
                                    <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$ccIconVariant" :size="$ccIconSize" />
                                </span>
                            @else
                                <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$ccIconVariant" :size="$ccIconSize" />
                            @endif
                        </button>
                    @endif
                @endif

                <span class="tedi-text tedi-text--secondary text-normal">
                    {{ $title ?? '' }}
                </span>
            </span>

            {{ $startDescription ?? '' }}
        </span>

        {{ $afterTitle ?? '' }}
    </div>

    {{ $endDescription ?? '' }}

    @if ($showEndExpandAction)
        @if ($headerClickable)
            <div
                class="tedi-text tedi-accordion-item-header__expand-indicator"
                x-bind:class="{ 'tedi-accordion-item-header__expand-indicator--with-label': {{ $showExpandLabel ? 'true' : 'false' }} }"
            >
                @if ($showExpandLabel)
                    <span x-text="expanded ? @js($closeLabel) : @js($openLabel)"></span>
                @endif
                <tedi:icon
                    name="expand_more"
                    class="tedi-accordion-item-header__icon"
                    x-bind:class="{ 'tedi-accordion-item-header__icon--expanded': expanded, 'tedi-accordion-item-header__icon--no-label': {{ $showExpandLabel ? 'false' : 'true' }} }"
                />
            </div>
        @else
            <button
                type="button"
                class="{{ implode(' ', $ccClasses) }}"
                x-bind:class="{ 'tedi-collapse-button--open': expanded }"
                @click="toggle()"
                x-bind:aria-expanded="expanded"
                @if ($contentId) aria-controls="{{ $contentId }}" @endif
                @if ($ccIconOnly) x-bind:aria-label="expanded ? @js($closeLabel) : @js($openLabel)" @endif
                @disabled($disabled)
            >
                @if (! $ccIconOnly)
                    <span class="tedi-collapse-button__text" x-text="expanded ? @js($closeLabel) : @js($openLabel)"></span>
                    <span class="tedi-collapse-button__icon-pad">
                        <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$ccIconVariant" :size="$ccIconSize" />
                    </span>
                @elseif ($expandActionArrowType === 'secondary')
                    <span class="tedi-collapse-button__icon-wrapper">
                        <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$ccIconVariant" :size="$ccIconSize" />
                    </span>
                @else
                    <tedi:icon class="tedi-collapse-button__icon" name="expand_more" color="inherit" :variant="$ccIconVariant" :size="$ccIconSize" />
                @endif
            </button>
        @endif
    @endif

    {{ $endAction ?? '' }}

@if ($headerClickable)
</button>
@else
</div>
@endif

@if ($headingTag)
</{{ $headingTag }}>
@endif
</div>
