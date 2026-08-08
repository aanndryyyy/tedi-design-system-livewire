{{--
    TEDI Header Profile.
    Port of angular/tedi/components/layout/header/header-profile/header-profile.component.{ts,html}

    Angular renders one of two UI patterns depending on the `showPopover`
    breakpoint: a `<tedi-popover>` (CDK/floating-ui) from that breakpoint up,
    or a trigger button + toggled `.tedi-header-profile__modal` below it.
    Breakpoints are not ported (CONVENTIONS.md §7) and popover positioning is
    out of scope (§7.3), so this port always renders the modal branch — the
    one that needs no floating-ui, just a plain toggled `<div>` — regardless of
    viewport. `showPopover` is accepted for API parity (CONVENTIONS.md §9 DoD
    item 2, same default `'lg'`) but is inert here, like alert's `closeDelay` —
    it only ever selected which of the two branches rendered, and this port
    always renders the modal branch. Documented as a §7 divergence.

    `isSmall()` (`isBelowBreakpoint('md')`) collapses the same way as
    header-login/-logout: an unset `size` resolves to the non-mobile `default`
    branch.

    Angular has no `host:` class on this component (the template has no single
    wrapping element), but header-profile.component.scss keys its rules on the
    *element* (`tedi-header-profile { … tedi-icon.tedi-header-profile__icon { … } }`),
    so the root must be `<tedi-header-profile>` and not a bare `<div>` — with a
    `<div>` the profile icon renders at 18px instead of the 36px `--icon-06`.
    It doubles as the scope for Alpine's `x-data`, per CONVENTIONS.md §6's
    one-root-element rule; it carries no class of its own.

    Display: the element's own SCSS block sets only `align-content`, so the
    `display: flex` comes from `header-actions.component.scss`'s
    `.tedi-header-actions > * { display: flex }` — same as in Angular. Used
    standalone outside header-actions it is `inline` in both, so parity holds.
--}}
@props([
    'label' => '',
    'showLabel' => false,
    'noStyle' => false,
    /** default|small. Same default as Angular: unset. */
    'size' => null,
    /** xs|sm|md|lg|xl|xxl. Accepted for API parity — inert; see doc comment above. */
    'showPopover' => 'lg',
])

@php
    $isSmall = ($size ?? 'default') === 'small';
    $resolvedLabel = $label ?: __('tedi::tedi.'.($isSmall ? 'header.profile.mobile' : 'header.profile'));
    $buttonVariant = ($isSmall || ! $showLabel) ? 'neutral' : 'secondary';
    $triggerAriaLabel = $showLabel ? null : $resolvedLabel;
    $profileIconClass = \Illuminate\Support\Arr::toCssClasses([
        'tedi-header-profile__icon',
        'tedi-header-profile__icon--small' => $showLabel,
    ]);
@endphp

<tedi-header-profile x-data="{ open: false }" {{ $attributes }}>
    @if ($isSmall)
        <tedi:header.mobile-button
            icon="account_circle"
            :label="$resolvedLabel"
            aria-has-popup="dialog"
            x-on:click="open = ! open"
            x-bind:aria-expanded="open.toString()"
            x-bind:class="{ 'tedi-header-mobile-button--selected': open }"
        />
    @else
        <tedi:button
            :variant="$buttonVariant"
            :aria-label="$triggerAriaLabel"
            x-on:click="open = ! open"
            x-bind:data-open="open"
            x-bind:aria-expanded="open.toString()"
            aria-haspopup="dialog"
        >
            <tedi:icon
                name="account_circle"
                color="brand"
                :class="$profileIconClass"
            />
            @if ($showLabel)
                <span class="tedi-header-profile__label">{{ $resolvedLabel }}</span>
                <tedi:icon name="expand_more" class="tedi-header-profile__icon--expand" />
            @endif
        </tedi:button>
    @endif

    <div
        x-show="open"
        x-on:click="open = false"
        class="tedi-header-profile__overlay"
        style="display: none;"
    ></div>
    <div
        x-show="open"
        class="tedi-header-profile__modal{{ $noStyle ? ' tedi-header-profile__modal--no-style' : '' }}"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</tedi-header-profile>
