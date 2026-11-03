{{--
    TEDI Dropdown Content.
    Port of angular/src/tedi/components/overlay/dropdown/dropdown-content/dropdown-content.component.{ts,html}

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

    The `<ul>` carries what Angular 9 moved onto it: `tedi-dropdown-content__list`
    (the vendored SCSS now resets padding/margin/list-style on that class rather
    than on a bare `ul`), `--plain` for the `list` role, `id` = the container id
    and `aria-labelledby` pointing at the trigger (the host used to carry the
    latter, where `role="presentation"` made it meaningless). Both ids are
    emitted only when the dropdown has a `container-id`.

    `dropdown-role="list"` (Angular 9, from the breadcrumbs WCAG work) is a plain
    list rather than a widget: items get no role, aria state or tabindex, the
    trigger gets no `aria-haspopup` and no arrow-key handling, and opening the
    panel focuses its first focusable element instead of a roving item. Escape
    still closes it and returns focus to the trigger (tediOverlay). Upstream also
    renders a `list` panel inline rather than in the body-level CDK container;
    this port never re-parents any panel, so that already holds for every role.
--}}
@aware([
    'containerId' => null,
])
@props([
    /**
     * How the panel is exposed to assistive technology: menu | listbox | list.
     * `menu` (actions) and `listbox` (selectable options) are composite
     * widgets; `list` is a plain list of links that keeps their own semantics.
     */
    'dropdownRole' => 'menu',
])

<div
    class="tedi-dropdown__panel"
    x-ref="panel"
    x-show="open"
    x-cloak
    x-on:keydown="menuKeydown($event)"
    x-bind:data-placement="side"
>
    <tedi-dropdown-content {{ $attributes->class(['tedi-dropdown-content'])->merge([
        'role' => 'presentation',
    ]) }}>
        {{ $before ?? '' }}

        <ul
            @class([
                'tedi-dropdown-content__list',
                'tedi-dropdown-content__list--plain' => $dropdownRole === 'list',
            ])
            @if ($containerId) id="{{ $containerId }}" @endif
            role="{{ $dropdownRole }}"
            @if ($containerId) aria-labelledby="{{ $containerId }}_trigger" @endif
        >
            {{ $slot }}
        </ul>
    </tedi-dropdown-content>
</div>
