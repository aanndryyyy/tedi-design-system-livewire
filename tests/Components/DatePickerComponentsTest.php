<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class DatePickerComponentsTest extends TestCase
{
    // -- date-picker ---------------------------------------------------------

    public function test_date_picker_root_is_the_custom_element_and_carries_no_class(): void
    {
        $html = Blade::render('<tedi:date-picker />');

        // The vendored SCSS styles the ELEMENT tedi-date-picker; there is no
        // .tedi-date-picker class rule, so the root must carry no block class.
        $this->assertMatchesRegularExpression('/<tedi-date-picker\s*>/', $html);
        $this->assertStringContainsString('</tedi-date-picker>', $html);
        $this->assertMissingClass('tedi-date-picker', $html, on: 'tedi-date-picker__input');
        $this->assertDoesNotMatchRegularExpression('/<tedi-date-picker[^>]*class=/', $html);
    }

    public function test_date_picker_input_base_class_and_attributes(): void
    {
        $html = Blade::render('<tedi:date-picker input-id="dp-1" input-placeholder="Enter date..." />');

        $this->assertHasClass('tedi-date-picker__input', $html);
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('role="combobox"', $html);
        $this->assertStringContainsString('aria-autocomplete="none"', $html);
        $this->assertStringContainsString('aria-haspopup="dialog"', $html);
        $this->assertStringContainsString('id="dp-1"', $html);
        $this->assertStringContainsString('placeholder="Enter date..."', $html);
    }

    public function test_date_picker_input_size_classes(): void
    {
        $html = Blade::render('<tedi:date-picker input-size="default" />');
        $this->assertMissingClass('tedi-date-picker__input--small', $html, on: 'tedi-date-picker__input');

        $html = Blade::render('<tedi:date-picker input-size="small" />');
        $this->assertHasClass('tedi-date-picker__input--small', $html, on: 'tedi-date-picker__input');
    }

    public function test_date_picker_input_state_classes(): void
    {
        $html = Blade::render('<tedi:date-picker input-state="default" />');
        $this->assertMissingClass('tedi-date-picker__input--valid', $html, on: 'tedi-date-picker__input');
        $this->assertMissingClass('tedi-date-picker__input--error', $html, on: 'tedi-date-picker__input');

        $html = Blade::render('<tedi:date-picker input-state="valid" />');
        $this->assertHasClass('tedi-date-picker__input--valid', $html, on: 'tedi-date-picker__input');
        $this->assertMissingClass('tedi-date-picker__input--error', $html, on: 'tedi-date-picker__input');

        $html = Blade::render('<tedi:date-picker input-state="error" />');
        $this->assertHasClass('tedi-date-picker__input--error', $html, on: 'tedi-date-picker__input');
        $this->assertMissingClass('tedi-date-picker__input--valid', $html, on: 'tedi-date-picker__input');
    }

    public function test_date_picker_forwards_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:date-picker wire:model="meetingDate" />');

        // The attribute bag lands on the control, not the wrapper — that is what
        // makes wire:model bind to the real <input>.
        $this->assertMatchesRegularExpression(
            '/<input[^>]*wire:model="meetingDate"/', $html
        );
    }

    public function test_date_picker_omits_empty_value_attribute(): void
    {
        $html = Blade::render('<tedi:date-picker />');
        $this->assertStringNotContainsString('value=', $html);

        // Angular's formatDate() is et-EE dd.MM.yyyy.
        $html = Blade::render('<tedi:date-picker selected="2024-06-15" />');
        $this->assertStringContainsString('value="15.06.2024"', $html);
    }

    public function test_date_picker_aria_expanded_and_readonly_are_always_rendered(): void
    {
        $html = Blade::render('<tedi:date-picker />');
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('aria-readonly="false"', $html);

        $html = Blade::render('<tedi:date-picker :open="true" />');
        $this->assertStringContainsString('aria-expanded="true"', $html);

        // Disabled suppresses the expanded state, exactly as Angular does.
        $html = Blade::render('<tedi:date-picker :open="true" :input-disabled="true" />');
        $this->assertStringContainsString('aria-expanded="false"', $html);

        $html = Blade::render('<tedi:date-picker :allow-manual-input="false" />');
        $this->assertStringContainsString('aria-readonly="true"', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*\sreadonly/', $html);
    }

    public function test_date_picker_action_buttons(): void
    {
        $html = Blade::render('<tedi:date-picker />');
        $this->assertHasClass('tedi-date-picker__input-buttons', $html, on: 'tedi-date-picker__input-buttons');
        $this->assertHasClass('tedi-date-picker__toggle', $html, on: 'tedi-date-picker__toggle');
        // The clear button only exists once a date is selected.
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__clear'));

        $html = Blade::render('<tedi:date-picker selected="2024-06-15" />');
        $this->assertHasClass('tedi-date-picker__clear', $html, on: 'tedi-date-picker__clear');
        $this->assertHasClass('tedi-closing-button', $html, on: 'tedi-date-picker__clear');
    }

    public function test_date_picker_calendar_renders_only_when_open(): void
    {
        $html = Blade::render('<tedi:date-picker />');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__calendar'));

        $html = Blade::render('<tedi:date-picker :open="true" />');
        $this->assertHasClass('tedi-date-picker__calendar', $html, on: 'tedi-date-picker__calendar');
        $this->assertHasClass('tedi-date-picker__header', $html, on: 'tedi-date-picker__header');
    }

    public function test_date_picker_current_view_selects_one_grid(): void
    {
        $html = Blade::render('<tedi:date-picker :open="true" current-view="calendar-grid" />');
        $this->assertHasClass('tedi-date-picker__grid', $html, on: 'tedi-date-picker__grid');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__month-year-grid'));

        $html = Blade::render('<tedi:date-picker :open="true" current-view="month-grid" />');
        $this->assertHasClass('tedi-date-picker__month-year-grid', $html, on: 'tedi-date-picker__month-year-grid');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__grid'));

        $html = Blade::render('<tedi:date-picker :open="true" current-view="year-grid" />');
        $this->assertHasClass('tedi-date-picker__month-year-grid', $html, on: 'tedi-date-picker__month-year-grid');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__grid'));
    }

    public function test_date_picker_does_not_render_the_unported_popover_or_dropdown_panel(): void
    {
        $html = Blade::render('<tedi:date-picker :open="true" />');

        $this->assertStringNotContainsString('tedi-popover', $html);
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__dropdown-content'));
    }

    // -- date-picker-header ---------------------------------------------------

    public function test_date_picker_header_base_class(): void
    {
        $html = Blade::render('<tedi:date-picker-header />');

        $this->assertHasClass('tedi-date-picker__header', $html, on: 'tedi-date-picker__header');
        $this->assertHasClass('tedi-date-picker__controls', $html, on: 'tedi-date-picker__controls');
    }

    public function test_date_picker_header_selector_modes(): void
    {
        // dropdown and grid emit the identical trigger; the panel is unported.
        foreach (['dropdown', 'grid'] as $mode) {
            $html = Blade::render('<tedi:date-picker-header month-mode="'.$mode.'" year-mode="none" />');
            $this->assertHasClass('tedi-date-picker__dropdown-trigger', $html, on: 'tedi-date-picker__dropdown-trigger');
            $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__label'));
        }

        $html = Blade::render('<tedi:date-picker-header month-mode="label" year-mode="none" />');
        $this->assertHasClass('tedi-date-picker__label', $html, on: 'tedi-date-picker__label');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__dropdown-trigger'));

        $html = Blade::render('<tedi:date-picker-header month-mode="none" year-mode="none" />');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__dropdown-trigger'));
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__label'));
    }

    public function test_date_picker_header_drops_the_unstyled_dropdown_arrow_class(): void
    {
        $html = Blade::render('<tedi:date-picker-header month-mode="dropdown" />');

        // dist/tedi.css ships no rule for it — CONVENTIONS.md §4 says drop it.
        $this->assertStringNotContainsString('tedi-date-picker__dropdown-arrow', $html);
    }

    public function test_date_picker_header_trigger_buttons_are_type_button(): void
    {
        $html = Blade::render('<tedi:date-picker-header month-mode="dropdown" year-mode="none" />');

        // Deliberate divergence: Angular omits type, which submits inside a form.
        $this->assertMatchesRegularExpression(
            '/<button\s+type="button"\s+class="tedi-date-picker__dropdown-trigger"/', $html
        );
    }

    public function test_date_picker_header_navigation_buttons(): void
    {
        $html = Blade::render('<tedi:date-picker-header :show-navigation="true" unique-id="grid-1" />');
        $this->assertHasClass('tedi-date-picker__nav', $html, on: 'tedi-date-picker__nav');
        $this->assertStringContainsString('aria-controls="grid-1"', $html);
        $this->assertStringContainsString('aria-label="Previous month"', $html);
        $this->assertStringContainsString('aria-label="Next month"', $html);

        $html = Blade::render('<tedi:date-picker-header :show-navigation="false" />');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__nav'));
    }

    public function test_date_picker_header_nav_disabled_state(): void
    {
        $html = Blade::render('<tedi:date-picker-header :can-go-prev="false" :can-go-next="false" />');

        $this->assertSame(2, substr_count($html, 'disabled'));
    }

    public function test_date_picker_header_month_grid_view_renders_only_the_month_label(): void
    {
        $html = Blade::render('<tedi:date-picker-header current-view="month-grid" month="2024-06-15" />');

        $this->assertHasClass('tedi-date-picker__controls', $html, on: 'tedi-date-picker__controls');
        $this->assertStringContainsString('June', $html);
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__nav'));
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__dropdown-trigger'));
    }

    public function test_date_picker_header_year_grid_view_pages_years(): void
    {
        $html = Blade::render('<tedi:date-picker-header current-view="year-grid" :selected-year="2024" :has-prev-year-page="false" />');

        $this->assertHasClass('tedi-date-picker__nav', $html, on: 'tedi-date-picker__nav');
        $this->assertStringContainsString('aria-label="Previous years"', $html);
        $this->assertStringContainsString('aria-label="Next years"', $html);
        $this->assertStringContainsString('>2024</div>', $html);
        $this->assertSame(1, substr_count($html, 'disabled'));
    }

    public function test_date_picker_header_uses_translated_month_names(): void
    {
        $html = Blade::render('<tedi:date-picker-header month="2024-01-10" month-mode="label" />');

        $this->assertStringContainsString('January', $html);
    }

    // -- date-picker-calendar-grid -------------------------------------------

    public function test_calendar_grid_structural_classes(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" />');

        $this->assertHasClass('tedi-date-picker__weekdays', $html, on: 'tedi-date-picker__weekdays');
        $this->assertHasClass('tedi-date-picker__weekday', $html, on: 'tedi-date-picker__weekday');
        $this->assertHasClass('tedi-date-picker__grid', $html, on: 'tedi-date-picker__grid');
        $this->assertHasClass('tedi-date-picker__row', $html, on: 'tedi-date-picker__row');
        $this->assertHasClass('tedi-date-picker__day', $html, on: 'tedi-date-picker__day');
        $this->assertStringContainsString('role="grid"', $html);
        $this->assertStringContainsString('aria-readonly="true"', $html);
    }

    public function test_calendar_grid_week_number_classes(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" />');
        $this->assertMissingClass('tedi-date-picker__weekdays--numbered', $html, on: 'tedi-date-picker__weekdays');
        $this->assertMissingClass('tedi-date-picker__row--numbered', $html, on: 'tedi-date-picker__row');
        $this->assertSame([], $this->classesOf($html, 'tedi-date-picker__weeknumber'));

        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" :show-week-numbers="true" />');
        $this->assertHasClass('tedi-date-picker__weekdays--numbered', $html, on: 'tedi-date-picker__weekdays');
        $this->assertHasClass('tedi-date-picker__row--numbered', $html, on: 'tedi-date-picker__row');
        $this->assertHasClass('tedi-date-picker__weeknumber', $html, on: 'tedi-date-picker__weeknumber');
        // ISO weeks of June 2024: 22..26.
        $this->assertStringContainsString('>22</div>', $html);
        $this->assertStringContainsString('>26</div>', $html);
    }

    public function test_calendar_grid_weekdays_start_on_monday(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid />');

        preg_match_all('/class="tedi-date-picker__weekday" role="columnheader">\s*([^\s<]+)/', $html, $m);
        $this->assertSame(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], $m[1]);
    }

    public function test_calendar_grid_uses_the_monday_hardcoded_variable_row_algorithm(): void
    {
        // June 2024 starts on a Saturday: 5 leading + 30 + 0 trailing = 35 = 5 rows.
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" />');
        $this->assertSame(5, substr_count($html, 'class="tedi-date-picker__row'));
        $this->assertSame(35, substr_count($html, 'data-date-key='));

        // September 2024 starts on a Sunday: 6 leading + 30 + 6 trailing = 42 = 6 rows.
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-09-01" />');
        $this->assertSame(6, substr_count($html, 'class="tedi-date-picker__row'));
        $this->assertSame(42, substr_count($html, 'data-date-key='));

        // February 2021 starts on a Monday and has 28 days: exactly 4 rows.
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2021-02-01" />');
        $this->assertSame(4, substr_count($html, 'class="tedi-date-picker__row'));
        $this->assertSame(28, substr_count($html, 'data-date-key='));
    }

    public function test_calendar_grid_always_renders_outside_days(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-09-01" />');

        $this->assertHasClass('tedi-date-picker__day--other-month', $html, on: 'tedi-date-picker__day--other-month');
        $this->assertSame(12, substr_count($html, 'tedi-date-picker__day--other-month'));
    }

    public function test_calendar_grid_selected_day(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" selected="2024-06-15" />');

        $this->assertHasClass('tedi-date-picker__day--selected', $html, on: 'tedi-date-picker__day--selected');
        $this->assertSame(1, substr_count($html, 'tedi-date-picker__day--selected'));
        $this->assertStringContainsString('aria-selected="true"', $html);
    }

    public function test_calendar_grid_today_gets_the_inner_marker_and_aria_current(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid />');

        $this->assertHasClass('tedi-date-picker__today', $html, on: 'tedi-date-picker__today');
        $this->assertStringContainsString('aria-current="date"', $html);
        $this->assertSame(1, substr_count($html, 'aria-current="date"'));
    }

    public function test_calendar_grid_disabled_days(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" :disabled-days="[\'2024-06-10\', \'2024-06-11\']" />');

        $this->assertSame(2, substr_count($html, 'aria-disabled="true"'));
        $this->assertSame(2, substr_count($html, ' disabled'));
    }

    public function test_calendar_grid_tabindex_follows_the_active_date(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" active-date="2024-06-20" />');

        $this->assertSame(1, substr_count($html, 'tabindex="0"'));
        $this->assertSame(34, substr_count($html, 'tabindex="-1"'));
    }

    public function test_calendar_grid_data_date_key_is_milliseconds(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid month="2024-06-01" />');

        $this->assertStringContainsString('data-date-key="'.(strtotime('2024-06-01 00:00:00') * 1000).'"', $html);
    }

    public function test_calendar_grid_forwards_the_grid_id(): void
    {
        $html = Blade::render('<tedi:date-picker-calendar-grid grid-id="dp-grid" />');

        $this->assertStringContainsString('id="dp-grid"', $html);
    }

    // -- date-picker-month-grid ----------------------------------------------

    public function test_month_grid_classes_and_role(): void
    {
        $html = Blade::render('<tedi:date-picker-month-grid current-month="2024-08-01" />');

        $this->assertHasClass('tedi-date-picker__month-year-grid', $html, on: 'tedi-date-picker__month-year-grid');
        $this->assertHasClass('tedi-date-picker__month-year-button', $html, on: 'tedi-date-picker__month-year-button');
        $this->assertStringContainsString('role="group"', $html);
        $this->assertSame(12, substr_count($html, 'class="tedi-date-picker__month-year-button'));
    }

    public function test_month_grid_marks_exactly_the_current_month_selected(): void
    {
        $html = Blade::render('<tedi:date-picker-month-grid current-month="2024-08-01" />');

        $this->assertSame(1, substr_count($html, 'tedi-date-picker__month-year-button--selected'));
        $this->assertMatchesRegularExpression(
            '/tedi-date-picker__month-year-button--selected"[^>]*>\s*Aug/', $html
        );
    }

    public function test_month_grid_disabled_months(): void
    {
        $html = Blade::render('<tedi:date-picker-month-grid :disabled-months="[0, 1]" />');

        $this->assertSame(2, substr_count($html, ' disabled'));
    }

    public function test_month_grid_buttons_are_type_button(): void
    {
        $html = Blade::render('<tedi:date-picker-month-grid />');

        $this->assertSame(12, substr_count($html, 'type="button"'));
    }

    // -- date-picker-year-grid ------------------------------------------------

    public function test_year_grid_classes_and_page(): void
    {
        $html = Blade::render('<tedi:date-picker-year-grid :page-start="2020" :page-size="12" :selected-year="2024" />');

        $this->assertHasClass('tedi-date-picker__month-year-grid', $html, on: 'tedi-date-picker__month-year-grid');
        $this->assertHasClass('tedi-date-picker__month-year-button', $html, on: 'tedi-date-picker__month-year-button');
        $this->assertStringContainsString('role="group"', $html);
        $this->assertSame(12, substr_count($html, 'class="tedi-date-picker__month-year-button'));
        $this->assertMatchesRegularExpression('/>\s*2020\s*</', $html);
        $this->assertMatchesRegularExpression('/>\s*2031\s*</', $html);
    }

    public function test_year_grid_marks_exactly_the_selected_year(): void
    {
        $html = Blade::render('<tedi:date-picker-year-grid :page-start="2020" :selected-year="2024" />');

        $this->assertSame(1, substr_count($html, 'tedi-date-picker__month-year-button--selected'));
        $this->assertMatchesRegularExpression(
            '/tedi-date-picker__month-year-button--selected"[^>]*>\s*2024/', $html
        );
    }

    public function test_year_grid_disabled_years(): void
    {
        $html = Blade::render('<tedi:date-picker-year-grid :page-start="2020" :disabled-years="[2021]" />');

        $this->assertSame(1, substr_count($html, ' disabled'));
    }

    public function test_year_grid_page_size_is_respected(): void
    {
        $html = Blade::render('<tedi:date-picker-year-grid :page-start="2020" :page-size="4" />');

        $this->assertSame(4, substr_count($html, 'class="tedi-date-picker__month-year-button'));
    }

}
