<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class CalendarComponentsTest extends TestCase
{
    /**
     * A month far enough from "today" that the --today / focusable-day fallbacks
     * stay deterministic whenever the suite runs.
     */
    private const MONTH = '2030-05-01';

    // -- calendar -----------------------------------------------------------

    public function test_calendar_host_classes(): void
    {
        $html = Blade::render('<tedi:calendar />');

        $this->assertHasClass('tedi-calendar', $html);
        $this->assertHasClass('tedi-calendar--bordered', $html);
        $this->assertMissingClass('tedi-calendar--disabled', $html);
        $this->assertMissingClass('tedi-calendar--with-week-numbers', $html);
    }

    public function test_calendar_host_modifier_classes(): void
    {
        $html = Blade::render('<tedi:calendar :bordered="false" :input-disabled="true" :show-week-numbers="true" />');

        $this->assertHasClass('tedi-calendar--disabled', $html);
        $this->assertHasClass('tedi-calendar--with-week-numbers', $html);
        $this->assertMissingClass('tedi-calendar--bordered', $html);
    }

    /** CALENDAR-SPEC §0.3 — dist/tedi.css has no rule for it, so it is dropped. */
    public function test_calendar_multi_month_class_is_dropped(): void
    {
        $html = Blade::render('<tedi:calendar :number-of-months="2" />');

        $this->assertMissingClass('tedi-calendar--multi-month', $html, on: 'tedi-calendar--multi-month');
    }

    public function test_calendar_emits_month_count_custom_property(): void
    {
        $html = Blade::render('<tedi:calendar :number-of-months="3" />');

        $this->assertStringContainsString('--_tedi-calendar-month-count: 3', $html);
    }

    public function test_calendar_renders_one_month_block_per_month(): void
    {
        $one = Blade::render('<tedi:calendar :current-month="\''.self::MONTH.'\'" />');
        $two = Blade::render('<tedi:calendar :current-month="\''.self::MONTH.'\'" :number-of-months="2" />');

        $this->assertSame(1, substr_count($one, 'class="tedi-calendar__month"'));
        $this->assertSame(2, substr_count($two, 'class="tedi-calendar__month"'));
        $this->assertStringContainsString('tedi-calendar__months', $two);
    }

    /**
     * calendar.component.scss:55 hides the footer with `&:empty`. Any whitespace
     * inside the div stops :empty from matching and leaves a stray top border.
     */
    public function test_calendar_footer_is_emitted_empty_on_one_line(): void
    {
        $html = Blade::render('<tedi:calendar />');

        $this->assertStringContainsString('<div class="tedi-calendar__footer"></div>', $html);
    }

    public function test_calendar_footer_slot_renders(): void
    {
        $html = Blade::render('<tedi:calendar><x-slot:footer><span>Legend</span></x-slot:footer></tedi:calendar>');

        $this->assertStringContainsString('<div class="tedi-calendar__footer"><span>Legend</span></div>', $html);
    }

    public function test_calendar_view_selects_the_grid(): void
    {
        $days = Blade::render('<tedi:calendar view="days" />');
        $this->assertHasClass('tedi-calendar-day-grid', $days, on: 'tedi-calendar-day-grid');
        $this->assertMissingClass('tedi-calendar-month-grid', $days, on: 'tedi-calendar-month-grid');

        $months = Blade::render('<tedi:calendar view="months" />');
        $this->assertHasClass('tedi-calendar-month-grid', $months, on: 'tedi-calendar-month-grid');
        $this->assertMissingClass('tedi-calendar-day-grid', $months, on: 'tedi-calendar-day-grid');

        $years = Blade::render('<tedi:calendar view="years" />');
        $this->assertHasClass('tedi-calendar-year-grid', $years, on: 'tedi-calendar-year-grid');
        $this->assertMissingClass('tedi-calendar-month-grid', $years, on: 'tedi-calendar-month-grid');
    }

    public function test_calendar_year_view_pages_from_current_month_minus_five(): void
    {
        $html = Blade::render('<tedi:calendar view="years" :current-month="\''.self::MONTH.'\'" />');

        $this->assertStringContainsString('>2025</button>', $html);
        $this->assertStringContainsString('>2036</button>', $html);
        $this->assertStringContainsString('2025-2036', $html);
    }

    // -- calendar-header ----------------------------------------------------

    public function test_calendar_header_root_classes_and_role(): void
    {
        $html = Blade::render('<tedi:calendar-header />');

        $this->assertHasClass('tedi-calendar-header', $html);
        $this->assertMissingClass('tedi-calendar-header--disabled', $html);
        $this->assertStringContainsString('role="group"', $html);
        $this->assertStringContainsString('class="tedi-calendar-header__title"', $html);
    }

    public function test_calendar_header_disabled_class(): void
    {
        $html = Blade::render('<tedi:calendar-header :input-disabled="true" />');

        $this->assertHasClass('tedi-calendar-header--disabled', $html);
    }

    public function test_calendar_header_navigation_buttons_toggle(): void
    {
        $with = Blade::render('<tedi:calendar-header />');
        $this->assertHasClass('tedi-calendar-header__nav-button', $with, on: 'tedi-calendar-header__nav-button');
        $this->assertSame(2, substr_count($with, 'tedi-calendar-header__nav-button'));

        $without = Blade::render('<tedi:calendar-header :show-navigation="false" />');
        $this->assertMissingClass('tedi-calendar-header__nav-button', $without, on: 'tedi-calendar-header__nav-button');
    }

    public function test_calendar_header_select_type_branches_in_days_view(): void
    {
        $dropdown = Blade::render('<tedi:calendar-header view="days" month-year-select-type="dropdown" />');
        $this->assertHasClass('tedi-calendar-header__select', $dropdown, on: 'tedi-calendar-header__select');
        $this->assertHasClass('tedi-calendar-header__select-arrow', $dropdown, on: 'tedi-calendar-header__select-arrow');
        $this->assertMissingClass('tedi-calendar-header__label-button', $dropdown, on: 'tedi-calendar-header__label-button');
        $this->assertMissingClass('tedi-calendar-header__static-label', $dropdown, on: 'tedi-calendar-header__static-label');

        $grid = Blade::render('<tedi:calendar-header view="days" month-year-select-type="grid" />');
        $this->assertHasClass('tedi-calendar-header__label-button', $grid, on: 'tedi-calendar-header__label-button');
        $this->assertMissingClass('tedi-calendar-header__select', $grid, on: 'tedi-calendar-header__select');

        $static = Blade::render('<tedi:calendar-header view="days" month-year-select-type="static" />');
        $this->assertHasClass('tedi-calendar-header__static-label', $static, on: 'tedi-calendar-header__static-label');
        $this->assertMissingClass('tedi-calendar-header__label-button', $static, on: 'tedi-calendar-header__label-button');
    }

    /** CALENDAR-SPEC §0.2 — the dropdown listbox panel is not ported. */
    public function test_calendar_header_dropdown_panel_is_not_rendered(): void
    {
        $html = Blade::render('<tedi:calendar-header month-year-select-type="dropdown" />');

        $this->assertMissingClass('tedi-calendar-header__dropdown', $html, on: 'tedi-calendar-header__dropdown');
        $this->assertMissingClass('tedi-calendar-header__dropdown--month', $html, on: 'tedi-calendar-header__dropdown--month');
        $this->assertMissingClass('tedi-calendar-header__dropdown--year', $html, on: 'tedi-calendar-header__dropdown--year');
        $this->assertStringNotContainsString('<li', $html);
    }

    public function test_calendar_header_months_view_hides_the_month_control(): void
    {
        $html = Blade::render('<tedi:calendar-header view="months" :current-month="\''.self::MONTH.'\'" />');

        $this->assertSame(1, substr_count($html, 'tedi-calendar-header__select"'));
        $this->assertStringContainsString('2030', $html);
        $this->assertStringNotContainsString('May', $html);
    }

    public function test_calendar_header_years_view_renders_the_range_label(): void
    {
        $html = Blade::render('<tedi:calendar-header view="years" :current-month="\''.self::MONTH.'\'" />');

        $this->assertHasClass('tedi-calendar-header__static-label', $html, on: 'tedi-calendar-header__static-label');
        $this->assertMissingClass('tedi-calendar-header__select', $html, on: 'tedi-calendar-header__select');
        $this->assertStringContainsString('2025-2036', $html);
    }

    public function test_calendar_header_year_page_props_drive_the_range(): void
    {
        $html = Blade::render('<tedi:calendar-header view="years" :year-page-start="2000" :year-page-size="6" />');

        $this->assertStringContainsString('2000-2005', $html);
    }

    public function test_calendar_header_nav_aria_labels_follow_the_view(): void
    {
        $days = Blade::render('<tedi:calendar-header view="days" />');
        $this->assertStringContainsString('aria-label="Previous month"', $days);
        $this->assertStringContainsString('aria-label="Next month"', $days);

        $years = Blade::render('<tedi:calendar-header view="years" />');
        $this->assertStringContainsString('aria-label="Previous years"', $years);
        $this->assertStringContainsString('aria-label="Next years"', $years);
    }

    public function test_calendar_header_announcement_span(): void
    {
        $html = Blade::render('<tedi:calendar-header :current-month="\''.self::MONTH.'\'" />');

        $this->assertHasClass('sr-only', $html, on: 'sr-only');
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringContainsString('May 2030', $html);
    }

    public function test_calendar_header_nav_buttons_honour_disabled_props(): void
    {
        $html = Blade::render('<tedi:calendar-header :prev-disabled="true" :next-disabled="true" />');

        $this->assertSame(2, substr_count($html, 'disabled'));
    }

    // -- calendar-day-grid --------------------------------------------------

    public function test_day_grid_root_is_a_table_with_grid_role(): void
    {
        $html = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" />');

        $this->assertMatchesRegularExpression('/<table[^>]*role="grid"/', $html);
        $this->assertHasClass('tedi-calendar-day-grid', $html);
        $this->assertStringContainsString('aria-label="May 2030"', $html);
    }

    public function test_day_grid_is_always_six_rows_of_seven(): void
    {
        $html = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" />');

        $this->assertSame(6, substr_count($html, 'class="tedi-calendar-day-grid__row"'));
        $this->assertSame(42, substr_count($html, 'class="tedi-calendar-day-grid__cell"'));
        $this->assertSame(7, substr_count($html, 'class="tedi-calendar-day-grid__weekday"'));
    }

    public function test_day_grid_hides_outside_days_when_asked(): void
    {
        $shown = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :show-outside-days="true" />');
        $this->assertHasClass('tedi-calendar-day-grid__day--outside', $shown, on: 'tedi-calendar-day-grid__day--outside');

        $hidden = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :show-outside-days="false" />');
        $this->assertMissingClass('tedi-calendar-day-grid__day--outside', $hidden, on: 'tedi-calendar-day-grid__day--outside');
        // The empty <td> still renders, and it is the cell that carries role="gridcell".
        $this->assertSame(42, substr_count($hidden, 'class="tedi-calendar-day-grid__cell"'));
        $this->assertStringContainsString('<td class="tedi-calendar-day-grid__cell" role="gridcell"></td>', $hidden);
    }

    /**
     * cellState() replaces the whole class attribute, so an exact attribute match
     * is both precise and safe from the prefix hazard — this is the one place a
     * string assertion is the right tool, because it is the collection ORDER
     * (base, range, availability, disabled) that is under test.
     */
    public function test_day_grid_cell_state_order(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :available-days="[\'2030-05-02\']" />'
        );

        $this->assertStringContainsString(
            'class="tedi-calendar-day-grid__day tedi-calendar-day-grid__day--outside tedi-calendar-day-grid__day--disabled"',
            $html
        );
        $this->assertStringContainsString(
            'class="tedi-calendar-day-grid__day tedi-calendar-day-grid__day--available-day"',
            $html
        );
    }

    public function test_day_grid_today_and_selected_modifiers(): void
    {
        $today = date('Y-m-d');
        $html = Blade::render('<tedi:calendar-day-grid :value="\''.$today.'\'" />');

        $this->assertHasClass('tedi-calendar-day-grid__day--today', $html, on: 'tedi-calendar-day-grid__day--today');
        $this->assertHasClass('tedi-calendar-day-grid__day--selected', $html, on: 'tedi-calendar-day-grid__day--selected');
    }

    public function test_day_grid_multiple_mode_selects_every_date(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" mode="multiple" :value="[\'2030-05-10\', \'2030-05-12\']" />'
        );

        $this->assertSame(2, substr_count($html, 'tedi-calendar-day-grid__day--selected'));
        $this->assertStringContainsString('aria-multiselectable="true"', $html);
    }

    public function test_day_grid_single_mode_is_not_multiselectable(): void
    {
        $html = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" />');

        $this->assertStringNotContainsString('aria-multiselectable', $html);
    }

    public function test_day_grid_committed_range_modifiers(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" mode="range"'
            .' :value="[\'from\' => \'2030-05-10\', \'to\' => \'2030-05-15\']" />'
        );

        $this->assertHasClass('tedi-calendar-day-grid__day--range-start', $html, on: 'tedi-calendar-day-grid__day--range-start');
        $this->assertHasClass('tedi-calendar-day-grid__day--range-end', $html, on: 'tedi-calendar-day-grid__day--range-end');
        $this->assertHasClass('tedi-calendar-day-grid__day--range-middle', $html, on: 'tedi-calendar-day-grid__day--range-middle');
        $this->assertSame(4, substr_count($html, 'tedi-calendar-day-grid__day--range-middle'));
    }

    /** collectCommittedRangeModifiers() returns early when from === to. */
    public function test_day_grid_single_day_range_emits_no_range_modifier(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" mode="range"'
            .' :value="[\'from\' => \'2030-05-10\', \'to\' => \'2030-05-10\']" />'
        );

        $this->assertMissingClass('tedi-calendar-day-grid__day--range-start', $html, on: 'tedi-calendar-day-grid__day--range-start');
        $this->assertMissingClass('tedi-calendar-day-grid__day--range-end', $html, on: 'tedi-calendar-day-grid__day--range-end');
        $this->assertHasClass('tedi-calendar-day-grid__day--selected', $html, on: 'tedi-calendar-day-grid__day--selected');
    }

    public function test_day_grid_preview_range_modifiers(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" mode="range"'
            .' :value="[\'from\' => \'2030-05-10\', \'to\' => null]" hovered-date="2030-05-15" />'
        );

        $this->assertHasClass('tedi-calendar-day-grid__day--range-start', $html, on: 'tedi-calendar-day-grid__day--range-start');
        $this->assertHasClass('tedi-calendar-day-grid__day--range-preview-end', $html, on: 'tedi-calendar-day-grid__day--range-preview-end');
        $this->assertHasClass('tedi-calendar-day-grid__day--range-preview-middle', $html, on: 'tedi-calendar-day-grid__day--range-preview-middle');
        $this->assertMissingClass('tedi-calendar-day-grid__day--range-preview-start', $html, on: 'tedi-calendar-day-grid__day--range-preview-start');
    }

    public function test_day_grid_backwards_preview_flips_the_modifiers(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" mode="range"'
            .' :value="[\'from\' => \'2030-05-15\', \'to\' => null]" hovered-date="2030-05-10" />'
        );

        $this->assertHasClass('tedi-calendar-day-grid__day--range-end', $html, on: 'tedi-calendar-day-grid__day--range-end');
        $this->assertHasClass('tedi-calendar-day-grid__day--range-preview-start', $html, on: 'tedi-calendar-day-grid__day--range-preview-start');
        $this->assertMissingClass('tedi-calendar-day-grid__day--range-preview-end', $html, on: 'tedi-calendar-day-grid__day--range-preview-end');
    }

    public function test_day_grid_availability_modifiers_and_disabling(): void
    {
        $available = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :available-days="[\'2030-05-10\']" />'
        );
        $this->assertHasClass('tedi-calendar-day-grid__day--available-day', $available, on: 'tedi-calendar-day-grid__day--available-day');
        // Everything outside the whitelist is disabled: 42 cells - 1 available.
        $this->assertSame(41, substr_count($available, 'tedi-calendar-day-grid__day--disabled'));

        $unavailable = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :unavailable-days="[\'2030-05-10\']" />'
        );
        $this->assertHasClass('tedi-calendar-day-grid__day--unavailable-day', $unavailable, on: 'tedi-calendar-day-grid__day--unavailable-day');
        $this->assertSame(1, substr_count($unavailable, 'tedi-calendar-day-grid__day--disabled'));
    }

    public function test_day_grid_input_disabled_disables_every_day(): void
    {
        $html = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :input-disabled="true" />');

        $this->assertSame(42, substr_count($html, 'tedi-calendar-day-grid__day--disabled'));
        $this->assertSame(42, substr_count($html, 'aria-disabled="true"'));
    }

    public function test_day_grid_week_numbers_column(): void
    {
        $without = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" />');
        $this->assertMissingClass('tedi-calendar-day-grid--with-week-numbers', $without);
        $this->assertMissingClass('tedi-calendar-day-grid__week-number', $without, on: 'tedi-calendar-day-grid__week-number');

        $with = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :show-week-numbers="true" />');
        $this->assertHasClass('tedi-calendar-day-grid--with-week-numbers', $with);
        $this->assertHasClass('tedi-calendar-day-grid__week-number-header', $with, on: 'tedi-calendar-day-grid__week-number-header');
        $this->assertSame(6, substr_count($with, 'class="tedi-calendar-day-grid__week-number"'));
        // date('W') is getISOWeek(); the first row of May 2030 starts 2030-04-29.
        $this->assertStringContainsString('>18</th>', $with);
        $this->assertStringContainsString('aria-label="Week 18"', $with);
    }

    public function test_day_grid_first_day_of_week_rotates_both_header_arrays(): void
    {
        $monday = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :first-day-of-week="1" />');
        $this->assertStringContainsString('aria-label="Monday" >Mon</th>', preg_replace('/\s+/', ' ', $monday));

        $sunday = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" :first-day-of-week="0" />');
        $normalised = preg_replace('/\s+/', ' ', $sunday);
        $this->assertStringContainsString('aria-label="Sunday" >Sun</th>', $normalised);
        $this->assertLessThan(
            strpos($normalised, 'aria-label="Monday"'),
            strpos($normalised, 'aria-label="Sunday"')
        );
    }

    public function test_day_grid_focusable_day_is_the_first_selectable_in_month_day(): void
    {
        $html = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" />');

        $this->assertSame(1, substr_count($html, 'tabindex="0"'));
        $this->assertSame(41, substr_count($html, 'tabindex="-1"'));
    }

    public function test_day_grid_day_aria_label_and_date_key(): void
    {
        $html = Blade::render('<tedi:calendar-day-grid :month="\''.self::MONTH.'\'" />');

        $this->assertStringContainsString('aria-label="Wednesday, 1. May 2030"', $html);
        $this->assertStringContainsString('data-date-key="'.(mktime(0, 0, 0, 5, 1, 2030) * 1000).'"', $html);
    }

    public function test_day_grid_status_indicator_and_status_label(): void
    {
        $html = Blade::render(
            '<tedi:calendar-day-grid :month="\''.self::MONTH.'\'"'
            .' :day-status="[\'2030-05-10\' => [\'type\' => \'success\', \'label\' => \'Kinnitatud\']]" />'
        );

        $this->assertHasClass('tedi-calendar-day-grid__status', $html, on: 'tedi-calendar-day-grid__status');
        $this->assertStringContainsString('aria-label="Friday, 10. May 2030, Kinnitatud"', $html);
    }

    // -- calendar-month-grid ------------------------------------------------

    public function test_month_grid_structure(): void
    {
        $html = Blade::render('<tedi:calendar-month-grid :year="2030" />');

        $this->assertHasClass('tedi-calendar-month-grid', $html);
        $this->assertStringContainsString('aria-label="Choose month"', $html);
        $this->assertSame(4, substr_count($html, 'class="tedi-calendar-month-grid__row"'));
        $this->assertSame(12, substr_count($html, 'class="tedi-calendar-month-grid__cell"'));
    }

    public function test_month_grid_name_format(): void
    {
        $short = Blade::render('<tedi:calendar-month-grid :year="2030" month-name-format="short" />');
        $this->assertStringContainsString('>Jan</button>', $short);
        $this->assertStringContainsString('aria-label="January"', $short);

        $long = Blade::render('<tedi:calendar-month-grid :year="2030" month-name-format="long" />');
        $this->assertStringContainsString('>January</button>', $long);
    }

    public function test_month_grid_selected_current_and_disabled_modifiers(): void
    {
        $html = Blade::render('<tedi:calendar-month-grid :year="2030" selected-month="2030-05-16" :disabled-months="[0, 1]" />');

        $this->assertHasClass('tedi-calendar-month-grid__month--selected', $html, on: 'tedi-calendar-month-grid__month--selected');
        $this->assertSame(2, substr_count($html, 'tedi-calendar-month-grid__month--disabled'));
        $this->assertMissingClass('tedi-calendar-month-grid__month--current', $html, on: 'tedi-calendar-month-grid__month--current');

        $current = Blade::render('<tedi:calendar-month-grid :year="'.date('Y').'" />');
        $this->assertHasClass('tedi-calendar-month-grid__month--current', $current, on: 'tedi-calendar-month-grid__month--current');
    }

    public function test_month_grid_input_disabled_disables_every_month(): void
    {
        $html = Blade::render('<tedi:calendar-month-grid :year="2030" :input-disabled="true" />');

        $this->assertSame(12, substr_count($html, 'tedi-calendar-month-grid__month--disabled'));
    }

    // -- calendar-year-grid -------------------------------------------------

    public function test_year_grid_structure_and_page(): void
    {
        $html = Blade::render('<tedi:calendar-year-grid :page-start="2030" />');

        $this->assertHasClass('tedi-calendar-year-grid', $html);
        $this->assertStringContainsString('aria-label="Choose year"', $html);
        $this->assertSame(4, substr_count($html, 'class="tedi-calendar-year-grid__row"'));
        $this->assertSame(12, substr_count($html, 'class="tedi-calendar-year-grid__cell"'));
        $this->assertStringContainsString('>2030</button>', $html);
        $this->assertStringContainsString('>2041</button>', $html);
    }

    public function test_year_grid_page_size(): void
    {
        $html = Blade::render('<tedi:calendar-year-grid :page-start="2030" :page-size="6" />');

        $this->assertSame(6, substr_count($html, 'class="tedi-calendar-year-grid__cell"'));
    }

    public function test_year_grid_selected_current_and_disabled_modifiers(): void
    {
        $html = Blade::render('<tedi:calendar-year-grid :page-start="2030" :selected-year="2032" :disabled-years="[2031]" />');

        $this->assertHasClass('tedi-calendar-year-grid__year--selected', $html, on: 'tedi-calendar-year-grid__year--selected');
        $this->assertHasClass('tedi-calendar-year-grid__year--disabled', $html, on: 'tedi-calendar-year-grid__year--disabled');
        $this->assertSame(1, substr_count($html, 'tedi-calendar-year-grid__year--disabled'));
        $this->assertMissingClass('tedi-calendar-year-grid__year--current', $html, on: 'tedi-calendar-year-grid__year--current');

        $current = Blade::render('<tedi:calendar-year-grid :page-start="'.date('Y').'" />');
        $this->assertHasClass('tedi-calendar-year-grid__year--current', $current, on: 'tedi-calendar-year-grid__year--current');
    }

    public function test_year_grid_input_disabled_disables_every_year(): void
    {
        $html = Blade::render('<tedi:calendar-year-grid :page-start="2030" :input-disabled="true" />');

        $this->assertSame(12, substr_count($html, 'tedi-calendar-year-grid__year--disabled'));
    }
}
