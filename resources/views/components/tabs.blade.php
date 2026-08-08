{{--
    TEDI Tabs (root).
    Port of angular/tedi/components/navigation/tabs/tabs.component.ts

    Angular owns `activeTab` as a signal read by `tedi-tabs-list`,
    `tedi-tabs-trigger` and `tedi-tabs-content` via `inject(TabsComponent)`.
    Blade has no shared component instance, so per CONVENTIONS.md §3 the
    active-tab value is exposed as ambient data via `@aware` in the
    sub-components (`tabs/trigger.blade.php`, `tabs/content.blade.php`), and
    the *live* switching is driven by Alpine state (`tediActiveTab`) declared
    here — CONVENTIONS.md §8, tabs is inert without JS otherwise.

    `output<string>()` (`valueChange`) is not re-emitted (CONVENTIONS.md §3);
    in uncontrolled mode (no `value`) the Alpine state is the only source of
    truth, so there is nothing to emit to. A consumer needing server-side
    awareness of the active tab can still bind `wire:click` on
    `<tedi:tabs.trigger>` via `$attributes`.
--}}
@props([
    /** Controlled active tab id. When set, this is the source of truth on initial render. */
    'value' => null,
    /** Initial active tab id for uncontrolled usage. */
    'defaultValue' => '',
])

@php
    $activeTab = $value ?? $defaultValue;
@endphp

<div
    x-data="{
        tediActiveTab: @js($activeTab),
        tediSelect(id) { this.tediActiveTab = id; },
        tediClick(event, id, disabled) {
            if (disabled) { event.preventDefault(); return; }
            if (event.currentTarget.tagName === 'A') {
                if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey) return;
                const target = event.currentTarget.target;
                if (target && target !== '_self') return;
            }
            this.tediSelect(id);
        },
        tediKeydown(event, disabled) {
            const isAnchor = event.currentTarget.tagName === 'A';
            if (isAnchor && event.key === ' ') {
                event.preventDefault();
                if (! disabled) event.currentTarget.click();
                return;
            }
            this.tediNavigate(event);
        },
        tediNavigate(event) {
            if (! ['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
            const current = event.currentTarget;
            const tablist = current.closest('[role=tablist]');
            if (! tablist) return;
            const tabs = Array.from(tablist.querySelectorAll('[role=tab]:not([disabled]):not([aria-disabled=true])'))
                .filter((tab) => getComputedStyle(tab).display !== 'none');
            const currentIndex = tabs.indexOf(current);
            if (tabs.length === 0 || currentIndex === -1) return;
            let newIndex = -1;
            if (event.key === 'ArrowLeft') newIndex = currentIndex === 0 ? tabs.length - 1 : currentIndex - 1;
            if (event.key === 'ArrowRight') newIndex = currentIndex === tabs.length - 1 ? 0 : currentIndex + 1;
            if (event.key === 'Home') newIndex = 0;
            if (event.key === 'End') newIndex = tabs.length - 1;
            if (newIndex === -1) return;
            event.preventDefault();
            tabs[newIndex].focus();
            tabs[newIndex].scrollIntoView({ block: 'nearest', inline: 'nearest' });
            if (tabs[newIndex].tagName !== 'A') this.tediSelect(tabs[newIndex].id);
        },
    }"
    data-name="tabs"
    {{ $attributes->class(['tedi-tabs']) }}
>
    {{ $slot }}
</div>
