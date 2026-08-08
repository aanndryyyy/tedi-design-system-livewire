{{--
    TEDI Slider — DOCUMENTED SUBSET (the thumb tooltip is not ported).
    Port of angular/tedi/components/form/slider/{slider.component.ts,slider.component.html}

    Root element: Angular's selector is the ELEMENT `tedi-slider`, but the
    vendored SCSS contains no `tedi-slider { … }` element rule — every rule keys
    on `.tedi-slider`/`.tedi-slider__*`. Per the batch ruling (CONVENTIONS.md §4,
    "element selectors in the vendored SCSS"), that renders as a plain <div>
    carrying the host class, like radio-card-group.blade.php.

    `{{ $attributes }}` sits on the <input type="range">, not on the wrapper, so
    a consumer's `wire:model` binds to the real form control — the native-control
    carve-out select.blade.php takes (CONVENTIONS.md §6 overriding §4). A
    consumer-supplied `class` therefore merges onto the <input>, not the root
    <div>. Host classes are built with @class([...]); nothing is concatenated.

    CLASSES DROPPED (CONVENTIONS.md §4, "classes Angular emits but TEDI never
    styles" / runtime-only state):

      * `tedi-slider--invalid` — Angular's classes() pushes it when isInvalid(),
        but dist/tedi.css ships NO rule for it (verified: 0 matches). Dropped per
        the guardrail. `isInvalid()` is still ported and still drives
        `aria-invalid` on the input, which is the part that carries meaning.
        Restore the class here if TEDI ever ships the rule.
      * `tedi-slider--dragging` — IS styled, but it is pure client-side pointer
        state (`isDragging` is set on pointerdown and cleared on a window
        pointerup listener). A server render has no drag in progress and this
        phase ships no JS for the slider, so emitting it would be a lie. Not
        emitted.

    NOT PORTED:

      * The thumb tooltip. Angular wraps the thumb in <tedi-tooltip> /
        <tedi-tooltip-trigger> / <tedi-tooltip-content>, which is CDK-Overlay
        positioning — explicitly out of scope (CONVENTIONS.md §7 item 3). The
        `tooltip` input is therefore NOT declared as a prop: per §7's ruling on
        inert props, an undeclared prop leaks a visible stray attribute rather
        than silently doing nothing. `.tedi-slider__thumb-anchor` (the overlay
        anchor: position:absolute, pointer-events:none) is not rendered either;
        the native range input draws its own thumb.
      * ControlValueAccessor, the hover/focus/drag signals, and the pointer
        event handlers — `wire:model` on the native input replaces them.

    DIVERGENCE from the batch's "don't unconditionally emit value=''" rule:
    `value` IS emitted unconditionally here. A `type="range"` input has no empty
    state — omit `value` and the browser parks the thumb at `min + (max-min)/2`,
    which would then disagree with the `--tedi-slider-progress` custom properties
    computed from `$value`. Emitting is strictly better, and Angular does the
    same (`[value]="clampedValue()"`). Consumers binding `wire:model` should also
    pass `:value` so the first paint matches the bound state.

    Angular's writeValue() maps a null/NaN value to `min()`; `$value ?? $min` in
    the clamp reproduces that.
--}}
@props([
    /** Id for the range <input>, also the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Name attribute of the underlying input. */
    'name' => null,
    /** Label rendered above the slider. */
    'label' => null,
    /** false|true|'keep-space' — true hides the label visually (sr-only), 'keep-space' also reserves its vertical space. */
    'hideLabel' => false,
    /** Marks the field as required. */
    'required' => false,
    /** Minimum allowed value. */
    'min' => 0,
    /** Maximum allowed value. */
    'max' => 100,
    /** Step size. */
    'step' => 1,
    /** Current value. */
    'value' => 0,
    /** Disables the slider. */
    'disabled' => false,
    /** Marks the slider as invalid. Drives aria-invalid; the unstyled tedi-slider--invalid class is dropped (see header). */
    'invalid' => false,
    /** Text rendered to the left of the track (e.g. the minimum value). */
    'minLabel' => null,
    /** Text rendered to the right of the track (e.g. the maximum value). Ignored when showCurrentValue is true. */
    'maxLabel' => null,
    /** Render the current value to the right of the track instead of maxLabel. */
    'showCurrentValue' => false,
    /**
     * Formats the current value for the showCurrentValue label. Angular's
     * `(value: number) => string` function input becomes any PHP callable —
     * a closure, 'number_format', or [$obj, 'method']. Only used by
     * showCurrentValue here, since the thumb tooltip is not ported.
     */
    'valueFormatter' => null,
    /** Feedback text below the slider: ['text' => ..., 'type' => 'hint', 'position' => 'left']. */
    'feedbackText' => null,
    /** Accessible label used when no visible `label` is provided. */
    'ariaLabel' => null,
    /** Id of an element that labels the slider, used when no visible `label` is provided. */
    'ariaLabelledby' => null,
    /** Human-readable text alternative of the current value. */
    'ariaValuetext' => null,
])

@php
    // Unconditional: the input id, the label's `for` and feedbackId all read it.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-slider');

    $min = (float) $min;
    $max = (float) $max;

    // isInvalid()
    $isInvalid = (bool) $invalid || (is_array($feedbackText) && ($feedbackText['type'] ?? null) === 'error');

    // clampedValue() — writeValue()'s null/NaN → min() fallback folded in.
    $numericValue = is_numeric($value) ? (float) $value : $min;
    $clampedValue = min($max, max($min, $numericValue));

    // progress() — guards max === min, as Angular does.
    $progress = $max === $min ? 0 : (($clampedValue - $min) / ($max - $min)) * 100;

    // progressStyle()
    $progressStyle = [
        '--tedi-slider-progress: '.$progress.'%',
        '--tedi-slider-progress-ratio: '.($progress / 100),
    ];

    // formattedValue()
    $formattedValue = is_callable($valueFormatter)
        ? (string) call_user_func($valueFormatter, $clampedValue)
        : (string) (0 + $clampedValue);

    // rightLabel()
    $rightLabel = $showCurrentValue ? $formattedValue : $maxLabel;

    // feedbackId()
    $feedbackId = $feedbackText ? $inputId.'-feedback' : null;

    // [class.sr-only] / [class.tedi-slider__label--reserve-space] on the label.
    $labelClass = $hideLabel === true
        ? 'sr-only'
        : ($hideLabel === 'keep-space' ? 'tedi-slider__label--reserve-space' : '');
@endphp

<div @class([
    'tedi-slider',
    'tedi-slider--disabled' => (bool) $disabled,
])>
    @if ($label)
        <tedi:form.label :for="$inputId" :required="$required" :class="$labelClass">{{ $label }}</tedi:form.label>
    @endif

    <div class="tedi-slider__container">
        <div class="tedi-slider__track-row">
            @if ($minLabel !== null)
                <span class="tedi-slider__range-label" aria-hidden="true">{{ $minLabel }}</span>
            @endif

            <div class="tedi-slider__track" style="{{ implode('; ', $progressStyle) }}">
                <input
                    type="range"
                    @disabled($disabled)
                    @if ($required) required @endif
                    {{ $attributes->class(['tedi-slider__input'])->merge([
                        // Always present, so merged unfiltered — array_filter()
                        // would drop value="0", min="0" and step="0".
                        'id' => $inputId,
                        'min' => 0 + $min,
                        'max' => 0 + $max,
                        'step' => $step,
                        'value' => 0 + $clampedValue,
                    ])->merge(array_filter([
                        'name' => $name,
                        'aria-invalid' => $isInvalid ? 'true' : null,
                        'aria-describedby' => $feedbackId,
                        'aria-label' => $ariaLabel,
                        'aria-labelledby' => $ariaLabelledby,
                        'aria-valuetext' => $ariaValuetext,
                    ])) }}
                />
            </div>

            @if ($rightLabel !== null)
                <span
                    class="tedi-slider__range-label"
                    @if ($showCurrentValue) aria-live="polite" @else aria-hidden="true" @endif
                >{{ $rightLabel }}</span>
            @endif
        </div>

        {{-- Angular's <ng-content select="[sliderAddon]" /> — always rendered,
             so the container keeps its layout whether or not it is filled. --}}
        <div class="tedi-slider__addon">{{ $addon ?? '' }}</div>
    </div>

    @if ($feedbackText)
        <tedi:feedback-text
            :id="$feedbackId"
            :text="$feedbackText['text']"
            :type="$feedbackText['type'] ?? 'hint'"
            :position="$feedbackText['position'] ?? 'left'"
        />
    @endif
</div>
