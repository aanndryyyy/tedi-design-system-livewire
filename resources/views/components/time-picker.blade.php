{{--
    TEDI Time Picker.
    Port of angular/tedi/components/form/time-picker/{time-picker.component.ts,html}

    Angular's selector `tedi-time-picker` is an element selector, but the
    vendored SCSS contains NO `tedi-time-picker { … }` element rule — it styles
    `.tedi-time-picker` only. Per the batch ruling this maps to a <div> carrying
    the host class (precedent: radio-card-group.blade.php). `.tedi-time-picker`
    sets `display: block`, so the swap is exact.

    ⚠ ALL THREE VARIANT CLASSES ARE DROPPED — THIS IS NOT A BUG.
    Angular's host block emits `tedi-time-picker--scroll`, `--slots` and
    `--dropdown`. `grep -cE '\.tedi-time-picker--(scroll|slots|dropdown)([^-A-Za-z0-9_]|$)' dist/tedi.css`
    returns 0 for all three: TEDI ships no rule for any of them. Per
    CONVENTIONS.md §4 ("classes Angular emits but TEDI never styles" — the
    stylesheet guardrail wins) they are dropped. `variant` still selects which
    markup branch renders; the host element simply carries no variant class.
    Only `--disabled` and `--bordered` survive. Restore all three if TEDI ever
    ships rules for them.

    Also dropped for the same reason: `tedi-time-picker__slot`, which Angular
    puts on each `slots`-variant <label tedi-radio-card> (grep 0).

    ⚠ `__item--selected` binds to the SCROLL INDEX, not to the value.
    Angular binds `[class.tedi-time-picker__item--selected]="highlightedHourIndex() === i"`,
    and `alignScroll()` seeds that index from `selectedHourIndex()`, which is
    `selectedHour() ?? 12`. So with no value Angular highlights hour `12` and
    minute index `0` — the wheel "parks" there. The formula is reproduced
    verbatim below; binding `--selected` to `selectedHour === i` instead would
    render nothing selected on a bare component, which is NOT what Angular does.

    Divergences beyond the dropped classes:
    - `trapFocus` is dropped: pure JS focus behaviour with no markup effect
      (CONVENTIONS.md §7 item 2 / §8 — no Alpine is added for this batch).
    - `output()`s (`valueChange`, `closeRequested`) are not re-emitted;
      consumers bind `wire:click` / `x-on:click` through `$attributes`
      (CONVENTIONS.md §7 item 2). Scroll-wheel snapping, keyboard roving and
      the ResizeObserver item-height measurement are likewise not ported — the
      rendered markup, ids and ARIA wiring are.
    - The inline `--tedi-time-picker-dropdown-min-width` custom property that
      Angular's time-field feeds from a JS width measurement is dropped; the
      vendored SCSS already falls back (`min-width: var(…, auto)`).
    - `aria-disabled` on the scroll columns and `aria-selected` on the scroll
      items are RAW boolean bindings in Angular, so they render as the literal
      strings `"true"`/`"false"` and are always present — they are emitted as
      strings here, never as PHP booleans (which `array_filter` would eat).
      The dropdown list/items use `? true : null` instead, so those omit the
      attribute entirely when enabled. Both forms are reproduced exactly.

    `$attributes` sits on the root <div>: this is a single-root component with
    no native form control of its own (CONVENTIONS.md §6).
--}}
@props([
    /** Selected time in `HH:mm` format. Invalid strings collapse to "no selection". */
    'value' => null,
    /** scroll|slots|dropdown — selects the markup branch. Emits no class (see above). */
    'variant' => 'scroll',
    /** Predefined `HH:mm` strings for the `slots` and `dropdown` variants. */
    'timeSlots' => [],
    /** Number of columns for the `slots` grid. */
    'columns' => 3,
    /** Show the radio indicator dot on each card in the `slots` variant. */
    'showSlotIndicator' => false,
    /** Minute step for the `scroll` variant — e.g. `5` renders `00, 05, 10…`. */
    'minuteStep' => 1,
    /** Disables interaction. */
    'disabled' => false,
    /** Render the picker with a surrounding border. */
    'border' => false,
    /** Shared `name` for the `slots` radios. Auto-generated (Tedi::id()) when omitted. */
    'name' => null,
])

@php
    // Unconditional: the ids are referenced by every branch's ARIA wiring, so
    // they must never depend on a branch being taken (precedent:
    // select.blade.php's $inputId).
    $uid = \Tedi\Livewire\Tedi::id('tedi-time-picker-');
    $name = $name ?? $uid.'slot-group';

    $minuteStep = max(1, (int) $minuteStep);
    $columns = max(1, (int) $columns);
    $timeSlots = array_values((array) $timeSlots);

    // safeValue(): anything not matching HH:mm collapses to null.
    $trimmed = $value === null ? '' : trim((string) $value);
    $safe = preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $trimmed) ? $trimmed : null;

    $selHour = $safe !== null ? (int) substr($safe, 0, 2) : null;
    $selMinute = $safe !== null ? (int) substr($safe, 3, 2) : null;

    // selectedHourIndex() / selectedMinuteIndex(): where the wheel parks.
    $hiHour = $selHour ?? 12;
    $hiMinute = $selMinute === null ? 0 : intdiv($selMinute, $minuteStep);

    $hours = [];
    for ($i = 0; $i < 24; $i++) {
        $hours[] = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
    }

    $minutes = [];
    for ($i = 0, $count = (int) ceil(60 / $minuteStep); $i < $count; $i++) {
        $minutes[] = str_pad((string) ($i * $minuteStep), 2, '0', STR_PAD_LEFT);
    }

    // getDropdownTabIndex(): roving tabindex — the selected slot, or the first
    // slot when nothing is selected, is the only one in the tab sequence.
    $selectedSlotIndex = array_search((string) ($value ?? ''), $timeSlots, true);
    $rovingIndex = $selectedSlotIndex === false ? 0 : $selectedSlotIndex;
@endphp

<div {{ $attributes->class([
    'tedi-time-picker',
    'tedi-time-picker--disabled' => (bool) $disabled,
    'tedi-time-picker--bordered' => (bool) $border,
]) }}>
    @if ($variant === 'scroll')
        <div class="tedi-time-picker__columns" role="group">
            <div
                class="tedi-time-picker__column"
                role="listbox"
                tabindex="{{ $disabled ? '-1' : '0' }}"
                aria-label="{{ __('tedi::tedi.time-picker.hours') }}"
                aria-activedescendant="{{ $uid }}hour-{{ $hiHour }}"
                aria-disabled="{{ $disabled ? 'true' : 'false' }}"
            >
                @foreach ($hours as $i => $hour)
                    <button
                        type="button"
                        @class([
                            'tedi-time-picker__item',
                            'tedi-time-picker__item--selected' => $hiHour === $i,
                        ])
                        role="option"
                        id="{{ $uid }}hour-{{ $i }}"
                        aria-selected="{{ $selHour === (int) $hour ? 'true' : 'false' }}"
                        tabindex="-1"
                        @disabled($disabled)
                    >{{ $hour }}</button>
                @endforeach
            </div>
            <div class="tedi-time-picker__separator"></div>
            <div
                class="tedi-time-picker__column"
                role="listbox"
                tabindex="{{ $disabled ? '-1' : '0' }}"
                aria-label="{{ __('tedi::tedi.time-picker.minutes') }}"
                aria-activedescendant="{{ $uid }}minute-{{ $hiMinute }}"
                aria-disabled="{{ $disabled ? 'true' : 'false' }}"
            >
                @foreach ($minutes as $i => $minute)
                    <button
                        type="button"
                        @class([
                            'tedi-time-picker__item',
                            'tedi-time-picker__item--selected' => $hiMinute === $i,
                        ])
                        role="option"
                        id="{{ $uid }}minute-{{ $i }}"
                        aria-selected="{{ $selMinute === (int) $minute ? 'true' : 'false' }}"
                        tabindex="-1"
                        @disabled($disabled)
                    >{{ $minute }}</button>
                @endforeach
            </div>
        </div>
    @elseif ($variant === 'dropdown')
        @if (count($timeSlots) === 0)
            <p class="tedi-time-picker__empty">{{ __('tedi::tedi.time-picker.no-slots') }}</p>
        @else
            <div
                class="tedi-time-picker__dropdown"
                role="listbox"
                aria-label="{{ __('tedi::tedi.time-picker.times') }}"
                @if ($disabled) aria-disabled="true" @endif
            >
                @foreach ($timeSlots as $i => $slot)
                    <div
                        @class([
                            'tedi-time-picker__dropdown-item',
                            'tedi-time-picker__dropdown-item--selected' => $value === $slot,
                        ])
                        role="option"
                        aria-selected="{{ $value === $slot ? 'true' : 'false' }}"
                        @if ($disabled) aria-disabled="true" @endif
                        tabindex="{{ $disabled ? '-1' : ($rovingIndex === $i ? '0' : '-1') }}"
                    >{{ $slot }}</div>
                @endforeach
            </div>
        @endif
    @elseif ($variant === 'slots')
        @if (count($timeSlots) === 0)
            <p class="tedi-time-picker__empty">{{ __('tedi::tedi.time-picker.no-slots') }}</p>
        @else
            <tedi:radio-card-group
                class="tedi-time-picker__grid"
                role="radiogroup"
                style="grid-template-columns: repeat({{ $columns }}, 1fr)"
            >
                @foreach ($timeSlots as $i => $slot)
                    <tedi:radio-card
                        variant="secondary"
                        :show-indicator="(bool) $showSlotIndicator"
                        :for="$uid.'slot-'.$i"
                    >
                        <tedi:radio
                            :id="$uid.'slot-'.$i"
                            :name="$name"
                            :value="$slot"
                            :checked="$value === $slot"
                            :disabled="(bool) $disabled"
                        />
                        {{ $slot }}
                    </tedi:radio-card>
                @endforeach
            </tedi:radio-card-group>
        @endif
    @endif
</div>
