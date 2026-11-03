<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class FormTextComponentsTest extends TestCase
{
    // -- text-field ---------------------------------------------------------

    public function test_text_field_renders_a_native_input_with_the_literal_selector_attribute(): void
    {
        $html = Blade::render('<tedi:text-field />');

        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field/', $html);
        $this->assertHasClass('tedi-text-field', $html);
    }

    public function test_text_field_hides_arrows_by_default(): void
    {
        $html = Blade::render('<tedi:text-field />');

        $this->assertHasClass('tedi-text-field--arrows-hidden', $html);
    }

    public function test_text_field_arrows_hidden_false_drops_the_modifier(): void
    {
        $html = Blade::render('<tedi:text-field :arrows-hidden="false" />');

        $this->assertHasClass('tedi-text-field', $html);
        $this->assertMissingClass('tedi-text-field--arrows-hidden', $html);
    }

    public function test_text_field_omits_the_value_attribute_when_empty(): void
    {
        foreach (['<tedi:text-field />', '<tedi:text-field value="" />'] as $template) {
            $this->assertStringNotContainsString('value=', Blade::render($template));
        }
    }

    public function test_text_field_emits_the_value_attribute_when_non_empty(): void
    {
        $this->assertStringContainsString('value="Tekst"', Blade::render('<tedi:text-field value="Tekst" />'));
        // "0" is a legitimate value and must survive the empty check.
        $this->assertStringContainsString('value="0"', Blade::render('<tedi:text-field value="0" />'));
    }

    public function test_text_field_aria_invalid(): void
    {
        $this->assertStringNotContainsString('aria-invalid', Blade::render('<tedi:text-field />'));
        $this->assertStringContainsString('aria-invalid="true"', Blade::render('<tedi:text-field :invalid="true" />'));
    }

    public function test_text_field_disabled(): void
    {
        $this->assertStringContainsString('disabled', Blade::render('<tedi:text-field :disabled="true" />'));
        $this->assertStringNotContainsString('disabled', Blade::render('<tedi:text-field />'));
    }

    public function test_text_field_forwards_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:text-field wire:model="query" />');

        $this->assertStringContainsString('wire:model="query"', $html);
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*wire:model="query"/s', $html);
    }

    public function test_text_field_consumer_class_merges_instead_of_replacing(): void
    {
        $html = Blade::render('<tedi:text-field class="custom" />');

        $this->assertHasClass('tedi-text-field', $html);
        $this->assertHasClass('custom', $html);
    }

    /**
     * Angular 8.0 (#612): standalone, the field paints its own surface.
     */
    public function test_text_field_paints_its_own_surface_when_standalone(): void
    {
        $html = Blade::render('<tedi:text-field />');
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-text-field');
        $this->assertMissingClass('tedi-field-surface--valid', $html, on: 'tedi-text-field');
    }

    public function test_text_field_size_classes(): void
    {
        $this->assertHasClass('tedi-text-field--small', Blade::render('<tedi:text-field size="small" />'), on: 'tedi-text-field');
        $this->assertHasClass('tedi-text-field--large', Blade::render('<tedi:text-field size="large" />'), on: 'tedi-text-field');

        $html = Blade::render('<tedi:text-field />');
        $this->assertMissingClass('tedi-text-field--small', $html, on: 'tedi-text-field');
        $this->assertMissingClass('tedi-text-field--large', $html, on: 'tedi-text-field');
    }

    public function test_text_field_reads_the_form_field_context(): void
    {
        // A bare field (no clear button, no icon) keeps painting the surface,
        // takes the field's size, and its `valid`.
        $html = Blade::render('<tedi:form-field size="large" :valid="true" :clearable="false"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-text-field');
        $this->assertHasClass('tedi-field-surface--valid', $html, on: 'tedi-text-field');
        $this->assertHasClass('tedi-text-field--large', $html, on: 'tedi-text-field');

        // Its own size wins over the field's.
        $html = Blade::render('<tedi:form-field size="large"><tedi:text-field size="small" /></tedi:form-field>');
        $this->assertHasClass('tedi-text-field--small', $html, on: 'tedi-text-field');

        // A box (the default clear button) owns the surface.
        $html = Blade::render('<tedi:form-field :valid="true"><tedi:text-field /></tedi:form-field>');
        $this->assertMissingClass('tedi-field-surface', $html, on: 'tedi-text-field');
        $this->assertMissingClass('tedi-field-surface--valid', $html, on: 'tedi-text-field');

        // An invalid field marks the control.
        $html = Blade::render('<tedi:form-field :invalid="true"><tedi:text-field /></tedi:form-field>');
        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_text_field_inside_a_date_field_never_paints_the_surface(): void
    {
        // Angular's date field provides a child context whose ownsSurface is
        // always true: the date field paints it, not the input.
        $html = Blade::render('<tedi:date-field input-id="d" />');
        $this->assertMissingClass('tedi-field-surface', $html, on: 'tedi-text-field');

        // Nor does it offer the enclosing form-field a clear button: the date
        // field owns its own.
        $html = Blade::render('<tedi:form-field><tedi:date-field input-id="d" value="2026-01-01" /></tedi:form-field>');
        $this->assertStringNotContainsString('tedi-form-field__buttons', $html);
        $this->assertMissingClass('tedi-field-surface', $html, on: 'tedi-text-field');

        // A standalone date input is not inside a date field, so its text field
        // paints its own surface, as Angular's would.
        $html = Blade::render('<tedi:date-input />');
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-text-field');
    }

    public function test_field_context_counts_only_string_values(): void
    {
        // A control registering an array (a multiple / range date field) must
        // not break the character counter.
        $html = Blade::render('<tedi:form-field :character-limit="5"><tedi:date-field input-id="d" mode="multiple" :value="[\'2026-01-01\', \'2026-01-02\']" /></tedi:form-field>');
        $this->assertMatchesRegularExpression('/>0\/5<\/span>/', $html);
    }

    public function test_text_field_readonly(): void
    {
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field\s+readonly/', Blade::render('<tedi:text-field readonly />'));
        $this->assertStringNotContainsString('readonly', Blade::render('<tedi:text-field />'));
    }

    // -- textarea -----------------------------------------------------------

    public function test_textarea_renders_a_native_textarea_with_the_literal_selector_attribute(): void
    {
        $html = Blade::render('<tedi:textarea />');

        $this->assertMatchesRegularExpression('/<textarea\s+tedi-textarea/', $html);
        $this->assertHasClass('tedi-textarea', $html);
    }

    public function test_textarea_resizable_modifier(): void
    {
        $resizable = Blade::render('<tedi:textarea />');
        $this->assertMissingClass('tedi-textarea--not-resizable', $resizable);

        $fixed = Blade::render('<tedi:textarea :resizable="false" />');
        $this->assertHasClass('tedi-textarea--not-resizable', $fixed);
    }

    public function test_textarea_auto_grow_modifier(): void
    {
        $this->assertMissingClass('tedi-textarea--auto-grow', Blade::render('<tedi:textarea />'));
        $this->assertHasClass('tedi-textarea--auto-grow', Blade::render('<tedi:textarea :auto-grow="true" />'));
    }

    /**
     * Angular 9.0 (#751): no fixed `height` default and no `maxRows` default of
     * 12; `minRows` (3) is the resting height in every mode.
     */
    public function test_textarea_default_height_style(): void
    {
        $html = Blade::render('<tedi:textarea />');

        $this->assertStringContainsString('min-height: calc(3 * 1lh + 2 * var(--_field-padding-y));', $html);
        $this->assertDoesNotMatchRegularExpression('/[^-]height: /', $html);
        $this->assertStringNotContainsString('max-height', $html);
    }

    public function test_textarea_numeric_height_becomes_pixels(): void
    {
        $this->assertStringContainsString('height: 200px;', Blade::render('<tedi:textarea :height="200" />'));
    }

    public function test_textarea_min_rows_floor_is_one(): void
    {
        $this->assertStringContainsString(
            'min-height: calc(1 * 1lh + 2 * var(--_field-padding-y));', Blade::render('<tedi:textarea :min-rows="0" />')
        );
    }

    public function test_textarea_auto_grow_ignores_height(): void
    {
        $html = Blade::render('<tedi:textarea :auto-grow="true" height="7.5rem" />');

        $this->assertStringNotContainsString('height: 7.5rem', $html);
        $this->assertStringContainsString('min-height: calc(3 * 1lh + 2 * var(--_field-padding-y));', $html);
        // No row limit unless one is set.
        $this->assertStringNotContainsString('max-height', $html);
    }

    public function test_textarea_max_rows_caps_in_every_mode(): void
    {
        foreach (['<tedi:textarea :auto-grow="true" :min-rows="5" :max-rows="8" />', '<tedi:textarea :min-rows="5" :max-rows="8" />'] as $template) {
            $html = Blade::render($template);

            $this->assertStringContainsString('min-height: calc(5 * 1lh + 2 * var(--_field-padding-y));', $html);
            $this->assertStringContainsString('max-height: calc(8 * 1lh + 2 * var(--_field-padding-y));', $html);
        }
    }

    public function test_textarea_max_height_alone_is_not_wrapped_in_min(): void
    {
        $html = Blade::render('<tedi:textarea max-height="12rem" />');

        $this->assertStringContainsString('max-height: 12rem;', $html);
        $this->assertStringNotContainsString('min(', $html);
    }

    public function test_textarea_max_rows_and_max_height_combine_with_min(): void
    {
        $html = Blade::render('<tedi:textarea :max-rows="12" max-height="200px" />');

        $this->assertStringContainsString(
            'max-height: min(calc(12 * 1lh + 2 * var(--_field-padding-y)), 200px);', $html
        );
    }

    public function test_textarea_numeric_max_height_becomes_pixels(): void
    {
        $this->assertStringContainsString('max-height: 12px;', Blade::render('<tedi:textarea :max-height="12" />'));
    }

    public function test_textarea_uses_the_slot_when_value_is_empty(): void
    {
        $this->assertStringContainsString('>Kirjuta siia</textarea>', Blade::render('<tedi:textarea>Kirjuta siia</tedi:textarea>'));
    }

    public function test_textarea_value_wins_over_the_slot(): void
    {
        $html = Blade::render('<tedi:textarea value="Väärtus">Slot</tedi:textarea>');

        $this->assertStringContainsString('>Väärtus</textarea>', $html);
        $this->assertStringNotContainsString('Slot', $html);
    }

    public function test_textarea_never_emits_a_value_attribute(): void
    {
        $this->assertStringNotContainsString('value=', Blade::render('<tedi:textarea value="Väärtus" />'));
    }

    public function test_textarea_aria_invalid(): void
    {
        $this->assertStringNotContainsString('aria-invalid', Blade::render('<tedi:textarea />'));
        $this->assertStringContainsString('aria-invalid="true"', Blade::render('<tedi:textarea :invalid="true" />'));
    }

    public function test_textarea_surface_and_size(): void
    {
        $html = Blade::render('<tedi:textarea />');
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-textarea');

        $this->assertHasClass('tedi-textarea--small', Blade::render('<tedi:textarea size="small" />'), on: 'tedi-textarea');
        $this->assertHasClass('tedi-textarea--small',
            Blade::render('<tedi:form-field size="small"><tedi:textarea /></tedi:form-field>'), on: 'tedi-textarea');

        // Clearable by default inside a form-field, so the box owns the surface.
        $html = Blade::render('<tedi:form-field><tedi:textarea /></tedi:form-field>');
        $this->assertMissingClass('tedi-field-surface', $html, on: 'tedi-textarea');
        $this->assertHasClass('tedi-form-field__box', $html, on: 'tedi-form-field__box');
    }

    public function test_textarea_forwards_wire_model_to_the_control(): void
    {
        $html = Blade::render('<tedi:textarea wire:model="bio" />');

        $this->assertMatchesRegularExpression('/<textarea\s+tedi-textarea[^>]*wire:model="bio"/s', $html);
    }
}
