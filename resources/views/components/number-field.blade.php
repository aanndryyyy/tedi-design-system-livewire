{{--
    TEDI Number field.
    Port of angular/src/tedi/components/form/number-field/{number-field.component.ts,number-field.component.html}

    ROOT ELEMENT. Angular's selector is the element `tedi-number-field`, so
    per CONVENTIONS.md §4 the root is that literal element. Upstream's host
    has no classes; every rule keys on the inner `.tedi-number-field` div that
    wraps the two buttons and the input. Label, field and feedback text are
    therefore all inside the one root, as in Angular (§6), and the root carries
    the Alpine scope.

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
    model default. With the bundle loaded they are recomputed from the live
    input as soon as Alpine starts, so a `wire:model`-bound field corrects
    itself; without it they stay at their server-rendered state.

    WHAT ALPINE ADDS (`tediNumberField`, resources/js/src/number-field.js):

      Angular                              Blade + Alpine
      -----------------------------------  --------------------------------
      handleButtonClick('decrement'|…)     stepBy(-1|1): ±step, clamped to
                                             min/max in the direction of
                                             travel, rounded to the step's
                                             decimals; fires `input` and
                                             `change` on the input, so
                                             wire:model / x-model follow.
      announceValue() / LiveAnnouncer      the root-level regions of
                                             announcer.js, politely, with
                                             `numberField.quantityUpdated`
                                             (or its `.suffix` form).
      decrementDisabled() /                recomputed from the live input on
        incrementDisabled() / isInvalid()    `input` / `change`, after a step
                                             and after a Livewire morph.
      (click)="focus()" on the wrapper     inline `x-on:click`.

    `decrementAttributes` / `incrementAttributes` still merge onto the two
    buttons. An `x-on:click` / `@click` in them runs after the step rather than
    replacing it. A `wire:click` that steps the value on the server now steps
    it twice: remove it (see the Upgrading notes).

    NOT PORTED:
      handleInputChange() / handleBlur()   the consumer's wire:model / x-model
                                             on the <input> does this.
      ControlValueAccessor, formDisabled   `isDisabled` is the `disabled` prop
        (setDisabledState)                   (the live input's `disabled` on
                                             the client).
      focus() / blur() public methods      no component instance.

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

    // 'numberField.decrement' | tediTranslate: step() — since Angular 10 (#896)
    // the label no longer mentions the step ("Decrease", not "Decrease by 3").
    $decrementLabel = __('tedi::tedi.numberField.decrement');
    $incrementLabel = __('tedi::tedi.numberField.increment');

    // Our step and a consumer's `x-on:click` / `@click` both run: merging them
    // as-is would let the consumer's replace ours (an attribute bag keeps one
    // value per key) — the same join file-upload.blade.php makes.
    $withClick = function ($attributes, string $own): array {
        $attributes = $attributes instanceof \Illuminate\View\ComponentAttributeBag ? $attributes->getAttributes() : (array) $attributes;
        $clicks = array_values(array_filter([$attributes['x-on:click'] ?? null, $attributes['@click'] ?? null]));
        unset($attributes['x-on:click'], $attributes['@click']);

        return ['x-on:click' => implode('; ', array_merge([$own], $clicks))] + $attributes;
    };

    $alpine = \Illuminate\Support\Js::from([
        'suffix' => filled($suffix) ? (string) $suffix : null,
        'labels' => [
            'updated' => __('tedi::tedi.numberField.quantityUpdated'),
            'updatedSuffix' => __('tedi::tedi.numberField.quantityUpdated.suffix'),
        ],
    ]);

    // <tedi:button> is a component tag, so its classes are passed as a plain
    // string attribute rather than through @class().
    $buttonClass = fn (string $direction) => implode(' ', array_filter([
        'tedi-number-field__button',
        'tedi-number-field__button--'.$direction,
        $size === 'small' ? 'tedi-number-field__button--small' : null,
    ]));
@endphp

{{-- Angular's host element: label, field and feedback are ONE root (CONVENTIONS.md §6),
     so a grid or flex parent lays them out as one item. --}}
<tedi-number-field x-data="tediNumberField({{ $alpine }})" x-on:input="sync()" x-on:change="sync()">
@if ($label)
    <tedi:form.label :for="$inputId" :required="$required" :size="$size">
        {{ $label }}
    </tedi:form.label>
@endif

<div @class([
    'tedi-number-field',
    'tedi-number-field--invalid' => $isInvalid,
    'tedi-number-field--disabled' => $isDisabled,
]) x-ref="field">
    <tedi:button
        type="button"
        variant="secondary"
        :icon-only="true"
        class="{{ $buttonClass('decrement') }}"
        :disabled="$decrementDisabled"
        :aria-label="$decrementLabel"
        x-ref="decrement"
        {{ $attributes->only([])->merge($withClick($decrementAttributes, 'stepBy(-1)')) }}
    >
        <tedi:icon name="remove" :size="18" />
    </tedi:button>

    <div @class([
        'tedi-number-field__input-wrapper',
        'tedi-number-field__input-wrapper--small' => $size === 'small',
        'tedi-number-field__input-wrapper--disabled' => $isDisabled,
        'tedi-number-field__input-wrapper--with-suffix' => filled($suffix),
        'tedi-number-field__input-wrapper--full-width' => $fullWidth,
    ]) x-on:click="$el.querySelector('input').focus()">
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
        x-ref="increment"
        {{ $attributes->only([])->merge($withClick($incrementAttributes, 'stepBy(1)')) }}
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
</tedi-number-field>
