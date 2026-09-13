<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class FormFieldComponentsTest extends TestCase
{
    // ---- bare renders (no optional props) ---------------------------------
    // Catches variables referenced unconditionally but only assigned inside an
    // @if branch or left without a default — the class of bug that bit
    // select.blade.php's $inputId (undefined when no id was passed at all).
    // feedback-text's `text` is a genuine Angular input.required<string>(), so
    // it's supplied here rather than omitted (see tests/IntegrityTest.php's
    // $requiredProps, which does the same for the whole-library sweep).

    public function test_select_renders_with_no_optional_props(): void
    {
        $html = Blade::render('<tedi:select />');
        $this->assertStringContainsString('<select', $html);
    }

    public function test_form_field_renders_with_no_optional_props(): void
    {
        $html = Blade::render('<tedi:form-field />');
        $this->assertHasClass('tedi-form-field', $html, on: 'tedi-form-field');
    }

    public function test_input_group_renders_with_no_optional_props(): void
    {
        $html = Blade::render('<tedi:input-group />');
        $this->assertHasClass('tedi-input-group', $html, on: 'tedi-input-group');
    }

    public function test_label_row_renders_with_no_optional_props(): void
    {
        $html = Blade::render('<tedi:label-row />');
        $this->assertHasClass('tedi-label-row', $html, on: 'tedi-label-row');
    }

    public function test_feedback_text_renders_with_only_its_required_prop(): void
    {
        $html = Blade::render('<tedi:feedback-text text="Viga" />');
        $this->assertHasClass('tedi-feedback-text', $html, on: 'tedi-feedback-text');
    }

    public function test_label_renders_with_no_optional_props(): void
    {
        $html = Blade::render('<tedi:form.label />');
        $this->assertHasClass('tedi-label', $html, on: 'tedi-label');
    }

    // ---- feedback-text -------------------------------------------------

    public function test_feedback_text_type_classes_and_aria(): void
    {
        foreach (['hint', 'valid', 'error'] as $type) {
            $html = Blade::render('<tedi:feedback-text text="Msg" type="'.$type.'" />');

            $this->assertHasClass('tedi-feedback-text--'.$type, $html, on: 'tedi-feedback-text--'.$type);

            if ($type === 'hint') {
                $this->assertStringNotContainsString('role="alert"', $html);
                $this->assertStringContainsString('aria-live="polite"', $html);
            } else {
                $this->assertStringContainsString('role="alert"', $html);
                $this->assertStringContainsString('aria-live="assertive"', $html);
            }
        }
    }

    public function test_feedback_text_position_classes(): void
    {
        foreach (['left', 'right'] as $position) {
            $html = Blade::render('<tedi:feedback-text text="Msg" position="'.$position.'" />');
            $this->assertHasClass('tedi-feedback-text--'.$position, $html, on: 'tedi-feedback-text--'.$position);
        }
    }

    // ---- form.label ------------------------------------------------------

    public function test_label_size_and_color_classes(): void
    {
        foreach (['small', 'default'] as $size) {
            foreach (['primary', 'secondary'] as $color) {
                $html = Blade::render('<tedi:form.label size="'.$size.'" color="'.$color.'">Nimi</tedi:form.label>');
                $this->assertHasClass('tedi-label--'.$color, $html, on: 'tedi-label--'.$color);
                if ($size === 'small') {
                    $this->assertHasClass('tedi-label--small', $html, on: 'tedi-label--small');
                } else {
                    $this->assertMissingClass('tedi-label--small', $html, on: 'tedi-label--small');
                }
            }
        }
    }

    public function test_label_required_renders_marker_and_sr_hint(): void
    {
        $html = Blade::render('<tedi:form.label required>Nimi</tedi:form.label>');
        $this->assertStringContainsString('tedi-label--required', $html);
        $this->assertStringContainsString('sr-only', $html);

        $html = Blade::render('<tedi:form.label>Nimi</tedi:form.label>');
        $this->assertStringNotContainsString('tedi-label--required', $html);
    }

    public function test_label_visually_hidden_and_reserve_space(): void
    {
        $hidden = Blade::render('<tedi:form.label :visually-hidden="true">Nimi</tedi:form.label>');
        $this->assertHasClass('sr-only', $hidden, on: 'tedi-label');
        $this->assertMissingClass('tedi-label--reserve-space', $hidden, on: 'tedi-label');

        $reserved = Blade::render('<tedi:form.label visually-hidden="reserve-space">Nimi</tedi:form.label>');
        $this->assertHasClass('tedi-label--reserve-space', $reserved, on: 'tedi-label');
        $this->assertMissingClass('sr-only', $reserved, on: 'tedi-label');
    }

    // ---- label-row -------------------------------------------------------

    public function test_label_row_renders_wrapper_class(): void
    {
        $html = Blade::render('<tedi:label-row>x</tedi:label-row>');
        $this->assertHasClass('tedi-label-row', $html, on: 'tedi-label-row');
    }

    // ---- form-field --------------------------------------------------------

    public function test_form_field_size_classes(): void
    {
        $expected = ['default' => null, 'small' => 'tedi-form-field--small', 'large' => 'tedi-form-field--large'];

        foreach ($expected as $size => $class) {
            $html = Blade::render('<tedi:form-field size="'.$size.'"><input /></tedi:form-field>');
            if ($class) {
                $this->assertHasClass($class, $html, on: $class);
            } else {
                $this->assertMissingClass('tedi-form-field--small', $html, on: 'tedi-form-field--small');
                $this->assertMissingClass('tedi-form-field--large', $html, on: 'tedi-form-field--large');
            }
        }
    }

    public function test_form_field_validation_precedence(): void
    {
        // Angular 8 paints validation on `tedi-field-surface` (the box), not
        // host modifiers. A box exists only when icon/clearable is set.
        $html = Blade::render('<tedi:form-field icon="search" :invalid="true" :valid="true"><input /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--invalid', $html, on: 'tedi-form-field__box');
        $this->assertMissingClass('tedi-field-surface--valid', $html, on: 'tedi-form-field__box');

        // character count exceeding the limit forces invalid regardless of `invalid`
        $html = Blade::render('<tedi:form-field icon="search" :character-limit="5" :character-count="10"><input /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--invalid', $html, on: 'tedi-form-field__box');
        $this->assertHasClass('tedi-form-field__character-count--error', $html, on: 'tedi-form-field__character-count--error');

        $html = Blade::render('<tedi:form-field icon="search" :valid="true"><input /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--valid', $html, on: 'tedi-form-field__box');
    }

    public function test_form_field_disabled_class(): void
    {
        $html = Blade::render('<tedi:form-field icon="search" :disabled="true"><input /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--disabled', $html, on: 'tedi-form-field__box');
    }

    public function test_form_field_icon_and_clearable_render_a_surface_box(): void
    {
        $html = Blade::render('<tedi:form-field icon="search"><input /></tedi:form-field>');
        $this->assertStringContainsString('tedi-form-field__icon', $html);
        $this->assertHasClass('tedi-form-field__box', $html, on: 'tedi-form-field__box');
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-form-field__box');

        $html = Blade::render('<tedi:form-field clearable value="x"><input /></tedi:form-field>');
        $this->assertStringContainsString('tedi-form-field__buttons', $html);
        $this->assertHasClass('tedi-form-field__box', $html, on: 'tedi-form-field__box');

        $html = Blade::render('<tedi:form-field><input /></tedi:form-field>');
        $this->assertStringNotContainsString('tedi-form-field__box', $html);
    }

    public function test_form_field_nested_text_field_defers_surface_when_box_exists(): void
    {
        $bare = Blade::render('<tedi:text-field />');
        $this->assertHasClass('tedi-field-surface', $bare, on: 'tedi-text-field');

        $boxed = Blade::render('<tedi:form-field icon="search"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface', $boxed, on: 'tedi-form-field__box');
        $this->assertMissingClass('tedi-field-surface', $boxed, on: 'tedi-text-field');
    }

    public function test_form_field_no_longer_suppresses_icon_for_textarea(): void
    {
        $html = Blade::render('<tedi:form-field icon="search" clearable value="x" textarea><textarea></textarea></tedi:form-field>');
        $this->assertStringContainsString('tedi-form-field__icon', $html);
        $this->assertStringContainsString('tedi-form-field__buttons', $html);
    }

    public function test_form_field_clear_button_visibility(): void
    {
        $html = Blade::render('<tedi:form-field clearable value="abc"><input /></tedi:form-field>');
        $this->assertHasClass('tedi-form-field__buttons', $html, on: 'tedi-form-field__buttons');
        $this->assertMissingClass('tedi-form-field__buttons--hidden', $html, on: 'tedi-form-field__buttons');

        $html = Blade::render('<tedi:form-field clearable><input /></tedi:form-field>');
        $this->assertHasClass('tedi-form-field__buttons--hidden', $html, on: 'tedi-form-field__buttons');
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    /**
     * The two modifiers must live in ONE class attribute. They used to be a
     * literal `class="tedi-form-field__buttons"` next to an `@class([...])`,
     * which is two class attributes on one element: the HTML parser keeps the
     * first, so `--hidden` was silently discarded and every clearable field
     * showed its clear button regardless of value.
     *
     * The old assertions could not see it — `classesOf()` scans every class
     * attribute in the document and returns the first list containing the token
     * it was asked to match, so it happily found `--hidden` in the discarded
     * second attribute. Anchoring both on `tedi-form-field__buttons` (above) is
     * what makes them meaningful; this pins the shape directly.
     */
    public function test_form_field_buttons_emit_a_single_class_attribute(): void
    {
        $html = Blade::render('<tedi:form-field clearable><input /></tedi:form-field>');

        $this->assertSame(1, preg_match_all(
            '/<div\s+class="tedi-form-field__buttons[^"]*"/', $html
        ), 'The buttons wrapper must open with exactly one class attribute.');

        preg_match('/<div\s+class="tedi-form-field__buttons[^>]*>/s', $html, $match);

        $this->assertSame(1, substr_count($match[0], 'class='),
            'A second class attribute on the wrapper would be dropped by the parser.');
    }

    public function test_form_field_feedback_row_shown_for_feedback_slot_or_character_limit(): void
    {
        $html = Blade::render('<tedi:form-field><input /></tedi:form-field>');
        $this->assertStringNotContainsString('tedi-form-field__feedback', $html);

        $html = Blade::render('<tedi:form-field :character-limit="10"><input /></tedi:form-field>');
        $this->assertStringContainsString('tedi-form-field__feedback', $html);

        $html = Blade::render(<<<'BLADE'
<tedi:form-field>
    <input />
    <x-slot:feedback><tedi:feedback-text text="Hint" /></x-slot:feedback>
</tedi:form-field>
BLADE);
        $this->assertStringContainsString('tedi-form-field__feedback', $html);
        $this->assertStringContainsString('tedi-feedback-text', $html);
    }

    public function test_form_field_inherits_disabled_and_invalid_from_input_group(): void
    {
        $html = Blade::render(<<<'BLADE'
<tedi:input-group :disabled="true" :invalid="true">
    <tedi:form-field><tedi:text-field /></tedi:form-field>
</tedi:input-group>
BLADE);
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field\s+disabled/', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);

        // an explicit value on form-field itself still wins
        $html = Blade::render(<<<'BLADE'
<tedi:input-group :disabled="true">
    <tedi:form-field :disabled="false"><tedi:text-field /></tedi:form-field>
</tedi:input-group>
BLADE);
        $this->assertDoesNotMatchRegularExpression('/<input\s+tedi-text-field\s+disabled/', $html);
    }

    // ---- input-group -------------------------------------------------------

    public function test_input_group_boolean_prop_classes(): void
    {
        $html = Blade::render('<tedi:input-group :addons="false" :disabled="true"><input /></tedi:input-group>');
        $this->assertMissingClass('tedi-input-group--addons', $html, on: 'tedi-input-group--addons');
        $this->assertHasClass('tedi-input-group--disabled', $html, on: 'tedi-input-group--disabled');
        $this->assertStringContainsString('aria-disabled="true"', $html);

        $html = Blade::render('<tedi:input-group><input /></tedi:input-group>');
        $this->assertHasClass('tedi-input-group--addons', $html, on: 'tedi-input-group--addons');
        $this->assertMissingClass('tedi-input-group--disabled', $html, on: 'tedi-input-group--disabled');
    }

    public function test_input_group_has_no_own_invalid_class(): void
    {
        // input-group.component.scss defines no `--invalid` rule of its own;
        // validity is styled entirely on the nested control. The `invalid`
        // prop still exists to drive @aware on the nested form-field/select
        // (covered by test_form_field_inherits_disabled_and_invalid_from_input_group
        // and test_select_invalid_aware_forces_error_state).
        $html = Blade::render('<tedi:input-group :invalid="true"><input /></tedi:input-group>');
        $this->assertMissingClass('tedi-input-group--invalid', $html, on: 'tedi-input-group--invalid');
    }

    public function test_input_group_prefix_suffix_presence_and_text_detection(): void
    {
        $html = Blade::render(<<<'BLADE'
<tedi:input-group>
    <x-slot:prefix>€</x-slot:prefix>
    <input />
</tedi:input-group>
BLADE);
        $this->assertHasClass('tedi-input-group--has-prefix', $html, on: 'tedi-input-group--has-prefix');
        $this->assertHasClass('tedi-input-group__prefix--text', $html, on: 'tedi-input-group__prefix--text');
        $this->assertMissingClass('tedi-input-group--has-suffix', $html, on: 'tedi-input-group--has-suffix');

        $html = Blade::render(<<<'BLADE'
<tedi:input-group>
    <input />
    <x-slot:suffix><tedi:icon name="euro" /></x-slot:suffix>
</tedi:input-group>
BLADE);
        $this->assertHasClass('tedi-input-group--has-suffix', $html, on: 'tedi-input-group--has-suffix');
        $this->assertMissingClass('tedi-input-group__suffix--text', $html, on: 'tedi-input-group__suffix--text');
    }

    // ---- select ----------------------------------------------------------
    // Native-<select> subset only — see select.blade.php's doc comment and
    // CONVENTIONS.md §7.4. Only classes that genuinely exist in
    // select.component.scss (tedi-select, tedi-select--multiselect, and the
    // shared tedi-input/tedi-input--disabled|small|error|valid modifiers) are
    // asserted; no tedi-select__* combobox classes are rendered or tested.

    public function test_select_state_classes(): void
    {
        $expected = ['default' => [], 'valid' => ['tedi-input--valid'], 'error' => ['tedi-input--error']];

        foreach ($expected as $state => $classes) {
            $html = Blade::render('<tedi:select input-id="s-'.$state.'" state="'.$state.'" :options="[]" />');
            foreach ($classes as $class) {
                $this->assertHasClass($class, $html, on: $class);
            }
        }
    }

    public function test_select_invalid_aware_forces_error_state(): void
    {
        $html = Blade::render(<<<'BLADE'
<tedi:input-group :invalid="true">
    <tedi:select input-id="s-aware" :options="[]" />
</tedi:input-group>
BLADE);
        $this->assertHasClass('tedi-input--error', $html, on: 'tedi-input--error');
    }

    public function test_select_size_and_disabled_classes(): void
    {
        $html = Blade::render('<tedi:select input-id="s-small" size="small" :options="[]" />');
        $this->assertHasClass('tedi-input--small', $html, on: 'tedi-input--small');

        $html = Blade::render('<tedi:select input-id="s-disabled" :disabled="true" :options="[]" />');
        $this->assertHasClass('tedi-input--disabled', $html, on: 'tedi-input--disabled');
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_select_multiselect_class_and_multiple_attribute(): void
    {
        $html = Blade::render('<tedi:select input-id="s-multi" :allow-multiple="true" :options="[]" />');
        $this->assertHasClass('tedi-select--multiselect', $html, on: 'tedi-select--multiselect');
        $this->assertStringContainsString('multiple', $html);
    }

    public function test_select_renders_options_from_array_shapes(): void
    {
        // flat value => label map
        $html = Blade::render('<tedi:select input-id="s-1" :options="[\'a\' => \'Esimene\', \'b\' => \'Teine\']" />');
        $this->assertStringContainsString('value="a"', $html);
        $this->assertStringContainsString('Esimene', $html);

        // list of objects with bindLabel/bindValue
        $html = Blade::render(<<<'BLADE'
<tedi:select
    input-id="s-2"
    :options="[['id' => 1, 'name' => 'Esimene'], ['id' => 2, 'name' => 'Teine', 'disabled' => true]]"
    bind-label="name"
    bind-value="id"
/>
BLADE);
        $this->assertStringContainsString('value="1"', $html);
        $this->assertStringContainsString('Esimene', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_select_placeholder_option(): void
    {
        $html = Blade::render('<tedi:select input-id="s-ph" placeholder="Vali..." :options="[]" />');
        $this->assertStringContainsString('Vali...', $html);
        $this->assertStringContainsString('disabled selected hidden', $html);
    }

    public function test_select_wire_model_lands_on_native_select(): void
    {
        $html = Blade::render('<tedi:select input-id="s-wire" wire:model="form.country" :options="[]" />');
        $this->assertMatchesRegularExpression('/<select[^>]*wire:model="form\.country"/', $html);
    }

    public function test_select_label_renders_via_label_row(): void
    {
        $html = Blade::render('<tedi:select input-id="s-label" label="Riik" required :options="[]" />');
        $this->assertHasClass('tedi-label-row', $html, on: 'tedi-label-row');
        $this->assertHasClass('tedi-label--required', $html, on: 'tedi-label--required');
        $this->assertStringContainsString('for="s-label"', $html);
    }

    public function test_select_feedback_text_option(): void
    {
        $html = Blade::render('<tedi:select input-id="s-fb" :options="[]" :feedback-text="[\'text\' => \'Vali riik\', \'type\' => \'error\']" />');
        $this->assertHasClass('tedi-feedback-text--error', $html, on: 'tedi-feedback-text--error');
        $this->assertStringContainsString('Vali riik', $html);
    }
}
