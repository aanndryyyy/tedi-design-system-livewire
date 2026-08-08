{{--
    TEDI Number field.
    Port of angular/tedi/components/form/number-field/{number-field.component.ts,number-field.component.html}

    ROOT ELEMENT. Angular's selector is the element `tedi-number-field`, but the
    vendored SCSS has no element rule for that tag — every rule keys on the
    `.tedi-number-field` class, which in the Angular template is an *inner* div
    wrapping the two buttons and the input. So there is no `<tedi-number-field>`
    here: the template renders the same three top-level nodes Angular's own
    template does (optional label, `div.tedi-number-field`, optional feedback
    text), exactly as `form-field.blade.php` does.

    `{{ $attributes }}` SITS ON THE `<input type="number">`, not on the wrapper.
    This component wraps a single native control, so per CONVENTIONS.md §6's
    native-control carve-out (the same resolution `select.blade.php` takes) a
    consumer's `wire:model` / `x-model` / `class` lands on the control that
    actually holds the value. The wrapper divs carry static classes.

    DIVERGENCE — the `value` @props default is `null`, not Angular's `0`.
    Angular's `value` is a `model<number>(0)`, so it always has a number to
    render. Emitting `value="0"` unconditionally on the server would blank out a
    `wire:model`-bound field on the initial render (see CONVENTIONS.md's ruling
    on value attributes), so the `value` attribute is emitted only when `value`
    is neither `null` nor `''` — pass `:value="0"` explicitly to render a zero.
    The `isInvalid` / `decrementDisabled` / `incrementDisabled` computeds still
    evaluate against `0` when `value` is omitted, which is faithful to Angular's
    model default; note that a `wire:model`-bound field therefore computes those
    against 0 on the first render, before Livewire has hydrated a value.

    NOT PORTED (CONVENTIONS.md §7, §8 — this is a template-only component):

      Angular                              Blade
      -----------------------------------  --------------------------------
      handleButtonClick('decrement'|…)     decrementAttributes /
        (mutates value by ±step)             incrementAttributes — arrays
                                             merged onto the two buttons so a
                                             consumer attaches wire:click /
                                             x-on:click themselves (the same
                                             hook form-field.blade.php gives
                                             its clear button). No Alpine
                                             state is invented here.
      announceValue() / LiveAnnouncer      dropped — a live region announcing
                                             a value only a client-side
                                             handler can change.
      handleInputChange() / handleBlur()   dropped — the consumer's wire:model
                                             on the <input> does this.
      (click)="focus()" on the wrapper     dropped — click-to-focus on the
                                             wrapper padding is DOM runtime
                                             behaviour.
      ControlValueAccessor, formDisabled   dropped — `isDisabled` is therefore
        (setDisabledState)                   just the `disabled` prop; there
                                             is no forms-API source for it.
      focus() / blur() public methods      dropped — no component instance.

    No classes are dropped: all fourteen classes number-field.component.html
    can emit have rules in the vendored SCSS.
--}}
@props([
    /**
     * Id for the <input>, also used for the label's `for` and the feedback
     * text's id. Angular's is `input.required`; here it is auto-generated
     * (Tedi::id()) when omitted so the component still renders bare.
     */
    'inputId' => null,
    /** The text content of the label that describes the input field. */
    'label' => null,
    /**
     * Value of the input field. Diverges from Angular's `0` default — see the
     * header comment; `null`/`''` omits the attribute entirely.
     */
    'value' => null,
    /** Is input disabled? */
    'disabled' => false,
    /** Marks the field required; shows the required indicator next to the label. */
    'required' => false,
    /** Minimum allowed value. Disables decrementing below it and marks lower values invalid. */
    'min' => null,
    /** Maximum allowed value. Disables incrementing above it and marks higher values invalid. */
    'max' => null,
    /** Step size for incrementing or decrementing the value. */
    'step' => 1,
    /** default|small */
    'size' => 'default',
    /** Marks the field as invalid for validation purposes. */
    'invalid' => false,
    /** Text displayed after the input value, typically a unit. */
    'suffix' => null,
    /** Configuration for the feedback text below the field: ['text' => ..., 'type' => 'hint', 'position' => 'left']. */
    'feedbackText' => null,
    /** Is input full width? */
    'fullWidth' => false,
    /** Accessible label for the input, used when no visible `label` is provided. */
    'ariaLabel' => null,
    /** Extra attributes forwarded to the decrement button (e.g. wire:click). */
    'decrementAttributes' => [],
    /** Extra attributes forwarded to the increment button (e.g. wire:click). */
    'incrementAttributes' => [],
])

@php
    // Unconditional: the <input> id, the label's `for` and the feedback id all
    // read it, so it must never depend on a branch being taken.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-number-field');

    $min = is_numeric($min) ? $min + 0 : null;
    $max = is_numeric($max) ? $max + 0 : null;

    // Angular's model defaults to 0; an omitted value is compared as 0 here for
    // the same reason, even though the attribute itself is not emitted.
    $hasValue = $value !== null && $value !== '';
    $currentValue = is_numeric($value) ? $value + 0 : 0;

    // isInvalid()
    $isInvalid = (bool) $invalid
        || ($min !== null && $currentValue < $min)
        || ($max !== null && $currentValue > $max);

    // isDisabled() — formDisabled() (ControlValueAccessor) is not ported.
    $isDisabled = (bool) $disabled;

    // decrementDisabled() / incrementDisabled()
    $decrementDisabled = $isDisabled || ($min !== null && $currentValue <= $min);
    $incrementDisabled = $isDisabled || ($max !== null && $currentValue >= $max);

    // feedbackId()
    $feedbackId = $feedbackText ? $inputId.'-feedback' : null;

    // 'numberField.decrement' | tediTranslate: step() — the parameterised keys
    // carry the argument as the literal "false" placeholder (pagination.blade.php
    // substitutes into the same .true/.false convention).
    $decrementLabel = str_replace('false', (string) $step, __('tedi::tedi.numberField.decrement.false'));
    $incrementLabel = str_replace('false', (string) $step, __('tedi::tedi.numberField.increment.false'));

    // <tedi:button> is a component tag, so its classes are passed as a plain
    // string attribute rather than through @class().
    $buttonClass = fn (string $direction) => implode(' ', array_filter([
        'tedi-number-field__button',
        'tedi-number-field__button--'.$direction,
        $size === 'small' ? 'tedi-number-field__button--small' : null,
    ]));
@endphp

@if ($label)
    <tedi:form.label :for="$inputId" :required="$required" :size="$size">
        {{ $label }}
    </tedi:form.label>
@endif

<div @class([
    'tedi-number-field',
    'tedi-number-field--invalid' => $isInvalid,
    'tedi-number-field--disabled' => $isDisabled,
])>
    <tedi:button
        type="button"
        variant="secondary"
        :icon-only="true"
        class="{{ $buttonClass('decrement') }}"
        :disabled="$decrementDisabled"
        :aria-label="$decrementLabel"
        {{ $attributes->only([])->merge($decrementAttributes) }}
    >
        <tedi:icon name="remove" :size="18" />
    </tedi:button>

    <div @class([
        'tedi-number-field__input-wrapper',
        'tedi-number-field__input-wrapper--small' => $size === 'small',
        'tedi-number-field__input-wrapper--disabled' => $isDisabled,
        'tedi-number-field__input-wrapper--with-suffix' => filled($suffix),
        'tedi-number-field__input-wrapper--full-width' => $fullWidth,
    ])>
        <input
            id="{{ $inputId }}"
            type="number"
            inputmode="numeric"
            @if ($hasValue) value="{{ $value }}" @endif
            @disabled($isDisabled)
            @required($required)
            {{ $attributes->class(['tedi-number-field__input'])->merge(array_filter([
                'min' => $min,
                'max' => $max,
                'step' => $step,
                'aria-invalid' => $isInvalid ? 'true' : 'false',
                // Suppressed whenever a visible label exists.
                'aria-label' => $label ? null : ($ariaLabel ?: null),
                'aria-describedby' => $feedbackId,
            ], fn ($v) => $v !== null)) }}
        />

        @if (filled($suffix))
            <tedi:text as="small" color="tertiary" class="tedi-number-field__suffix">
                {{ $suffix }}
            </tedi:text>
        @endif
    </div>

    <tedi:button
        type="button"
        variant="secondary"
        :icon-only="true"
        class="{{ $buttonClass('increment') }}"
        :disabled="$incrementDisabled"
        :aria-label="$incrementLabel"
        {{ $attributes->only([])->merge($incrementAttributes) }}
    >
        <tedi:icon name="add" :size="18" />
    </tedi:button>
</div>

@if ($feedbackText)
    <tedi:feedback-text
        :id="$feedbackId"
        :text="$feedbackText['text']"
        :type="$feedbackText['type'] ?? 'hint'"
        :position="$feedbackText['position'] ?? 'left'"
    />
@endif
