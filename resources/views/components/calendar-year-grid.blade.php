{{--
    TEDI Calendar year grid.
    Port of angular/tedi/components/content/calendar/calendar-year-grid/{calendar-year-grid.component.ts,calendar-year-grid.component.html}

    Root element: the Angular template's own <div class="tedi-calendar-year-grid">.
    `tedi-calendar-year-grid` has no ELEMENT selector in the vendored SCSS, so no
    custom element is rendered. `{{ $attributes }}` sits on that single root.

    Divergences:
    * `isYearDisabled` is a JS predicate with no server-side analogue; it becomes
      the explicit `disabledYears` list (CONVENTIONS.md §5).
    * `selectedYear` is narrowed from `Date | null` to an integer year — the
      Angular component only ever compares `isSameYear`.
    * `yearSelect` is not re-emitted (CONVENTIONS.md §7 item 2).

    Nothing is dropped from the class list — every class this component emits
    exists in dist/tedi.css.
--}}
@props([
    /** First year of the page. null → the current year minus 5. */
    'pageStart' => null,
    /** How many years the page holds. */
    'pageSize' => 12,
    /** Selected year. */
    'selectedYear' => null,
    /** Years that cannot be selected. */
    'disabledYears' => [],
    /** Disables every year. */
    'inputDisabled' => false,
])

@php
    $pageStart = $pageStart !== null && $pageStart !== '' ? (int) $pageStart : (int) date('Y') - 5;
    $pageSize = max(1, (int) $pageSize);
    $selectedYear = $selectedYear !== null && $selectedYear !== '' ? (int) $selectedYear : null;
    $disabledYears = array_map('intval', is_array($disabledYears) ? $disabledYears : []);
    $currentYear = (int) date('Y');

    $rows = array_chunk(range($pageStart, $pageStart + $pageSize - 1), 3);
@endphp

<div {{ $attributes->class(['tedi-calendar-year-grid'])->merge([
    'role' => 'grid',
    'aria-label' => __('tedi::tedi.date-picker.choose-year'),
]) }}>
    @foreach ($rows as $row)
        <div class="tedi-calendar-year-grid__row" role="row">
            @foreach ($row as $year)
                @php
                    $selected = $selectedYear !== null && $selectedYear === $year;
                    $current = $year === $currentYear;
                    $disabled = $inputDisabled || in_array($year, $disabledYears, true);
                @endphp
                <div class="tedi-calendar-year-grid__cell" role="gridcell">
                    <button
                        type="button"
                        class="{{ implode(' ', array_filter([
                            'tedi-calendar-year-grid__year',
                            $selected ? 'tedi-calendar-year-grid__year--selected' : null,
                            $current ? 'tedi-calendar-year-grid__year--current' : null,
                            $disabled ? 'tedi-calendar-year-grid__year--disabled' : null,
                        ])) }}"
                        @if ($selected) aria-selected="true" @endif
                        @if ($disabled) aria-disabled="true" @endif
                    >{{ $year }}</button>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
