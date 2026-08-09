{{--
    TEDI SideNav Item.
    Port of angular/tedi/components/layout/sidenav/sidenav-item/sidenav-item.component.{ts,html}

    Angular's host is the custom element `<tedi-sidenav-item role="presentation"
    style="display: contents">` and the class list lives on an inner `<li>`.
    Both are reproduced literally, because the vendored SCSS keys on the ELEMENT
    as well as the class — `.tedi-sidenav--dividers tedi-sidenav-item >
    .tedi-sidenav-item { border-bottom: … }` and its `:last-child` companion
    would not match a bare `<li class="tedi-sidenav-item">` (CONVENTIONS.md §4).
    `$attributes` therefore lands on the host element, which is also where a
    consumer's `class` goes in Angular; the `<li>` carries the computed list.

    EXPLICIT PROPS replacing runtime introspection / the sidenav service (§5):

    | Angular                                   | Blade prop        |
    |-------------------------------------------|-------------------|
    | `contentChild(SideNavDropdownComponent)`  | `has-dropdown`    |
    | `dropdown.open()`                         | `open`            |
    | `textContent` (read off the DOM)          | `label`           |
    | `SideNavService.isCollapsed()`            | `@aware collapsed`|
    | `SideNavService.isMobile()`               | `@aware mobile`   |
    | `SideNavService.isMobileItemOpen()`       | `@aware mobileItemOpen` |

    The `@aware` fallbacks equal `<tedi:sidenav>`'s `@props` defaults, as
    CONVENTIONS.md §3 requires.

    `route` is Angular's `[routerLink]`. There is no Angular router here, so it
    renders as an ordinary `href`; it is kept as a separate prop because the
    template branches on `route()` vs `href()` in the mobile drill-down.

    DIVERGENCES:
    - `tooltipEnabled()` is `isCollapsed() && no item has an open dropdown`
      upstream. The second term is a runtime nicety over the whole item
      registry, which does not exist here, so the tooltip branch is keyed on
      `collapsed` alone. The literal `<tedi-tooltip-trigger>` element matters:
      `.tedi-sidenav--collapsed tedi-tooltip-trigger { display: block; width: 100% }`.
    - Focus management (focus the first flyout item on open, return focus to the
      trigger on close/Escape) is not ported. This is the sidenav's own flyout —
      a plain `x-data` below, not <tedi:dropdown> — so it does not get the
      roving tabindex that CONVENTIONS.md §11's `tediDropdown` gives that
      component; porting it here would mean a second, separate keyboard layer.
      Escape-to-close and outside-click dismissal of the collapsed flyout ARE
      ported.
    - The mobile drill-down (`mobile-item-open`) hides this item's icon in
      Angular whenever its own dropdown is open. That is computed statically
      here from `mobileItemOpen`/`open`, not re-evaluated by Alpine, because
      `mobile` is itself a static opt-in prop (§7 #1).
--}}
@props([
    /** Is navigation item selected? */
    'selected' => false,
    /** Name of the item icon. */
    'icon' => null,
    /** External link. */
    'href' => null,
    /** Router link upstream; rendered as a plain href here. */
    'route' => null,
    /** Does this item contain a sidenav.dropdown? (§5 — Blade can't inspect its slot.) */
    'hasDropdown' => false,
    /** Is that dropdown open? Mirrors Angular's `dropdown.open()`. */
    'open' => false,
    /** Plain-text label. Angular reads it off the DOM (§5); needed for the tooltip and sr-only text. */
    'label' => null,
])
@aware([
    'collapsed' => false,
    'mobile' => false,
    'mobileItemOpen' => false,
])

@php
    $isCollapsed = $collapsed && ! $mobile;
    $linkHref = $route ?: $href;
    $hasLink = (bool) $linkHref;

    // isMobileItemOpen() && dropdown?.open() — the mobile "drilled into" view.
    $drilledIn = $mobileItemOpen && $open;
    $showIcon = $icon && ! $drilledIn;

    // buttonOrLink: a <button> when there is no link at all, or when a linked
    // item also owns a dropdown while collapsed/mobile (the caret becomes the
    // whole trigger). Otherwise an <a> plus a separate caret button.
    $renderAsButton = ! $hasLink || ($hasDropdown && ($isCollapsed || $mobile));
    $ariaExpanded = $open ? 'true' : 'false';

    // 'sidenav.toggleSubmenu' | tediTranslate: textContent(): open — the
    // generated lang files carry the parameter as the literal "false"
    // placeholder (the number-field / pagination convention).
    $submenuLabel = str_replace('false', (string) $label, __('tedi::tedi.sidenav.toggleSubmenu.false'));

    $tooltipEnabled = $isCollapsed;

    // Alpine expressions read the parent <nav>'s scope through $data so the
    // item still works when rendered on its own (a bare identifier would throw).
    // Optional-call syntax, not `x && x(…)`: Blade escapes `&&` to `&amp;&amp;`
    // when a computed string is interpolated into an attribute.
    $bump = '$data.tediSidenavItemToggled?.(sidenavOpen)';
    $toggleExpr = 'sidenavOpen = ! sidenavOpen; '.$bump;
    $closeExpr = 'if (sidenavOpen) { sidenavOpen = false; '.$bump.' }';

    // Angular renders `content` from an <ng-template> reused by five branches.
    // Blade has no local partial, so it is captured once here and echoed where
    // Angular instantiates the template — same output, no duplication.
    ob_start();
@endphp
@if ($showIcon)<tedi:icon :name="$icon" color="white" class="tedi-sidenav-item__icon" />@endif<span class="tedi-sidenav-item__text">{{ $slot }}</span>
@php
    $content = new \Illuminate\Support\HtmlString(trim(ob_get_clean()));

    ob_start();
@endphp
@if ($renderAsButton)
    <button
        type="button"
        class="tedi-sidenav-item__title"
        @if ($hasDropdown) aria-expanded="{{ $ariaExpanded }}" @endif
        x-on:click="{{ $toggleExpr }}"
        @if ($hasDropdown) x-bind:aria-expanded="sidenavOpen ? 'true' : 'false'" @endif
    >
        {{ $content }}
        @if ($hasDropdown)
            <tedi:icon
                name="expand_more"
                class="tedi-sidenav-item__caret"
                color="white"
                :data-open="$ariaExpanded"
                x-bind:data-open="sidenavOpen ? 'true' : 'false'"
            />
        @endif
    </button>
@else
    <a class="tedi-sidenav-item__title" href="{{ $linkHref }}">{{ $content }}</a>
    @if ($hasDropdown)
        <button
            type="button"
            class="tedi-sidenav-item__caret-button"
            aria-expanded="{{ $ariaExpanded }}"
            x-on:click="{{ $toggleExpr }}"
            x-bind:aria-expanded="sidenavOpen ? 'true' : 'false'"
        >
            <span class="sr-only">{{ $submenuLabel }}</span>
            <div class="tedi-sidenav-item__caret-container">
                <tedi:icon
                    name="expand_more"
                    class="tedi-sidenav-item__caret"
                    color="white"
                    :data-open="$ariaExpanded"
                    x-bind:data-open="sidenavOpen ? 'true' : 'false'"
                />
            </div>
        </button>
    @endif
@endif
@php
    $buttonOrLink = new \Illuminate\Support\HtmlString(trim(ob_get_clean()));
@endphp

<tedi-sidenav-item {{ $attributes->merge(['role' => 'presentation'])->style(['display: contents']) }}>
    <li
        @class([
            'tedi-sidenav-item',
            'tedi-sidenav-item--selected' => $selected,
            'tedi-sidenav-item--hidden' => $mobileItemOpen && ! $open,
        ])
        x-data="{ sidenavOpen: {{ $open ? 'true' : 'false' }} }"
        x-on:tedi-sidenav-close-all.window="sidenavOpen = false"
        x-on:click.outside="if ($data.tediSidenavCollapsed) { {{ $closeExpr }} }"
        x-on:keydown.escape.window="if ($data.tediSidenavCollapsed) { {{ $closeExpr }} }"
        x-bind:class="{ 'tedi-sidenav-item--hidden': $data.tediSidenavMobileItemOpen && ! sidenavOpen }"
    >
        @if ($drilledIn)
            @if (! $hasLink)
                <tedi:sidenav.group-title>{{ $content }}</tedi:sidenav.group-title>
            @else
                <a class="tedi-sidenav-item__link" href="{{ $linkHref }}">{{ $content }}</a>
            @endif
        @elseif ($tooltipEnabled)
            <tedi:tooltip position="right">
                <tedi:tooltip-trigger>
                    <div class="tedi-sidenav-item__trigger">{{ $buttonOrLink }}</div>
                </tedi:tooltip-trigger>
                <tedi:tooltip-content>{{ $label ?? $slot }}</tedi:tooltip-content>
            </tedi:tooltip>
        @else
            <div class="tedi-sidenav-item__trigger">{{ $buttonOrLink }}</div>
        @endif

        {{ $dropdown ?? '' }}
    </li>
</tedi-sidenav-item>
