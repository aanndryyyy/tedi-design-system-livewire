{{--
    TEDI Calendar day grid.
    Port of angular/tedi/components/content/calendar/calendar-day-grid/{calendar-day-grid.component.ts,calendar-day-grid.component.html}

    Root element: the Angular template's own <table class="tedi-calendar-day-grid">.
    The vendored SCSS has no `tedi-calendar-day-grid` ELEMENT selector (only the
    class), so no custom element wrapper is rendered — CONVENTIONS.md §4's
    element-selector ruling does not apply here.
    `{{ $attributes }}` sits on that single root <table>.

    Divergences from the Angular source, all deliberate:

    * `localeCode` is DROPPED. Angular reads month/weekday names from `Intl`
      (`getWeekdayNames(localeCode, …)`); `ext-intl` is not a declared dependency
      of this package, so names come from the `date-picker.*` translation keys
      that already ship in `lang/en` and `lang/et`. `firstDayOfWeek` — which
      Angular derived from the locale — is therefore an explicit numeric prop.
    * There is no "narrow" (one-letter) weekday translation key, so Angular's
      `getWeekdayNames(…, "narrow", …)` column headers map to the `-short` keys
      ("Mon", "Tue", …) instead of single letters.
    * The long `aria-label` date is built as "<weekday>, <d>. <month> <Y>" from
      the same keys rather than `Intl`-formatted (`formatLocaleDateLong`).
    * `disabledMatchers` (matcher objects / predicates) and the callable forms of
      `availableDays` / `unavailableDays` / `dayStatus` have no server-side
      analogue — they become flat `Y-m-d` arrays and a `Y-m-d`-keyed map.
    * Angular distinguishes `availableDays === undefined` from `[]` (an
      explicitly-empty whitelist disables every day). Blade cannot tell the two
      apart, so an empty array means "no whitelist" and disables nothing.
    * `daySelect` / `hoveredDate` two-way output are not re-emitted
      (CONVENTIONS.md §7 item 2). `hoveredDate` stays an INPUT prop so the
      range-preview modifiers remain reachable from a story or matrix.

    Nothing is dropped from the class list: all 12 `__day--*` modifiers exist in
    dist/tedi.css.
--}}
@props([
    /** The month to render. Any strtotime()-able value; null → the current month. */
    'month' => null,
    /** single|multiple|range */
    'mode' => 'single',
    /**
     * Selected value; shape follows `mode`:
     *   single   → 'Y-m-d'
     *   multiple → ['Y-m-d', …]
     *   range    → ['from' => 'Y-m-d', 'to' => 'Y-m-d'|null]
     */
    'value' => null,
    /** First column of the grid: 0 = Sunday … 6 = Saturday. */
    'firstDayOfWeek' => 1,
    /** Render the leading/trailing days of the adjacent months. */
    'showOutsideDays' => true,
    /** Render the ISO week-number column. */
    'showWeekNumbers' => false,
    /** Disables every day. */
    'inputDisabled' => false,
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
    $toKey = function ($value) {
        if ($value === null || $value === '' || is_bool($value)) {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        if (is_int($value)) {
            return date('Y-m-d', $value);
        }
        $ts = strtotime((string) $value);

        return $ts === false ? null : date('Y-m-d', $ts);
    };

    $keyList = function ($values) use ($toKey) {
        return array_values(array_filter(array_map($toKey, is_array($values) ? $values : [])));
    };

    // Defensive resolution: every required Angular input needs a working default
    // so the component renders bare (IntegrityTest).
    $monthTs = $month ? (strtotime((string) $toKey($month)) ?: time()) : time();
    $monthNumber = (int) date('n', $monthTs);
    $monthYear = (int) date('Y', $monthTs);
    $firstDayOfWeek = ((int) $firstDayOfWeek % 7 + 7) % 7;

    $monthKeys = [
        1 => 'january', 2 => 'february', 3 => 'march', 4 => 'april',
        5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august',
        9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december',
    ];
    // date('w'): 0 = Sunday … 6 = Saturday.
    $weekdayKeys = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    // Both header arrays are rotated by the SAME offset so the short label and
    // the long aria-label always describe the same weekday.
    $columnKeys = [];
    for ($i = 0; $i < 7; $i++) {
        $columnKeys[] = $weekdayKeys[($firstDayOfWeek + $i) % 7];
    }

    // buildMonthGrid() — date.util.ts:457-478. Always 6 rows x 7 columns.
    $monthStart = mktime(0, 0, 0, $monthNumber, 1, $monthYear);
    $offset = ((int) date('w', $monthStart) - $firstDayOfWeek + 7) % 7;
    $gridStart = strtotime("-{$offset} days", $monthStart);

    $grid = [];
    for ($row = 0; $row < 6; $row++) {
        $cells = [];
        for ($col = 0; $col < 7; $col++) {
            $cell = strtotime('+'.($row * 7 + $col).' days', $gridStart);
            $inMonth = (int) date('n', $cell) === $monthNumber && (int) date('Y', $cell) === $monthYear;
            $cells[] = ($inMonth || $showOutsideDays) ? $cell : null;
        }
        $grid[] = $cells;
    }

    $todayKey = date('Y-m-d');

    $disabledKeys = $keyList($disabledDays);
    $availableKeys = $keyList($availableDays);
    $unavailableKeys = $keyList($unavailableDays);
    $hasAvailable = count($availableKeys) > 0;
    $hasUnavailable = count($unavailableKeys) > 0;

    $statusMap = [];
    foreach (is_array($dayStatus) ? $dayStatus : [] as $statusDay => $status) {
        $statusKey = $toKey($statusDay);
        if ($statusKey !== null && is_array($status)) {
            $statusMap[$statusKey] = $status;
        }
    }

    // isSelected() — shape follows the mode.
    $selectedKeys = [];
    $rangeFrom = null;
    $rangeTo = null;
    if ($mode === 'single') {
        $selectedKeys = $keyList([$value]);
    } elseif ($mode === 'multiple') {
        $selectedKeys = $keyList($value);
    } elseif ($mode === 'range' && is_array($value)) {
        $rangeFrom = $toKey($value['from'] ?? null);
        $rangeTo = $toKey($value['to'] ?? null);
        $selectedKeys = array_values(array_filter([$rangeFrom, $rangeTo]));
    }

    $hoveredKey = $toKey($hoveredDate);

    $isDisabled = function (string $key) use ($inputDisabled, $disabledKeys, $hasAvailable, $availableKeys, $hasUnavailable, $unavailableKeys) {
        if ($inputDisabled) {
            return true;
        }
        if (in_array($key, $disabledKeys, true)) {
            return true;
        }
        if ($hasAvailable && ! in_array($key, $availableKeys, true)) {
            return true;
        }

        return $hasUnavailable && in_array($key, $unavailableKeys, true);
    };

    // collectRangeModifiers() — calendar-day-grid.component.ts:170-211.
    $rangeModifiers = function (string $key) use ($mode, $rangeFrom, $rangeTo, $hoveredKey) {
        if ($mode !== 'range' || $rangeFrom === null) {
            return [];
        }

        if ($rangeTo !== null) {
            if ($rangeFrom === $rangeTo) {
                return [];
            }
            $modifiers = [];
            if ($key === $rangeFrom) {
                $modifiers[] = 'range-start';
            }
            if ($key === $rangeTo) {
                $modifiers[] = 'range-end';
            }
            [$start, $end] = $rangeFrom <= $rangeTo ? [$rangeFrom, $rangeTo] : [$rangeTo, $rangeFrom];
            if ($key > $start && $key < $end) {
                $modifiers[] = 'range-middle';
            }

            return $modifiers;
        }

        if ($hoveredKey === null || $hoveredKey === $rangeFrom) {
            return [];
        }

        $hoverIsAfter = $hoveredKey > $rangeFrom;

        if ($key === $rangeFrom) {
            return [$hoverIsAfter ? 'range-start' : 'range-end'];
        }
        if ($key === $hoveredKey) {
            return [$hoverIsAfter ? 'range-preview-end' : 'range-preview-start'];
        }

        [$start, $end] = $hoverIsAfter ? [$rangeFrom, $hoveredKey] : [$hoveredKey, $rangeFrom];

        return ($key > $start && $key < $end) ? ['range-preview-middle'] : [];
    };

    // focusableKey() — today when it is in this month and selectable, else the
    // first selectable in-month day; everything else gets tabindex="-1".
    $inMonthKeys = [];
    foreach ($grid as $rowCells) {
        foreach ($rowCells as $cell) {
            if ($cell !== null && (int) date('n', $cell) === $monthNumber && (int) date('Y', $cell) === $monthYear) {
                $inMonthKeys[] = date('Y-m-d', $cell);
            }
        }
    }

    $focusableKey = null;
    if (in_array($todayKey, $inMonthKeys, true) && ! $isDisabled($todayKey)) {
        $focusableKey = $todayKey;
    } else {
        foreach ($inMonthKeys as $candidate) {
            if (! $isDisabled($candidate)) {
                $focusableKey = $candidate;
                break;
            }
        }
    }

    $gridAriaLabel = __('tedi::tedi.date-picker.'.$monthKeys[$monthNumber]).' '.$monthYear;
@endphp

<table {{ $attributes->class([
    'tedi-calendar-day-grid',
    'tedi-calendar-day-grid--with-week-numbers' => (bool) $showWeekNumbers,
])->merge(array_filter([
    'role' => 'grid',
    'aria-label' => $gridAriaLabel,
    'aria-multiselectable' => in_array($mode, ['multiple', 'range'], true) ? 'true' : null,
])) }}>
    <thead>
        <tr class="tedi-calendar-day-grid__header" role="row">
            @if ($showWeekNumbers)
                <th class="tedi-calendar-day-grid__week-number-header" scope="col">
                    <span class="sr-only">{{ __('tedi::tedi.date-picker.week-number-header') }}</span>
                </th>
            @endif
            @foreach ($columnKeys as $columnKey)
                <th
                    class="tedi-calendar-day-grid__weekday"
                    role="columnheader"
                    scope="col"
                    aria-label="{{ __('tedi::tedi.date-picker.'.$columnKey) }}"
                >{{ __('tedi::tedi.date-picker.'.$columnKey.'-short') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($grid as $rowCells)
            @php
                $reference = null;
                foreach ($rowCells as $cell) {
                    if ($cell !== null) {
                        $reference = $cell;
                        break;
                    }
                }
                // date('W') IS getISOWeek() — "05" comes back zero-padded, Angular
                // renders the bare number, so cast.
                $weekNumber = $reference !== null ? (int) date('W', $reference) : null;
            @endphp
            <tr class="tedi-calendar-day-grid__row" role="row">
                @if ($showWeekNumbers)
                    <th
                        class="tedi-calendar-day-grid__week-number"
                        role="rowheader"
                        scope="row"
                        @if ($weekNumber !== null)
                            {{-- Parameterised key: the .false variant carries the literal
                                 placeholder word, substituted as in pagination.blade.php. --}}
                            aria-label="{{ str_replace('false', (string) $weekNumber, __('tedi::tedi.date-picker.week-number.false')) }}"
                        @endif
                    >{{ $weekNumber }}</th>
                @endif

                @foreach ($rowCells as $cell)
                    @if ($cell === null)
                        <td class="tedi-calendar-day-grid__cell" role="gridcell"></td>
                    @else
                        @php
                            $dayKey = date('Y-m-d', $cell);
                            $inMonth = (int) date('n', $cell) === $monthNumber && (int) date('Y', $cell) === $monthYear;
                            $selected = in_array($dayKey, $selectedKeys, true);
                            $disabled = $isDisabled($dayKey);

                            // cellState() — collection order is load-bearing:
                            // base, range, availability, then disabled last.
                            $modifiers = [];
                            if (! $inMonth) {
                                $modifiers[] = 'outside';
                            }
                            if ($dayKey === $todayKey) {
                                $modifiers[] = 'today';
                            }
                            if ($selected) {
                                $modifiers[] = 'selected';
                            }
                            $modifiers = array_merge($modifiers, $rangeModifiers($dayKey));
                            if ($hasAvailable && in_array($dayKey, $availableKeys, true)) {
                                $modifiers[] = 'available-day';
                            }
                            if ($hasUnavailable && in_array($dayKey, $unavailableKeys, true)) {
                                $modifiers[] = 'unavailable-day';
                            }
                            if ($disabled) {
                                $modifiers[] = 'disabled';
                            }

                            $cellClasses = array_merge(
                                ['tedi-calendar-day-grid__day'],
                                array_map(fn ($modifier) => 'tedi-calendar-day-grid__day--'.$modifier, $modifiers)
                            );

                            $status = $statusMap[$dayKey] ?? null;

                            // ariaLabelForDay() — "Today, <weekday>, <d>. <month> <Y>, <status>".
                            $labelParts = [];
                            if ($dayKey === $todayKey) {
                                $labelParts[] = __('tedi::tedi.date-picker.today');
                            }
                            $labelParts[] = __('tedi::tedi.date-picker.'.$weekdayKeys[(int) date('w', $cell)])
                                .', '.(int) date('j', $cell)
                                .'. '.__('tedi::tedi.date-picker.'.$monthKeys[(int) date('n', $cell)])
                                .' '.(int) date('Y', $cell);
                            if (! empty($status['label'])) {
                                $labelParts[] = $status['label'];
                            }
                        @endphp
                        <td class="tedi-calendar-day-grid__cell" role="presentation">
                            {{-- Angular binds [class]="cellState(day)", REPLACING the class
                                 attribute rather than merging; $attributes lives on the
                                 <table>, so a plain class="" here cannot drop consumer
                                 classes (CONVENTIONS.md §4). --}}
                            <button
                                type="button"
                                role="gridcell"
                                class="{{ implode(' ', $cellClasses) }}"
                                @if ($selected) aria-selected="true" @endif
                                @if ($disabled) aria-disabled="true" @endif
                                aria-label="{{ implode(', ', $labelParts) }}"
                                tabindex="{{ $dayKey === $focusableKey ? 0 : -1 }}"
                                data-date-key="{{ $cell * 1000 }}"
                            >{{ (int) date('j', $cell) }}@if ($status)<tedi:status-indicator
                                    class="tedi-calendar-day-grid__status"
                                    :type="$status['type'] ?? 'success'"
                                    size="sm"
                                    :has-border="true"
                                />@endif</button>
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
