{{--
    TEDI Dropdown Trigger.
    Port of angular/tedi/components/overlay/dropdown/dropdown-trigger/dropdown-trigger.directive.ts

    Angular's selector is the attribute directive `[tedi-dropdown-trigger]`,
    placed directly on the consumer's button. The vendored SCSS, however,
    styles the *element* (`tedi-dropdown-trigger { display: inline-flex }`), so
    per CONVENTIONS.md §4 this renders as that literal element and wraps the
    consumer's trigger — the same shape upstream supports through its
    "wrapping button component" branch.

    That branch is also why the ARIA state is not written on this element: the
    wrapper is not focusable, so `aria-haspopup` / `aria-expanded` on it would
    be announced on the wrong node. `x-init` resolves the real focusable
    element with upstream's FOCUSABLE_SELECTOR ("button, a[href], [tabindex]")
    and decorates that, exactly as the directive's effect() does — including
    the role="button" / tabindex="0" fallback when nothing focusable is found.

    `id` and `aria-controls` are emitted only when the dropdown was given a
    `container-id` (see dropdown.blade.php); without one there is no shared id
    to point at, and a dangling reference is worse than none.

    ArrowDown/ArrowUp ("open and focus the first/last item", or move to that end
    when already open) go through `tediDropdown.triggerKeydown`. The directive's
    Escape branch is deliberately NOT re-bound here: tediOverlay already closes
    on Escape at the document level and returns focus to this trigger, so a
    second handler would only fire `hide` twice.
--}}
@aware([
    'containerId' => null,
])
@props([
    /** Defines the aria-haspopup attribute for the trigger: menu | listbox | dialog | true. */
    'ariaHaspopup' => 'menu',
])

<tedi-dropdown-trigger
    {{ $attributes }}
    x-ref="trigger"
    x-on:click="toggle()"
    x-on:keydown="triggerKeydown($event)"
    x-init="(() => {
        const focusable = $el.matches('button, a[href]')
            ? $el
            : ($el.querySelector('button, a[href], [tabindex]') || $el);

        focusable.setAttribute('aria-haspopup', '{{ $ariaHaspopup }}');
        focusable.setAttribute('aria-expanded', 'false');

        @if ($containerId)
            focusable.setAttribute('id', '{{ $containerId }}_trigger');
            focusable.setAttribute('aria-controls', '{{ $containerId }}');
        @endif

        if (! focusable.matches('button, a[href]')) {
            focusable.setAttribute('role', 'button');
            focusable.setAttribute('tabindex', '0');
        }

        $watch('open', (value) => focusable.setAttribute('aria-expanded', value ? 'true' : 'false'));
    })()"
>
    {{ $slot }}
</tedi-dropdown-trigger>
