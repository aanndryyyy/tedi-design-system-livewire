{{--
    TEDI Calendar month grid.
    Port of angular/tedi/components/content/calendar/calendar-month-grid/{calendar-month-grid.component.ts,calendar-month-grid.component.html}

    Root element: the Angular template's own <div class="tedi-calendar-month-grid">.
    `tedi-calendar-month-grid` has no ELEMENT selector in the vendored SCSS, so no
    custom element is rendered. `{{ $attributes }}` sits on that single root.

    Divergences:
    * `localeCode` is DROPPED — month names come from the existing
      `date-picker.{january…december}` / `-short` translation keys rather than
      `Intl` (`ext-intl` is not a declared dependency of this package).
    * `isMonthDisabled` is a JS predicate with no server-side analogue; it becomes
      the explicit `disabledMonths` list of 0-based month indexes
      (CONVENTIONS.md §5).
    * `monthSelect` is not re-emitted (CONVENTIONS.md §7 item 2).

    Nothing is dropped from the class list — every class this component emits
    exists in dist/tedi.css.
--}}
@props([
    /** Year whose 12 months are rendered. null → the current year. */
    'year' => null,
    /** Selected month; any strtotime()-able value ('2026-05', '2026-05-16', …). */
    'selectedMonth' => null,
    /** long|short — the visible button label. The aria-label is always the long name. */
    'monthNameFormat' => 'short',
    /** 0-based month indexes that cannot be selected. */
    'disabledMonths' => [],
    /** Disables every month. */
    'inputDisabled' => false,
])

@php
    $year = $year !== null && $year !== '' ? (int) $year : (int) date('Y');

    $monthKeys = [
        'january', 'february', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december',
    ];

    $selectedTs = null;
    if ($selectedMonth instanceof \DateTimeInterface) {
        $selectedTs = $selectedMonth->getTimestamp();
    } elseif (is_int($selectedMonth)) {
        $selectedTs = $selectedMonth;
    } elseif (is_string($selectedMonth) && $selectedMonth !== '') {
        $selectedTs = strtotime($selectedMonth) ?: null;
    }

    $selectedKey = $selectedTs !== null ? date('Y-n', $selectedTs) : null;
    $todayKey = date('Y-n');

    $disabledMonths = array_map('intval', is_array($disabledMonths) ? $disabledMonths : []);

    $rows = array_chunk(range(0, 11), 3);
@endphp

<div {{ $attributes->class(['tedi-calendar-month-grid'])->merge([
    'role' => 'grid',
    'aria-label' => __('tedi::tedi.date-picker.choose-month'),
]) }}>
    @foreach ($rows as $row)
        <div class="tedi-calendar-month-grid__row" role="row">
            @foreach ($row as $index)
                @php
                    $monthKey = $year.'-'.($index + 1);
                    $selected = $selectedKey !== null && $selectedKey === $monthKey;
                    $current = $todayKey === $monthKey;
                    $disabled = $inputDisabled || in_array($index, $disabledMonths, true);
                    $longName = __('tedi::tedi.date-picker.'.$monthKeys[$index]);
                @endphp
                <div class="tedi-calendar-month-grid__cell" role="gridcell">
                    <button
                        type="button"
                        class="{{ implode(' ', array_filter([
                            'tedi-calendar-month-grid__month',
                            $selected ? 'tedi-calendar-month-grid__month--selected' : null,
                            $current ? 'tedi-calendar-month-grid__month--current' : null,
                            $disabled ? 'tedi-calendar-month-grid__month--disabled' : null,
                        ])) }}"
                        aria-label="{{ $longName }}"
                        @if ($selected) aria-selected="true" @endif
                        @if ($disabled) aria-disabled="true" @endif
                    >{{ $monthNameFormat === 'short' ? __('tedi::tedi.date-picker.'.$monthKeys[$index].'-short') : $longName }}</button>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
