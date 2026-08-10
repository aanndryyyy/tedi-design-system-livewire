{{--
    TEDI Header Profile.
    Port of angular/tedi/components/layout/header/header-profile/header-profile.component.{ts,html}

    Angular renders one of two UI patterns around the `showPopover` breakpoint:
    a `<tedi-popover>` from that breakpoint up, or a trigger button plus a
    toggled `.tedi-header-profile__modal` below it. Both are ported here — the
    popover is `tedi:popover`, positioned by `tediOverlay` (CONVENTIONS.md §11) —
    but WHICH one renders is decided server-side rather than by viewport:

    - `size="small"` (Angular's `isSmall()` / below `md`) renders the mobile
      button + full-screen modal branch;
    - anything else renders the popover branch.

    `tedi:show-at` / `tedi:hide-at` are deliberately NOT used to switch between
    them: they render a WRAPPER `<div>`, and `.tedi-header-actions > *` is a
    child selector, so a wrapper would become the styled flex child and break
    the header's layout. Angular's `*showAt` / `*hideAt` are structural and add
    no element, which Blade cannot reproduce. `showPopover` is therefore still
    accepted for API parity (CONVENTIONS.md §9 DoD item 2, same default `'lg'`)
    but stays inert — documented as a §7 divergence. The visible consequence:
    between `md` and `showPopover` Angular shows the desktop button + modal,
    while this port already shows the popover.

    Angular has no `host:` class on this component (the template has no single
    wrapping element), but header-profile.component.scss keys its rules on the
    *element* (`tedi-header-profile { … tedi-icon.tedi-header-profile__icon { … } }`),
    so the root must be `<tedi-header-profile>` and not a bare `<div>` — with a
    `<div>` the profile icon renders at 18px instead of the 36px `--icon-06`.
    It doubles as the scope for Alpine's `x-data` in the modal branch, per
    CONVENTIONS.md §6's one-root-element rule; it carries no class of its own.

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
    $popoverId = \Tedi\Livewire\Tedi::id('tedi-header-profile');
@endphp

@if ($isSmall)
    {{-- Mobile branch: trigger + overlay + full-screen modal, no positioning engine. --}}
    <tedi-header-profile x-data="{ open: false }" {{ $attributes }}>
        {{-- DIVERGENCE: Angular swaps the icon to `close` while the modal is
             open (`[icon]="modalOpen() ? 'close' : 'account_circle'"`). The
             icon name is the icon element's text content, so the swap needs an
             `x-text` on that inner element (the trick header/toggle.blade.php
             uses on its own icon) — and `<tedi:header.mobile-button>` forwards
             `$attributes` to the button, not to the icon, with no hook for
             reaching it. The button still gets
             `tedi-header-mobile-button--selected` while open, which is the
             modifier the vendored SCSS styles; only the glyph stays
             `account_circle`. --}}
        <tedi:header.mobile-button
            icon="account_circle"
            :label="$resolvedLabel"
            aria-has-popup="dialog"
            x-on:click="open = ! open"
            x-bind:aria-expanded="open.toString()"
            x-bind:class="{ 'tedi-header-mobile-button--selected': open }"
        />

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
@else
    {{-- Desktop branch: the popover, matching Angular's `*showAt(showPopover)` half. --}}
    <tedi-header-profile {{ $attributes }}>
        <tedi:popover :with-border="true" position="bottom" :prevent-overflow="true" :container-id="$popoverId">
            <x-slot:trigger>
                <tedi:button
                    tedi-popover-trigger
                    tabindex="0"
                    :variant="$buttonVariant"
                    :aria-label="$triggerAriaLabel"
                    aria-haspopup="dialog"
                    aria-expanded="false"
                    :id="$popoverId.'_trigger'"
                    x-ref="trigger"
                    x-on:click="toggle()"
                    x-bind:data-open="open"
                    x-bind:aria-expanded="open.toString()"
                    x-bind:aria-controls="open ? '{{ $popoverId }}' : null"
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
            </x-slot:trigger>

            <tedi:popover-content max-width="small" class="tedi-header-profile__popover">
                {{ $slot }}
            </tedi:popover-content>
        </tedi:popover>
    </tedi-header-profile>
@endif
