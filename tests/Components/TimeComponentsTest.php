<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class TimeComponentsTest extends TestCase
{
    /** Isolate one element from a rendered fragment so classesOf() inspects it alone. */
    private function element(string $pattern, string $html): string
    {
        $this->assertMatchesRegularExpression($pattern, $html);
        preg_match($pattern, $html, $matches);

        return $matches[0];
    }

    // -- time-picker ------------------------------------------------------

    public function test_time_picker_root_is_a_div_with_the_host_class(): void
    {
        $html = Blade::render('<tedi:time-picker />');

        $this->assertHasClass('tedi-time-picker', $html);
        $this->assertMatchesRegularExpression('/^\s*<div[^>]*class="[^"]*tedi-time-picker/', $html);
    }

    /**
     * CALENDAR-SPEC §0.3: all three variant modifiers are unstyled in
     * dist/tedi.css and are therefore dropped (CONVENTIONS.md §4). `variant`
     * still selects the markup branch — this is deliberate, not a bug.
     */
    public function test_time_picker_variant_classes_are_dropped(): void
    {
        foreach (['scroll', 'slots', 'dropdown'] as $variant) {
            $html = Blade::render('<tedi:time-picker variant="'.$variant.'" />');

            $this->assertMissingClass('tedi-time-picker--scroll', $html, on: 'tedi-time-picker');
            $this->assertMissingClass('tedi-time-picker--slots', $html, on: 'tedi-time-picker');
            $this->assertMissingClass('tedi-time-picker--dropdown', $html, on: 'tedi-time-picker');
        }
    }

    public function test_time_picker_disabled_and_bordered_classes(): void
    {
        $html = Blade::render('<tedi:time-picker />');
        $this->assertMissingClass('tedi-time-picker--disabled', $html, on: 'tedi-time-picker');
        $this->assertMissingClass('tedi-time-picker--bordered', $html, on: 'tedi-time-picker');

        $html = Blade::render('<tedi:time-picker :disabled="true" :border="true" />');
        $this->assertHasClass('tedi-time-picker--disabled', $html, on: 'tedi-time-picker');
        $this->assertHasClass('tedi-time-picker--bordered', $html, on: 'tedi-time-picker');
    }

    public function test_time_picker_scroll_branch_structure(): void
    {
        $html = Blade::render('<tedi:time-picker variant="scroll" />');

        $this->assertHasClass('tedi-time-picker__columns', $html, on: 'tedi-time-picker__columns');
        $this->assertHasClass('tedi-time-picker__separator', $html, on: 'tedi-time-picker__separator');
        $this->assertSame(2, substr_count($html, 'class="tedi-time-picker__column"'));
        $this->assertSame(24, preg_match_all('/id="[^"]*hour-\d+"/', $html));
        $this->assertSame(60, preg_match_all('/id="[^"]*minute-\d+"/', $html));
    }

    public function test_time_picker_minute_step_controls_the_minute_column(): void
    {
        $html = Blade::render('<tedi:time-picker :minute-step="15" />');

        $this->assertSame(4, preg_match_all('/id="[^"]*minute-\d+"/', $html));
        $this->assertStringContainsString('>45</button>', $html);
    }

    /**
     * CALENDAR-SPEC §3.1: `--selected` follows the scroll index, which seeds
     * from `selectedHour() ?? 12` / `floor(selectedMinute / minuteStep)`. With
     * no value Angular parks the wheel on hour 12 / minute index 0.
     */
    public function test_time_picker_parks_on_hour_twelve_when_there_is_no_value(): void
    {
        $html = Blade::render('<tedi:time-picker />');

        $this->assertMatchesRegularExpression('/aria-activedescendant="[^"]*hour-12"/', $html);
        $this->assertMatchesRegularExpression('/aria-activedescendant="[^"]*minute-0"/', $html);

        $twelve = $this->element('/<button[^>]*id="[^"]*hour-12"[^>]*>/', $html);
        $this->assertHasClass('tedi-time-picker__item--selected', $twelve);

        // …and nothing is reported as *selected* to assistive tech.
        $this->assertStringNotContainsString('aria-selected="true"', $html);
    }

    public function test_time_picker_highlights_the_value_when_one_is_given(): void
    {
        $html = Blade::render('<tedi:time-picker value="00:00" />');

        $this->assertMatchesRegularExpression('/aria-activedescendant="[^"]*hour-0"/', $html);
        $this->assertHasClass('tedi-time-picker__item--selected', $html, on: 'tedi-time-picker__item');

        $html = Blade::render('<tedi:time-picker />');
        $this->assertMissingClass('tedi-time-picker__item--selected', $html, on: 'tedi-time-picker__item');
    }

    public function test_time_picker_invalid_value_collapses_to_no_selection(): void
    {
        $html = Blade::render('<tedi:time-picker value="25:99" />');

        $this->assertMatchesRegularExpression('/aria-activedescendant="[^"]*hour-12"/', $html);
        $this->assertStringNotContainsString('aria-selected="true"', $html);
    }

    public function test_time_picker_scroll_aria_disabled_is_the_string_false_when_enabled(): void
    {
        // §0.7 trap 2: Angular binds a raw boolean here, so the attribute is
        // always present. A PHP `false` would be filtered out entirely.
        $html = Blade::render('<tedi:time-picker />');
        $this->assertSame(2, substr_count($html, 'aria-disabled="false"'));
        $this->assertStringContainsString('tabindex="0"', $html);

        $html = Blade::render('<tedi:time-picker :disabled="true" />');
        $this->assertSame(2, substr_count($html, 'aria-disabled="true"'));
        $this->assertStringContainsString('tabindex="-1"', $html);
        $this->assertMatchesRegularExpression('/<button[^>]*\sdisabled/s', $html);
    }

    public function test_time_picker_dropdown_branch(): void
    {
        $html = Blade::render('<tedi:time-picker variant="dropdown" value="13:30" :time-slots="[\'12:30\', \'13:30\']" />');

        $this->assertHasClass('tedi-time-picker__dropdown', $html, on: 'tedi-time-picker__dropdown');

        $selected = $this->element('/<div[^>]*>13:30/', $html);
        $this->assertHasClass('tedi-time-picker__dropdown-item--selected', $selected);
        $this->assertStringContainsString('tabindex="0"', $selected);

        $other = $this->element('/<div[^>]*>12:30/', $html);
        $this->assertMissingClass('tedi-time-picker__dropdown-item--selected', $other);
        $this->assertStringContainsString('tabindex="-1"', $other);
    }

    public function test_time_picker_dropdown_roving_tabindex_falls_back_to_the_first_item(): void
    {
        $html = Blade::render('<tedi:time-picker variant="dropdown" :time-slots="[\'12:30\', \'13:30\']" />');

        $first = $this->element('/<div[^>]*>12:30/', $html);
        $this->assertStringContainsString('tabindex="0"', $first);
    }

    public function test_time_picker_dropdown_aria_disabled_is_omitted_when_enabled(): void
    {
        // Angular uses `isDisabled() ? true : null` here — the opposite of the
        // scroll columns' raw boolean binding.
        $html = Blade::render('<tedi:time-picker variant="dropdown" :time-slots="[\'12:30\']" />');
        $this->assertStringNotContainsString('aria-disabled', $html);

        $html = Blade::render('<tedi:time-picker variant="dropdown" :disabled="true" :time-slots="[\'12:30\']" />');
        $this->assertSame(2, substr_count($html, 'aria-disabled="true"'));
    }

    public function test_time_picker_empty_state_for_slot_variants(): void
    {
        foreach (['slots', 'dropdown'] as $variant) {
            $html = Blade::render('<tedi:time-picker variant="'.$variant.'" />');

            $this->assertHasClass('tedi-time-picker__empty', $html, on: 'tedi-time-picker__empty');
            $this->assertStringContainsString('No times available', $html);
        }
    }

    public function test_time_picker_slots_branch_composes_radio_cards(): void
    {
        $html = Blade::render('<tedi:time-picker variant="slots" value="11:30" :columns="2" :time-slots="[\'11:30\', \'12:00\']" />');

        $this->assertHasClass('tedi-time-picker__grid', $html, on: 'tedi-radio-card-group');
        $this->assertStringContainsString('role="radiogroup"', $html);
        $this->assertStringContainsString('grid-template-columns: repeat(2, 1fr)', $html);
        $this->assertHasClass('tedi-radio-card--secondary', $html, on: 'tedi-radio-card');
        $this->assertSame(2, substr_count($html, 'type="radio"'));
        $this->assertStringContainsString('value="11:30"', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /** §0.3: `tedi-time-picker__slot` is unstyled in dist/tedi.css. */
    public function test_time_picker_slot_class_is_dropped(): void
    {
        $html = Blade::render('<tedi:time-picker variant="slots" :time-slots="[\'11:30\']" />');

        $this->assertMissingClass('tedi-time-picker__slot', $html, on: 'tedi-radio-card');
    }

    public function test_time_picker_slot_indicator_is_hidden_by_default(): void
    {
        $html = Blade::render('<tedi:time-picker variant="slots" :time-slots="[\'11:30\']" />');
        $this->assertHasClass('tedi-radio-card--hide-indicator', $html, on: 'tedi-radio-card');

        $html = Blade::render('<tedi:time-picker variant="slots" :show-slot-indicator="true" :time-slots="[\'11:30\']" />');
        $this->assertMissingClass('tedi-radio-card--hide-indicator', $html, on: 'tedi-radio-card');
    }

    public function test_time_picker_slots_share_a_radio_group_name(): void
    {
        $html = Blade::render('<tedi:time-picker variant="slots" name="shift" :time-slots="[\'11:30\', \'12:00\']" />');

        $this->assertSame(2, substr_count($html, 'name="shift"'));
    }

    public function test_time_picker_merges_consumer_attributes_on_the_root(): void
    {
        $html = Blade::render('<tedi:time-picker class="mine" data-x="1" />');

        $this->assertHasClass('mine', $html, on: 'tedi-time-picker');
        $this->assertStringContainsString('data-x="1"', $html);
    }

    // -- time-field -------------------------------------------------------

    public function test_time_field_root_carries_the_host_class(): void
    {
        $html = Blade::render('<tedi:time-field />');

        $this->assertHasClass('tedi-time-field', $html, on: 'tedi-time-field');
        $this->assertMatchesRegularExpression('/^\s*<div class="tedi-time-field">/', $html);
    }

    public function test_time_field_puts_wire_model_on_the_input(): void
    {
        $html = Blade::render('<tedi:time-field wire:model.live="algus" />');

        $this->assertMatchesRegularExpression(
            '/<input[^>]*wire:model\.live="algus"/', $html,
            'wire:model must land on the <input>, not a wrapper.'
        );
        $this->assertHasClass('tedi-time-field__input', $html, on: 'tedi-time-field__input');
    }

    public function test_time_field_input_base_attributes(): void
    {
        $html = Blade::render('<tedi:time-field input-id="aeg" placeholder="tt:mm" />');

        $input = $this->element('/<input[^>]*>/s', $html);
        $this->assertStringContainsString('type="text"', $input);
        $this->assertStringContainsString('inputmode="numeric"', $input);
        $this->assertStringContainsString('id="aeg"', $input);
        $this->assertStringContainsString('placeholder="tt:mm"', $input);
    }

    public function test_time_field_auto_generates_an_input_id(): void
    {
        $html = Blade::render('<tedi:time-field />');

        $this->assertMatchesRegularExpression('/id="tedi-time-field-[0-9a-f]{8}"/', $html);
    }

    /** BRIEF's value ruling: never emit value="" — it would blank a bound field. */
    public function test_time_field_emits_value_only_when_non_empty(): void
    {
        // Scoped to the <input>: other nested components legitimately emit
        // `value=` (the slots radios) and would make a document-wide assertion
        // pass or fail for the wrong reason.
        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field />'));
        $this->assertStringNotContainsString('value=', $input);

        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field value="13:30" />'));
        $this->assertStringContainsString('value="13:30"', $input);
    }

    public function test_time_field_aria_invalid_is_absent_unless_invalid(): void
    {
        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field />'));
        $this->assertStringNotContainsString('aria-invalid', $input);

        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field :invalid="true" />'));
        $this->assertStringContainsString('aria-invalid="true"', $input);
    }

    public function test_time_field_popover_branch_and_button_trigger_class(): void
    {
        $html = Blade::render('<tedi:time-field />');
        $this->assertStringContainsString('class="tedi-time-field__popover"', $html);
        $this->assertHasClass('tedi-time-field__field--button-trigger', $html, on: 'tedi-time-field__field');

        $html = Blade::render('<tedi:time-field picker-trigger="input" />');
        $this->assertMissingClass('tedi-time-field__field--button-trigger', $html, on: 'tedi-time-field__field');
    }

    public function test_time_field_input_trigger_makes_the_input_readonly(): void
    {
        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field />'));
        $this->assertStringNotContainsString('readonly', $input);

        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field picker-trigger="input" />'));
        $this->assertStringContainsString('readonly', $input);

        // …but not when there is no picker to open.
        $input = $this->element('/<input[^>]*>/s', Blade::render('<tedi:time-field picker-trigger="input" picker-variant="none" />'));
        $this->assertStringNotContainsString('readonly', $input);
    }

    public function test_time_field_open_renders_the_picker_inline(): void
    {
        $html = Blade::render('<tedi:time-field />');
        $this->assertStringNotContainsString('tedi-time-field__popover-content', $html);
        $this->assertStringNotContainsString('aria-expanded', $html);
        $this->assertMissingClass('tedi-time-field__icon--open', $html, on: 'tedi-time-field__icon');

        $html = Blade::render('<tedi:time-field :open="true" />');
        $this->assertHasClass('tedi-time-field__popover-content', $html, on: 'tedi-time-field__popover-content');
        $this->assertStringContainsString('aria-expanded="true"', $html);
        $this->assertHasClass('tedi-time-field__icon--open', $html, on: 'tedi-time-field__icon');
        $this->assertHasClass('tedi-time-picker', $html, on: 'tedi-time-picker');
    }

    public function test_time_field_forwards_picker_props(): void
    {
        $html = Blade::render(
            '<tedi:time-field :open="true" picker-variant="dropdown" value="13:30" :time-slots="[\'12:30\', \'13:30\']" />'
        );

        $this->assertHasClass('tedi-time-picker__dropdown', $html, on: 'tedi-time-picker__dropdown');
        $this->assertStringContainsString('>13:30</div>', $html);
    }

    public function test_time_field_without_a_picker_renders_a_static_icon(): void
    {
        $html = Blade::render('<tedi:time-field picker-variant="none" />');

        $this->assertStringNotContainsString('class="tedi-time-field__popover"', $html);
        $this->assertHasClass('tedi-time-field__icon--static', $html, on: 'tedi-time-field__icon');
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    public function test_time_field_disabled_drops_the_popover_and_disables_the_icon(): void
    {
        $html = Blade::render('<tedi:time-field :disabled="true" />');

        $this->assertStringNotContainsString('class="tedi-time-field__popover"', $html);
        $this->assertMissingClass('tedi-time-field__field--button-trigger', $html, on: 'tedi-time-field__field');
        $this->assertMissingClass('tedi-time-field__icon--static', $html, on: 'tedi-time-field__icon');
        $this->assertMatchesRegularExpression('/<input[^>]*disabled/s', $html);
        $this->assertStringContainsString('tedi-button--neutral', $html);
    }

    public function test_time_field_clear_button_visibility(): void
    {
        $html = Blade::render('<tedi:time-field />');
        $this->assertStringNotContainsString('tedi-time-field__clear', $html);

        $html = Blade::render('<tedi:time-field value="13:30" />');
        $this->assertHasClass('tedi-time-field__clear', $html, on: 'tedi-time-field__clear');
        $this->assertHasClass('tedi-separator--vertical', $html, on: 'tedi-separator');

        $html = Blade::render('<tedi:time-field value="13:30" :clearable="false" />');
        $this->assertStringNotContainsString('tedi-time-field__clear', $html);
    }

    public function test_time_field_icon_is_labelled(): void
    {
        $html = Blade::render('<tedi:time-field />');

        $this->assertStringContainsString('aria-label="Select time"', $html);
        $this->assertStringContainsString('aria-haspopup="dialog"', $html);
        $this->assertStringContainsString('>schedule</tedi-icon>', $html);
    }
}
