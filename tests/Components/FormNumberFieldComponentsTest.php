<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Parity tests for <tedi:number-field>.
 *
 * Every assertion pins `on:` explicitly: the component renders up to five
 * elements carrying classes (label, wrapper, two buttons, input, suffix), so an
 * unqualified assertHasClass() would inspect whichever comes first in the HTML.
 */
class FormNumberFieldComponentsTest extends TestCase
{
    // ---- bare render ------------------------------------------------------

    public function test_number_field_renders_with_no_optional_props(): void
    {
        $html = Blade::render('<tedi:number-field />');

        $this->assertHasClass('tedi-number-field', $html, on: 'tedi-number-field');
        $this->assertStringContainsString('type="number"', $html);
        $this->assertStringContainsString('inputmode="numeric"', $html);
    }

    public function test_input_id_is_generated_when_omitted_and_wired_to_the_label(): void
    {
        $html = Blade::render('<tedi:number-field label="Kogus" />');

        $this->assertSame(1, preg_match('/id="(tedi-number-field-[a-f0-9]+)"/', $html, $m));
        $this->assertStringContainsString('for="'.$m[1].'"', $html);
    }

    public function test_explicit_input_id_is_used_for_the_input_and_the_label(): void
    {
        $html = Blade::render('<tedi:number-field input-id="qty" label="Kogus" />');

        $this->assertStringContainsString('id="qty"', $html);
        $this->assertStringContainsString('for="qty"', $html);
    }

    // ---- structure --------------------------------------------------------

    public function test_label_is_only_rendered_when_label_is_given(): void
    {
        $withLabel = Blade::render('<tedi:number-field input-id="a" label="Kogus" />');
        $this->assertHasClass('tedi-label', $withLabel, on: 'tedi-label');
        $this->assertStringContainsString('Kogus', $withLabel);

        $without = Blade::render('<tedi:number-field input-id="a" />');
        $this->assertMissingClass('tedi-label', $without, on: 'tedi-number-field');
        $this->assertStringNotContainsString('<label', $without);
    }

    public function test_both_buttons_render_with_their_direction_modifier(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" />');

        $this->assertHasClass('tedi-number-field__button', $html, on: 'tedi-number-field__button--decrement');
        $this->assertHasClass('tedi-number-field__button', $html, on: 'tedi-number-field__button--increment');
    }

    public function test_buttons_are_secondary_icon_only_tedi_buttons(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" />');

        foreach (['decrement', 'increment'] as $direction) {
            $on = 'tedi-number-field__button--'.$direction;
            $this->assertHasClass('tedi-button', $html, on: $on);
            $this->assertHasClass('tedi-button--secondary', $html, on: $on);
            $this->assertHasClass('tedi-button--icon-only', $html, on: $on);
            // BaseButtonDirective drops both padding modifiers for icon-only.
            $this->assertMissingClass('tedi-button--pl', $html, on: $on);
            $this->assertMissingClass('tedi-button--pr', $html, on: $on);
        }
    }

    // ---- size -------------------------------------------------------------

    public function test_size_default_emits_no_small_modifiers(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" size="default" />');

        $this->assertMissingClass('tedi-number-field__input-wrapper--small', $html, on: 'tedi-number-field__input-wrapper');
        $this->assertMissingClass('tedi-number-field__button--small', $html, on: 'tedi-number-field__button--decrement');
        $this->assertMissingClass('tedi-number-field__button--small', $html, on: 'tedi-number-field__button--increment');
    }

    public function test_size_small_emits_the_small_modifiers(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" size="small" />');

        $this->assertHasClass('tedi-number-field__input-wrapper--small', $html, on: 'tedi-number-field__input-wrapper');
        $this->assertHasClass('tedi-number-field__button--small', $html, on: 'tedi-number-field__button--decrement');
        $this->assertHasClass('tedi-number-field__button--small', $html, on: 'tedi-number-field__button--increment');
    }

    /**
     * Angular passes no `size` down to <tedi-button>, so the buttons stay
     * `tedi-button--default` even in the small number field — only the
     * number-field's own `__button--small` changes.
     */
    public function test_small_size_does_not_shrink_the_underlying_buttons(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" size="small" />');

        $this->assertHasClass('tedi-button--default', $html, on: 'tedi-number-field__button--decrement');
        $this->assertMissingClass('tedi-button--small', $html, on: 'tedi-number-field__button--decrement');
    }

    public function test_label_size_follows_the_field_size(): void
    {
        $small = Blade::render('<tedi:number-field input-id="a" label="Kogus" size="small" />');
        $this->assertHasClass('tedi-label--small', $small, on: 'tedi-label');

        $default = Blade::render('<tedi:number-field input-id="a" label="Kogus" />');
        $this->assertMissingClass('tedi-label--small', $default, on: 'tedi-label');
    }

    // ---- invalid ----------------------------------------------------------

    public function test_invalid_prop_marks_the_field_invalid(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :invalid="true" />');

        $this->assertHasClass('tedi-number-field--invalid', $html, on: 'tedi-number-field');
        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_value_below_min_is_invalid(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :min="5" :value="2" />');

        $this->assertHasClass('tedi-number-field--invalid', $html, on: 'tedi-number-field');
        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_value_above_max_is_invalid(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :max="5" :value="9" />');

        $this->assertHasClass('tedi-number-field--invalid', $html, on: 'tedi-number-field');
    }

    public function test_value_inside_the_range_is_valid(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :min="1" :max="5" :value="3" />');

        $this->assertMissingClass('tedi-number-field--invalid', $html, on: 'tedi-number-field');
        $this->assertStringContainsString('aria-invalid="false"', $html);
    }

    /** Angular's model defaults to 0, so an omitted value is compared as 0. */
    public function test_omitted_value_is_compared_as_zero(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :min="1" />');

        $this->assertHasClass('tedi-number-field--invalid', $html, on: 'tedi-number-field');
    }

    // ---- disabled ---------------------------------------------------------

    /**
     * Counts real `disabled` attributes only. A plain /\bdisabled\b/ would also
     * match inside the `--disabled` class modifiers, since `-` is a word
     * boundary — the exact kind of false positive CONVENTIONS.md §10 warns
     * about for classes.
     */
    private function disabledAttributeCount(string $html): int
    {
        return preg_match_all('/\sdisabled(?=[\s>])/', $html);
    }

    /** The opening tag of the decrement/increment button, attributes included. */
    private function buttonTag(string $html, string $direction): string
    {
        $matched = preg_match(
            '/<button\b[^>]*\btedi-number-field__button--'.$direction.'\b[^>]*>/s',
            $html,
            $m
        );

        $this->assertSame(1, $matched, "No {$direction} button found in the rendered output.");

        return $m[0];
    }

    public function test_disabled_marks_the_wrapper_and_the_input_wrapper(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :disabled="true" />');

        $this->assertHasClass('tedi-number-field--disabled', $html, on: 'tedi-number-field');
        $this->assertHasClass('tedi-number-field__input-wrapper--disabled', $html, on: 'tedi-number-field__input-wrapper');
    }

    public function test_disabled_disables_both_buttons_and_the_input(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :disabled="true" />');

        // <input>, decrement <button>, increment <button>.
        $this->assertSame(3, $this->disabledAttributeCount($html));
    }

    public function test_decrement_is_disabled_at_the_minimum(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :min="1" :value="1" />');

        $this->assertSame(1, $this->disabledAttributeCount($html));
        $this->assertSame(1, $this->disabledAttributeCount($this->buttonTag($html, 'decrement')));
        $this->assertSame(0, $this->disabledAttributeCount($this->buttonTag($html, 'increment')));
        $this->assertMissingClass('tedi-number-field--invalid', $html, on: 'tedi-number-field');
    }

    public function test_increment_is_disabled_at_the_maximum(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :max="1" :value="1" />');

        $this->assertSame(1, $this->disabledAttributeCount($html));
        $this->assertSame(1, $this->disabledAttributeCount($this->buttonTag($html, 'increment')));
        $this->assertSame(0, $this->disabledAttributeCount($this->buttonTag($html, 'decrement')));
    }

    public function test_neither_button_is_disabled_without_bounds(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :value="3" />');

        $this->assertSame(0, $this->disabledAttributeCount($html));
    }

    // ---- value / min / max / step attributes ------------------------------

    /**
     * The value attribute is omitted when no value is given, so a wire:model
     * binding is not blanked out on the initial server render.
     */
    public function test_value_attribute_is_omitted_when_no_value_is_given(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" />');

        $this->assertSame(0, preg_match('/<input\b[^>]*\svalue=/s', $html));
    }

    public function test_explicit_zero_still_renders_a_value_attribute(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :value="0" />');

        $this->assertStringContainsString('value="0"', $html);
    }

    public function test_decimal_value_is_rendered_verbatim(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :value="1.5" />');

        $this->assertStringContainsString('value="1.5"', $html);
    }

    public function test_min_max_step_attributes_are_emitted_only_when_set(): void
    {
        $bare = Blade::render('<tedi:number-field input-id="a" />');
        $this->assertSame(0, preg_match('/<input\b[^>]*\smin=/s', $bare));
        $this->assertSame(0, preg_match('/<input\b[^>]*\smax=/s', $bare));
        // step defaults to 1 and Angular always emits [attr.step].
        $this->assertStringContainsString('step="1"', $bare);

        $bounded = Blade::render('<tedi:number-field input-id="a" :min="0" :max="10" :step="5" />');
        $this->assertStringContainsString('min="0"', $bounded);
        $this->assertStringContainsString('max="10"', $bounded);
        $this->assertStringContainsString('step="5"', $bounded);
    }

    public function test_required_is_forwarded_to_the_input_and_the_label(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" label="Kogus" :required="true" />');

        // Scoped to the <input>: a bare substring check would be satisfied by
        // the label's own `tedi-label--required` span even if the input had no
        // required attribute at all.
        $this->assertSame(1, preg_match('/<input\b[^>]*\srequired(?=[\s>])/s', $html));
        $this->assertHasClass('tedi-label--required', $html, on: 'tedi-label--required');

        $notRequired = Blade::render('<tedi:number-field input-id="a" label="Kogus" />');
        $this->assertSame(0, preg_match('/<input\b[^>]*\srequired(?=[\s>])/s', $notRequired));
    }

    // ---- suffix / full width ----------------------------------------------

    public function test_suffix_adds_the_wrapper_modifier_and_the_suffix_element(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" suffix="tk" />');

        $this->assertHasClass('tedi-number-field__input-wrapper--with-suffix', $html, on: 'tedi-number-field__input-wrapper');
        $this->assertHasClass('tedi-text--tertiary', $html, on: 'tedi-number-field__suffix');
        $this->assertStringContainsString('<small', $html);
        $this->assertStringContainsString('tk', $html);
    }

    public function test_no_suffix_means_no_suffix_modifier(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" />');

        $this->assertMissingClass('tedi-number-field__input-wrapper--with-suffix', $html, on: 'tedi-number-field__input-wrapper');
        $this->assertStringNotContainsString('tedi-number-field__suffix', $html);
    }

    public function test_full_width_modifier(): void
    {
        $on = Blade::render('<tedi:number-field input-id="a" :full-width="true" />');
        $this->assertHasClass('tedi-number-field__input-wrapper--full-width', $on, on: 'tedi-number-field__input-wrapper');

        $off = Blade::render('<tedi:number-field input-id="a" />');
        $this->assertMissingClass('tedi-number-field__input-wrapper--full-width', $off, on: 'tedi-number-field__input-wrapper');
    }

    // ---- aria -------------------------------------------------------------

    public function test_aria_label_is_used_only_when_there_is_no_visible_label(): void
    {
        $without = Blade::render('<tedi:number-field input-id="a" aria-label="Kogus" />');
        $this->assertStringContainsString('aria-label="Kogus"', $without);

        $with = Blade::render('<tedi:number-field input-id="a" label="Kogus" aria-label="Muu" />');
        $this->assertStringNotContainsString('aria-label="Muu"', $with);
    }

    public function test_button_aria_labels_substitute_the_step(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" :step="3" />');

        $this->assertStringContainsString('aria-label="Decrease by 3"', $html);
        $this->assertStringContainsString('aria-label="Increase by 3"', $html);
    }

    // ---- feedback text ----------------------------------------------------

    public function test_feedback_text_renders_and_is_wired_via_aria_describedby(): void
    {
        $html = Blade::render(
            '<tedi:number-field input-id="qty" :feedback-text="[\'text\' => \'Vihje\']" />'
        );

        $this->assertHasClass('tedi-feedback-text', $html, on: 'tedi-feedback-text');
        $this->assertHasClass('tedi-feedback-text--hint', $html, on: 'tedi-feedback-text');
        $this->assertHasClass('tedi-feedback-text--left', $html, on: 'tedi-feedback-text');
        $this->assertStringContainsString('id="qty-feedback"', $html);
        $this->assertStringContainsString('aria-describedby="qty-feedback"', $html);
    }

    public function test_feedback_text_type_and_position_are_forwarded(): void
    {
        $html = Blade::render(
            '<tedi:number-field input-id="qty" :feedback-text="[\'text\' => \'Viga\', \'type\' => \'error\', \'position\' => \'right\']" />'
        );

        $this->assertHasClass('tedi-feedback-text--error', $html, on: 'tedi-feedback-text');
        $this->assertHasClass('tedi-feedback-text--right', $html, on: 'tedi-feedback-text');
    }

    public function test_no_feedback_text_means_no_aria_describedby(): void
    {
        $html = Blade::render('<tedi:number-field input-id="qty" />');

        $this->assertStringNotContainsString('aria-describedby', $html);
        $this->assertStringNotContainsString('tedi-feedback-text', $html);
    }

    // ---- attribute forwarding ---------------------------------------------

    /** $attributes lands on the <input>, so wire:model binds the value. */
    public function test_wire_model_lands_on_the_input(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" wire:model="qty" />');

        $this->assertSame(1, preg_match('/<input\b[^>]*wire:model="qty"/s', $html));
    }

    public function test_consumer_classes_merge_onto_the_input(): void
    {
        $html = Blade::render('<tedi:number-field input-id="a" class="my-input" />');

        $this->assertHasClass('tedi-number-field__input', $html, on: 'my-input');
    }

    public function test_button_attribute_hooks_are_merged_onto_their_buttons(): void
    {
        $html = Blade::render(
            '<tedi:number-field input-id="a"'
            .' :decrement-attributes="[\'wire:click\' => \'dec\']"'
            .' :increment-attributes="[\'wire:click\' => \'inc\']" />'
        );

        $this->assertSame(1, preg_match('/tedi-number-field__button--decrement[^>]*wire:click="dec"/s', $html));
        $this->assertSame(1, preg_match('/tedi-number-field__button--increment[^>]*wire:click="inc"/s', $html));
        // The hooks must not leak onto the input.
        $this->assertSame(0, preg_match('/<input\b[^>]*wire:click/s', $html));
    }
}
