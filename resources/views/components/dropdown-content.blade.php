{{--
    TEDI Dropdown Content.
    Port of angular/tedi/components/overlay/dropdown/dropdown-content/dropdown-content.component.{ts,html}

    Renders two elements: the `.tedi-dropdown__panel` wrapper (Angular puts it
    in dropdown.component.html, inside the CDK overlay template) and the
    content element itself. The wrapper carries only static classes and the
    positioning refs, so `$attributes` goes on the inner element per
    CONVENTIONS.md §6 — the same split the form components use for their
    wrapper/control pairs.

    The panel is the element tediOverlay positions (CONVENTIONS.md §11):
    `x-ref="panel"`, `x-show="open"`, `x-cloak` — the last of which is what
    hides it between first paint and Alpine booting, via the `[x-cloak]` rule
    this package ships in resources/scss/_alpine.scss. Per §8 the panel is in
    the DOM with its real class list even while closed.

    The panel also carries the ONE keydown listener for the whole item list.
    Angular binds `keydown` per item (dropdown-item.component.ts); here the item
    is an anonymous Blade component with nowhere to hang per-instance state, and
    the handler needs its siblings anyway, so `tediDropdown.menuKeydown` is
    delegated from here and resolves the item with `event.target.closest()`.
    The listener sits on the panel rather than the `<ul>` so it still fires for
    anything the `before` slot renders above the list.

    `<tedi-dropdown-content>` is the literal Angular element selector (§4).
    Angular's untargeted `<ng-content />` renders above the `<ul>`; that is the
    `before` slot here, while the default slot holds the `li[tedi-dropdown-item]`
    projection and therefore renders inside the `<ul>`.
--}}
@aware([
    'containerId' => null,
])
@props([
    /** Role for content, use listbox for list and menu for actions. */
    'dropdownRole' => 'menu',
])

<div
    class="tedi-dropdown__panel"
    @if ($containerId) id="{{ $containerId }}" @endif
    x-ref="panel"
    x-show="open"
    x-cloak
    x-on:keydown="menuKeydown($event)"
    x-bind:data-placement="side"
>
    <tedi-dropdown-content {{ $attributes->class(['tedi-dropdown-content'])->merge(array_filter([
        'role' => 'presentation',
        'aria-labelledby' => $containerId ? $containerId.'_trigger' : null,
    ])) }}>
        {{ $before ?? '' }}

        <ul role="{{ $dropdownRole }}">
            {{ $slot }}
        </ul>
    </tedi-dropdown-content>
</div>
