{{--
    TEDI Form field.
    Port of angular/tedi/components/form/form-field/{form-field.component.ts,form-field.component.html}

    Angular's FormFieldComponent leans heavily on runtime DOM introspection
    (contentChild queries for the projected control/feedback, AfterContentInit
    tag-name sniffing, a live NgControl subscription for validation state, and
    manual aria-describedby wiring). None of that is available on the server,
    so per CONVENTIONS.md §5 every such case becomes an explicit prop:

      Angular runtime detection                  Blade prop
      -----------------------------------------  ------------------------
      isTextarea() from the projected tag name    textarea
      ngControl invalid && (touched || dirty)     invalid
      feedback?.type() === 'valid'                valid
      control()?.value() (for the clear button    value
        and the character counter)                  + characterCount
      control()?.disabled() / inputGroup.disabled  disabled (+ @aware)
      inputGroup.invalid()                         invalid (+ @aware)

    `handleBoxMouseDown` (click-to-focus on the box padding) and the live
    aria-describedby sync are DOM-runtime behaviour with no static
    equivalent; per CONVENTIONS.md §7 they are not ported.

    `disabled` / `invalid` are also @aware so nesting inside <tedi:input-group>
    (CONVENTIONS.md §3, mirroring Angular's `inject(TEDI_INPUT_GROUP, {optional:true})`)
    merges the group's state in without the consumer repeating it, while a
    value set directly on <tedi:form-field> still wins.

    NOTE: Angular's hostClasses() also emits `tedi-form-field--with-icon`, but
    that class has no rule in the vendored form-field.component.scss (styling
    a control with an icon relies entirely on `__input`'s flex/gap layout, not
    a host modifier). Per the library-wide class/stylesheet guardrail
    (tests/IntegrityTest.php), this port only emits classes the vendored SCSS
    actually defines, so `--with-icon` is deliberately dropped.
--}}
@props([
    /** default|small|large */
    'size' => 'default',
    /** Material Symbols icon name, or an array: name/size/color/type/variant. */
    'icon' => null,
    /** Shows a clear button when `value` is non-empty. */
    'clearable' => false,
    /** Extra class added to the input box alongside tedi-form-field__input. */
    'inputClass' => null,
    /** Maximum character count; shows a live counter and forces invalid past it. */
    'characterLimit' => null,
    /** Current character count, for the counter and the limit check. */
    'characterCount' => 0,
    /** The wrapped control's current value — only used to decide clear-button visibility. */
    'value' => null,
    /** Explicit stand-in for Angular's runtime `<textarea>` tag detection. */
    'textarea' => false,
    'disabled' => false,
    'invalid' => false,
    'valid' => false,
    /** Extra attributes forwarded to the clear button (e.g. wire:click). */
    'clearAttributes' => [],
])

@aware([
    'disabled' => false,
    'invalid' => false,
])

@php
    $characterCountExceeded = $characterLimit !== null && $characterCount > $characterLimit;
    $isInvalid = $invalid || $characterCountExceeded;
    $isValid = $valid && ! $isInvalid;
    $showClearButton = $clearable && ! $textarea && (bool) $value;

    $resolvedIcon = ($icon && ! $textarea) ? (is_array($icon) ? $icon : ['name' => $icon]) : null;
    if ($resolvedIcon) {
        $resolvedIcon += [
            'size' => $size === 'small' ? 16 : ($size === 'large' ? 24 : 18),
            'color' => 'inherit',
            'type' => 'outlined',
            'variant' => 'outlined',
        ];
    }

    $showFeedbackRow = (isset($feedback) && $feedback->isNotEmpty()) || $characterLimit !== null;
    $baseId = \Tedi\Livewire\Tedi::id('tedi-form-field');
    $characterCountId = $characterLimit !== null ? $baseId.'-character-count' : null;
@endphp

@if (isset($label) && $label->isNotEmpty())
    {{ $label }}
@endif

<div {{ $attributes->class([
    'tedi-form-field',
    'tedi-form-field--valid' => $isValid,
    'tedi-form-field--invalid' => $isInvalid,
    'tedi-form-field--disabled' => $disabled,
    'tedi-form-field--small' => $size === 'small',
    'tedi-form-field--large' => $size === 'large',
]) }}>
    <div @class([
        'tedi-form-field__input',
        $inputClass => $inputClass,
    ])>
        {{ $slot }}

        @if ($clearable && ! $textarea)
            <div
                @class([
                    'tedi-form-field__buttons',
                    'tedi-form-field__buttons--hidden' => ! $showClearButton,
                ])
                @if (! $showClearButton) aria-hidden="true" @endif
            >
                <tedi:closing-button
                    class="tedi-form-field__clear"
                    size="small"
                    :icon-size="18"
                    icon="close"
                    :aria-label="__('tedi::tedi.clear')"
                    :show-title="false"
                    tabindex="{{ $showClearButton ? 0 : -1 }}"
                    :disabled="$disabled || ! $showClearButton"
                    {{ \Tedi\Livewire\Tedi::consumerAttributes($clearAttributes) }}
                />

                @if ($icon)
                    {{-- Mirrors <tedi-separator axis="vertical" size="1rem" /> (not yet ported as its own component). --}}
                    <span class="tedi-separator tedi-separator--primary tedi-separator--vertical tedi-separator--thickness-1" style="width: 0px; height: 1rem"></span>
                @endif
            </div>
        @endif

        @if ($resolvedIcon)
            <div class="tedi-form-field__icon">
                <tedi:icon
                    :name="$resolvedIcon['name']"
                    :size="$resolvedIcon['size']"
                    :color="$resolvedIcon['color']"
                    :type="$resolvedIcon['type']"
                    :variant="$resolvedIcon['variant']"
                    aria-hidden="true"
                />
            </div>
        @endif
    </div>

    @if ($showFeedbackRow)
        <div class="tedi-form-field__feedback">
            @isset($feedback)
                {{ $feedback }}
            @endisset

            @if ($characterLimit !== null)
                <span
                    class="tedi-form-field__character-count"
                    @class(['tedi-form-field__character-count--error' => $characterCountExceeded])
                    id="{{ $characterCountId }}"
                    aria-live="{{ $characterCountExceeded ? 'assertive' : 'polite' }}"
                >{{ $characterCount }}/{{ $characterLimit }}</span>
            @endif
        </div>
    @endif

    @isset($extra)
        <div class="tedi-form-field__extra">
            {{ $extra }}
        </div>
    @endisset
</div>
