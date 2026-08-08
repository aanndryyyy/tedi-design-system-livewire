{{--
    TEDI Tabs Content.
    Port of angular/tedi/components/navigation/tabs/tabs-content/tabs-content.component.{ts,html}

    Angular both hides inactive panels (`[hidden]`) *and* skips rendering
    their `ng-content` entirely. Blade renders once on the server and Alpine
    can only toggle attributes on what already exists in the DOM — it cannot
    fetch content after the fact — so every panel's slot is always rendered
    here and visibility is controlled purely by `hidden`. The initial `hidden`
    value still matches Angular's `isActive()` exactly (via `@aware`, see
    tabs.blade.php), so a JS-stripped page shows only the active panel.
--}}
@props([
    /** Id matching the corresponding trigger's id. Omit to always render (e.g. router outlets). */
    'id' => null,
])
@aware(['value' => null, 'defaultValue' => ''])

@php
    $activeTab = $value ?? $defaultValue;
    $isActive = ! $id || $activeTab === $id;
@endphp

<div
    role="tabpanel"
    data-name="tabs-content"
    @if ($id)
        id="{{ $id }}-panel"
        aria-labelledby="{{ $id }}"
    @endif
    @unless ($isActive) hidden @endunless
    x-bind:hidden="{{ $id ? "tediActiveTab !== '$id'" : 'false' }}"
    {{ $attributes->class(['tedi-tabs-content']) }}
>
    {{ $slot }}
</div>
