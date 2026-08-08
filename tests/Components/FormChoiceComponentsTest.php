<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class FormChoiceComponentsTest extends TestCase
{
    // -- checkbox ---------------------------------------------------------

    public function test_checkbox_has_base_attribute_and_type(): void
    {
        $html = Blade::render('<tedi:checkbox />');

        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*\stedi-checkbox[\s>]/', $html);
    }

    public function test_checkbox_size_classes(): void
    {
        $html = Blade::render('<tedi:checkbox size="default" />');
        $this->assertMissingClass('tedi-checkbox--large', $html);

        $html = Blade::render('<tedi:checkbox size="large" />');
        $this->assertHasClass('tedi-checkbox--large', $html);
    }

    public function test_checkbox_invalid_class(): void
    {
        $html = Blade::render('<tedi:checkbox :invalid="true" />');
        $this->assertHasClass('tedi-checkbox--invalid', $html);

        $html = Blade::render('<tedi:checkbox :invalid="false" />');
        $this->assertMissingClass('tedi-checkbox--invalid', $html);
    }

    public function test_checkbox_forwards_name_value_id_checked_disabled(): void
    {
        $html = Blade::render('<tedi:checkbox name="tags[]" value="urgent" id="tags-urgent" :checked="true" :disabled="true" />');

        $this->assertStringContainsString('name="tags[]"', $html);
        $this->assertStringContainsString('value="urgent"', $html);
        $this->assertStringContainsString('id="tags-urgent"', $html);
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_checkbox_forwards_wire_model_to_input(): void
    {
        $html = Blade::render('<tedi:checkbox wire:model="agree" />');

        $this->assertStringContainsString('<input', $html);
        $this->assertStringContainsString('wire:model="agree"', $html);
    }

    // -- radio --------------------------------------------------------------

    public function test_radio_has_base_attribute_and_type(): void
    {
        $html = Blade::render('<tedi:radio />');

        $this->assertStringContainsString('type="radio"', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*\stedi-radio[\s>]/', $html);
    }

    public function test_radio_size_classes(): void
    {
        $html = Blade::render('<tedi:radio size="default" />');
        $this->assertMissingClass('tedi-radio--large', $html);

        $html = Blade::render('<tedi:radio size="large" />');
        $this->assertHasClass('tedi-radio--large', $html);
    }

    public function test_radio_invalid_class(): void
    {
        $html = Blade::render('<tedi:radio :invalid="true" />');
        $this->assertHasClass('tedi-radio--invalid', $html);

        $html = Blade::render('<tedi:radio :invalid="false" />');
        $this->assertMissingClass('tedi-radio--invalid', $html);
    }

    public function test_radio_forwards_name_value_id_checked_disabled(): void
    {
        $html = Blade::render('<tedi:radio name="plan" value="pro" id="plan-pro" :checked="true" :disabled="true" />');

        $this->assertStringContainsString('name="plan"', $html);
        $this->assertStringContainsString('value="pro"', $html);
        $this->assertStringContainsString('id="plan-pro"', $html);
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_radio_forwards_wire_model_to_input(): void
    {
        $html = Blade::render('<tedi:radio wire:model="plan" value="pro" />');

        $this->assertStringContainsString('<input', $html);
        $this->assertStringContainsString('wire:model="plan"', $html);
    }

    // -- checkbox-card --------------------------------------------------------

    public function test_checkbox_card_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:checkbox-card variant="'.$variant.'">Text</tedi:checkbox-card>');

            $this->assertMatchesRegularExpression('/<label[^>]*\stedi-checkbox-card[\s>]/', $html);
            $this->assertHasClass('tedi-checkbox-card', $html, on: 'tedi-checkbox-card');
            $this->assertHasClass('tedi-checkbox-card--'.$variant, $html, on: 'tedi-checkbox-card');
        }
    }

    public function test_checkbox_card_hide_indicator_class(): void
    {
        $html = Blade::render('<tedi:checkbox-card :show-indicator="false">Text</tedi:checkbox-card>');
        $this->assertHasClass('tedi-checkbox-card--hide-indicator', $html, on: 'tedi-checkbox-card');

        $html = Blade::render('<tedi:checkbox-card>Text</tedi:checkbox-card>');
        $this->assertMissingClass('tedi-checkbox-card--hide-indicator', $html, on: 'tedi-checkbox-card');
    }

    public function test_checkbox_card_wraps_slot_in_content_div(): void
    {
        $html = Blade::render('<tedi:checkbox-card>Hello</tedi:checkbox-card>');

        $this->assertHasClass('tedi-checkbox-card__content', $html, on: 'tedi-checkbox-card__content');
        $this->assertStringContainsString('Hello', $html);
    }

    public function test_checkbox_card_renders_feedback_slot(): void
    {
        $html = Blade::render('<tedi:checkbox-card>Text<x-slot:feedback>Hint</x-slot:feedback></tedi:checkbox-card>');

        $this->assertStringContainsString('Hint', $html);
    }

    // -- checkbox-card-group ---------------------------------------------------

    public function test_checkbox_card_group_base_class(): void
    {
        $html = Blade::render('<tedi:checkbox-card-group>content</tedi:checkbox-card-group>');

        $this->assertHasClass('tedi-checkbox-card-group', $html, on: 'tedi-checkbox-card-group');
        $this->assertStringContainsString('content', $html);
    }

    // -- radio-card -------------------------------------------------------------

    public function test_radio_card_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:radio-card variant="'.$variant.'">Text</tedi:radio-card>');

            $this->assertMatchesRegularExpression('/<label[^>]*\stedi-radio-card[\s>]/', $html);
            $this->assertHasClass('tedi-radio-card', $html, on: 'tedi-radio-card');
            $this->assertHasClass('tedi-radio-card--'.$variant, $html, on: 'tedi-radio-card');
        }
    }

    public function test_radio_card_hide_indicator_class(): void
    {
        $html = Blade::render('<tedi:radio-card :show-indicator="false">Text</tedi:radio-card>');
        $this->assertHasClass('tedi-radio-card--hide-indicator', $html, on: 'tedi-radio-card');

        $html = Blade::render('<tedi:radio-card>Text</tedi:radio-card>');
        $this->assertMissingClass('tedi-radio-card--hide-indicator', $html, on: 'tedi-radio-card');
    }

    public function test_radio_card_wraps_slot_in_content_div(): void
    {
        $html = Blade::render('<tedi:radio-card>Hello</tedi:radio-card>');

        $this->assertHasClass('tedi-radio-card__content', $html, on: 'tedi-radio-card__content');
        $this->assertStringContainsString('Hello', $html);
    }

    public function test_radio_card_grouped_own_prop(): void
    {
        $html = Blade::render('<tedi:radio-card :grouped="true">Text</tedi:radio-card>');
        $this->assertHasClass('tedi-radio-card--grouped', $html, on: 'tedi-radio-card');

        $html = Blade::render('<tedi:radio-card>Text</tedi:radio-card>');
        $this->assertMissingClass('tedi-radio-card--grouped', $html, on: 'tedi-radio-card');
    }

    public function test_radio_card_grouped_does_not_leak_as_html_attribute(): void
    {
        $html = Blade::render('<tedi:radio-card :grouped="true">Text</tedi:radio-card>');

        $this->assertStringNotContainsString('grouped="1"', $html);
        $this->assertStringNotContainsString('grouped="true"', $html);
    }

    public function test_radio_card_inherits_grouped_from_ancestor_group(): void
    {
        $html = Blade::render('<tedi:radio-card-group :grouped="true"><tedi:radio-card>Text</tedi:radio-card></tedi:radio-card-group>');

        $this->assertHasClass('tedi-radio-card-group--grouped', $html, on: 'tedi-radio-card-group');
        $this->assertHasClass('tedi-radio-card--grouped', $html, on: 'tedi-radio-card');
    }

    public function test_radio_card_not_grouped_when_ancestor_group_is_not_grouped(): void
    {
        $html = Blade::render('<tedi:radio-card-group><tedi:radio-card>Text</tedi:radio-card></tedi:radio-card-group>');

        $this->assertMissingClass('tedi-radio-card--grouped', $html, on: 'tedi-radio-card');
    }

    // -- radio-card-group ------------------------------------------------------

    public function test_radio_card_group_base_class(): void
    {
        $html = Blade::render('<tedi:radio-card-group>content</tedi:radio-card-group>');

        $this->assertHasClass('tedi-radio-card-group', $html, on: 'tedi-radio-card-group');
        $this->assertMissingClass('tedi-radio-card-group--grouped', $html, on: 'tedi-radio-card-group');
        $this->assertStringContainsString('content', $html);
    }

    public function test_radio_card_group_grouped_class(): void
    {
        $html = Blade::render('<tedi:radio-card-group :grouped="true">content</tedi:radio-card-group>');

        $this->assertHasClass('tedi-radio-card-group--grouped', $html, on: 'tedi-radio-card-group');
    }
}
