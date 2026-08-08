{{--
    TEDI Input group.
    Port of angular/tedi/components/form/input-group/{input-group.component.ts,input-group.component.html,
    input-group-addon.directive.ts,input-group-prefix.directive.ts,input-group-suffix.directive.ts}

    Angular projects an arbitrary element marked with the `[tediInputGroupPrefix]`
    / `[tediInputGroupSuffix]` attribute directives, which add the addon class
    and an `isText` modifier detected from `childElementCount === 0`. Blade has
    no attribute-directive equivalent, so the prefix/suffix become named slots
    that this component wraps itself — the consumer supplies bare content
    (`<x-slot:prefix>Tänav</x-slot:prefix>`) instead of adding a directive to
    their own element. `isText` is still detected, just from the rendered slot
    HTML (no tag present) rather than a live DOM count.

    `disabled` / `invalid` are exposed to nested controls (`<tedi:form-field>`,
    `<tedi:select>`) via their matching `@@aware` — mirrors Angular's
    `inject(TEDI_INPUT_GROUP, { optional: true })` (CONVENTIONS.md §3).

    NOTE: Angular's host bindings also include
    `[class.tedi-input-group--invalid]`, but input-group.component.scss defines
    no `--invalid` rule of its own — validity is styled entirely on the nested
    control (`tedi-form-field--invalid` / a `tedi-input--error` on `<select>`),
    which is exactly what the `@@aware`-propagated `invalid` prop below drives.
    Per the library-wide class/stylesheet guardrail (tests/IntegrityTest.php)
    this port only emits host classes the vendored SCSS defines, so the
    `--invalid` host class is deliberately dropped while the prop itself is
    kept (it still does real work via @@aware).
--}}
@props([
    /** Merges the borders/radii of the addons and the control into one visual unit. */
    'addons' => true,
    'disabled' => false,
    /** Marks the whole group as invalid; propagates to the wrapped control via @aware. */
    'invalid' => false,
])

@php
    $isTextSlot = fn ($slot) => isset($slot) && $slot->isNotEmpty() && ! preg_match('/<[a-zA-Z]/', trim((string) $slot));
@endphp

<div
    role="group"
    @if ($disabled) aria-disabled="true" @endif
    {{ $attributes->class([
        'tedi-input-group',
        'tedi-input-group--addons' => $addons,
        'tedi-input-group--has-prefix' => isset($prefix) && $prefix->isNotEmpty(),
        'tedi-input-group--has-suffix' => isset($suffix) && $suffix->isNotEmpty(),
        'tedi-input-group--disabled' => $disabled,
    ]) }}
>
    @isset($label)
        {{ $label }}
    @endisset

    <div class="tedi-input-group__row">
        @isset($prefix)
            @if ($prefix->isNotEmpty())
                <span
                    @class([
                        'tedi-input-group__prefix',
                        'tedi-input-group__prefix--text' => $isTextSlot($prefix),
                    ])
                    @if ($disabled) aria-disabled="true" @endif
                >{{ $prefix }}</span>
            @endif
        @endisset

        {{ $slot }}

        @isset($suffix)
            @if ($suffix->isNotEmpty())
                <span
                    @class([
                        'tedi-input-group__suffix',
                        'tedi-input-group__suffix--text' => $isTextSlot($suffix),
                    ])
                    @if ($disabled) aria-disabled="true" @endif
                >{{ $suffix }}</span>
            @endif
        @endisset
    </div>

    @isset($feedback)
        {{ $feedback }}
    @endisset
</div>
