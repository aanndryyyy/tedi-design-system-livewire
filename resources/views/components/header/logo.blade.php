{{--
    TEDI Header Logo.
    Port of angular/tedi/components/layout/header/header-logo/header-logo.component.{ts,html}

    Angular's `useDark` is computed from `ThemeService.theme()` (a runtime
    signal) plus whether a `[tedi-header-logo-dark]` child was projected. Theme
    state isn't available at Blade render time, so per CONVENTIONS.md §5 it
    becomes the explicit prop `dark`. The `[tedi-header-logo-dark]` content
    projection becomes the named slot `darkLogo`. Both the default and dark
    `<span>`s always render — the vendored SCSS toggles which one is visible
    via the `tedi-header-logo--dark` class, exactly as in Angular.
--}}
@props([
    /** Optional link URL. Wraps the logo in an anchor when set. */
    'href' => null,
    'showLogo' => true,
    /** Explicit replacement for Angular's runtime theme() === 'dark' check. */
    'dark' => false,
])

<div {{ $attributes->class([
    'tedi-header-logo',
    'tedi-header-logo--dark' => $dark,
    'tedi-header-logo--hidden' => ! $showLogo,
]) }}>
    @if ($href)
        <a href="{{ $href }}" class="tedi-header-logo__link">
            <span class="tedi-header-logo__default">{{ $slot }}</span>
            <span class="tedi-header-logo__dark">{{ $darkLogo ?? '' }}</span>
        </a>
    @else
        <span class="tedi-header-logo__default">{{ $slot }}</span>
        <span class="tedi-header-logo__dark">{{ $darkLogo ?? '' }}</span>
    @endif
</div>
