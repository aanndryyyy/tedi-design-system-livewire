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

    /**
     * Angular 8.0 (#612): the field state lives on the box's
     * `tedi-field-surface--*` modifiers. The host's `--valid|invalid|disabled`
     * classes are still emitted upstream but have no rule, so they are dropped.
     */
    public function test_form_field_validation_precedence(): void
    {
        // invalid wins over valid
        $html = Blade::render('<tedi:form-field icon="search" :invalid="true" :valid="true"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--invalid', $html, on: 'tedi-form-field__box');
        $this->assertMissingClass('tedi-field-surface--valid', $html, on: 'tedi-form-field__box');

        // character count exceeding the limit forces invalid regardless of `invalid`
        $html = Blade::render('<tedi:form-field icon="search" :character-limit="5" :character-count="10"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--invalid', $html, on: 'tedi-form-field__box');
        $this->assertHasClass('tedi-form-field__character-count--error', $html, on: 'tedi-form-field__character-count');

        $html = Blade::render('<tedi:form-field icon="search" :valid="true"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--valid', $html, on: 'tedi-form-field__box');

        foreach (['tedi-form-field--valid', 'tedi-form-field--invalid', 'tedi-form-field--disabled'] as $dropped) {
            $html = Blade::render('<tedi:form-field :invalid="true" :disabled="true"><tedi:text-field /></tedi:form-field>');
            $this->assertMissingClass($dropped, $html, on: 'tedi-form-field');
        }
    }

    public function test_form_field_disabled_box(): void
    {
        $html = Blade::render('<tedi:form-field icon="search" :disabled="true"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--disabled', $html, on: 'tedi-form-field__box');
        // The field context disables the control too.
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field\s+disabled/', $html);

        // The control's own `disabled` reaches the box through registration.
        $html = Blade::render('<tedi:form-field icon="search"><tedi:text-field :disabled="true" /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--disabled', $html, on: 'tedi-form-field__box');
    }

    public function test_form_field_renders_no_box_without_an_icon_or_a_clear_button(): void
    {
        // clearable defaults to true, but a bare <input> registers no control
        // and so gets no clear button — and with no icon, no box.
        $html = Blade::render('<tedi:form-field><input /></tedi:form-field>');
        $this->assertStringNotContainsString('tedi-form-field__box', $html);
        $this->assertStringNotContainsString('tedi-form-field__buttons', $html);

        // Opting out of the clear button leaves a text field bare too, and it
        // paints its own surface.
        $html = Blade::render('<tedi:form-field :clearable="false"><tedi:text-field value="x" /></tedi:form-field>');
        $this->assertStringNotContainsString('tedi-form-field__box', $html);
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-text-field');

        // An icon alone renders the box, which then owns the surface.
        $html = Blade::render('<tedi:form-field icon="search" :clearable="false"><tedi:text-field /></tedi:form-field>');
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-form-field__box');
        $this->assertMissingClass('tedi-field-surface', $html, on: 'tedi-text-field');
        $this->assertHasClass('tedi-form-field__icon', $html, on: 'tedi-form-field__icon');
        $this->assertStringNotContainsString('tedi-form-field__buttons', $html);
    }

    public function test_form_field_box_carries_the_scrollbar_custom_property(): void
    {
        // textarea.component.scss reads it inside calc() with no fallback.
        $html = Blade::render('<tedi:form-field><tedi:textarea /></tedi:form-field>');
        $this->assertMatchesRegularExpression('/class="tedi-form-field__box[^"]*"\s+style="--tedi-form-field-control-scrollbar: 0px"/', $html);
    }

    /**
     * Angular 9.0 (#572 / #751): clearable defaults to true, and a textarea is
     * clearable too.
     */
    public function test_form_field_clear_button_defaults_on_for_text_field_and_textarea(): void
    {
        foreach (['<tedi:text-field value="abc" />', '<tedi:textarea value="abc" />'] as $control) {
            $html = Blade::render('<tedi:form-field>'.$control.'</tedi:form-field>');
            $this->assertHasClass('tedi-form-field__buttons', $html, on: 'tedi-form-field__buttons');
            $this->assertMissingClass('tedi-form-field__buttons--hidden', $html, on: 'tedi-form-field__buttons');
            $this->assertHasClass('tedi-form-field__clear', $html, on: 'tedi-form-field__clear');
        }
    }

    public function test_form_field_clear_button_visibility(): void
    {
        $html = Blade::render('<tedi:form-field><tedi:text-field value="abc" /></tedi:form-field>');
        $this->assertHasClass('tedi-form-field__buttons', $html, on: 'tedi-form-field__buttons');
        $this->assertMissingClass('tedi-form-field__buttons--hidden', $html, on: 'tedi-form-field__buttons');
        $this->assertStringContainsString('tabindex="0"', $html);

        // Empty, disabled or read-only: rendered, but hidden and disabled.
        foreach ([
            '<tedi:text-field />',
            '<tedi:text-field value="abc" :disabled="true" />',
            '<tedi:text-field value="abc" readonly />',
        ] as $control) {
            $html = Blade::render('<tedi:form-field>'.$control.'</tedi:form-field>');
            $this->assertHasClass('tedi-form-field__buttons--hidden', $html, on: 'tedi-form-field__buttons');
            $this->assertStringContainsString('aria-hidden="true"', $html);
            $this->assertStringContainsString('tabindex="-1"', $html);
            $this->assertMatchesRegularExpression('/tedi-form-field__clear"[^>]*disabled/', $html);
        }

        // An explicit `value` on the form-field wins over the control's.
        $html = Blade::render('<tedi:form-field value="x"><tedi:text-field /></tedi:form-field>');
        $this->assertMissingClass('tedi-form-field__buttons--hidden', $html, on: 'tedi-form-field__buttons');
    }

    public function test_form_field_clear_on_interaction_class_needs_a_visible_button(): void
    {
        $html = Blade::render('<tedi:form-field show-clear-on-interaction><tedi:text-field value="abc" /></tedi:form-field>');
        $this->assertHasClass('tedi-form-field--clear-on-interaction', $html, on: 'tedi-form-field');

        $html = Blade::render('<tedi:form-field show-clear-on-interaction><tedi:text-field /></tedi:form-field>');
        $this->assertMissingClass('tedi-form-field--clear-on-interaction', $html, on: 'tedi-form-field');

        $html = Blade::render('<tedi:form-field show-clear-on-interaction :clearable="false"><tedi:text-field value="abc" /></tedi:form-field>');
        $this->assertMissingClass('tedi-form-field--clear-on-interaction', $html, on: 'tedi-form-field');
    }

    public function test_form_field_icon_is_followed_by_a_separator_after_the_clear_button(): void
    {
        $html = Blade::render('<tedi:form-field icon="search"><tedi:text-field value="x" /></tedi:form-field>');
        $this->assertHasClass('tedi-separator--vertical', $html, on: 'tedi-separator');
        $this->assertHasClass('tedi-form-field__icon', $html, on: 'tedi-form-field__icon');
        $this->assertStringContainsString('--_tedi-icon-size', $html);
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
        $html = Blade::render('<tedi:form-field><tedi:text-field /></tedi:form-field>');

        $this->assertSame(1, preg_match_all(
            '/<div\s+class="tedi-form-field__buttons[^"]*"/', $html
        ), 'The buttons wrapper must open with exactly one class attribute.');

        preg_match('/<div\s+class="tedi-form-field__buttons[^>]*>/s', $html, $match);

        // x-bind:class is a separate attribute Alpine merges at runtime; only a
        // second plain `class` would be dropped by the parser.
        $this->assertSame(1, preg_match_all('/(?<![\w:-])class=/', $match[0]),
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

    public function test_form_field_character_count_follows_the_control_and_describes_it(): void
    {
        $html = Blade::render('<tedi:form-field :character-limit="3"><tedi:text-field value="abcd" /></tedi:form-field>');

        // The count comes from the control's value; exceeding it is an error
        // on the box and on the control.
        $this->assertMatchesRegularExpression('/>4\/3<\/span>/', $html);
        $this->assertHasClass('tedi-form-field__character-count--error', $html, on: 'tedi-form-field__character-count');
        $this->assertHasClass('tedi-field-surface--invalid', $html, on: 'tedi-form-field__box');
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);

        // The counter's id is pushed into the control's aria-describedby,
        // after the consumer's own ids.
        preg_match('/id="([^"]+-character-count)"/', $html, $id);
        $this->assertNotEmpty($id);
        $this->assertStringContainsString('aria-describedby="'.$id[1].'"', $html);

        $html = Blade::render('<tedi:form-field :character-limit="3"><tedi:text-field aria-describedby="own" value="ab" /></tedi:form-field>');
        preg_match('/id="([^"]+-character-count)"/', $html, $id);
        $this->assertStringContainsString('aria-describedby="own '.$id[1].'"', $html);
        $this->assertMatchesRegularExpression('/>2\/3<\/span>/', $html);
    }

    public function test_form_field_label_and_extra_render_inside_the_host(): void
    {
        $html = Blade::render(<<<'BLADE'
<tedi:form-field>
    <x-slot:label><tedi:form.label for="f">Nimi</tedi:form.label></x-slot:label>
    <tedi:text-field id="f" />
    <x-slot:extra>Lisa</x-slot:extra>
</tedi:form-field>
BLADE);
        $this->assertMatchesRegularExpression('/^\s*<tedi-form-field[^>]*>\s*<label/', $html);
        $this->assertStringContainsString('<div class="tedi-form-field__extra">Lisa</div>', $html);

        // With nothing in it, the extra row is empty so `:empty` hides it.
        $html = Blade::render('<tedi:form-field><input /></tedi:form-field>');
        $this->assertStringContainsString('<div class="tedi-form-field__extra"></div>', $html);
    }

    public function test_form_field_inherits_disabled_and_invalid_from_input_group(): void
    {
        $html = Blade::render(<<<'BLADE'
<tedi:input-group :disabled="true" :invalid="true">
    <tedi:form-field icon="search"><tedi:text-field /></tedi:form-field>
</tedi:input-group>
BLADE);
        $this->assertHasClass('tedi-field-surface--disabled', $html, on: 'tedi-form-field__box');
        $this->assertHasClass('tedi-field-surface--invalid', $html, on: 'tedi-form-field__box');

        // an explicit value on form-field itself still wins
        $html = Blade::render(<<<'BLADE'
<tedi:input-group :disabled="true">
    <tedi:form-field icon="search" :disabled="false"><tedi:text-field /></tedi:form-field>
</tedi:input-group>
BLADE);
        $this->assertMissingClass('tedi-field-surface--disabled', $html, on: 'tedi-form-field__box');
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

    public function test_select_value_marks_the_selected_option(): void
    {
        $html = Blade::render('<tedi:select input-id="s-v" placeholder="Vali" value="b" :options="[\'a\' => \'A\', \'b\' => \'B\']" />');
        $this->assertMatchesRegularExpression('/<option\s+value="b"\s+selected/', $html);
        $this->assertDoesNotMatchRegularExpression('/<option\s+value="a"\s+selected/', $html);
        $this->assertStringNotContainsString('disabled selected hidden', $html);

        // Values compare as strings.
        $html = Blade::render('<tedi:select input-id="s-0" :value="0" :options="[\'0\', \'1\']" />');
        $this->assertMatchesRegularExpression('/<option\s+value="0"\s+selected/', $html);

        // allowMultiple takes an array.
        $html = Blade::render('<tedi:select input-id="s-m" :allow-multiple="true" :value="[\'a\', \'b\']" :options="[\'a\' => \'A\', \'b\' => \'B\', \'c\' => \'C\']" />');
        $this->assertSame(2, preg_match_all('/<option\s+value="[ab]"\s+selected/', $html));
    }

    /**
     * Angular 9.0 (#745): null and '' both mean nothing is selected, so an
     * option whose value is '' is never shown as selected.
     */
    public function test_select_empty_string_value_selects_nothing(): void
    {
        foreach (['value=""', ''] as $value) {
            $html = Blade::render('<tedi:select input-id="s-e" placeholder="Vali" '.$value.' :options="[\'\' => \'Kõik\', \'a\' => \'A\']" />');
            $this->assertStringContainsString('disabled selected hidden', $html);
            $this->assertDoesNotMatchRegularExpression('/<option\s+value=""\s+selected/', $html);
        }
    }

    public function test_select_name_and_aria(): void
    {
        $html = Blade::render('<tedi:select input-id="s-n" name="county" state="error" :options="[]" :feedback-text="[\'text\' => \'Viga\', \'type\' => \'error\']" />');
        $this->assertMatchesRegularExpression('/<select[^>]*name="county"/s', $html);
        $this->assertMatchesRegularExpression('/<select[^>]*aria-invalid="true"/s', $html);
        $this->assertMatchesRegularExpression('/<select[^>]*aria-describedby="s-n-feedback"/s', $html);
        $this->assertStringContainsString('id="s-n-feedback"', $html);

        $html = Blade::render('<tedi:select input-id="s-plain" :options="[]" />');
        $this->assertStringNotContainsString('aria-invalid', $html);
        $this->assertStringNotContainsString('aria-describedby', $html);
        $this->assertStringNotContainsString('name=', $html);
    }

    public function test_select_clearable_props_are_inert_and_do_not_leak(): void
    {
        $html = Blade::render('<tedi:select input-id="s-c" clearable show-clear-on-interaction :options="[]" />');
        $this->assertStringNotContainsString('clearable', $html);
        $this->assertStringNotContainsString('clear-on-interaction', $html);
        $this->assertStringNotContainsString('tedi-select__clear', $html);
    }

    public function test_feedback_text_always_carries_an_id(): void
    {
        $this->assertStringContainsString('id="hint"', Blade::render('<tedi:feedback-text id="hint" text="Msg" />'));
        $this->assertMatchesRegularExpression('/<tedi-feedback-text\s+id="tedi-feedback-text-[^"]+"/', Blade::render('<tedi:feedback-text text="Msg" />'));
    }

    public function test_label_size_falls_back_to_the_form_field(): void
    {
        $label = '<x-slot:label><tedi:form.label>Nimi</tedi:form.label></x-slot:label>';

        $html = Blade::render('<tedi:form-field size="small">'.$label.'<input /></tedi:form-field>');
        $this->assertHasClass('tedi-label--small', $html, on: 'tedi-label');

        // large has no label size; it stays default.
        $html = Blade::render('<tedi:form-field size="large">'.$label.'<input /></tedi:form-field>');
        $this->assertMissingClass('tedi-label--small', $html, on: 'tedi-label');

        // Its own size wins.
        $html = Blade::render('<tedi:form-field size="small"><x-slot:label><tedi:form.label size="default">Nimi</tedi:form.label></x-slot:label><input /></tedi:form-field>');
        $this->assertMissingClass('tedi-label--small', $html, on: 'tedi-label');
    }

    public function test_label_visually_hidden(): void
    {
        $this->assertHasClass('sr-only', Blade::render('<tedi:form.label visually-hidden>Nimi</tedi:form.label>'), on: 'tedi-label');

        $html = Blade::render('<tedi:form.label visually-hidden="reserve-space">Nimi</tedi:form.label>');
        $this->assertHasClass('tedi-label--reserve-space', $html, on: 'tedi-label');
        $this->assertMissingClass('sr-only', $html, on: 'tedi-label');

        $html = Blade::render('<tedi:form.label>Nimi</tedi:form.label>');
        $this->assertMissingClass('sr-only', $html, on: 'tedi-label');
        $this->assertMissingClass('tedi-label--reserve-space', $html, on: 'tedi-label');
    }

    public function test_label_row_root_is_the_selector_element(): void
    {
        $this->assertMatchesRegularExpression('/^<tedi-label-row\s+class="tedi-label-row"/', trim(Blade::render('<tedi:label-row>x</tedi:label-row>')));
    }

    public function test_clear_button_is_wired_to_the_form_field_behaviour(): void
    {
        $html = Blade::render('<tedi:form-field><tedi:text-field value="x" /></tedi:form-field>');

        $this->assertStringContainsString('x-data="tediFormField({ clearOnInteraction: false })"', $html);
        $this->assertStringContainsString('x-on:click="clear()"', $html);
        $this->assertStringContainsString('x-bind:disabled="! showClear"', $html);
        $this->assertStringContainsString("x-bind:class=\"{ 'tedi-form-field__buttons--hidden': ! showClear }\"", $html);
    }

    public function test_form_field_without_a_clear_button_carries_no_behaviour(): void
    {
        $html = Blade::render('<tedi:form-field :clearable="false"><tedi:text-field value="x" /></tedi:form-field>');

        $this->assertStringNotContainsString('tediFormField', $html);
    }
}
