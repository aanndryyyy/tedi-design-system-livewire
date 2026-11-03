{{--
    TEDI Dropdown Trigger.
    Port of angular/src/tedi/components/overlay/dropdown/dropdown-trigger/dropdown-trigger.directive.ts

    Angular's selector is the attribute directive `[tedi-dropdown-trigger]`,
    placed directly on the consumer's control. The vendored SCSS, however,
    styles the *element* (`tedi-dropdown-trigger { display: inline-flex }`), so
    per CONVENTIONS.md §4 this renders as that literal element and wraps the
    consumer's trigger — the same shape upstream supports through its
    "wrapping button component" branch.

    That branch is also why the ARIA state is not written on this element: the
    wrapper is not focusable, so `aria-haspopup` / `aria-expanded` on it would
    be announced on the wrong node. `tediDropdown.initTrigger` resolves the real
    element exactly as the directive does — a natively interactive control
    (`button, a[href], input:not([type="hidden"]), select, textarea`), else the
    first such control or `[tabindex]` inside — and decorates that.

    Angular 9 changes ported here (#669 and the breadcrumbs WCAG work):

    - `aria-haspopup` has no fixed default any more. Omitted, it follows the
      content's `dropdown-role`: `menu` / `listbox`, and no attribute for a
      plain `list`. `false` also omits it. The content is a sibling, out of
      `@aware`'s reach, so the role is read from the rendered `ul[role]` at
      init — the same DOM registry `tediDropdown` already uses.
    - `aria-controls` is present only while the panel is open.
    - A non-native trigger gets `role="button"` / `tabindex="0"` only when it
      has no role / tabindex of its own, so a consumer's `role="checkbox"`
      survives.
    - Keys: Enter and Space toggle a non-native trigger (Space on keyup, and only
      for a press that began on it); a checkbox or radio trigger toggles on
      Enter, which also keeps Enter from submitting its form, while Space stays
      the input's own native click. ArrowDown/ArrowUp open-and-focus only for
      `menu` / `listbox` content. Keys from other controls inside the wrapper
      are left alone.

    `id` and `aria-controls` are emitted only when the dropdown was given a
    `container-id` (see dropdown.blade.php); without one there is no shared id
    to point at, and a dangling reference is worse than none.

    The directive's Escape branch is deliberately NOT re-bound here:
    tediOverlay already closes on Escape at the document level and returns
    focus to this trigger, so a second handler would only fire `hide` twice.
--}}
@aware([
    'containerId' => null,
])
@props([
    /**
     * The aria-haspopup value for the trigger: menu | listbox | dialog | true | false.
     * Omitted, it follows the content's dropdown-role (none for `list`).
     */
    'ariaHaspopup' => null,
])

<tedi-dropdown-trigger
    {{ $attributes }}
    x-ref="trigger"
    x-on:click="toggle()"
    x-on:keydown="triggerKeydown($event)"
    x-on:keyup="triggerKeyup($event)"
    x-on:focusout="triggerFocusout($event)"
    x-init="initTrigger($el, {
        ariaHaspopup: {!! $ariaHaspopup ? "'".e($ariaHaspopup)."'" : 'null' !!},
        containerId: {!! $containerId ? "'".e($containerId)."'" : 'null' !!},
    })"
>
    {{ $slot }}
</tedi-dropdown-trigger>
