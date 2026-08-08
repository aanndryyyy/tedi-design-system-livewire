{{--
    TEDI Calendar — DOCUMENTED SUBSET.
    Port of angular/tedi/components/content/calendar/{calendar.component.ts,calendar.component.html}

    Root element: a <div> carrying the host classes. `tedi-calendar` is NOT one of
    the tags the vendored SCSS targets with an ELEMENT selector (verified by
    grepping the whole resources/scss tree — `tedi-date-picker` is the only hit in
    this family), so CONVENTIONS.md §4's custom-element ruling does not apply and
    the precedent is radio-card-group.blade.php. `{{ $attributes }}` sits on that
    single root.

    SUBSET: the default `monthYearSelectType="dropdown"` renders the header
    trigger only — see calendar-header.blade.php for why the dropdown panel is
    dropped (no dropdown component in this package; CONVENTIONS.md §7 item 3).

    Class dropped because dist/tedi.css ships no rule for it (CONVENTIONS.md §4
    "classes Angular emits but TEDI never styles"; restore on a re-sync if TEDI
    ever adds the rule):
      * `tedi-calendar--multi-month` (Angular emits it when numberOfMonths > 1)

    Further divergences:
    * `localeCode` is DROPPED — month/weekday names come from the existing
      `date-picker.*` translation keys, not `Intl`, because `ext-intl` is not a
      declared dependency of this package. `firstDayOfWeek`, which Angular
      derived from the locale, is an explicit numeric prop instead.
    * `disabledMatchers`, `shouldDisableMonth` and `shouldDisableYear` are
      callable/object matchers with no server-side analogue — they are replaced
      by the flat `disabledDays` array (CONVENTIONS.md §5). `minYear` / `maxYear`
      only fed the unported year dropdown and the prev/next disabled computation
      that itself depends on the matchers, so they are dropped too.
    * Angular distinguishes `availableDays === undefined` from `[]` (an
      explicitly-empty whitelist disables every day). Blade cannot tell the two
      apart, so an empty array means "no whitelist" and disables nothing.
    * `--_tedi-calendar-month-count` IS emitted (the SCSS `width: calc(…)` needs
      it). `--_tedi-calendar-fit-columns` is JS-measured and dropped; the vendored
      SCSS authorises that itself — "The fallback to --_tedi-calendar-month-count
      keeps it correct before/without the measurement (e.g. SSR)".
    * The `select` output, `ControlValueAccessor` and the breakpoint form of
      `numberOfMonths` are not ported (CONVENTIONS.md §7 items 1, 2). Bind
      `wire:click` on the rendered day buttons instead.
    * `selectionLevel` and `required` are declared for API parity but are
      behavioural only — they change nothing in the rendered markup, and are
      declared (rather than omitted) so they do not leak into the DOM as stray
      attributes.
--}}
@props([
    /** days|months|years — which view renders. */
    'view' => 'days',
    /** First (left-most) month shown. Any strtotime()-able value; null → the current month. */
    'currentMonth' => null,
    /**
     * Selected value; shape follows `mode`:
     *   single   → 'Y-m-d'
     *   multiple → ['Y-m-d', …]
     *   range    → ['from' => 'Y-m-d', 'to' => 'Y-m-d'|null]
     */
    'value' => null,
    /** single|multiple|range */
    'mode' => 'single',
    /** days|months|years — lowest level the user can commit to. Behavioural only. */
    'selectionLevel' => 'days',
    /** Render the leading/trailing days of the adjacent months. */
    'showOutsideDays' => true,
    /** Render the ISO week-number column. */
    'showWeekNumbers' => false,
    /** Show the previous/next chevrons in the header. */
    'showNavigation' => true,
    /** Render the outer border and rounded corners. */
    'bordered' => true,
    /** dropdown|grid|static — how the header exposes month/year picking. */
    'monthYearSelectType' => 'dropdown',
    /** When mode='multiple', prevents clearing the last date. Behavioural only. */
    'required' => false,
    /** How many consecutive months render side by side. */
    'numberOfMonths' => 1,
    /** Disables all interactions. */
    'inputDisabled' => false,
    /** First column of the day grid: 0 = Sunday … 6 = Saturday. */
    'firstDayOfWeek' => 1,
    /** First year of the year page. null → year(currentMonth) - 5. */
    'yearPageStart' => null,
    /** Days that cannot be selected, as 'Y-m-d' strings. */
    'disabledDays' => [],
    /** Whitelist of selectable days, as 'Y-m-d' strings. Empty = no whitelist. */
    'availableDays' => [],
    /** Blacklist of unavailable days, as 'Y-m-d' strings. */
    'unavailableDays' => [],
    /** ['Y-m-d' => ['type' => 'success|danger|warning|inactive', 'label' => '…']] */
    'dayStatus' => [],
    /** Hovered day driving the range preview modifiers, as 'Y-m-d'. */
    'hoveredDate' => null,
])

@php
    $monthTs = time();
    if ($currentMonth instanceof \DateTimeInterface) {
        $monthTs = $currentMonth->getTimestamp();
    } elseif (is_int($currentMonth)) {
        $monthTs = $currentMonth;
    } elseif (is_string($currentMonth) && $currentMonth !== '') {
        $monthTs = strtotime($currentMonth) ?: time();
    }

    $baseMonth = (int) date('n', $monthTs);
    $baseYear = (int) date('Y', $monthTs);
    $numberOfMonths = max(1, (int) $numberOfMonths);

    // monthDates() — addMonths(currentMonth, i), normalised to the 1st.
    $monthDates = [];
    for ($i = 0; $i < $numberOfMonths; $i++) {
        $monthDates[] = date('Y-m-d', mktime(0, 0, 0, $baseMonth + $i, 1, $baseYear));
    }

    // selectedSingle() — only `single` mode feeds the month/year grids.
    $selectedSingle = $mode === 'single' && is_string($value) && $value !== ''
        ? (strtotime($value) ?: null)
        : null;

    $resolvedYearPageStart = $yearPageStart !== null && $yearPageStart !== ''
        ? (int) $yearPageStart
        : $baseYear - 5;
@endphp

<div {{ $attributes->class([
    'tedi-calendar',
    'tedi-calendar--disabled' => (bool) $inputDisabled,
    'tedi-calendar--with-week-numbers' => (bool) $showWeekNumbers,
    'tedi-calendar--bordered' => (bool) $bordered,
])->style([
    '--_tedi-calendar-month-count: '.$numberOfMonths,
]) }}>
    @if ($view === 'days')
        <div class="tedi-calendar__months">
            @foreach ($monthDates as $monthDate)
                <div class="tedi-calendar__month">
                    <tedi:calendar-header
                        :current-month="$monthDate"
                        view="days"
                        :show-navigation="$showNavigation"
                        :month-year-select-type="$monthYearSelectType"
                        :year-page-start="$resolvedYearPageStart"
                        :number-of-months="$numberOfMonths"
                        :input-disabled="$inputDisabled"
                    />
                    <tedi:calendar-day-grid
                        :month="$monthDate"
                        :mode="$mode"
                        :value="$value"
                        :first-day-of-week="$firstDayOfWeek"
                        :show-outside-days="$showOutsideDays"
                        :show-week-numbers="$showWeekNumbers"
                        :input-disabled="$inputDisabled"
                        :disabled-days="$disabledDays"
                        :available-days="$availableDays"
                        :unavailable-days="$unavailableDays"
                        :day-status="$dayStatus"
                        :hovered-date="$hoveredDate"
                    />
                </div>
            @endforeach
        </div>
    @elseif ($view === 'months')
        <div class="tedi-calendar__month">
            <tedi:calendar-header
                :current-month="$monthDates[0]"
                view="months"
                :show-navigation="$showNavigation"
                :month-year-select-type="$monthYearSelectType"
                :year-page-start="$resolvedYearPageStart"
                :number-of-months="$numberOfMonths"
                :input-disabled="$inputDisabled"
            />
            <tedi:calendar-month-grid
                :year="$baseYear"
                :selected-month="$selectedSingle"
                :input-disabled="$inputDisabled"
            />
        </div>
    @else
        <div class="tedi-calendar__month">
            <tedi:calendar-header
                :current-month="$monthDates[0]"
                view="years"
                :show-navigation="$showNavigation"
                :month-year-select-type="$monthYearSelectType"
                :year-page-start="$resolvedYearPageStart"
                :number-of-months="$numberOfMonths"
                :input-disabled="$inputDisabled"
            />
            <tedi:calendar-year-grid
                :page-start="$resolvedYearPageStart"
                :selected-year="$selectedSingle ? (int) date('Y', $selectedSingle) : null"
                :input-disabled="$inputDisabled"
            />
        </div>
    @endif

    {{-- calendar.component.scss:55 has `&:empty { display: none }`. Blade
         indentation would put whitespace inside the div, `:empty` would stop
         matching, and an empty footer would render a stray top border — so this
         div MUST stay on one line with nothing around the slot. --}}
    <div class="tedi-calendar__footer">{{ $footer ?? '' }}</div>
</div>
