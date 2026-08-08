{{--
    TEDI Date picker year grid.
    Port of angular/tedi/components/form/date-picker/date-picker-year-grid/{date-picker-year-grid.component.ts,date-picker-year-grid.component.html}

    Root element is the Angular template's own root,
    <div class="tedi-date-picker__month-year-grid" role="group">. There is no
    element selector for `tedi-date-picker-year-grid` in the vendored SCSS, so no
    custom element is rendered (CONVENTIONS.md §4). Single-root component, so
    {{ $attributes }} sits on that root. No classes are dropped.

    Divergences from Angular:

    * Angular receives a ready-made `pagedYears: number[]` from the parent. Here the
      page is computed from `pageStart` + `pageSize`, so the component renders with
      no props at all (IntegrityTest renders every component bare). The defaults
      mirror the parent's own fallback range, `current year - 100`, twelve years per
      page.
    * `selectedYear` is `input.required<number>()` upstream; here it defaults to the
      current year.
    * `disabledYears` is added for parity with the sibling month grid — Angular never
      disables a year button, and the default `[]` keeps the output identical.
    * The `yearSelect` output is not re-emitted (CONVENTIONS.md §7 item 2).
--}}
@props([
    /** First year of the page. Defaults to `current year - 100`, the parent's own fallback. */
    'pageStart' => null,
    /** How many years the page holds. */
    'pageSize' => 12,
    /** Year marked selected. Defaults to the current year. */
    'selectedYear' => null,
    /** Years rendered disabled. */
    'disabledYears' => [],
])

@php
    $pageStart = $pageStart !== null ? (int) $pageStart : (int) date('Y') - 100;
    $pageSize = max(0, (int) $pageSize);
    $selectedYear = $selectedYear !== null ? (int) $selectedYear : (int) date('Y');

    $pagedYears = $pageSize > 0 ? range($pageStart, $pageStart + $pageSize - 1) : [];
@endphp

<div {{ $attributes->class(['tedi-date-picker__month-year-grid'])->merge(['role' => 'group']) }}>
    @foreach ($pagedYears as $year)
        <button
            type="button"
            class="tedi-date-picker__month-year-button{{ $year === $selectedYear ? ' tedi-date-picker__month-year-button--selected' : '' }}"
            @disabled(in_array($year, $disabledYears, true))
        >
            {{ $year }}
        </button>
    @endforeach
</div>
