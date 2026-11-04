{{--
    TEDI Date Time Field — DOCUMENTED SUBSET.
    Port of react/src/tedi/components/form/date-time-field/date-time-field.tsx
    (CONVENTIONS.md §13).

    A date *and* time picker: the `tedi:date-field` input with a popup that
    carries a calendar and a time picker together. Upstream composes exactly the
    three components this package already has — `TextField`, `Calendar`,
    `TimePicker` — so the port composes `tedi:date-input`, `tedi:calendar` and
    `tedi:time-picker`, and adds only this component's own wrapper classes.

    THE FOUR PANEL LAYOUTS, all upstream's and all ported:

    | condition | panel |
    |---|---|
    | `mode="range"` | calendar, a separator, then a *pair* of time pickers (from / to) |
    | `layout="side-by-side"` | calendar, a separator, one time picker beside it |
    | `layout="multi-step"`, `step="date"` | the calendar alone, with a "Select time" footer link |
    | `layout="multi-step"`, `step="time"` | a header with a Back link and the chosen date, then the time picker |

    SUBSET — this inherits every gap of the pieces it is built from, and adds
    none of its own:

    * **Positioning.** Upstream mounts the popup through a floating-ui portal.
      `tedi:date-field`'s ruling applies unchanged (CONVENTIONS.md §7 item 3 as
      amended by §11): the panel renders inline under an explicit `open` prop.
      `.tedi-date-time-field__popup` styles background, radius and shadow, so
      nothing visual is lost — only placement.
    * **The native branch.** `useNative` swaps the whole thing for
      `<input type="datetime-local">` below a breakpoint; both the breakpoint
      (§7 item 1) and `showPicker()` are runtime, so the port always takes the
      custom branch.
    * **State.** `value`, `step`, `open`, `current-month` and the formatted
      `display` string are explicit props standing in for upstream's signals
      (CONVENTIONS.md §5). Parsing typed input, `stepMinutes` arithmetic,
      `availableTimes` resolution and the disabled-date error are the
      consumer's — pass the result in.
    * **`step-minutes` / `available-times`** are forwarded to `tedi:time-picker`
      as `minute-step` / `time-slots`, which is where that component already
      takes them.

    DEAD UPSTREAM RULE. The vendored stylesheet's `__time-header` block reaches
    out with `:global .tedi-btn--link .tedi-btn__icon--left`, which is React's
    button vocabulary and matches nothing here — see the note at the top of the
    vendored file and CONVENTIONS.md §13.3. The Back and "Select time" controls
    are `tedi:link`, which is this package's link-styled button, and it already
    lays its icon out the way that rule intended.

    React 20.1 sync:
    * `clearable` (default true) now gates the clear button — upstream's
      `isClearable: clearable`, previously hard-coded `true`.
    * The popup ARIA moved from the container and the input to the calendar
      icon button: `aria-haspopup="dialog"`, `aria-expanded` and, while open,
      `aria-controls` pointing at the popup. The container no longer carries
      `aria-haspopup`, and the input no longer carries `aria-expanded`. The
      button's accessible name stays `date-picker.open-calendar` (the same
      text as React's `dateField.openCalendar`), because the button is
      `tedi:date-input`'s.
    * THE FIELD SURFACE. Upstream's TextField always draws its own border.
      Since the Angular 8.0 sync the border here is the field surface, and
      the only vendored rule that paints one around a `tedi:date-input` is
      `.tedi-date-field.tedi-field-surface`. So the date-input is wrapped in
      a `tedi-date-field tedi-field-surface` element — the same shell
      `tedi:date-field` renders — carrying the `--invalid|--valid|--disabled`
      modifiers from the enclosing form-field (`Tedi::fieldContext()`). This
      registers with that form-field as a control without `reset`, so a
      wrapping form-field adds no clear button of its own and, unless it has
      an `icon`, no box either; with an `icon` the box paints the surface
      and the shell carries `tedi-date-field` alone.
    * NOT ported: `labelProps` (this component renders no label — wrap it in
      `tedi:form-field` with a `tedi:form.label`, and style that), and
      `minYear` / `maxYear`, which only feed the calendar header's year
      dropdown that `tedi:calendar` does not render (see calendar.blade.php).

    `{{ $attributes }}` goes on the container, as in `tedi:date-field`: this
    component renders no control of its own, and the nested `tedi:date-input`
    owns the native-control carve-out (CONVENTIONS.md §6).
--}}
@props([
    /** Id for the <input>, also the label's `for`. Auto-generated when omitted. */
    'inputId' => null,
    /** Formatted text shown in the input. Stands in for upstream's inputText. */
    'display' => '',
    /** Selected value: 'Y-m-d H:i', or ['from' => …, 'to' => …] in range mode. */
    'value' => null,
    /** single|range. */
    'mode' => 'single',
    /** side-by-side|multi-step — ignored in range mode, which has its own layout. */
    'layout' => 'side-by-side',
    /** date|time — which step of the multi-step layout is showing. */
    'step' => 'date',
    /** Whether the popup is rendered. Stands in for upstream's open state. */
    'open' => false,
    /** Placeholder shown when the input is empty. */
    'placeholder' => '',
    /** Disables the field entirely. */
    'disabled' => false,
    /** Blocks typing but leaves the pickers interactive. */
    'readOnly' => false,
    /** Sets the native `required` attribute. */
    'required' => false,
    /** Month the calendar opens on. Any strtotime()-able value. */
    'currentMonth' => null,
    /** Days that cannot be selected, as 'Y-m-d' strings. */
    'disabledDays' => [],
    /** dropdown|grid — how the calendar header exposes month/year picking. */
    'monthYearSelectType' => 'dropdown',
    /** Render the leading/trailing days of the adjacent months. */
    'showOutsideDays' => true,
    /** First column of the day grid: 0 = Sunday … 6 = Saturday. */
    'firstDayOfWeek' => 1,
    /** scroll|grid — forwarded to tedi:time-picker as `variant`. */
    'timeVariant' => 'scroll',
    /** Selectable times, e.g. ['09:00', '09:30']. Empty = the wheel. */
    'availableTimes' => [],
    /** Minute granularity of the wheel. */
    'stepMinutes' => 1,
    /** Heading above the time picker. Defaults to the translated "Time". */
    'timeHeading' => null,
    /** Whether the field shows a clear button once it has a value. */
    'clearable' => true,
])

@php
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-date-time-field');
    // Upstream's `context.floatingId`: what the icon button's aria-controls names.
    $popupId = $inputId.'-popup';

    $isRange = $mode === 'range';
    // effectiveLayout — range has its own branch, so the layout prop only
    // selects between side-by-side and multi-step for single mode.
    $isSideBySide = ! $isRange && $layout === 'side-by-side';
    $isMultiStep = ! $isRange && $layout === 'multi-step';

    // TEDI_FIELD_CONTEXT, as in date-field.blade.php — see the header.
    $fieldContext = \Tedi\Livewire\Tedi::fieldContext($__env, [
        'value' => filled($display) ? (string) $display : null,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readOnly,
    ]);
    $disabled = (bool) $disabled || (bool) ($fieldContext['disabled'] ?? false);
    $isInvalid = (bool) ($fieldContext['invalid'] ?? false);
    $isValid = (bool) ($fieldContext['valid'] ?? false);
    $paintsSurface = ! ($fieldContext['ownsSurface'] ?? false);
    // The date field's `childContext`, handed to the date input's text-field
    // (see date-field.blade.php). This component has no `size`.
    $childContext = \Tedi\Livewire\View\FieldContext::child(
        invalid: $isInvalid,
        valid: $isValid,
        disabled: $disabled,
        clearable: (bool) $clearable,
    );

    $timeHeading = $timeHeading ?? __('tedi::tedi.dateTimeField.timeHeading');

    $calendarValue = $isRange ? $value : ($value ?: null);
    // isWheelMode — upstream: no availableTimes means the scrolling wheel.
    $isWheelMode = count($availableTimes) === 0;

    // The multi-step time header shows the chosen date.
    $stepDate = null;
    if ($isMultiStep && $step === 'time' && filled($value) && ! is_array($value)) {
        $stepDate = date('d.m.Y', strtotime($value));
    }
@endphp

<div {{ $attributes->class(['tedi-date-time-field__container']) }}>
    <div @class([
        'tedi-date-field',
        'tedi-field-surface' => $paintsSurface,
        'tedi-field-surface--invalid' => $paintsSurface && $isInvalid,
        'tedi-field-surface--valid' => $paintsSurface && $isValid,
        'tedi-field-surface--disabled' => $paintsSurface && $disabled,
    ])>
        <tedi:date-input
            class="tedi-date-time-field__textfield"
            :input-id="$inputId"
            :value="$display"
            :placeholder="$placeholder"
            :disabled="$disabled"
            :invalid="$isInvalid"
            :read-only="(bool) $readOnly"
            :required="(bool) $required"
            :icon-active="(bool) $open"
            :icon-disabled="$disabled"
            :field-context="$childContext"
            :clearable="(bool) $clearable && filled($display) && ! $disabled && ! $readOnly"
            :icon-attributes="array_filter([
                'aria-haspopup' => 'dialog',
                'aria-controls' => ($open && ! $disabled) ? $popupId : null,
            ])"
        />
    </div>

    @if ($open && ! $disabled)
        <div class="tedi-date-time-field__popup" id="{{ $popupId }}" role="dialog">
            @if ($isRange)
                <div class="tedi-date-time-field__range">
                    <tedi:calendar
                        class="tedi-date-time-field__calendar"
                        :bordered="false"
                        mode="range"
                        :value="$calendarValue"
                        :current-month="$currentMonth"
                        :month-year-select-type="$monthYearSelectType"
                        :show-outside-days="(bool) $showOutsideDays"
                        :first-day-of-week="$firstDayOfWeek"
                        :disabled-days="$disabledDays"
                        :required="(bool) $required"
                        :number-of-months="2"
                    />

                    <div class="tedi-date-time-field__range-separator" aria-hidden="true">
                        <div class="tedi-date-time-field__range-separator-line"></div>
                    </div>

                    <div class="tedi-date-time-field__range-times">
                        <div class="tedi-date-time-field__range-time">
                            <div class="tedi-date-time-field__split-time-header">
                                <span class="tedi-date-time-field__split-heading">{{ $timeHeading }}</span>
                            </div>
                            <div class="tedi-date-time-field__split-time-body">
                                <tedi:time-picker
                                    @class([
                                        'tedi-date-time-field__time-picker',
                                        'tedi-date-time-field__time-picker--wheel' => $isWheelMode,
                                    ])
                                    :variant="$timeVariant"
                                    :time-slots="$availableTimes"
                                    :minute-step="$stepMinutes"
                                    :disabled="(bool) $disabled"
                                />
                            </div>
                        </div>
                        <div class="tedi-date-time-field__range-time">
                            <div class="tedi-date-time-field__split-time-header">
                                <span class="tedi-date-time-field__split-heading">{{ $timeHeading }}</span>
                            </div>
                            <div class="tedi-date-time-field__split-time-body">
                                <tedi:time-picker
                                    @class([
                                        'tedi-date-time-field__time-picker',
                                        'tedi-date-time-field__time-picker--wheel' => $isWheelMode,
                                    ])
                                    :variant="$timeVariant"
                                    :time-slots="$availableTimes"
                                    :minute-step="$stepMinutes"
                                    :disabled="(bool) $disabled"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($isSideBySide)
                <div class="tedi-date-time-field__split">
                    <tedi:calendar
                        class="tedi-date-time-field__calendar tedi-date-time-field__calendar--split"
                        :bordered="false"
                        :value="$calendarValue"
                        :current-month="$currentMonth"
                        :month-year-select-type="$monthYearSelectType"
                        :show-outside-days="(bool) $showOutsideDays"
                        :first-day-of-week="$firstDayOfWeek"
                        :disabled-days="$disabledDays"
                        :required="(bool) $required"
                    />

                    <div class="tedi-date-time-field__split-separator" aria-hidden="true">
                        <div class="tedi-date-time-field__split-separator-line"></div>
                    </div>

                    <div class="tedi-date-time-field__split-time">
                        <div class="tedi-date-time-field__split-time-header">
                            <span class="tedi-date-time-field__split-heading">{{ $timeHeading }}</span>
                        </div>
                        <div class="tedi-date-time-field__split-time-body">
                            <tedi:time-picker
                                @class([
                                    'tedi-date-time-field__time-picker',
                                    'tedi-date-time-field__time-picker--wheel' => $isWheelMode,
                                ])
                                :variant="$timeVariant"
                                :time-slots="$availableTimes"
                                :minute-step="$stepMinutes"
                                :disabled="(bool) $disabled"
                            />
                        </div>
                    </div>
                </div>
            @elseif ($step === 'time')
                <div class="tedi-date-time-field__time-step">
                    <header class="tedi-date-time-field__time-header">
                        <tedi:link size="small" icon-start="arrow_back">{{ __('tedi::tedi.dateTimeField.back') }}</tedi:link>
                        <span class="tedi-date-time-field__time-date">{{ $stepDate }}</span>
                    </header>
                    <div class="tedi-date-time-field__time-body">
                        <tedi:time-picker
                            @class([
                                'tedi-date-time-field__time-picker',
                                'tedi-date-time-field__time-picker--wheel' => $isWheelMode,
                            ])
                            :variant="$timeVariant"
                            :time-slots="$availableTimes"
                            :minute-step="$stepMinutes"
                            :disabled="(bool) $disabled"
                        />
                    </div>
                </div>
            @else
                <tedi:calendar
                    class="tedi-date-time-field__calendar"
                    :bordered="false"
                    :value="$calendarValue"
                    :current-month="$currentMonth"
                    :month-year-select-type="$monthYearSelectType"
                    :show-outside-days="(bool) $showOutsideDays"
                    :first-day-of-week="$firstDayOfWeek"
                    :disabled-days="$disabledDays"
                    :required="(bool) $required"
                >{{-- one line: .tedi-calendar__footer relies on :empty --}}<x-slot:footer><div class="tedi-date-time-field__select-time-wrapper"><tedi:link size="small" icon-start="schedule">{{ __('tedi::tedi.dateTimeField.selectTime') }}</tedi:link></div></x-slot:footer>
                </tedi:calendar>
            @endif
        </div>
    @endif
</div>
