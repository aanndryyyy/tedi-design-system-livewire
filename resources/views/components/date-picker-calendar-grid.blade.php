{{--
    TEDI Date picker calendar grid.
    Port of angular/tedi/components/form/date-picker/date-picker-calendar-grid/{date-picker-calendar-grid.component.ts,date-picker-calendar-grid.component.html}

    Root element. The Angular template has TWO sibling roots (the weekday header row
    and the grid). Blade allows exactly one (CONVENTIONS.md §6), so they are wrapped
    in a bare <div> carrying NO class — a documented divergence. The parent
    `tedi-date-picker__calendar` is `display: block`, so an extra block child changes
    nothing visually. {{ $attributes }} sits on that wrapper.

    `tedi-date-picker-calendar-grid` has no element selector in the vendored SCSS, so
    no custom element is rendered.

    Divergences from Angular:

    * `weekRows` / `weekNumbers` are Angular inputs computed by the parent. Here they
      are computed inside the component from `month`, so the component renders with
      no props at all. The algorithm is date-picker's own (date-picker.component.ts,
      `days()`): Monday is HARD-CODED as the first weekday
      (`(firstOfMonth.getDay() + 6) % 7`), the trailing days pad to a whole number of
      weeks, the row count therefore VARIES between 4 and 6, and outside days are
      ALWAYS rendered. This is a different algorithm from the `calendar` family's
      always-6-row grid — the two intentionally share no code.
    * ISO week numbers are PHP's `date('W')`, which is exactly Angular's
      `getISOWeek()`.
    * `today` is an Angular input but is always `new Date()` at the call site, so it
      is computed here rather than declared as a prop.
    * Per-day `disabled` comes from a flat `disabledDays` array of `Y-m-d` strings,
      replacing Angular's matcher objects and predicates (no server-side analogue).
    * Weekday abbreviations use the existing `date-picker.{monday…sunday}-short`
      translation keys, in Angular's literal Monday-first order.
    * `output()` events (day select, day keydown) are not re-emitted.
--}}
@props([
    /** Id for the grid element, referenced by the header's `aria-controls`. Auto-generated when omitted. */
    'gridId' => null,
    /** Month to render (any strtotime-parsable string or timestamp). Defaults to today. */
    'month' => null,
    /** Show ISO week numbers in a leading column. */
    'showWeekNumbers' => false,
    /** The keyboard-focusable date (`tabindex="0"`). Falls back to `selected`, then today. */
    'activeDate' => null,
    /** Currently selected date. */
    'selected' => null,
    /** Dates that cannot be selected, as `Y-m-d` strings. */
    'disabledDays' => [],
])

@php
    $toTs = function ($value) {
        if ($value === null || $value === '') {
            return null;
        }

        return (is_numeric($value) ? (int) $value : strtotime($value)) ?: null;
    };

    // Double-quoted on purpose: IntegrityTest::test_emitted_classes_exist_in_stylesheet

    // harvests single-quoted 'tedi-*' literals as class names, and this is an id prefix.

    $gridId = $gridId ?? \Tedi\Livewire\Tedi::id('tedi-date-picker-id-');

    $monthTs = $toTs($month) ?? time();
    $selectedTs = $toTs($selected);
    $todayKey = date('Y-m-d');
    $activeKey = date('Y-m-d', $toTs($activeDate) ?? $selectedTs ?? time());
    $selectedKey = $selectedTs ? date('Y-m-d', $selectedTs) : null;

    // days() — date-picker.component.ts:258-310. Monday-hardcoded, variable rows.
    $y = (int) date('Y', $monthTs);
    $m = (int) date('n', $monthTs);
    $firstOfMonth = mktime(0, 0, 0, $m, 1, $y);
    $daysInMonth = (int) date('t', $firstOfMonth);
    $firstWeekday = ((int) date('w', $firstOfMonth) + 6) % 7;
    $trailing = (7 - (($firstWeekday + $daysInMonth) % 7)) % 7;

    $cells = [];

    for ($i = 0; $i < $firstWeekday; $i++) {
        $cells[] = ['ts' => mktime(0, 0, 0, $m, $i - $firstWeekday + 1, $y), 'inCurrentMonth' => false];
    }

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $cells[] = ['ts' => mktime(0, 0, 0, $m, $day, $y), 'inCurrentMonth' => true];
    }

    for ($i = 1; $i <= $trailing; $i++) {
        $cells[] = ['ts' => mktime(0, 0, 0, $m, $daysInMonth + $i, $y), 'inCurrentMonth' => false];
    }

    foreach ($cells as $index => $cell) {
        $cells[$index]['key'] = date('Y-m-d', $cell['ts']);
        $cells[$index]['disabled'] = in_array(date('Y-m-d', $cell['ts']), $disabledDays, true);
    }

    $weekRows = array_chunk($cells, 7);

    $weekdayKeys = [
        'monday-short', 'tuesday-short', 'wednesday-short', 'thursday-short',
        'friday-short', 'saturday-short', 'sunday-short',
    ];
@endphp

<div {{ $attributes }}>
    <div
        class="tedi-date-picker__weekdays{{ $showWeekNumbers ? ' tedi-date-picker__weekdays--numbered' : '' }}"
        role="row"
    >
        @if ($showWeekNumbers)
            <div role="columnheader"></div>
        @endif

        @foreach ($weekdayKeys as $weekdayKey)
            <div class="tedi-date-picker__weekday" role="columnheader">
                {{ __('tedi::tedi.date-picker.'.$weekdayKey) }}
            </div>
        @endforeach
    </div>

    <div class="tedi-date-picker__grid" role="grid" id="{{ $gridId }}" aria-readonly="true">
        @foreach ($weekRows as $week)
            <div
                class="tedi-date-picker__row{{ $showWeekNumbers ? ' tedi-date-picker__row--numbered' : '' }}"
                role="row"
            >
                @if ($showWeekNumbers)
                    <div class="tedi-date-picker__weeknumber" role="gridcell" aria-readonly="true">{{ (int) date('W', $week[0]['ts']) }}</div>
                @endif

                @foreach ($week as $day)
                    @php
                        $isSelected = $selectedKey !== null && $day['key'] === $selectedKey;
                        $isToday = $day['key'] === $todayKey;
                        $dayClasses = ['tedi-date-picker__day'];

                        if (! $day['inCurrentMonth']) {
                            $dayClasses[] = 'tedi-date-picker__day--other-month';
                        }

                        if ($isSelected) {
                            $dayClasses[] = 'tedi-date-picker__day--selected';
                        }
                    @endphp

                    <button
                        type="button"
                        role="gridcell"
                        class="{{ implode(' ', $dayClasses) }}"
                        @disabled($day['disabled'])
                        @if ($isSelected) aria-selected="true" @endif
                        @if ($day['disabled']) aria-disabled="true" @endif
                        @if ($isToday) aria-current="date" @endif
                        tabindex="{{ $day['key'] === $activeKey ? 0 : -1 }}"
                        data-date-key="{{ $day['ts'] * 1000 }}"
                    >
                        @if ($isToday)
                            <div class="tedi-date-picker__today">{{ (int) date('j', $day['ts']) }}</div>
                        @else
                            {{ (int) date('j', $day['ts']) }}
                        @endif
                    </button>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
