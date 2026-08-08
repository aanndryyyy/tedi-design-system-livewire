{{--
    TEDI Toggle (switch).
    Port of angular/tedi/components/form/toggle/{toggle.component.ts,toggle.component.html}

    Root element: Angular's selector is the ELEMENT `tedi-toggle`, but the
    vendored SCSS contains no `tedi-toggle { … }` element rule — every rule keys
    on `.tedi-toggle`/`.tedi-toggle__*`. Per the batch ruling (CONVENTIONS.md §4,
    "element selectors in the vendored SCSS"), an element selector with no
    element rule renders as a plain <div> carrying the host class, exactly like
    radio-card-group.blade.php.

    `{{ $attributes }}` sits on the <input type="checkbox" role="switch">, not on
    the wrapper, so a consumer's `wire:model` binds to the real form control —
    the same native-control carve-out select.blade.php takes (CONVENTIONS.md §6
    overriding §4's "attributes on the single root"). Consequence worth knowing:
    a consumer-supplied `class` therefore merges onto the <input>, not the
    wrapper. The wrapper's host classes are built with @class([...]) so nothing
    is hand-concatenated.

    Angular's ControlValueAccessor (writeValue/registerOnChange/handleChange)
    and the focus()/blur() imperative API are not ported — `wire:model` on the
    native input replaces value tracking, and the browser owns focus.

    `inputId` is `input.required<string>()` in Angular. Blade cannot fail a
    render for a missing prop without breaking IntegrityTest's bare render, so
    it falls back to `Tedi::id('tedi-toggle')`. Pass it explicitly whenever an
    external <label for="…"> must point at the input.

    Every class emitted here exists in dist/tedi.css; nothing is dropped.
--}}
@props([
    /** Id for the <input>, also the target of an external label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Is the toggle checked? */
    'checked' => false,
    /** Is the input disabled? */
    'disabled' => false,
    /** Indicates whether the input field is required. */
    'required' => false,
    /** Color variant: primary|colored */
    'variant' => 'primary',
    /** Type: filled|outlined */
    'type' => 'filled',
    /** Size: default|large */
    'size' => 'default',
    /** Show the lock icon. Works only with size="large". */
    'icon' => false,
    /** Accessible label, for when no visible <label> is associated with the input. */
    'ariaLabel' => null,
])

@php
    // Unconditional: the <input> id is on every render path, so it must never
    // depend on a branch being taken.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-toggle');

    // iconColor() from toggle.component.ts — depends on type, variant and checked.
    if ($type === 'outlined') {
        $iconColor = 'white';
    } elseif ($variant === 'colored') {
        $iconColor = $checked ? 'success' : 'danger';
    } else {
        $iconColor = $checked ? 'brand' : 'tertiary';
    }
@endphp

<div @class([
    'tedi-toggle',
    'tedi-toggle--'.$variant.'-'.$type,
    'tedi-toggle--size-'.$size,
])>
    <input
        type="checkbox"
        role="switch"
        @checked($checked)
        @disabled($disabled)
        @if ($required) required @endif
        {{ $attributes->class(['tedi-toggle__input'])->merge(['id' => $inputId])->merge(array_filter([
            // Strings, not bools: array_filter would drop a literal false and
            // the attribute would vanish, while Angular emits aria-checked="false".
            'aria-checked' => $checked ? 'true' : 'false',
            'aria-label' => $ariaLabel ?: null,
        ])) }}
    />
    <span class="tedi-toggle__slider" aria-hidden="true">
        @if ($icon && $size === 'large')
            <tedi:icon
                :name="$checked ? 'lock_open_right' : 'lock'"
                :color="$iconColor"
                :size="16"
                class="tedi-toggle__icon"
            />
        @endif
    </span>
</div>
