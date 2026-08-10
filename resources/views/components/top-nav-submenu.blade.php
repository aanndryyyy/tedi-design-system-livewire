{{--
    TEDI Top Nav Submenu panel.
    Blade-only piece of react/src/tedi/components/layout/top-nav/top-nav.tsx
    (CONVENTIONS.md §13).

    The full-width mega-menu panel — the `submenuFit="full"` branch that
    upstream renders inline inside `TopNav`, having first found the open item
    and hoisted its `submenu` children up out of it.

    Blade cannot read a descendant's slot (CONVENTIONS.md §5/§13.5), so the
    hoisting cannot happen and the panel is written where upstream *renders* it
    instead of where upstream *declares* it: in `tedi:top-nav`'s `submenu` slot,
    one panel per submenu item, each addressed by the same `key` its item
    carries. The markup, the classes and the inline max-width are upstream's;
    the only difference is N panels in the document rather than one — which is
    what CONVENTIONS.md §8 asks for anyway, since a closed panel then exists
    with its real class list instead of being conjured by JS.

    There is no `--inline` modifier here. That pair of classes belongs to the
    `content` fit, which renders inside the item — see `tedi:top-nav-item`.
--}}
@props([
    /** The `key` of the item this panel belongs to. */
    'for' => null,
])

@aware([
    /** Matches tedi:top-nav's default. */
    'panelId' => 'top-nav-submenu',
    /** Matches tedi:top-nav's default. */
    'maxWidth' => 'xxl',
])

@php
    $innerStyle = ($resolved = \Tedi\Livewire\Tedi::maxWidth($maxWidth))
        ? ['max-width: '.$resolved]
        : [];
@endphp

<div
    id="{{ $panelId }}-{{ $for }}"
    {{ $attributes->class(['tedi-top-nav__submenu']) }}
    x-show="openKey === {{ \Illuminate\Support\Js::from($for) }}"
    x-cloak
>
    <div @class(['tedi-top-nav__submenu-inner']) @style($innerStyle)>
        {{ $slot }}
    </div>
</div>
