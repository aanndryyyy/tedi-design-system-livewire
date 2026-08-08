{{--
    TEDI Calendar header — DOCUMENTED SUBSET.
    Port of angular/tedi/components/content/calendar/calendar-header/{calendar-header.component.ts,calendar-header.component.html}

    Root element: the Angular template's own <div class="tedi-calendar-header">.
    `tedi-calendar-header` has no ELEMENT selector in the vendored SCSS, so no
    custom element is rendered (CONVENTIONS.md §4 ruling, evidenced by a grep of
    the whole vendored tree). `{{ $attributes }}` sits on that single root.

    SUBSET: `monthYearSelectType="dropdown"` stays the Angular default, but only
    the TRIGGER renders. Angular composes <tedi-dropdown> / -trigger / -content /
    -item; this package has no dropdown component and CDK Overlay positioning is
    out of scope (CONVENTIONS.md §7 item 3), so the <tedi-dropdown-content>
    listbox panel and its <li> options are dropped. The trigger
    (`tedi-calendar-header__select` + `__select-arrow`) is faithful.

    Classes dropped because dist/tedi.css ships no rule for them (CONVENTIONS.md
    §4 "classes Angular emits but TEDI never styles"; restore on a re-sync if
    TEDI ever adds the rules):
      * `tedi-calendar-header__dropdown--month`
      * `tedi-calendar-header__dropdown--year`
    `tedi-calendar-header__dropdown` itself IS styled, but it is the class of the
    dropped panel element, so it is not emitted either.

    Further divergences:
    * `localeCode` is DROPPED — the month label and the `role="status"`
      announcement come from the existing `date-picker.{january…december}` keys
      instead of `Intl` (`formatMonthYear`); `ext-intl` is not a declared
      dependency of this package.
    * `disabledMatchers`, `isMonthDisabled`, `isYearDisabled`, `minYear` and
      `maxYear` exist only to compute `prevDisabled` / `nextDisabled` and the
      per-option disabled state of the dropped panel. They are replaced by the
      explicit `prevDisabled` / `nextDisabled` props (CONVENTIONS.md §5).
    * `prevClick` / `nextClick` / `monthChange` / `yearChange` / `viewChange`
      outputs are not re-emitted (CONVENTIONS.md §7 item 2) — bind
      `wire:click` / `x-on:click` on the rendered buttons instead.
--}}
@props([
    /** Month the header describes. Any strtotime()-able value; null → the current month. */
    'currentMonth' => null,
    /** days|months|years */
    'view' => 'days',
    /** Render the previous/next chevron buttons. */
    'showNavigation' => true,
    /** dropdown|grid|static — how the month/year labels are exposed. */
    'monthYearSelectType' => 'dropdown',
    /** First year of the year page. null → year(currentMonth) - 5. */
    'yearPageStart' => null,
    /** How many years a year page holds. */
    'yearPageSize' => 12,
    /** How many months the calendar renders side by side. */
    'numberOfMonths' => 1,
    /** Disables the header controls. */
    'inputDisabled' => false,
    /** Disables the previous button (Angular computes this from the matchers). */
    'prevDisabled' => false,
    /** Disables the next button (Angular computes this from the matchers). */
    'nextDisabled' => false,
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

    $currentYear = (int) date('Y', $monthTs);
    $monthNumber = (int) date('n', $monthTs);

    $monthKeys = [
        1 => 'january', 2 => 'february', 3 => 'march', 4 => 'april',
        5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august',
        9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december',
    ];

    $currentMonthLabel = __('tedi::tedi.date-picker.'.$monthKeys[$monthNumber]);

    $resolvedYearPageStart = $yearPageStart !== null && $yearPageStart !== ''
        ? (int) $yearPageStart
        : $currentYear - 5;
    $yearPageSize = max(1, (int) $yearPageSize);
    $yearRangeLabel = $resolvedYearPageStart.'-'.($resolvedYearPageStart + $yearPageSize - 1);

    // captionAnnouncement()
    $captionAnnouncement = match ($view) {
        'years' => $yearRangeLabel,
        'months' => (string) $currentYear,
        default => $currentMonthLabel.' '.$currentYear,
    };

    $prevAriaLabel = $view === 'years'
        ? __('tedi::tedi.date-picker.previous-years')
        : __('tedi::tedi.date-picker.go-prev-month');
    $nextAriaLabel = $view === 'years'
        ? __('tedi::tedi.date-picker.next-years')
        : __('tedi::tedi.date-picker.go-next-month');
@endphp

<div {{ $attributes->class([
    'tedi-calendar-header',
    'tedi-calendar-header--disabled' => (bool) $inputDisabled,
])->merge([
    'role' => 'group',
    'aria-label' => __('tedi::tedi.date-picker.calendar-nav'),
]) }}>
    @if ($showNavigation)
        <tedi:button
            class="tedi-calendar-header__nav-button"
            variant="neutral"
            size="small"
            :icon-only="true"
            :aria-label="$prevAriaLabel"
            :disabled="(bool) $prevDisabled"
        >
            <tedi:icon name="arrow_back" :size="18" />
        </tedi:button>
    @endif

    <div class="tedi-calendar-header__title">
        @if ($view === 'days')
            @if ($monthYearSelectType === 'dropdown')
                <button
                    type="button"
                    class="tedi-calendar-header__select"
                    aria-haspopup="listbox"
                    aria-label="{{ __('tedi::tedi.date-picker.select-month') }}"
                    @disabled($inputDisabled)
                >
                    {{ $currentMonthLabel }}
                    <tedi:icon
                        name="arrow_drop_down"
                        class="tedi-calendar-header__select-arrow"
                        color="inherit"
                        size="inherit"
                    />
                </button>
            @elseif ($monthYearSelectType === 'grid')
                <button
                    type="button"
                    class="tedi-calendar-header__label-button"
                    aria-label="{{ __('tedi::tedi.date-picker.select-month') }}"
                    @disabled($inputDisabled)
                >{{ $currentMonthLabel }}</button>
            @else
                <span class="tedi-calendar-header__static-label">{{ $currentMonthLabel }}</span>
            @endif
        @endif

        @if ($view === 'days' || $view === 'months')
            @if ($monthYearSelectType === 'dropdown')
                <button
                    type="button"
                    class="tedi-calendar-header__select"
                    aria-haspopup="listbox"
                    aria-label="{{ __('tedi::tedi.date-picker.select-year') }}"
                    @disabled($inputDisabled)
                >
                    {{ $currentYear }}
                    <tedi:icon
                        name="arrow_drop_down"
                        class="tedi-calendar-header__select-arrow"
                        color="inherit"
                        size="inherit"
                    />
                </button>
            @elseif ($monthYearSelectType === 'grid')
                <button
                    type="button"
                    class="tedi-calendar-header__label-button"
                    aria-label="{{ __('tedi::tedi.date-picker.select-year') }}"
                    @disabled($inputDisabled)
                >{{ $currentYear }}</button>
            @else
                <span class="tedi-calendar-header__static-label">{{ $currentYear }}</span>
            @endif
        @elseif ($view === 'years')
            <span class="tedi-calendar-header__static-label">{{ $yearRangeLabel }}</span>
        @endif
    </div>

    @if ($showNavigation)
        <tedi:button
            class="tedi-calendar-header__nav-button"
            variant="neutral"
            size="small"
            :icon-only="true"
            :aria-label="$nextAriaLabel"
            :disabled="(bool) $nextDisabled"
        >
            <tedi:icon name="arrow_forward" :size="18" />
        </tedi:button>
    @endif

    <span class="sr-only" role="status" aria-live="polite" aria-atomic="true">{{ $captionAnnouncement }}</span>
</div>
