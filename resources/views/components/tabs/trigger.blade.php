{{--
    TEDI Tabs Trigger.
    Port of angular/tedi/components/navigation/tabs/tabs-trigger/tabs-trigger.component.{ts,html}

    Angular applies this as an attribute directive to a consumer-chosen
    `<button>` or `<a>`; `href` picks the tag, as in button.blade.php/link.blade.php.

    `inject(TabsComponent)` (parent context) maps to `@aware` per
    CONVENTIONS.md §3 — the initial selected/tabindex/aria-selected state is
    computed from the same `value`/`defaultValue` the root `<tedi:tabs>`
    resolved, so a JS-stripped page still renders the correct static markup.
    After Alpine hydrates, `x-bind:*` keeps them in sync with `tediActiveTab`
    (root component, CONVENTIONS.md §8).
--}}
@props([
    /** Unique id for this tab. Required — also used as the panel's aria-labelledby target. */
    'id',
    /** Icon displayed before the label. */
    'icon' => null,
    /** Whether the tab is disabled. */
    'disabled' => false,
    /** Renders as <a> when set; otherwise a <button type="button">. */
    'href' => null,
])
@aware(['value' => null, 'defaultValue' => ''])

@php
    $activeTab = $value ?? $defaultValue;
    $isSelected = $activeTab === $id;
    $tag = $href ? 'a' : 'button';
    $tabIndex = $disabled ? -1 : ($isSelected ? 0 : -1);
    $disabledJs = $disabled ? 'true' : 'false';
@endphp

<{{ $tag }}
    role="tab"
    id="{{ $id }}"
    data-name="tabs-trigger"
    aria-controls="{{ $id }}-panel"
    aria-selected="{{ $isSelected ? 'true' : 'false' }}"
    tabindex="{{ $tabIndex }}"
    @if ($tag === 'a')
        @if ($href) href="{{ $href }}" @endif
        @if ($disabled) aria-disabled="true" @endif
    @else
        type="button"
        @disabled($disabled)
    @endif
    x-bind:aria-selected="(tediActiveTab === '{{ $id }}').toString()"
    x-bind:tabindex="({{ $disabledJs }}) ? -1 : (tediActiveTab === '{{ $id }}' ? 0 : -1)"
    x-on:click="tediClick($event, '{{ $id }}', {{ $disabledJs }})"
    x-on:keydown="tediKeydown($event, {{ $disabledJs }})"
    x-bind:class="{ 'tedi-tabs-trigger--selected': tediActiveTab === '{{ $id }}' }"
    {{ $attributes->class([
        'tedi-tabs-trigger',
        'tedi-tabs-trigger--selected' => $isSelected,
        'tedi-tabs-trigger--disabled' => (bool) $disabled,
    ]) }}
>
    @if ($icon)
        <tedi:icon :name="$icon" :size="18" color="inherit" />
    @endif
    {{ $slot }}
</{{ $tag }}>
