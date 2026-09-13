<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class DateFieldComponentsTest extends TestCase
{
    /** Isolate one element from a rendered fragment so classesOf() inspects it alone. */
    private function element(string $pattern, string $html): string
    {
        $this->assertMatchesRegularExpression($pattern, $html);
        preg_match($pattern, $html, $matches);

        return $matches[0];
    }

    /**
     * Every class token emitted anywhere in the fragment, de-duplicated.
     *
     * Token-based, never substring: "tedi-date-input__tags" is a prefix of
     * "tedi-date-input__tags-counter" (CONVENTIONS.md §10).
     *
     * @return string[]
     */
    private function allClasses(string $html): array
    {
        preg_match_all('/(?<![-:.\w])class="([^"]*)"/', $html, $matches);

        $tokens = [];

        foreach ($matches[1] as $classAttr) {
            foreach (preg_split('/\s+/', trim($classAttr), -1, PREG_SPLIT_NO_EMPTY) as $token) {
                $tokens[$token] = true;
            }
        }

        return array_keys($tokens);
    }

    private function assertRendersClass(string $class, string $html): void
    {
        $this->assertContains($class, $this->allClasses($html), sprintf(
            'Expected some element to carry [%s].', $class
        ));
    }

    private function assertRendersNoClass(string $class, string $html): void
    {
        $this->assertNotContains($class, $this->allClasses($html), sprintf(
            'Did not expect any element to carry [%s].', $class
        ));
    }

    /** A rendered <tedi:date-input> with `count` tags in multiple mode. */
    private function tags(int $count): string
    {
        $tags = [];

        for ($i = 1; $i <= $count; $i++) {
            $tags[] = "['id' => '{$i}', 'label' => '0{$i}.06.2026']";
        }

        return '['.implode(', ', $tags).']';
    }

    // -- date-input -------------------------------------------------------

    public function test_date_input_root_is_a_div_with_the_host_class(): void
    {
        $html = Blade::render('<tedi:date-input />');

        $this->assertHasClass('tedi-date-input', $html, on: 'tedi-date-input');
        $this->assertMatchesRegularExpression('/^\s*<div[^>]*class="[^"]*tedi-date-input/', $html);
    }

    /**
     * CALENDAR-SPEC §0.3: dist/tedi.css ships no rule for either modifier, so
     * both are dropped (CONVENTIONS.md §4). The states themselves still render
     * as the native `disabled` / `readonly` attributes.
     */
    public function test_date_input_disabled_and_readonly_modifiers_are_dropped(): void
    {
        $html = Blade::render('<tedi:date-input :disabled="true" />');
        $this->assertRendersNoClass('tedi-date-input--disabled', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*\sdisabled/', $html);

        $html = Blade::render('<tedi:date-input :read-only="true" />');
        $this->assertRendersNoClass('tedi-date-input--readonly', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*\sreadonly/', $html);
    }

    public function test_date_input_tag_layout_classes(): void
    {
        // No tags at all → none of the tag modifiers.
        $html = Blade::render('<tedi:date-input />');
        foreach (['with-tags', 'tags-wrap', 'tags-single-row', 'tags-measuring'] as $modifier) {
            $this->assertMissingClass('tedi-date-input--'.$modifier, $html, on: 'tedi-date-input');
        }

        // Tags outside `multiple` mode are not tags — hasTags() is mode-gated.
        $html = Blade::render('<tedi:date-input :tags="'.$this->tags(2).'" />');
        $this->assertMissingClass('tedi-date-input--with-tags', $html, on: 'tedi-date-input');

        // multiple + multiRow (default) → wrap.
        $html = Blade::render('<tedi:date-input mode="multiple" :tags="'.$this->tags(2).'" />');
        $this->assertHasClass('tedi-date-input--with-tags', $html, on: 'tedi-date-input');
        $this->assertHasClass('tedi-date-input--tags-wrap', $html, on: 'tedi-date-input');
        $this->assertMissingClass('tedi-date-input--tags-single-row', $html, on: 'tedi-date-input');
        $this->assertMissingClass('tedi-date-input--tags-measuring', $html, on: 'tedi-date-input');

        // Single row, unmeasured → the measuring state, every tag rendered.
        $html = Blade::render('<tedi:date-input mode="multiple" :multi-row="false" :tags="'.$this->tags(3).'" />');
        $this->assertHasClass('tedi-date-input--tags-single-row', $html, on: 'tedi-date-input');
        $this->assertHasClass('tedi-date-input--tags-measuring', $html, on: 'tedi-date-input');
        $this->assertMissingClass('tedi-date-input--tags-wrap', $html, on: 'tedi-date-input');
        $this->assertSame(3, substr_count($html, 'tedi-tag__content'));
        $this->assertRendersNoClass('tedi-date-input__tags-counter', $html);

        // Single row, measured → the slice plus a +N counter.
        $html = Blade::render('<tedi:date-input mode="multiple" :multi-row="false" :visible-tag-count="1" :tags="'.$this->tags(3).'" />');
        $this->assertMissingClass('tedi-date-input--tags-measuring', $html, on: 'tedi-date-input');
        $this->assertRendersClass('tedi-date-input__tags-counter', $html);
        $this->assertStringContainsString('+2', $html);
    }

    public function test_date_input_counter_is_absent_when_every_tag_is_visible(): void
    {
        $html = Blade::render('<tedi:date-input mode="multiple" :multi-row="false" :visible-tag-count="3" :tags="'.$this->tags(3).'" />');

        $this->assertRendersNoClass('tedi-date-input__tags-counter', $html);
    }

    public function test_date_input_tags_are_closable_only_when_editable(): void
    {
        $html = Blade::render('<tedi:date-input mode="multiple" :tags="'.$this->tags(1).'" />');
        $this->assertRendersClass('tedi-tag--closable', $html);

        foreach (['disabled', 'read-only', 'removable="false"'] as $off) {
            $attr = $off === 'removable="false"' ? ':removable="false"' : ':'.$off.'="true"';
            $html = Blade::render('<tedi:date-input mode="multiple" '.$attr.' :tags="'.$this->tags(1).'" />');
            $this->assertRendersNoClass('tedi-tag--closable', $html);
        }
    }

    /**
     * The control is <tedi:text-field>, not a hand-rolled <input>: Angular's
     * selector is the attribute `input[tedi-text-field]` while the vendored SCSS
     * styles the class `.tedi-text-field`, and only composing emits both.
     */
    public function test_date_input_composes_text_field_and_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:date-input class="my-own" />');
        $input = $this->element('/<input\b[^>]*>/s', $html);

        $this->assertStringContainsString('tedi-text-field', $this->element('/<input\s+tedi-text-field/', $html));
        $this->assertHasClass('tedi-text-field', $input);
        $this->assertHasClass('tedi-date-input__input', $input);
        $this->assertHasClass('my-own', $input);
    }

    /** CONVENTIONS.md §6 native-control carve-out: the bag lands on the <input>. */
    public function test_date_input_forwards_attributes_to_the_control(): void
    {
        $html = Blade::render('<tedi:date-input wire:model="selectedDate" />');
        $input = $this->element('/<input\b[^>]*>/s', $html);

        $this->assertStringContainsString('wire:model="selectedDate"', $input);
        $this->assertStringNotContainsString('wire:model', $this->element('/<div[^>]*>/', $html));
    }

    public function test_date_input_emits_value_only_when_non_empty(): void
    {
        $html = Blade::render('<tedi:date-input />');
        $this->assertStringNotContainsString('value=', $this->element('/<input\b[^>]*>/s', $html));

        $html = Blade::render('<tedi:date-input value="05.06.2026" />');
        $this->assertStringContainsString('value="05.06.2026"', $this->element('/<input\b[^>]*>/s', $html));
    }

    public function test_date_input_placeholder_and_required_attributes(): void
    {
        $html = Blade::render('<tedi:date-input />');
        $input = $this->element('/<input\b[^>]*>/s', $html);
        $this->assertStringNotContainsString('placeholder', $input);
        $this->assertStringNotContainsString('required', $input);

        $html = Blade::render('<tedi:date-input placeholder="pp.kk.aaaa" :required="true" />');
        $input = $this->element('/<input\b[^>]*>/s', $html);
        $this->assertStringContainsString('placeholder="pp.kk.aaaa"', $input);
        $this->assertMatchesRegularExpression('/\srequired/', $input);
    }

    public function test_date_input_generates_an_input_id_when_none_is_given(): void
    {
        $html = Blade::render('<tedi:date-input />');
        $this->assertMatchesRegularExpression('/id="tedi-date-input-[0-9a-f]+"/', $html);

        $html = Blade::render('<tedi:date-input input-id="mine" />');
        $this->assertStringContainsString('id="mine"', $html);
    }

    public function test_date_input_clear_button_visibility(): void
    {
        // clearable is off by default, even with a value.
        $html = Blade::render('<tedi:date-input value="05.06.2026" />');
        $this->assertRendersNoClass('tedi-date-input__clear', $html);

        // clearable with a value.
        $html = Blade::render('<tedi:date-input value="05.06.2026" :clearable="true" />');
        $this->assertRendersClass('tedi-date-input__clear', $html);
        $this->assertRendersClass('tedi-separator--vertical', $html);

        // clearable with tags but no text value.
        $html = Blade::render('<tedi:date-input mode="multiple" :clearable="true" :tags="'.$this->tags(1).'" />');
        $this->assertRendersClass('tedi-date-input__clear', $html);

        // ...but never while disabled, read-only, or empty.
        foreach ([':disabled="true"', ':read-only="true"'] as $off) {
            $html = Blade::render('<tedi:date-input value="05.06.2026" :clearable="true" '.$off.' />');
            $this->assertRendersNoClass('tedi-date-input__clear', $html);
        }

        $html = Blade::render('<tedi:date-input :clearable="true" />');
        $this->assertRendersNoClass('tedi-date-input__clear', $html);
    }

    public function test_date_input_icon_button(): void
    {
        $html = Blade::render('<tedi:date-input />');
        $button = $this->element('/<button[^>]*tedi-date-input__icon[^>]*>/s', $html);

        $this->assertHasClass('tedi-date-input__icon', $button);
        $this->assertMissingClass('tedi-date-input__icon--active', $button);
        // Angular binds a raw boolean, so the attribute is always present.
        $this->assertStringContainsString('aria-expanded="false"', $button);
        $this->assertStringNotContainsString('disabled', $button);
        $this->assertStringContainsString('type="button"', $button);

        $html = Blade::render('<tedi:date-input :icon-active="true" />');
        $button = $this->element('/<button[^>]*tedi-date-input__icon[^>]*>/s', $html);
        $this->assertHasClass('tedi-date-input__icon--active', $button);
        $this->assertStringContainsString('aria-expanded="true"', $button);

        foreach ([':icon-disabled="true"', ':disabled="true"'] as $off) {
            $html = Blade::render('<tedi:date-input '.$off.' />');
            $button = $this->element('/<button[^>]*tedi-date-input__icon[^>]*>/s', $html);
            $this->assertStringContainsString('disabled', $button);
        }
    }

    public function test_date_input_structural_classes(): void
    {
        $html = Blade::render('<tedi:date-input mode="multiple" :clearable="true" :tags="'.$this->tags(1).'" />');

        foreach (['field', 'tags', 'input', 'actions', 'clear', 'icon'] as $element) {
            $this->assertRendersClass('tedi-date-input__'.$element, $html);
        }
    }

    // -- date-field -------------------------------------------------------

    public function test_date_field_root_is_a_div_with_the_host_class(): void
    {
        $html = Blade::render('<tedi:date-field />');

        $this->assertHasClass('tedi-date-field', $html, on: 'tedi-date-field');
        $this->assertMatchesRegularExpression('/^\s*<div[^>]*class="[^"]*tedi-date-field/', $html);
    }

    /** date-field is single-root, so the bag stays on the root (CALENDAR-SPEC §0.11). */
    public function test_date_field_merges_attributes_on_the_root(): void
    {
        $html = Blade::render('<tedi:date-field class="my-own" data-test="x" />');
        $root = $this->element('/<div[^>]*>/', $html);

        $this->assertHasClass('tedi-date-field', $root);
        $this->assertHasClass('my-own', $root);
        $this->assertStringContainsString('data-test="x"', $root);
    }

    public function test_date_field_renders_a_date_input(): void
    {
        $html = Blade::render('<tedi:date-field />');

        $this->assertRendersClass('tedi-date-input', $html);
        $this->assertRendersClass('tedi-date-input__input', $html);
    }

    /**
     * CALENDAR-SPEC §4.1: the CdkConnectedOverlay is dropped, so the panel
     * renders inline under an explicit `open` prop.
     */
    public function test_date_field_overlay_requires_open_and_enable_calendar(): void
    {
        $html = Blade::render('<tedi:date-field />');
        $this->assertRendersNoClass('tedi-date-field__overlay', $html);
        $this->assertRendersNoClass('tedi-calendar', $html);

        $html = Blade::render('<tedi:date-field :open="true" :enable-calendar="false" />');
        $this->assertRendersNoClass('tedi-date-field__overlay', $html);

        $html = Blade::render('<tedi:date-field :open="true" />');
        $this->assertRendersClass('tedi-date-field__overlay', $html);
        $this->assertRendersClass('tedi-calendar', $html);

        $overlay = $this->element('/<div class="tedi-date-field__overlay"[^>]*>/', $html);
        $this->assertStringContainsString('role="dialog"', $overlay);
        $this->assertStringContainsString('aria-label="Choose date"', $overlay);
    }

    /** Angular passes [bordered]="false" into the overlay-mounted calendar. */
    public function test_date_field_calendar_is_borderless(): void
    {
        $html = Blade::render('<tedi:date-field :open="true" />');

        $this->assertMissingClass('tedi-calendar--bordered', $html, on: 'tedi-calendar');
    }

    public function test_date_field_forwards_calendar_options(): void
    {
        $html = Blade::render('<tedi:date-field :open="true" :show-week-numbers="true" :number-of-months="2" />');

        $this->assertHasClass('tedi-calendar--with-week-numbers', $html, on: 'tedi-calendar');
        $this->assertStringContainsString('--_tedi-calendar-month-count: 2', $html);
        $this->assertSame(2, substr_count($html, 'class="tedi-calendar__month"'));
    }

    /** CALENDAR-SPEC §0.3: `tedi-calendar--multi-month` is unstyled and dropped. */
    public function test_date_field_multi_month_class_is_dropped(): void
    {
        $html = Blade::render('<tedi:date-field :open="true" :number-of-months="2" />');

        $this->assertRendersNoClass('tedi-calendar--multi-month', $html);
    }

    /**
     * Angular 8 paints size modifiers on the date-field host so standalone
     * fields get the field-surface height tokens.
     */
    public function test_date_field_size_classes(): void
    {
        $default = Blade::render('<tedi:date-field />');
        $this->assertRendersNoClass('tedi-date-field--small', $default);
        $this->assertRendersNoClass('tedi-date-field--large', $default);

        $small = Blade::render('<tedi:date-field size="small" />');
        $this->assertHasClass('tedi-date-field--small', $small, on: 'tedi-date-field');
    }

    public function test_date_field_input_trigger_makes_the_input_read_only(): void
    {
        $html = Blade::render('<tedi:date-field />');
        $this->assertDoesNotMatchRegularExpression('/<input[^>]*\sreadonly/', $html);

        $html = Blade::render('<tedi:date-field calendar-trigger="input" />');
        $this->assertMatchesRegularExpression('/<input[^>]*\sreadonly/', $html);

        // No calendar means no trigger, so the input stays editable.
        $html = Blade::render('<tedi:date-field calendar-trigger="input" :enable-calendar="false" />');
        $this->assertDoesNotMatchRegularExpression('/<input[^>]*\sreadonly/', $html);
    }

    public function test_date_field_disables_the_icon_when_the_calendar_is_off(): void
    {
        $html = Blade::render('<tedi:date-field :enable-calendar="false" />');
        $button = $this->element('/<button[^>]*tedi-date-input__icon[^>]*>/s', $html);

        $this->assertStringContainsString('disabled', $button);
    }

    public function test_date_field_clear_button_follows_can_clear(): void
    {
        $html = Blade::render('<tedi:date-field />');
        $this->assertRendersNoClass('tedi-date-input__clear', $html);

        $html = Blade::render('<tedi:date-field value="2026-06-05" display="05.06.2026" />');
        $this->assertRendersClass('tedi-date-input__clear', $html);

        foreach ([':input-disabled="true"', ':read-only="true"'] as $off) {
            $html = Blade::render('<tedi:date-field value="2026-06-05" display="05.06.2026" '.$off.' />');
            $this->assertRendersNoClass('tedi-date-input__clear', $html);
        }
    }

    public function test_date_field_display_and_tags_reach_the_input(): void
    {
        $html = Blade::render('<tedi:date-field display="05.06.2026" />');
        $this->assertStringContainsString('value="05.06.2026"', $this->element('/<input\b[^>]*>/s', $html));

        $html = Blade::render('<tedi:date-field mode="multiple" :tags="'.$this->tags(2).'" />');
        $this->assertHasClass('tedi-date-input--with-tags', $html, on: 'tedi-date-input');
        $this->assertStringContainsString('01.06.2026', $html);
    }

    /**
     * `.tedi-calendar__footer` has `&:empty { display: none }`, so the forwarded
     * slot must not introduce whitespace when no footer was passed.
     */
    public function test_date_field_footer_slot(): void
    {
        $html = Blade::render('<tedi:date-field :open="true" />');
        $this->assertStringContainsString('<div class="tedi-calendar__footer"></div>', $html);

        $html = Blade::render('<tedi:date-field :open="true"><x-slot:footer>Vali kellaaeg</x-slot:footer></tedi:date-field>');
        $this->assertStringContainsString('<div class="tedi-calendar__footer">Vali kellaaeg</div>', $html);
    }

    // -- prop unions --------------------------------------------------------

    /**
     * `DateFieldMode = "single" | "multiple" | "range"`. Only `multiple` renders
     * tags — hasTags() is mode-gated on both components.
     */
    public function test_mode_union_gates_the_tag_markup_on_both_components(): void
    {
        foreach (['date-input', 'date-field'] as $tag) {
            $tagsProp = $tag === 'date-field' ? ':tags' : ':tags';

            foreach (['single', 'range'] as $mode) {
                $html = Blade::render('<tedi:'.$tag.' mode="'.$mode.'" '.$tagsProp.'="'.$this->tags(2).'" />');
                $this->assertMissingClass('tedi-date-input--with-tags', $html, on: 'tedi-date-input');
                $this->assertRendersNoClass('tedi-date-input__tags', $html);
            }

            $html = Blade::render('<tedi:'.$tag.' mode="multiple" '.$tagsProp.'="'.$this->tags(2).'" />');
            $this->assertHasClass('tedi-date-input--with-tags', $html, on: 'tedi-date-input');
            $this->assertRendersClass('tedi-date-input__tags', $html);
        }
    }

    /**
     * `TagEllipsis = false | "start" | "end"`, reached through date-input's
     * `ellipsis` and date-field's `tagEllipsis`. <tedi:tag> emits the single
     * modifier `tedi-tag--ellipsis` and picks the side on the inner
     * <tedi:ellipsis>, so the side is pinned on `tedi-ellipsis__content`.
     */
    public function test_tag_ellipsis_union_reaches_the_tags(): void
    {
        foreach (['date-input' => 'ellipsis', 'date-field' => 'tag-ellipsis'] as $tag => $prop) {
            $html = Blade::render('<tedi:'.$tag.' mode="multiple" :'.$prop.'="false" :tags="'.$this->tags(1).'" />');
            $this->assertRendersNoClass('tedi-tag--ellipsis', $html);
            $this->assertRendersNoClass('tedi-ellipsis', $html);

            $start = Blade::render('<tedi:'.$tag.' mode="multiple" '.$prop.'="start" :tags="'.$this->tags(1).'" />');
            $this->assertRendersClass('tedi-tag--ellipsis', $start);
            $this->assertHasClass('tedi-ellipsis__content--start', $start, on: 'tedi-ellipsis__content');

            $end = Blade::render('<tedi:'.$tag.' mode="multiple" '.$prop.'="end" :tags="'.$this->tags(1).'" />');
            $this->assertRendersClass('tedi-tag--ellipsis', $end);
            $this->assertMissingClass('tedi-ellipsis__content--start', $end, on: 'tedi-ellipsis__content');
        }
    }

    /** `calendarTrigger = "button" | "input"` — both values render the icon button. */
    public function test_calendar_trigger_union(): void
    {
        foreach (['button', 'input'] as $trigger) {
            $html = Blade::render('<tedi:date-field calendar-trigger="'.$trigger.'" />');
            $this->assertRendersClass('tedi-date-input__icon', $html);
        }
    }

    /** `monthYearSelectType = "dropdown" | "grid"` reaches the overlay's header. */
    public function test_month_year_select_type_union_reaches_the_calendar_header(): void
    {
        $dropdown = Blade::render('<tedi:date-field :open="true" month-year-select-type="dropdown" />');
        $this->assertRendersClass('tedi-calendar-header__select', $dropdown);
        $this->assertRendersNoClass('tedi-calendar-header__label-button', $dropdown);

        $grid = Blade::render('<tedi:date-field :open="true" month-year-select-type="grid" />');
        $this->assertRendersClass('tedi-calendar-header__label-button', $grid);
        $this->assertRendersNoClass('tedi-calendar-header__select', $grid);
    }

    /**
     * `selectionLevel: CalendarView = "days" | "months" | "years"` seeds the
     * calendar's `view`, matching Angular's
     * `effect(() => this.view.set(this.selectionLevel()))` — the grid at first
     * paint is always the selectionLevel one.
     */
    public function test_selection_level_union_seeds_the_calendar_view(): void
    {
        $grids = [
            'days' => 'tedi-calendar-day-grid',
            'months' => 'tedi-calendar-month-grid',
            'years' => 'tedi-calendar-year-grid',
        ];

        foreach ($grids as $level => $grid) {
            $html = Blade::render('<tedi:date-field :open="true" selection-level="'.$level.'" />');

            $this->assertRendersClass($grid, $html);

            foreach (array_diff($grids, [$grid]) as $other) {
                $this->assertRendersNoClass($other, $html);
            }
        }
    }

    /**
     * An explicit `view` outranks `selectionLevel` — but only on <tedi:calendar>,
     * which is where `view` is a prop. That precedence is what keeps the
     * MonthView / YearView calendar stories expressible.
     */
    public function test_an_explicit_view_outranks_selection_level_on_the_calendar(): void
    {
        $html = Blade::render('<tedi:calendar view="days" selection-level="years" />');

        $this->assertRendersClass('tedi-calendar-day-grid', $html);
        $this->assertRendersNoClass('tedi-calendar-year-grid', $html);
    }

    /**
     * <tedi:date-field> deliberately has NO `view` prop — Angular's date-field
     * has no such input either, so the overlay's opening grid is `selectionLevel`
     * and nothing else. Consequence worth pinning: `view` written on the field is
     * not a prop, so it falls into the attribute bag and lands on the root <div>
     * as a stray HTML attribute while the grid stays on `selectionLevel`. Anyone
     * reaching for `<tedi:date-field view="...">` is writing a no-op.
     */
    public function test_date_field_has_no_view_prop_so_selection_level_always_wins(): void
    {
        $html = Blade::render('<tedi:date-field :open="true" view="days" selection-level="years" />');

        $this->assertRendersClass('tedi-calendar-year-grid', $html);
        $this->assertRendersNoClass('tedi-calendar-day-grid', $html);
        // It leaked onto the root rather than reaching the calendar.
        $this->assertStringContainsString('view="days"', $this->element('/<div[^>]*>/', $html));
    }

    /** showOutsideDays / showWeekNumbers / inputDisabled / disabledDays forwarding. */
    public function test_date_field_forwards_the_remaining_calendar_flags(): void
    {
        $html = Blade::render('<tedi:date-field :open="true" :current-month="\'2030-05-01\'" :show-outside-days="false" />');
        $this->assertRendersNoClass('tedi-calendar-day-grid__day--outside', $html);

        $html = Blade::render('<tedi:date-field :open="true" :current-month="\'2030-05-01\'" :show-outside-days="true" />');
        $this->assertRendersClass('tedi-calendar-day-grid__day--outside', $html);

        $html = Blade::render('<tedi:date-field :open="true" :input-disabled="true" />');
        $this->assertHasClass('tedi-calendar--disabled', $html, on: 'tedi-calendar');

        $html = Blade::render(
            '<tedi:date-field :open="true" :current-month="\'2030-05-01\'" :disabled-days="[\'2030-05-10\', \'2030-05-11\']" />'
        );
        $this->assertSame(2, substr_count($html, 'tedi-calendar-day-grid__day--disabled'));
    }

    /** CONVENTIONS.md §6 carve-out, reached through the parent: the bag stays on the root. */
    public function test_date_field_keeps_its_attribute_bag_on_the_root(): void
    {
        $html = Blade::render('<tedi:date-field wire:model="birthday" />');

        $this->assertStringContainsString('wire:model="birthday"', $this->element('/<div[^>]*>/', $html));
        $this->assertStringNotContainsString('wire:model', $this->element('/<input\b[^>]*>/s', $html));
    }
}
