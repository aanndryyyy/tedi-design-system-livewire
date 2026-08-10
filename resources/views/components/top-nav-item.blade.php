{{--
    TEDI Top Nav Item.
    Port of react/src/tedi/components/layout/top-nav/components/top-nav-item/top-nav-item.tsx
    (CONVENTIONS.md §13).

    One entry in the bar: an `<li>` wrapping either a link or, when it owns a
    submenu and has no `href`, a toggle `<button>`. Upstream decides that with
    `isToggle = !href && hasSubmenu`; here `has-submenu` is explicit, because
    the parent cannot count a slot to work it out (CONVENTIONS.md §5). Setting
    `key` implies it, so in practice you set `key` and nothing else.

    The active class is upstream's `isActive || submenuOpen`, and `submenuOpen`
    itself falls back to `isActive` when the item is not a toggle — which is why
    a plain active link is `--active` and an open mega-menu trigger is too. The
    Alpine binding adds the class while the panel is open, after
    `$attributes->class()` per CONVENTIONS.md §4.

    ARIA follows the branch, exactly as upstream: a toggle gets
    `aria-haspopup` / `aria-expanded` / `aria-controls` and the native
    `disabled`; a link gets `aria-current="page"` when active, `aria-disabled`
    when disabled, and loses its `href` entirely when disabled.

    `icon` takes a Material Symbols name. Upstream also accepts a full icon
    props object and spreads its own leftover props onto the icon; here the icon
    is rendered with upstream's fixed defaults (size 18, `color="inherit"`) so
    that an attribute meant for the `<li>` cannot end up on it.

    The chevron is upstream's, including the `--open` rotation modifier.

    ONE THING TO PAIR UP. Upstream opens the submenu of an item that is both a
    toggle and `isActive` on mount, so this renders that item's static markup
    open (`aria-expanded="true"`, `--active`) to keep §8's "the open classes
    must be in the DOM" promise. Alpine's `openKey` is the runtime authority,
    though, so give the nav a matching `open-key` — otherwise Alpine closes the
    item as soon as it boots: `tedi:top-nav open-key="services"` alongside
    `tedi:top-nav-item key="services" is-active`. (Written without angle
    brackets on purpose — CONVENTIONS.md §2.)
--}}
@props([
    /** Identifies this item's submenu panel. Required for a submenu item. */
    'key' => null,
    /** Destination URL. Omit for a submenu toggle. */
    'href' => null,
    /** Material Symbols name rendered before the label. */
    'icon' => null,
    /** Marks the item as the current page. */
    'isActive' => false,
    /** True when the item owns a submenu. Implied by `key`. */
    'hasSubmenu' => null,
    /** Disables the item. */
    'disabled' => false,
])

@aware([
    /** Matches tedi:top-nav's default. */
    'submenuFit' => 'full',
    /** Matches tedi:top-nav's default. */
    'panelId' => 'top-nav-submenu',
])

@php
    $hasSubmenu = $hasSubmenu ?? ($key !== null);
    // isToggle — upstream: `!href && hasSubmenu`.
    $isToggle = ! $href && $hasSubmenu;
    $isInlineFit = $submenuFit === 'content';
    $panelKey = $panelId.'-'.$key;

    // submenuOpen — upstream: `isSubmenuOpen ?? isActive`; the runtime half is
    // Alpine's, so the server value is the isActive fallback.
    $submenuOpen = (bool) $isActive;
    $showInlineSubmenu = $isInlineFit && $hasSubmenu && isset($submenu);
@endphp

<li @class([
    'tedi-top-nav__item',
    'tedi-top-nav__item--has-inline-submenu' => $showInlineSubmenu,
])>
    @if ($isToggle)
        <button
            type="button"
            aria-haspopup="true"
            aria-controls="{{ $panelKey }}"
            aria-expanded="{{ $submenuOpen ? 'true' : 'false' }}"
            @disabled($disabled)
            {{ $attributes->class([
                'tedi-top-nav__link',
                'tedi-top-nav__link--active' => $isActive || $submenuOpen,
            ]) }}
            x-bind:class="{ 'tedi-top-nav__link--active': openKey === {{ \Illuminate\Support\Js::from($key) }} }"
            x-bind:aria-expanded="(openKey === {{ \Illuminate\Support\Js::from($key) }}).toString()"
            x-on:click="openKey = openKey === {{ \Illuminate\Support\Js::from($key) }} ? null : {{ \Illuminate\Support\Js::from($key) }}"
        >
            @if ($icon)
                <tedi:icon :name="$icon" class="tedi-top-nav__icon" color="inherit" :size="18" />
            @endif

            {{ $slot }}

            <tedi:icon
                name="keyboard_arrow_down"
                color="inherit"
                :size="18"
                class="tedi-top-nav__icon tedi-top-nav__chevron"
                x-bind:class="{ 'tedi-top-nav__chevron--open': openKey === {{ \Illuminate\Support\Js::from($key) }} }"
            />
        </button>
    @else
        <a
            @if (! $disabled && $href) href="{{ $href }}" @endif
            @if ($isActive) aria-current="page" @endif
            @if ($disabled) aria-disabled="true" @endif
            {{ $attributes->class([
                'tedi-top-nav__link',
                'tedi-top-nav__link--active' => $isActive || $submenuOpen,
            ]) }}
        >
            @if ($icon)
                <tedi:icon :name="$icon" class="tedi-top-nav__icon" color="inherit" :size="18" />
            @endif

            {{ $slot }}

            @if ($hasSubmenu)
                <tedi:icon
                    name="keyboard_arrow_down"
                    color="inherit"
                    :size="18"
                    class="tedi-top-nav__icon tedi-top-nav__chevron"
                />
            @endif
        </a>
    @endif

    @if ($showInlineSubmenu)
        <div
            id="{{ $panelKey }}"
            class="tedi-top-nav__submenu tedi-top-nav__submenu--inline"
            x-show="openKey === {{ \Illuminate\Support\Js::from($key) }}"
            x-cloak
        >
            <div class="tedi-top-nav__submenu-inner tedi-top-nav__submenu-inner--inline">
                {{ $submenu }}
            </div>
        </div>
    @endif
</li>
