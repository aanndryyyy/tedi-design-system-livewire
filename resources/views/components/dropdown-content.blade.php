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
