{{--
    TEDI Header Logout.
    Port of angular/tedi/components/layout/header/header-logout/header-logout.component.{ts,html}

    Breakpoints are not ported (CONVENTIONS.md §7); an unset `size` resolves to
    the non-mobile `default` branch — pass `size="small"` explicitly for the
    compact tedi:header.mobile-button variant.

    The `link` component itself isn't part of this port's scope, but per
    CONVENTIONS.md §4 the emitted class list must still match Angular exactly:
    `<a tedi-link [underline]="false">` renders `tedi-link tedi-link--no-underline`
    (see navigation/link/link.component.ts `classes()`), applied here as static
    classes alongside `tedi-header-logout__button`.

    Angular's label is `<span tedi-text color="inherit">`, but `tedi-text--inherit`
    has no rule anywhere in the vendored SCSS (verified against dist/tedi.css) —
    a dead class upstream. A plain `<span>` with no color class already inherits
    its parent's color by default CSS behaviour, so it reproduces the same
    visual result without emitting an unstyled class.
--}}
@props([
    /** default|small. Same default as Angular: unset. */
    'size' => null,
    /** Custom label; falls back to translated header.logout / header.logout.mobile. */
    'label' => '',
    /** Renders as <a> when set. */
    'href' => null,
])

@php
    $isSmall = ($size ?? 'default') === 'small';
    $resolvedLabel = $label ?: __('tedi::tedi.'.($isSmall ? 'header.logout.mobile' : 'header.logout'));
@endphp

{{-- Root is <tedi-header-logout>, the Angular selector's element. Its own SCSS is
     class-based, but header-profile.component.scss:60,86,92 reaches into it by
     element (`tedi-header-logout .tedi-header-mobile-button`,
     `tedi-header-logout .tedi-header-logout__button tedi-icon`) for logouts placed
     inside a profile modal or popover — those rules are dead against a <div>. --}}
<tedi-header-logout {{ $attributes->class(['tedi-header-logout']) }}>
    @if ($isSmall)
        <tedi:header.mobile-button icon="logout" :label="$resolvedLabel" :href="$href" />
    @elseif ($href)
        <a href="{{ $href }}" class="tedi-link tedi-link--no-underline tedi-header-logout__button">
            <tedi:icon name="logout" color="inherit" />
            <span>{{ $resolvedLabel }}</span>
        </a>
    @else
        <button type="button" class="tedi-header-logout__button">
            <tedi:icon name="logout" color="inherit" />
            <span>{{ $resolvedLabel }}</span>
        </button>
    @endif
</tedi-header-logout>
