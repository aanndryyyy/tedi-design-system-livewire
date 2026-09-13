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

    public function test_textarea_default_height_style(): void
    {
        $html = Blade::render('<tedi:textarea />');

        $this->assertStringContainsString('height: 7.5rem;', $html);
        $this->assertStringNotContainsString('min-height', $html);
        $this->assertStringNotContainsString('max-height', $html);
    }

    public function test_textarea_numeric_height_becomes_pixels(): void
    {
        $this->assertStringContainsString('height: 200px;', Blade::render('<tedi:textarea :height="200" />'));
    }

    public function test_textarea_empty_height_disables_the_fixed_height(): void
    {
        $this->assertStringNotContainsString('style=', Blade::render('<tedi:textarea height="" />'));
    }

    public function test_textarea_auto_grow_replaces_height_with_row_bounds(): void
    {
        $html = Blade::render('<tedi:textarea :auto-grow="true" />');

        $this->assertStringNotContainsString('height: 7.5rem', $html);
        $this->assertStringContainsString(
            'min-height: calc(3 * 1lh + 2 * var(--_field-padding-y));', $html
        );
        $this->assertStringContainsString(
            'max-height: calc(12 * 1lh + 2 * var(--_field-padding-y));', $html
        );
    }

    public function test_textarea_auto_grow_honours_custom_rows(): void
    {
        $html = Blade::render('<tedi:textarea :auto-grow="true" :min-rows="5" :max-rows="8" />');

        $this->assertStringContainsString(
            'min-height: calc(5 * 1lh + 2 * var(--_field-padding-y));', $html
        );
        $this->assertStringContainsString(
            'max-height: calc(8 * 1lh + 2 * var(--_field-padding-y));', $html
        );
    }

    public function test_textarea_max_height_alone_is_not_wrapped_in_min(): void
    {
        $html = Blade::render('<tedi:textarea max-height="12rem" />');

        $this->assertStringContainsString('max-height: 12rem;', $html);
        $this->assertStringNotContainsString('min(', $html);
    }

    public function test_textarea_auto_grow_and_max_height_combine_with_min(): void
    {
        $html = Blade::render('<tedi:textarea :auto-grow="true" max-height="200px" />');

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

    public function test_textarea_forwards_wire_model_to_the_control(): void
    {
        $html = Blade::render('<tedi:textarea wire:model="bio" />');

        $this->assertMatchesRegularExpression('/<textarea\s+tedi-textarea[^>]*wire:model="bio"/s', $html);
    }

    // -- search -------------------------------------------------------------

    public function test_search_root_is_a_div_with_the_host_class_and_role(): void
    {
        $html = Blade::render('<tedi:search />');

        $this->assertHasClass('tedi-search', $html, on: 'tedi-search');
        $this->assertStringContainsString('role="search"', $html);
    }

    public function test_search_renders_with_no_props_and_generates_an_input_id(): void
    {
        $html = Blade::render('<tedi:search />');

        $this->assertMatchesRegularExpression('/id="tedi-search-[a-z0-9]+"/', $html);
    }

    public function test_search_input_is_a_text_field_with_the_searchbox_role(): void
    {
        $html = Blade::render('<tedi:search input-id="s" />');

        $this->assertHasClass('tedi-text-field', $html, on: 'tedi-text-field');
        $this->assertStringContainsString('role="searchbox"', $html);
        $this->assertStringContainsString('id="s"', $html);
    }

    public function test_search_field_height_custom_property_per_size(): void
    {
        $expected = [
            'small' => 'var(--form-field-height-sm)',
            'default' => 'var(--form-field-height)',
            'large' => 'var(--form-field-height-lg)',
        ];

        foreach ($expected as $size => $value) {
            $html = Blade::render('<tedi:search size="'.$size.'" />');

            $this->assertStringContainsString('--tedi-search-field-height: '.$value, $html);
        }
    }

    public function test_search_button_icon_only_modifier(): void
    {
        $none = Blade::render('<tedi:search />');
        $this->assertMissingClass('tedi-search--button-icon-only', $none, on: 'tedi-search');

        $iconOnly = Blade::render('<tedi:search :button="[]" />');
        $this->assertHasClass('tedi-search--button-icon-only', $iconOnly, on: 'tedi-search');

        $withText = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertMissingClass('tedi-search--button-icon-only', $withText, on: 'tedi-search');
    }

    public function test_search_wraps_the_form_field_in_the_field_element(): void
    {
        $html = Blade::render('<tedi:search />');

        $this->assertHasClass('tedi-search__field', $html, on: 'tedi-search__field');
        $this->assertHasClass('tedi-form-field', $html, on: 'tedi-form-field');
    }

    public function test_search_shows_the_inline_icon_only_without_a_button(): void
    {
        $withIcon = Blade::render('<tedi:search />');
        $this->assertHasClass('tedi-form-field__icon', $withIcon, on: 'tedi-form-field__icon');

        $withButton = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertMissingClass('tedi-form-field__icon', $withButton, on: 'tedi-form-field__icon');
    }

    public function test_search_host_gets_the_has_button_class(): void
    {
        $plain = Blade::render('<tedi:search />');
        $this->assertMissingClass('tedi-search--has-button', $plain, on: 'tedi-search');

        $withButton = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertHasClass('tedi-search--has-button', $withButton, on: 'tedi-search');
    }

    public function test_search_button_classes_and_variant(): void
    {
        $html = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\', \'variant\' => \'secondary\']" />');

        $this->assertHasClass('tedi-search__button', $html, on: 'tedi-search__button');
        $this->assertHasClass('tedi-button--secondary', $html, on: 'tedi-search__button');
        // Icon-then-text: only the right-hand padding modifier survives.
        $this->assertHasClass('tedi-button--pr', $html, on: 'tedi-search__button');
        $this->assertMissingClass('tedi-button--pl', $html, on: 'tedi-search__button');
        $this->assertStringContainsString('Otsi', $html);
    }

    public function test_search_icon_only_button_classes(): void
    {
        $html = Blade::render('<tedi:search :button="[\'ariaLabel\' => \'Otsi\']" />');

        $this->assertHasClass('tedi-button--icon-only', $html, on: 'tedi-search__button');
        $this->assertMissingClass('tedi-button--pl', $html, on: 'tedi-search__button');
        $this->assertMissingClass('tedi-button--pr', $html, on: 'tedi-search__button');
        $this->assertStringContainsString('aria-label="Otsi"', $html);
    }

    public function test_search_button_size_follows_the_field_size(): void
    {
        $this->assertHasClass('tedi-button--small',
            Blade::render('<tedi:search size="small" :button="[]" />'), on: 'tedi-search__button');

        foreach (['default', 'large'] as $size) {
            $this->assertHasClass('tedi-button--default',
                Blade::render('<tedi:search size="'.$size.'" :button="[]" />'), on: 'tedi-search__button');
        }
    }

    public function test_search_button_icon_size_follows_the_field_size(): void
    {
        // buttonIconSize(): 24 (--icon-05) for large, 18 (--icon-03) otherwise.
        // Only exercised on the icon-only branch, where this component renders
        // the icon itself rather than delegating to <tedi:button>'s icon-start.
        $this->assertStringContainsString('--_tedi-icon-size: var(--icon-05)',
            Blade::render('<tedi:search size="large" :button="[]" />'));

        foreach (['default', 'small'] as $size) {
            $this->assertStringContainsString('--_tedi-icon-size: var(--icon-03)',
                Blade::render('<tedi:search size="'.$size.'" :button="[]" />'));
        }
    }

    public function test_search_aria_label_falls_back_through_label_then_placeholder(): void
    {
        $this->assertStringContainsString('aria-label="Otsi tooteid"',
            Blade::render('<tedi:search aria-label="Otsi tooteid" label="Otsing" placeholder="Trüki" />'));

        $this->assertStringContainsString('aria-label="Otsing"',
            Blade::render('<tedi:search label="Otsing" placeholder="Trüki" />'));

        $this->assertStringContainsString('aria-label="Trüki"',
            Blade::render('<tedi:search placeholder="Trüki" />'));

        $this->assertStringContainsString('aria-label="Search"', Blade::render('<tedi:search />'));
    }

    public function test_search_input_aria_label_is_suppressed_when_a_visible_label_exists(): void
    {
        $labelled = Blade::render('<tedi:search input-id="s" label="Otsing" />');
        $this->assertStringContainsString('for="s"', $labelled);
        // The input is named by the visible <label>, so aria-label would override it.
        $this->assertDoesNotMatchRegularExpression('/<input\s+tedi-text-field[^>]*aria-label=/s', $labelled);

        $unlabelled = Blade::render('<tedi:search input-id="s" placeholder="Trüki" />');
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*aria-label="Trüki"/s', $unlabelled);
    }

    public function test_search_feedback_text_is_wired_by_id(): void
    {
        $html = Blade::render('<tedi:search input-id="s" :feedback-text="[\'text\' => \'Vihjetekst\']" />');

        $this->assertStringContainsString('aria-describedby="s-feedback"', $html);
        $this->assertStringContainsString('id="s-feedback"', $html);
        $this->assertStringContainsString('Vihjetekst', $html);
    }

    public function test_search_without_feedback_text_emits_no_describedby(): void
    {
        $this->assertStringNotContainsString('aria-describedby', Blade::render('<tedi:search />'));
    }

    public function test_search_feedback_type_drives_the_form_field_state(): void
    {
        $error = Blade::render('<tedi:search :feedback-text="[\'text\' => \'Viga\', \'type\' => \'error\']" />');
        $this->assertHasClass('tedi-field-surface--invalid', $error, on: 'tedi-form-field__box');

        $valid = Blade::render('<tedi:search :feedback-text="[\'text\' => \'Sobib\', \'type\' => \'valid\']" />');
        $this->assertHasClass('tedi-field-surface--valid', $valid, on: 'tedi-form-field__box');

        $hint = Blade::render('<tedi:search :feedback-text="[\'text\' => \'Vihje\']" />');
        $this->assertMissingClass('tedi-field-surface--invalid', $hint, on: 'tedi-form-field__box');
        $this->assertMissingClass('tedi-field-surface--valid', $hint, on: 'tedi-form-field__box');
    }

    public function test_search_size_reaches_the_form_field(): void
    {
        $this->assertHasClass('tedi-form-field--small',
            Blade::render('<tedi:search size="small" />'), on: 'tedi-form-field');
        $this->assertHasClass('tedi-form-field--large',
            Blade::render('<tedi:search size="large" />'), on: 'tedi-form-field');
    }

    public function test_search_disabled_reaches_the_form_field_and_the_input(): void
    {
        $html = Blade::render('<tedi:search :disabled="true" />');

        $this->assertHasClass('tedi-field-surface--disabled', $html, on: 'tedi-form-field__box');
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field\s+disabled/', $html);
    }

    public function test_search_clear_button_appears_only_when_the_field_has_a_value(): void
    {
        $empty = Blade::render('<tedi:search />');
        $this->assertHasClass('tedi-form-field__buttons--hidden', $empty, on: 'tedi-form-field__buttons--hidden');

        $filled = Blade::render('<tedi:search value="Lorem ipsum" />');
        $this->assertMissingClass('tedi-form-field__buttons--hidden', $filled, on: 'tedi-form-field__buttons');
        $this->assertStringContainsString('value="Lorem ipsum"', $filled);
    }

    public function test_search_clearable_false_drops_the_clear_button(): void
    {
        $html = Blade::render('<tedi:search value="Lorem" :clearable="false" />');

        $this->assertMissingClass('tedi-form-field__clear', $html, on: 'tedi-form-field__clear');
    }

    public function test_search_forwards_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:search wire:model="query" />');

        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*wire:model="query"/s', $html);
    }

    public function test_search_forwards_button_and_clear_attributes(): void
    {
        $html = Blade::render(
            '<tedi:search value="Lorem"'
            .' :button="[\'text\' => \'Otsi\']"'
            .' :button-attributes="[\'wire:click\' => \'search\']"'
            .' :clear-attributes="[\'wire:click\' => \'reset\']" />'
        );

        $this->assertMatchesRegularExpression('/tedi-search__button[^>]*wire:click="search"/s', $html);
        $this->assertMatchesRegularExpression('/tedi-form-field__clear[^>]*wire:click="reset"/s', $html);
    }
}
