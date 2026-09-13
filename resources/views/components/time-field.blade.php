{{--
    TEDI Time Field — DOCUMENTED SUBSET.
    Port of angular/tedi/components/form/time-field/{time-field.component.ts,html}

    Angular's selector `tedi-time-field` is an element selector, but the vendored
    SCSS has no `tedi-time-field { … }` element rule — it styles `.tedi-time-field`
    only. Per the batch ruling this maps to a <div> carrying the host class.

    `{{ $attributes }}` goes on the inner <input>, not the root: this component
    exists so `wire:model` binds to a real form control (CONVENTIONS.md §6's
    native-control carve-out; precedent select.blade.php). The wrapper divs carry
    static classes.

    Divergences:
    - **Popover positioning is dropped** (CONVENTIONS.md §7 item 3). Angular mounts
      the picker in a `<tedi-popover-content>` placed by the overlay engine. Here
      `.tedi-time-field__popover-content` renders INLINE under an explicit `open`
      prop. That is faithful to the class's own styling — its only vendored rule is
      `padding: 0`, there is no positioning to lose — and it is what makes the
      `open` / `__icon--open` states reachable at all. What is missing is placement
      only. Likewise `popoverPosition` (`bottom-start` / `bottom-end`) is dropped.
    - **The mobile-modal branch and the `modal` prop are NOT ported.** Angular's
      `modal` defaults to `"md"` (modal below the md breakpoint) and opens
      `<tedi-time-picker-modal>`, which composes four `tedi-modal*` components that
      do not exist in this package; `grep -c '.tedi-time-picker-modal__form' dist/tedi.css`
      is 0. Both the breakpoint prop (CONVENTIONS.md §7 item 1) and the modal
      itself (§7 item 3) are out of scope, so the port always takes the
      popover/inline branch. `fullscreen` goes with it.
    - **`useNativePicker` is dropped** (breakpoint prop, §7 item 1). The input is
      therefore always `type="text"`; the `type="time"` branch is unreachable.
    - `closeOnSelect` is dropped: runtime behaviour with no markup effect.
    - `output()`s are not re-emitted; blur-time normalisation of typed input
      (`9:5` → `09:05`) is JS and is not ported (§7 item 2, §8).
    - `open` is an explicit prop standing in for Angular's `popover().isOpen()`
      runtime signal (CONVENTIONS.md §5).
    - `value` is emitted as a `value` attribute only when non-empty — an
      unconditional `value=""` would blank a `wire:model`-bound field on the
      initial server render.
    - `aria-invalid` follows Angular's `invalid() || null`: present as `"true"`
      when invalid, absent otherwise. `aria-expanded` likewise
      (`popoverIsOpen() || null`).
--}}
@props([
    /** Id for the <input>, also used for the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Selected time in `HH:mm` format. */
    'value' => null,
    /** Placeholder shown when the input is empty. */
    'placeholder' => null,
    /** Marks the field invalid: renders `aria-invalid="true"` on the input. */
    'invalid' => false,
    /** Disables interaction. Also suppresses the popover branch, as in Angular. */
    'disabled' => false,
    /** Show a clear button when the field has a value. */
    'clearable' => true,
    /** scroll|slots|dropdown|none — `none` renders just the input with no picker UI. */
    'pickerVariant' => 'scroll',
    /** button|input — what opens the picker: only the icon, or also the input. */
    'pickerTrigger' => 'button',
    /** Predefined `HH:mm` strings for the `slots` and `dropdown` picker variants. */
    'timeSlots' => [],
    /** Grid columns for the `slots` picker variant. */
    'columns' => 3,
    /** Show the radio indicator dot on each card in the `slots` picker variant. */
    'showSlotIndicator' => false,
    /** Minute step for the `scroll` picker variant. */
    'minuteStep' => 1,
    /** Whether the picker panel is shown. Stands in for Angular's popover open state. */
    'open' => false,
    /** default|small|large */
    'size' => 'default',
    'ownsSurface' => false,
    'valid' => false,
])

@aware([
    'ownsSurface' => false,
    'valid' => false,
    'size' => 'default',
    'invalid' => false,
    'disabled' => false,
])

@php
    $ownsSurface = \Tedi\Livewire\Tedi::fieldOwnsSurface((bool) $ownsSurface);
    // Unconditional: the id is referenced on every render path (CONVENTIONS.md §5,
    // precedent select.blade.php).
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-time-field');

    // hasPicker(): useNativePicker is dropped, so it reduces to the variant check.
    $hasPicker = $pickerVariant !== 'none';
    // usePopover(): useMobileModal() is always false here (modal branch not ported).
    $usePopover = $hasPicker && ! $disabled;
    $inputIsTrigger = $pickerTrigger === 'input' && $hasPicker;

    $hasValue = $value !== null && $value !== '';
    $showClear = $hasValue && $clearable;

    $fieldClasses = ['tedi-time-field__field'];
    if ($usePopover && ! $inputIsTrigger) {
        $fieldClasses[] = 'tedi-time-field__field--button-trigger';
    }

    $iconClasses = ['tedi-time-field__icon'];
    if ($open) {
        $iconClasses[] = 'tedi-time-field__icon--open';
    }

    $iconLabel = __('tedi::tedi.time-field.select-time');
@endphp

<div @class([
    'tedi-time-field',
    'tedi-time-field--small' => $size === 'small',
    'tedi-time-field--large' => $size === 'large',
])>
    {{-- The popover wrapper only exists in the popover branch; Angular renders a
         bare field div otherwise, and `.tedi-time-field__popover` is a flex box
         that would change the layout if emitted unconditionally. --}}
    @if ($usePopover)
    <div class="tedi-time-field__popover">
    @endif

        <div @class(array_merge($fieldClasses, [
            'tedi-field-surface' => ! $ownsSurface,
            'tedi-field-surface--invalid' => ! $ownsSurface && $invalid,
            'tedi-field-surface--valid' => ! $ownsSurface && $valid,
            'tedi-field-surface--disabled' => ! $ownsSurface && $disabled,
        ]))>
            <input
                inputmode="numeric"
                type="text"
                @disabled($disabled)
                @if ($inputIsTrigger) readonly @endif
                {{ $attributes->class(['tedi-time-field__input'])->merge(array_filter([
                    'id' => $inputId,
                    'value' => $hasValue ? $value : null,
                    'placeholder' => ($placeholder !== null && $placeholder !== '') ? $placeholder : null,
                    'aria-invalid' => $invalid ? 'true' : null,
                ], fn ($v) => $v !== null)) }}
            />

            <div class="tedi-time-field__actions">
                @if ($showClear)
                    <tedi:closing-button
                        size="small"
                        class="tedi-time-field__clear"
                        :icon-size="18"
                        :aria-label="__('tedi::tedi.time-field.clear')"
                        :disabled="$disabled ? 'disabled' : null"
                    />
                    <tedi:separator axis="vertical" size="1rem" />
                @endif

                @if ($usePopover)
                    <tedi:button
                        type="button"
                        variant="neutral"
                        size="small"
                        :class="implode(' ', $iconClasses)"
                        :aria-label="$iconLabel"
                        :aria-expanded="$open ? 'true' : null"
                        aria-haspopup="dialog"
                    >
                        <tedi:icon name="schedule" color="inherit" size="inherit" />
                    </tedi:button>
                @elseif ($hasPicker)
                    <tedi:button
                        type="button"
                        variant="neutral"
                        size="small"
                        class="tedi-time-field__icon"
                        :aria-label="$iconLabel"
                        :disabled="(bool) $disabled"
                    >
                        <tedi:icon name="schedule" color="inherit" size="inherit" />
                    </tedi:button>
                @else
                    <span class="tedi-time-field__icon tedi-time-field__icon--static" aria-hidden="true">
                        <tedi:icon name="schedule" color="inherit" size="inherit" />
                    </span>
                @endif
            </div>
        </div>

        @if ($usePopover && $open)
            <div class="tedi-time-field__popover-content">
                <tedi:time-picker
                    :value="$value"
                    :variant="$pickerVariant"
                    :time-slots="$timeSlots"
                    :columns="$columns"
                    :show-slot-indicator="(bool) $showSlotIndicator"
                    :minute-step="$minuteStep"
                />
            </div>
        @endif

    @if ($usePopover)
    </div>
    @endif
</div>
