{{--
    TEDI Date field — DOCUMENTED SUBSET.
    Port of angular/tedi/components/form/date-field/{date-field.component.ts,html}

    Angular's `tedi-date-field` is a thin composer: a <tedi-date-input> plus a
    CDK-overlay-mounted <tedi-calendar>. The selector is an element selector, but
    the vendored SCSS has no `tedi-date-field { … }` rule — it styles
    `.tedi-date-field` only — so per the batch ruling the root is a <div> carrying
    the host class.

    `{{ $attributes }}` goes on that root <div>. This component is NOT one of the
    native-control carve-outs (CONVENTIONS.md §6): it renders no control of its
    own, it composes <tedi:date-input>, which owns the carve-out and puts its own
    bag on the real <input>. So `wire:model` written on <tedi:date-field> lands on
    the wrapper — put it on a nested <tedi:date-input> if you need the binding, or
    bind the calendar's day buttons directly.

    SUBSET — the overlay:
    * **Popover positioning is dropped** (CONVENTIONS.md §7 item 3). Angular mounts
      the calendar through `CdkConnectedOverlay`. Here `.tedi-date-field__overlay`
      renders INLINE under an explicit `open` prop. That is faithful to the class's
      own styling — its only vendored rules are background / border-radius /
      box-shadow, there is no positioning to lose — and it is what makes the open
      state reachable at all. What is missing is placement only. `hideOnScroll` and
      the overlay position list go with it.
    * **The modal branch and the `modal` prop are NOT ported.** Angular's
      `<tedi-date-field-modal>` composes four `tedi-modal*` components that do not
      exist in this package, and its styles were inline in the Angular decorator,
      never vendored: `grep -c '.tedi-date-field-modal' dist/tedi.css` is 0. Both
      the breakpoint prop (§7 item 1) and the modal itself (§7 item 3) are out of
      scope, so the port always takes the popover/inline branch. `fullscreen` goes
      with it.

    Further divergences:
    * `useNativePicker` is dropped (breakpoint prop, §7 item 1), so `showCalendar()`
      reduces to `enableCalendar` and the `<input type="date">` branch is
      unreachable. `numberOfMonths`, `enableCalendar` and `calendarTrigger` are
      Angular `BreakpointInput`s whose names do not signal breakpoints; per §7
      item 1's exception they are declared as plain scalars carrying the Angular
      `xs` value as the default.
    * `localeCode` is dropped — the calendar sources month/weekday names from the
      existing `date-picker.*` translation keys, not `Intl`, because `ext-intl` is
      not a declared dependency of this package. As a consequence Angular's
      `effectivePlaceholder()` fallback (`formatLocaleDateHint(localeCode)`, which
      renders "pp.kk.aaaa" in single mode when no placeholder is set) is dropped
      too: the placeholder here is exactly what you pass, and nothing when you
      pass nothing.
    * `formatDate` / `parseDate` are JS callables. Instead of accepting PHP
      callables, the formatted strings Angular derives from them are explicit
      props: `display` (Angular's `displayValue()`) and `tags` (its
      `tagsForMultipleMode()`), per CONVENTIONS.md §5. Format the value however
      your application wants and pass the result.
    * `disabledMatchers`, `minDate`, `maxDate`, `disablePast`, `disableFuture`,
      `shouldDisableMonth`, `shouldDisableYear`, `availableDays`,
      `unavailableDays`, `minYear` and `maxYear` are matcher machinery with no
      server-side analogue — they collapse into the flat `disabledDays` array of
      'Y-m-d' strings that <tedi:calendar> already takes.
    * `size` is declared for API parity but emits NO class: `tedi-date-field--small`
      has no rule in dist/tedi.css (grep 0), and Angular only forwards `size` to
      the wrapping `tedi-form-field`. Set `size` on <tedi:form-field> instead.
    * `selectionLevel` is forwarded to <tedi:calendar> but is behavioural only
      there too — it changes nothing in the rendered markup (the calendar always
      opens on the view its own `view` prop names). It is declared rather than
      omitted so it does not leak into the DOM as a stray attribute.
    * `canClear` reads an array `value` as "has a value" only when it is
      non-empty. Angular's `!!value` is true for `[]` as well, so a
      `mode="multiple"` field holding an explicitly-empty array shows a clear
      button in Angular and none here. That is the PHP-natural reading of a
      degenerate case, but it is a real difference.
    * `currentMonth` and `open` are explicit props standing in for Angular's
      runtime signals (CONVENTIONS.md §5); `closeOnSelect` and the `openChange`
      output are runtime-only and not ported (§7 item 2).
--}}
@props([
    /** Id for the <input>, also used for the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /**
     * Selected value, forwarded to the calendar; shape follows `mode`:
     *   single   → 'Y-m-d'
     *   multiple → ['Y-m-d', …]
     *   range    → ['from' => 'Y-m-d', 'to' => 'Y-m-d'|null]
     */
    'value' => null,
    /** Formatted text shown in the input. Stands in for Angular's displayValue(). */
    'display' => '',
    /** Multiple mode tags: [['id' => …, 'label' => …], …]. Stands in for tagsForMultipleMode(). */
    'tags' => [],
    /** single|multiple|range */
    'mode' => 'single',
    /** Multiple mode: true wraps tags across rows, false keeps one row with a +N counter. */
    'multiRow' => true,
    /** false|'start'|'end' — which end a multiple-mode tag label truncates from. */
    'tagEllipsis' => false,
    /** Whether multiple-mode tags show a remove button. */
    'isTagRemovable' => true,
    /** How many tags fit on one row; null = unmeasured (renders them all). */
    'visibleTagCount' => null,
    /** Placeholder shown when the input is empty. */
    'placeholder' => '',
    /** Disables the field entirely — input, icon button and calendar. */
    'inputDisabled' => false,
    /** Blocks typing but leaves the calendar interactive. */
    'readOnly' => false,
    /** Sets the native `required` attribute on the input. */
    'required' => false,
    /** days|months|years — lowest level the user can commit to. */
    'selectionLevel' => 'days',
    /** dropdown|grid — how the calendar header exposes month/year picking. */
    'monthYearSelectType' => 'dropdown',
    /** Render the leading/trailing days of the adjacent months. */
    'showOutsideDays' => true,
    /** Render the ISO week-number column. */
    'showWeekNumbers' => false,
    /** How many month grids render side by side (Angular's `xs` base value). */
    'numberOfMonths' => 1,
    /** Enables the calendar picker UI; false hides the icon button (Angular's `xs` base value). */
    'enableCalendar' => true,
    /** button|input — what opens the calendar (Angular's `xs` base value). */
    'calendarTrigger' => 'button',
    /** Month the calendar opens on. Any strtotime()-able value; null → the value's month, else the current month. */
    'currentMonth' => null,
    /** Whether the calendar panel is shown. Stands in for Angular's overlay open state. */
    'open' => false,
    /** First column of the calendar's day grid: 0 = Sunday … 6 = Saturday. */
    'firstDayOfWeek' => 1,
    /** Days that cannot be selected, as 'Y-m-d' strings. */
    'disabledDays' => [],
    /** default|small — forwarded-only, emits no class. Set it on the wrapping form-field too. */
    'size' => 'default',
])

@php
    // Unconditional: the id is referenced on every render path (CONVENTIONS.md §5,
    // precedent select.blade.php).
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-date-field');

    // showCalendar(): useNativePicker is dropped, so it reduces to enableCalendar.
    $showCalendar = (bool) $enableCalendar;
    // inputIsTrigger()
    $inputIsTrigger = $showCalendar && $calendarTrigger === 'input';

    $hasValue = is_array($value) ? count($value) > 0 : ($value !== null && $value !== '');
    // canClear()
    $canClear = $hasValue && ! $inputDisabled && ! $readOnly;
@endphp

<div {{ $attributes->class(['tedi-date-field']) }}>
    <tedi:date-input
        :input-id="$inputId"
        :value="$display"
        :tags="$tags"
        :mode="$mode"
        :multi-row="(bool) $multiRow"
        :ellipsis="$tagEllipsis"
        :removable="(bool) $isTagRemovable"
        :visible-tag-count="$visibleTagCount"
        :placeholder="$placeholder"
        :disabled="(bool) $inputDisabled"
        :read-only="$readOnly || $inputIsTrigger"
        :required="(bool) $required"
        :icon-active="(bool) $open"
        :icon-disabled="! $showCalendar"
        :clearable="$canClear"
    />

    @if ($open && $showCalendar)
        <div class="tedi-date-field__overlay" role="dialog" aria-label="{{ __('tedi::tedi.date-field.calendar-dialog') }}">
            <tedi:calendar
                :bordered="false"
                :value="$value"
                :current-month="$currentMonth"
                :mode="$mode"
                :selection-level="$selectionLevel"
                :show-outside-days="(bool) $showOutsideDays"
                :show-week-numbers="(bool) $showWeekNumbers"
                :number-of-months="$numberOfMonths"
                :month-year-select-type="$monthYearSelectType"
                :required="(bool) $required"
                :input-disabled="(bool) $inputDisabled"
                :disabled-days="$disabledDays"
                :first-day-of-week="$firstDayOfWeek"
            >{{-- one line: .tedi-calendar__footer relies on :empty --}}<x-slot:footer>{{ $footer ?? '' }}</x-slot:footer>
            </tedi:calendar>
        </div>
    @endif
</div>
