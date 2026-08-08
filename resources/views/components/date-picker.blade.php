{{--
    TEDI Date picker — DOCUMENTED SUBSET.
    Port of angular/tedi/components/form/date-picker/{date-picker.component.ts,date-picker.component.html}

    Root element. The Angular selector is the element `tedi-date-picker`, and the
    vendored SCSS styles it as an ELEMENT selector
    (resources/scss/components/form/date-picker/date-picker.component.scss:1) — it
    carries the field border, background and every `:has()` state rule. There is no
    matching CLASS rule (a boundary-anchored grep of dist/tedi.css returns 0), so the
    root is the literal custom element and carries NO block class at all
    (CONVENTIONS.md §4, "element selectors in the vendored SCSS"). The element rule
    sets `display: flex` itself, so the swap needs no fallback.

    {{ $attributes }} placement. This is a composite wrapping a single native
    control, so the attribute bag goes on the <input> and the wrapper stays static —
    CONVENTIONS.md §6's native-control carve-out, the same resolution
    select.blade.php takes. That is what makes `wire:model` land on the real input.

    Divergences from Angular:

    * The <tedi-popover> / <tedi-popover-content> wrapper around the calendar is
      DROPPED — this package has no popover component and CDK Overlay positioning is
      out of scope (CONVENTIONS.md §7 item 3). The toggle button is kept and the
      calendar panel renders INLINE under `@if($open)`. The vendored
      `tedi-date-picker__calendar` rule is `display:block; width:fit-content` plus a
      background and radius — it carries no positioning of its own, so rendering it
      inline is faithful to its own styling; only the overlay placement is missing.
    * `open` and `currentView` are Angular runtime signals surfaced as explicit props
      (CONVENTIONS.md §5), since Blade renders once on the server.
    * `yearPageIndex` is likewise the Angular signal surfaced as a prop; it selects
      which 12-year page the year grid shows.
    * `disabled` (deprecated in Angular in favour of `disabledMatchers`) and
      `disabledMatchers` are dropped: both accept `Date`, ranges, or a
      `(date) => boolean` predicate, none of which has a server-side analogue. They
      are replaced by the flat `disabledDays` array of `Y-m-d` strings.
    * `closeOnSelect` is dropped — pure runtime behaviour with no markup effect.
    * `value` on the <input> is emitted only when `selected` is non-empty. Angular
      always writes the (possibly empty) formatted value; emitting `value=""` here
      would blank a `wire:model`-bound field on the initial server render.
    * The formatted input value is Angular's `formatDate()`, i.e.
      `Intl.DateTimeFormat("et-EE", {2-digit day/month, numeric year})` → `d.m.Y`.
      Reproduced with plain `date()` so the package does not depend on ext-intl,
      which composer.json does not declare.
    * `output()` events (`selected`/`month` two-way binding, day selection, calendar
      open/close) are not re-emitted — consumers bind Livewire/Alpine listeners
      through the attribute bag (CONVENTIONS.md §7 item 2).
--}}
@props([
    /** Selected date (any strtotime-parsable string or timestamp). */
    'selected' => null,
    /** Currently shown month. Defaults to today. */
    'month' => null,
    /** Shows or hides the calendar navigation controls (previous/next month buttons). */
    'showNavigation' => true,
    /** Month selector mode: none|label|grid|dropdown. Only the trigger renders — the dropdown panel is out of scope. */
    'monthMode' => 'dropdown',
    /** Year selector mode: none|label|grid|dropdown. Only the trigger renders — the dropdown panel is out of scope. */
    'yearMode' => 'dropdown',
    /** Explicit starting year for the year list. Null uses `current year - 100`. */
    'startYear' => null,
    /** Explicit ending year for the year list. Null uses `current year + 20`. */
    'endYear' => null,
    /** Id for the <input>. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Input placeholder. */
    'inputPlaceholder' => null,
    /** default|error|valid */
    'inputState' => 'default',
    /** default|small */
    'inputSize' => 'default',
    /** Is the input disabled? */
    'inputDisabled' => false,
    /** Is manual typing into the input allowed? */
    'allowManualInput' => true,
    /** Show ISO week numbers before the calendar grid. */
    'showWeekNumbers' => false,
    /** month-grid|year-grid|calendar-grid — which view the open calendar shows. */
    'currentView' => 'calendar-grid',
    /** Whether the calendar panel is open. Angular's `popover().isOpen()` as an explicit prop. */
    'open' => false,
    /** Dates that cannot be selected, as `Y-m-d` strings. Replaces Angular's matcher objects. */
    'disabledDays' => [],
    /** Which 12-year page the year grid shows. Angular's `yearPageIndex` signal as an explicit prop. */
    'yearPageIndex' => 0,
])

@php
    // Unconditional: `aria-controls` on the input and the grid/header ids all
    // reference it on every render path, so it must not depend on a branch being
    // taken (precedent: select.blade.php).
    $uniqueId = \Tedi\Livewire\Tedi::id('tedi-date-picker-id-');
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-date-picker-');

    $selectedTs = $selected ? (is_numeric($selected) ? (int) $selected : strtotime($selected)) : null;
    $selectedTs = $selectedTs ?: null;

    $monthTs = $month ? (is_numeric($month) ? (int) $month : strtotime($month)) : time();
    $monthTs = $monthTs ?: time();

    // years() / pagedYears() / hasPrevYearPage() / hasNextYearPage()
    $currentYear = (int) date('Y');
    $rangeStart = $startYear ?? $currentYear - 100;
    $rangeEnd = $endYear ?? $currentYear + 20;
    $yearsPerPage = 12;
    $pageStart = min($rangeStart, $rangeEnd) + ((int) $yearPageIndex * $yearsPerPage);
    $yearCount = abs($rangeEnd - $rangeStart) + 1;

    $selectedYear = (int) date('Y', $monthTs);

    $inputValue = $selectedTs ? date('d.m.Y', $selectedTs) : null;
@endphp

<tedi-date-picker>
    <input
        @readonly(! $allowManualInput)
        @disabled($inputDisabled)
        {{ $attributes->class([
            'tedi-date-picker__input',
            'tedi-date-picker__input--small' => $inputSize === 'small',
            'tedi-date-picker__input--valid' => $inputState === 'valid',
            'tedi-date-picker__input--error' => $inputState === 'error',
        ])->merge(array_filter([
            'type' => 'text',
            'role' => 'combobox',
            'aria-autocomplete' => 'none',
            'aria-haspopup' => 'dialog',
            'id' => $inputId,
            'placeholder' => $inputPlaceholder,
            // Strings, not booleans: array_filter() would drop a PHP false and the
            // attribute would vanish, where Angular always renders it.
            'aria-expanded' => ! $inputDisabled && $open ? 'true' : 'false',
            'aria-controls' => $uniqueId,
            'aria-readonly' => $allowManualInput ? 'false' : 'true',
            'value' => $inputValue,
        ])) }}
    />

    <div class="tedi-date-picker__input-buttons">
        @if ($selectedTs)
            <tedi:closing-button
                size="small"
                class="tedi-date-picker__clear"
                :icon-size="18"
                :aria-label="__('tedi::tedi.date-picker.clear-date')"
                :disabled="$inputDisabled"
            />
            <tedi:separator axis="vertical" size="1rem" />
        @endif

        <tedi:button
            variant="neutral"
            size="small"
            class="tedi-date-picker__toggle"
            icon-start="calendar_today"
            :icon-only="true"
            :aria-label="__('tedi::tedi.date-picker.open-calendar')"
            :disabled="$inputDisabled"
        />

        @if ($open)
            <div class="tedi-date-picker__calendar">
                <tedi:date-picker-header
                    :unique-id="$uniqueId"
                    :current-view="$currentView"
                    :month="$monthTs"
                    :month-mode="$monthMode"
                    :year-mode="$yearMode"
                    :show-navigation="$showNavigation"
                    :selected-year="$selectedYear"
                    :has-prev-year-page="$yearPageIndex > 0"
                    :has-next-year-page="((int) $yearPageIndex + 1) * $yearsPerPage < $yearCount"
                />

                @if ($currentView === 'calendar-grid')
                    <tedi:date-picker-calendar-grid
                        :grid-id="$uniqueId"
                        :month="$monthTs"
                        :show-week-numbers="$showWeekNumbers"
                        :active-date="$selectedTs"
                        :selected="$selectedTs"
                        :disabled-days="$disabledDays"
                    />
                @elseif ($currentView === 'month-grid')
                    <tedi:date-picker-month-grid :current-month="$monthTs" />
                @elseif ($currentView === 'year-grid')
                    <tedi:date-picker-year-grid
                        :page-start="$pageStart"
                        :page-size="$yearsPerPage"
                        :selected-year="$selectedYear"
                    />
                @endif
            </div>
        @endif
    </div>
</tedi-date-picker>
