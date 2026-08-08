{{--
    TEDI Date picker month grid.
    Port of angular/tedi/components/form/date-picker/date-picker-month-grid/{date-picker-month-grid.component.ts,date-picker-month-grid.component.html}

    Root element is the Angular template's own root,
    <div class="tedi-date-picker__month-year-grid" role="group">. There is no
    element selector for `tedi-date-picker-month-grid` in the vendored SCSS, so no
    custom element is rendered (CONVENTIONS.md §4). Single-root component, so
    {{ $attributes }} sits on that root.

    No classes are dropped — all three this component emits have rules in
    dist/tedi.css. The twelve buttons are not chunked into rows: the vendored SCSS
    lays them out with `grid-template-columns: repeat(4, 1fr)`.

    Divergences from Angular:

    * `currentMonth` is `input.required<Date>()` upstream; here it defaults to today
      so the component renders bare (IntegrityTest renders every component with no
      props).
    * `disabledMonths` is added. Angular's month grid never disables a button, but
      the shared `tedi-date-picker__month-year-button:disabled` rule exists and the
      sibling year grid needs the same shape, so the prop is offered for parity.
      Default `[]` keeps the rendered output identical to Angular's.
    * Month abbreviations use the existing `date-picker.{january…december}-short`
      translation keys — the same keys Angular reads.
    * The `monthSelect` output is not re-emitted (CONVENTIONS.md §7 item 2).
--}}
@props([
    /** Month whose index is marked selected (any strtotime-parsable string or timestamp). Defaults to today. */
    'currentMonth' => null,
    /** Month indices (0-11) rendered disabled. */
    'disabledMonths' => [],
])

@php
    $currentMonthTs = $currentMonth
        ? ((is_numeric($currentMonth) ? (int) $currentMonth : strtotime($currentMonth)) ?: time())
        : time();

    $selectedIndex = (int) date('n', $currentMonthTs) - 1;

    $monthShortKeys = [
        'january-short', 'february-short', 'march-short', 'april-short',
        'may-short', 'june-short', 'july-short', 'august-short',
        'september-short', 'october-short', 'november-short', 'december-short',
    ];
@endphp

<div {{ $attributes->class(['tedi-date-picker__month-year-grid'])->merge(['role' => 'group']) }}>
    @foreach ($monthShortKeys as $index => $monthShortKey)
        <button
            type="button"
            class="tedi-date-picker__month-year-button{{ $index === $selectedIndex ? ' tedi-date-picker__month-year-button--selected' : '' }}"
            @disabled(in_array($index, $disabledMonths, true))
        >
            {{ __('tedi::tedi.date-picker.'.$monthShortKey) }}
        </button>
    @endforeach
</div>
