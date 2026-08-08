{{--
    TEDI Header Login.
    Port of angular/tedi/components/layout/header/header-login/header-login.component.{ts,html}

    Angular auto-picks `small` below the `md` breakpoint when `size` is unset.
    Breakpoints are not ported (CONVENTIONS.md §7), so an unset `size` resolves
    to the non-mobile `default` branch here — pass `size="small"` explicitly to
    get the compact tedi:header.mobile-button variant.
--}}
@props([
    /** default|small. Same default as Angular: unset. */
    'size' => null,
    /** Custom label; falls back to translated header.login / header.login.mobile. */
    'label' => '',
    /** Renders as <a> when set. */
    'href' => null,
])

@php
    $isSmall = ($size ?? 'default') === 'small';
    $resolvedLabel = $label ?: __('tedi::tedi.'.($isSmall ? 'header.login.mobile' : 'header.login'));
@endphp

{{-- Root is <tedi-header-login>, the Angular selector's element:
     header-login.component.scss keys `display: flex`, `align-items: center` and
     the `.tedi-header-login__button { flex-shrink: 0 }` guard on it. --}}
<tedi-header-login {{ $attributes }}>
    @if ($isSmall)
        <tedi:header.mobile-button icon="login" :label="$resolvedLabel" :href="$href" />
    @else
        <tedi:button variant="primary" :href="$href" class="tedi-header-login__button">
            {{ $resolvedLabel }}
        </tedi:button>
    @endif
</tedi-header-login>
