{{--
    TEDI Date picker header — DOCUMENTED SUBSET.
    Port of angular/tedi/components/form/date-picker/date-picker-header/{date-picker-header.component.ts,date-picker-header.component.html}

    Root element is the Angular template's own root, a <div class="tedi-date-picker__header">.
    `tedi-date-picker-header` has no element selector anywhere in the vendored SCSS,
    so no custom element is rendered (CONVENTIONS.md §4). Single-root component, so
    {{ $attributes }} sits on that root.

    Divergences from Angular:

    * The month/year DROPDOWN PANEL is not rendered. Angular composes
      <tedi-dropdown> / -trigger / -content / -item, and this package has no dropdown
      component — CDK Overlay positioning is out of scope (CONVENTIONS.md §7 item 3).
      Only the trigger <button> renders, for `monthMode`/`yearMode` of both
      `dropdown` and `grid`, which emit the identical trigger markup. The defaults
      stay `dropdown` so the prop contract still matches Angular; what changes is the
      markup, not the prop.
    * As a consequence, the `years`, `pagedYears`, `disabledMonths` and
      `disabledYears` inputs are dropped — they only populate the unported panel's
      <li> list.
    * The class `tedi-date-picker__dropdown-arrow` on the trigger's arrow icon is
      DROPPED: TEDI ships no rule for it (a boundary-anchored grep of dist/tedi.css
      returns 0), and CONVENTIONS.md §4 rules that the stylesheet guardrail wins.
      Restore it if a future TEDI release adds the rule. The unrendered panel's
      classes (`__dropdown-content` and its `--month`/`--year` modifiers) do have
      rules but style elements this port does not emit.
    * `type="button"` is added to the two trigger buttons. Angular omits it, which
      makes a bare <button> submit when the picker sits inside a form — a deliberate
      one-word divergence.
    * Month names come from the existing `date-picker.*` translation keys rather than
      `Intl`, matching Angular here (this family already reads static keys).
    * `output()` events (prev/next month, month/year select, year paging) are not
      re-emitted; consumers bind their own listeners.
--}}
@props([
    /** Id of the grid this header controls, for `aria-controls`. Auto-generated when omitted. */
    'uniqueId' => null,
    /** month-grid|year-grid|calendar-grid */
    'currentView' => 'calendar-grid',
    /** Currently displayed month (any strtotime-parsable string or timestamp). Defaults to today. */
    'month' => null,
    /** Month selector mode: none|label|grid|dropdown. */
    'monthMode' => 'dropdown',
    /** Year selector mode: none|label|grid|dropdown. */
    'yearMode' => 'dropdown',
    /** Whether to show the previous/next month buttons. */
    'showNavigation' => true,
    /** Whether previous-month navigation is enabled. */
    'canGoPrev' => true,
    /** Whether next-month navigation is enabled. */
    'canGoNext' => true,
    /** Year shown in the year control. Falls back to the year of `month`. */
    'selectedYear' => null,
    /** Whether a previous year page exists (year-grid view). */
    'hasPrevYearPage' => true,
    /** Whether a next year page exists (year-grid view). */
    'hasNextYearPage' => true,
])

@php
    $uniqueId = $uniqueId ?? \Tedi\Livewire\Tedi::id('tedi-date-picker-id-');

    $monthTs = $month ? (is_numeric($month) ? (int) $month : strtotime($month)) : time();
    $monthTs = $monthTs ?: time();

    $selectedYear = $selectedYear !== null ? (int) $selectedYear : (int) date('Y', $monthTs);

    // monthNames — the same static date-picker.* keys Angular's header reads.
    $monthKeys = [
        'january', 'february', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december',
    ];
    $monthName = __('tedi::tedi.date-picker.'.$monthKeys[(int) date('n', $monthTs) - 1]);

    // dropdown and grid emit the identical trigger button; only the dropped panel
    // differed between them.
    $monthIsTrigger = in_array($monthMode, ['dropdown', 'grid'], true);
    $yearIsTrigger = in_array($yearMode, ['dropdown', 'grid'], true);
@endphp

<div {{ $attributes->class(['tedi-date-picker__header']) }}>
    @if ($currentView === 'calendar-grid')
        @if ($showNavigation)
            <tedi:button
                variant="neutral"
                size="small"
                class="tedi-date-picker__nav"
                icon-start="arrow_back"
                :icon-only="true"
                :aria-label="__('tedi::tedi.date-picker.go-prev-month')"
                :aria-controls="$uniqueId"
                :disabled="! $canGoPrev"
            />
        @endif

        <div class="tedi-date-picker__controls">
            @if ($monthIsTrigger)
                <button
                    type="button"
                    class="tedi-date-picker__dropdown-trigger"
                    aria-haspopup="listbox"
                    aria-label="{{ __('tedi::tedi.date-picker.select-month') }}"
                >
                    {{ $monthName }}
                    <tedi:icon name="arrow_drop_down" size="inherit" color="inherit" />
                </button>
            @elseif ($monthMode === 'label')
                <div class="tedi-date-picker__label">{{ $monthName }}</div>
            @endif

            @if ($yearIsTrigger)
                <button
                    type="button"
                    class="tedi-date-picker__dropdown-trigger"
                    aria-haspopup="listbox"
                    aria-label="{{ __('tedi::tedi.date-picker.select-year') }}"
                >
                    {{ $selectedYear }}
                    <tedi:icon name="arrow_drop_down" size="inherit" color="inherit" />
                </button>
            @elseif ($yearMode === 'label')
                <div class="tedi-date-picker__label">{{ $selectedYear }}</div>
            @endif
        </div>

        @if ($showNavigation)
            <tedi:button
                variant="neutral"
                size="small"
                class="tedi-date-picker__nav"
                icon-start="arrow_forward"
                :icon-only="true"
                :aria-label="__('tedi::tedi.date-picker.go-next-month')"
                :aria-controls="$uniqueId"
                :disabled="! $canGoNext"
            />
        @endif
    @elseif ($currentView === 'month-grid')
        <div class="tedi-date-picker__controls">{{ $monthName }}</div>
    @elseif ($currentView === 'year-grid')
        <tedi:button
            variant="neutral"
            size="small"
            class="tedi-date-picker__nav"
            icon-start="arrow_back"
            :icon-only="true"
            :aria-label="__('tedi::tedi.date-picker.previous-years')"
            :disabled="! $hasPrevYearPage"
        />
        <div class="tedi-date-picker__controls">{{ $selectedYear }}</div>
        <tedi:button
            variant="neutral"
            size="small"
            class="tedi-date-picker__nav"
            icon-start="arrow_forward"
            :icon-only="true"
            :aria-label="__('tedi::tedi.date-picker.next-years')"
            :disabled="! $hasNextYearPage"
        />
    @endif
</div>
