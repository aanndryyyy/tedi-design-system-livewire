{{--
    TEDI Header.
    Port of angular/tedi/components/layout/header/header.component.{ts,html}

    Angular's selector is the attribute form `header[tedi-header]`; the Blade
    port renders the `<header>` element directly.

    `<ng-content select="tedi-header-top">` / `select="tedi-header-bottom">`
    become the named slots `top` / `bottom` (CONVENTIONS.md §2). The mobile
    sidenav toggle slot (`select="button[tedi-sidenav-toggle]">`) becomes the
    named slot `toggle` — see header/toggle.blade.php for the divergence note
    on what that toggle actually is in this port. The default slot is the main
    content, matching the unqualified `<ng-content />`.
--}}
@props([])

<header {{ $attributes->class(['tedi-header']) }}>
    {{ $top ?? '' }}

    <div class="tedi-header__main">
        {{ $toggle ?? '' }}

        <div class="tedi-header__main--content">
            {{ $slot }}
        </div>
    </div>

    {{ $bottom ?? '' }}
</header>
