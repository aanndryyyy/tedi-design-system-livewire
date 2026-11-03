{{--
    TEDI Form field.
    Port of angular/src/tedi/components/form/form-field/{form-field.component.ts,form-field.component.html,
    field-context.token.ts,form-field-control.ts,form-field-extra.directive.ts}

    Since Angular 8.0 (#612) a control no longer needs this wrapper: text
    field, textarea, date and time fields paint their own field surface. The
    form-field adds a label, feedback text, a character counter, an icon or a
    clear button. When it has an icon or a clear button it renders a box
    (`tedi-form-field__box tedi-field-surface`) that paints the surface
    instead, and the control inside drops its own; with neither, there is no
    box at all and the control renders bare inside the host.

    The two halves of Angular's DI contract run through the component stack
    (`Tedi::fieldContext()` in src/Tedi.php):

      Angular                                      Blade
      -------------------------------------------  ------------------------------------------
      TEDI_FIELD_CONTEXT provided to the control   the control calls Tedi::fieldContext() and
        (size, ownsSurface, invalid, valid,          reads what the consumer wrote here, with
        disabled, clearable)                         this component's @props defaults applied
      contentChild(TEDI_FORM_FIELD_CONTROL)        the control registers itself in this
        (value, disabled, readOnly, invalid,         component's frame; it arrives here as
        reset)                                       `$__tediFieldControl`

    So `tedi:text-field` and `tedi:textarea` drive the clear button and the
    character counter with no props repeated here. A plain `<input>`,
    `tedi:select` or `tedi:number-field` registers nothing — like
    Angular's, which do not implement FormFieldControl — and so never gets a
    clear button. Date and time fields own their clear button
    (`ownsClearButton`) and register nothing either.

    Explicit props still win over what the control registers, for wrappers
    that know better (search passes all of them):

      Angular runtime detection                    Blade prop
      -------------------------------------------  ------------------------
      control()?.value()                           value
      control.characterCount ?? value length       characterCount
      control()?.disabled() / inputGroup.disabled  disabled (+ @aware)
      control()?.invalid() / feedback 'error'      invalid (+ @aware)
        / inputGroup.invalid()
      feedback()?.type() === 'valid'               valid

    Divergences:

    * Dropped: `tedi-form-field--valid`, `--invalid` and `--disabled`. Angular
      still emits them on the host, but TEDI ships no rule for any of them
      since 8.0 moved the state onto `tedi-field-surface--*` (CONVENTIONS.md §4,
      "classes Angular emits but TEDI never styles"). The state is still
      rendered: on the box's `tedi-field-surface--invalid|valid|disabled`
      modifiers, or on the control itself when there is no box.
    * `aria-describedby`: the character counter's id reaches the control
      (Angular's `setDescribedBy`). The feedback text's id cannot — the feedback
      slot renders after the control — so pass it yourself:
      an `aria-describedby` on `tedi:text-field` naming the `id` of `tedi:feedback-text`.
    * The clear button's state is server-rendered from the registered value,
      then kept live by the bundled `tediFormField` behaviour
      (resources/js/src/form-field.js): it follows typing, and `clear()` empties
      the control, fires `input` and `change` (so `wire:model` follows) and focuses
      it, which is Angular's `reset()` + `focus()`. Without the bundle the button
      keeps its render-time state and only `clearAttributes` (e.g. `wire:click`)
      act on a click. An `x-on:click` / `@click` in `clearAttributes` runs after
      `clear()` rather than replacing it. The `clear` output is not re-emitted (CONVENTIONS.md §7 item 2).
    * `handleBoxMouseDown` (click-to-focus on the box padding) is not ported.
    * `--tedi-form-field-control-scrollbar` is always `0px`: Angular measures
      the textarea's classic scrollbar with a ResizeObserver; nothing measures
      it here, so on platforms with classic scrollbars a scrolling textarea's
      scrollbar sits under the clear button and icon.
    * `inputClass` is kept (deprecated upstream too).
    * The `extra` slot is Angular's `[tediFormFieldExtra]` projection.
--}}
@props([
    /** default|small|large — the label, the control and the box row scale together. */
    'size' => 'default',
    /** Material Symbols icon name, or an array: name/size/color/type/variant. */
    'icon' => null,
    /** Shows a clear button once the control holds a value. Read by every control inside. */
    'clearable' => true,
    /** Show the clear button only while the filled field is hovered or focused. Requires `clearable`. */
    'showClearOnInteraction' => false,
    /** Deprecated upstream: extra class on the field box. Style the control directly instead. */
    'inputClass' => null,
    /** Maximum character count; shows a live counter and forces the error state past it. */
    'characterLimit' => null,
    /** Character count for the counter. Defaults to the length of the control's value. */
    'characterCount' => null,
    /** The control's value, for the clear button and the counter. Defaults to what the control registered. */
    'value' => null,
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
    // contentChild(TEDI_FORM_FIELD_CONTROL) — see Tedi::fieldContext().
    $control = $__tediFieldControl ?? [];

    $controlValue = $value ?? ($control['value'] ?? null);
    $hasValue = is_array($controlValue) ? $controlValue !== [] : ($controlValue !== null && (string) $controlValue !== '');

    // renderClearButton(): only a control that can be reset gets one.
    $renderClearButton = $clearable && ! empty($control['reset']);
    // hasBox()
    $hasBox = ! empty($icon) || $renderClearButton;

    $isDisabled = (bool) $disabled || ! empty($control['disabled']);

    $characterCount = $characterCount ?? mb_strlen(is_array($controlValue) ? '' : (string) $controlValue);
    $characterCountExceeded = $characterLimit !== null && $characterCount > $characterLimit;

    // validationState()
    $validationState = ($invalid || ! empty($control['invalid']) || $characterCountExceeded)
        ? 'invalid'
        : ($valid ? 'valid' : 'neutral');

    // showClearButton()
    $showClearButton = $renderClearButton && ! $isDisabled && $hasValue && empty($control['readOnly']);

    $resolvedIcon = $icon ? (is_array($icon) ? $icon : ['name' => $icon]) : null;
    if ($resolvedIcon) {
        $resolvedIcon += [
            // iconSize()
            'size' => $size === 'small' ? 16 : ($size === 'large' ? 24 : 18),
            'color' => 'inherit',
            'type' => 'outlined',
            'variant' => 'outlined',
        ];
    }

    // A consumer click handler in clearAttributes runs after clear(); merging
    // it as-is would replace clear() (an attribute bag keeps one value per key).
    $clearAttributesRest = $clearAttributes instanceof \Illuminate\View\ComponentAttributeBag ? $clearAttributes->getAttributes() : (array) $clearAttributes;
    $consumerClicks = array_values(array_filter([
        $clearAttributesRest['x-on:click'] ?? null,
        $clearAttributesRest['@click'] ?? null,
    ]));
    unset($clearAttributesRest['x-on:click'], $clearAttributesRest['@click']);
    $clearClick = implode('; ', array_merge(['clear()'], $consumerClicks));

    $showFeedbackRow = (isset($feedback) && $feedback->isNotEmpty()) || $characterLimit !== null;
    // Shared with the control, which may already have put it in its aria-describedby.
    $baseId = $__tediFieldId ?? \Tedi\Livewire\Tedi::id('tedi-form-field');
    $characterCountId = $characterLimit !== null ? $baseId.'-character-count' : null;
@endphp

<tedi-form-field {{ $attributes->class([
    'tedi-form-field',
    'tedi-form-field--clear-on-interaction' => $showClearOnInteraction && $showClearButton,
    'tedi-form-field--small' => $size === 'small',
    'tedi-form-field--large' => $size === 'large',
]) }}>
    @if (isset($label) && $label->isNotEmpty())
        {{ $label }}
    @endif

    @if ($hasBox)
        <div
            @class([
                'tedi-form-field__box',
                'tedi-field-surface',
                'tedi-field-surface--invalid' => $validationState === 'invalid',
                'tedi-field-surface--valid' => $validationState === 'valid',
                'tedi-field-surface--disabled' => $isDisabled,
                $inputClass => (bool) $inputClass,
            ])
            style="--tedi-form-field-control-scrollbar: 0px"
        >
            {{ $slot }}

            @if ($renderClearButton)
                <div
                    @class([
                        'tedi-form-field__buttons',
                        'tedi-form-field__buttons--hidden' => ! $showClearButton,
                    ])
                    @if (! $showClearButton) aria-hidden="true" @endif
                    x-data="tediFormField({ clearOnInteraction: @js((bool) $showClearOnInteraction) })"
                    x-effect="syncRoot()"
                    x-bind:class="{ 'tedi-form-field__buttons--hidden': ! showClear }"
                    x-bind:aria-hidden="showClear ? null : 'true'"
                >
                    <tedi:closing-button
                        class="tedi-form-field__clear"
                        size="small"
                        :icon-size="18"
                        icon="close"
                        :aria-label="__('tedi::tedi.clear')"
                        :show-title="false"
                        tabindex="{{ $showClearButton ? 0 : -1 }}"
                        :disabled="! $showClearButton"
                        x-bind:tabindex="showClear ? 0 : -1"
                        x-bind:disabled="! showClear"
                        x-on:click="{{ $clearClick }}"
                        {{ $attributes->only([])->merge($clearAttributesRest) }}
                    />

                    @if ($icon)
                        <tedi:separator axis="vertical" size="1rem" />
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
    @else
        {{ $slot }}
    @endif

    @if ($showFeedbackRow)
        <div class="tedi-form-field__feedback">
            @isset($feedback)
                {{ $feedback }}
            @endisset

            @if ($characterLimit !== null)
                <span
                    @class([
                        'tedi-form-field__character-count',
                        'tedi-form-field__character-count--error' => $characterCountExceeded,
                    ])
                    id="{{ $characterCountId }}"
                    aria-live="polite"
                >{{ $characterCount }}/{{ $characterLimit }}</span>
            @endif
        </div>
    @endif

    {{-- Always rendered, like Angular's; `.tedi-form-field__extra:empty` hides it, so no whitespace inside. --}}
    <div class="tedi-form-field__extra">@isset($extra){{ $extra }}@endisset</div>
</tedi-form-field>
