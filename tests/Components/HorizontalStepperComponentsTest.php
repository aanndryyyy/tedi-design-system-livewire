<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class HorizontalStepperComponentsTest extends TestCase
{
    // -- horizontal-stepper ---------------------------------------------

    public function test_stepper_renders_the_custom_element_with_base_class(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper>x</tedi:horizontal-stepper>');

        $this->assertStringContainsString('<tedi-horizontal-stepper', $html);
        $this->assertHasClass('tedi-horizontal-stepper', $html, 'tedi-horizontal-stepper');
    }

    public function test_stepper_has_navigation_role(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper>x</tedi:horizontal-stepper>');

        $this->assertStringContainsString('role="navigation"', $html);
    }

    public function test_stepper_aria_label(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper aria-label="Vormi edenemine">x</tedi:horizontal-stepper>');
        $this->assertStringContainsString('aria-label="Vormi edenemine"', $html);

        $html = Blade::render('<tedi:horizontal-stepper>x</tedi:horizontal-stepper>');
        $this->assertStringNotContainsString('aria-label', $html);
    }

    public function test_stepper_background_classes(): void
    {
        foreach (['default', 'transparent'] as $background) {
            $html = Blade::render('<tedi:horizontal-stepper background="'.$background.'">x</tedi:horizontal-stepper>');

            if ($background === 'transparent') {
                $this->assertHasClass('tedi-horizontal-stepper--transparent', $html, 'tedi-horizontal-stepper');
            } else {
                $this->assertMissingClass('tedi-horizontal-stepper--transparent', $html, 'tedi-horizontal-stepper');
            }
        }
    }

    public function test_stepper_compact_defaults_to_the_sm_breakpoint(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper>x</tedi:horizontal-stepper>');

        $this->assertHasClass('tedi-horizontal-stepper--compact-sm', $html, 'tedi-horizontal-stepper');
        $this->assertMissingClass('tedi-horizontal-stepper--compact', $html, 'tedi-horizontal-stepper');
    }

    public function test_stepper_compact_true_emits_the_unsuffixed_class(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper :compact="true">x</tedi:horizontal-stepper>');

        $this->assertHasClass('tedi-horizontal-stepper--compact', $html, 'tedi-horizontal-stepper');
        $this->assertMissingClass('tedi-horizontal-stepper--compact-sm', $html, 'tedi-horizontal-stepper');
        // `true` must not concatenate into the breakpoint branch.
        $this->assertMissingClass('tedi-horizontal-stepper--compact-1', $html, 'tedi-horizontal-stepper');
    }

    public function test_stepper_compact_false_emits_no_compact_class(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper :compact="false">x</tedi:horizontal-stepper>');

        $tokens = $this->classesOf($html, 'tedi-horizontal-stepper');

        foreach ($tokens as $token) {
            $this->assertStringStartsNotWith('tedi-horizontal-stepper--compact', $token);
        }
    }

    public function test_stepper_compact_breakpoint_classes(): void
    {
        foreach (['sm', 'md', 'lg', 'xl', 'xxl'] as $breakpoint) {
            $html = Blade::render('<tedi:horizontal-stepper compact="'.$breakpoint.'">x</tedi:horizontal-stepper>');

            $this->assertHasClass('tedi-horizontal-stepper--compact-'.$breakpoint, $html, 'tedi-horizontal-stepper');
            $this->assertMissingClass('tedi-horizontal-stepper--compact', $html, 'tedi-horizontal-stepper');
        }
    }

    public function test_stepper_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper class="my-stepper">x</tedi:horizontal-stepper>');

        $this->assertHasClass('my-stepper', $html, 'tedi-horizontal-stepper');
        $this->assertHasClass('tedi-horizontal-stepper', $html, 'tedi-horizontal-stepper');
    }

    // -- horizontal-stepper-item ----------------------------------------

    public function test_item_renders_the_custom_element_and_a_button(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" />');

        $this->assertStringContainsString('<tedi-horizontal-stepper-item', $html);
        $this->assertHasClass('tedi-horizontal-stepper-item', $html, 'tedi-horizontal-stepper-item');
        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertHasClass('tedi-horizontal-stepper-item__step', $html, 'tedi-horizontal-stepper-item__step');
    }

    public function test_item_renders_label_and_step_number(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :step-number="3" />');

        $this->assertStringContainsString('>Kutse</span>', $html);
        $this->assertStringContainsString('>3</span>', $html);
        $this->assertHasClass('tedi-horizontal-stepper-item__number', $html, 'tedi-horizontal-stepper-item__number');
    }

    public function test_item_step_number_defaults_to_one(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" />');

        $this->assertStringContainsString('>1</span>', $html);
    }

    public function test_item_description_renders_only_when_given(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" description="Ametnik täidab" />');
        $this->assertHasClass('tedi-horizontal-stepper-item__description', $html, 'tedi-horizontal-stepper-item__description');
        $this->assertStringContainsString('Ametnik täidab', $html);

        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" />');
        $this->assertMissingClass('tedi-horizontal-stepper-item__description', $html, 'tedi-horizontal-stepper-item__description');
    }

    public function test_item_state_classes(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" />');
        $this->assertMissingClass('tedi-horizontal-stepper-item--selected', $html, 'tedi-horizontal-stepper-item');
        $this->assertMissingClass('tedi-horizontal-stepper-item--completed', $html, 'tedi-horizontal-stepper-item');
        $this->assertMissingClass('tedi-horizontal-stepper-item--error', $html, 'tedi-horizontal-stepper-item');

        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :selected="true" />');
        $this->assertHasClass('tedi-horizontal-stepper-item--selected', $html, 'tedi-horizontal-stepper-item');

        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :completed="true" />');
        $this->assertHasClass('tedi-horizontal-stepper-item--completed', $html, 'tedi-horizontal-stepper-item');

        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :error="true" />');
        $this->assertHasClass('tedi-horizontal-stepper-item--error', $html, 'tedi-horizontal-stepper-item');
    }

    public function test_item_error_wins_over_completed(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :completed="true" :error="true" />');

        $this->assertHasClass('tedi-horizontal-stepper-item--error', $html, 'tedi-horizontal-stepper-item');
        $this->assertMissingClass('tedi-horizontal-stepper-item--completed', $html, 'tedi-horizontal-stepper-item');
        $this->assertStringContainsString('>exclamation</tedi-icon>', $html);
    }

    public function test_item_completed_renders_the_check_icon_instead_of_the_number(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :completed="true" :step-number="2" />');

        $this->assertStringContainsString('>check</tedi-icon>', $html);
        $this->assertMissingClass('tedi-horizontal-stepper-item__number', $html, 'tedi-horizontal-stepper-item__number');
    }

    public function test_item_error_renders_the_exclamation_icon_instead_of_the_number(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :error="true" />');

        $this->assertStringContainsString('>exclamation</tedi-icon>', $html);
        $this->assertMissingClass('tedi-horizontal-stepper-item__number', $html, 'tedi-horizontal-stepper-item__number');
    }

    public function test_item_selected_sets_aria_current_on_the_button(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :selected="true" />');
        $this->assertStringContainsString('aria-current="step"', $html);

        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" />');
        $this->assertStringNotContainsString('aria-current', $html);
    }

    public function test_item_disabled_disables_the_button_and_emits_no_modifier_class(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" />');
        $this->assertStringNotContainsString('disabled', $html);

        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" :disabled="true" />');

        $this->assertStringContainsString('disabled', $html);
        // Angular emits `--disabled`; the vendored SCSS has no rule for it, so
        // it is dropped per CONVENTIONS.md §4.
        $this->assertMissingClass('tedi-horizontal-stepper-item--disabled', $html, 'tedi-horizontal-stepper-item');
    }

    public function test_item_forwards_consumer_attributes_to_the_root(): void
    {
        $html = Blade::render('<tedi:horizontal-stepper-item label="Kutse" class="my-step" data-index="0" />');

        $this->assertHasClass('my-step', $html, 'tedi-horizontal-stepper-item');
        $this->assertStringContainsString('data-index="0"', $html);
    }

    // -- variant matrix smoke test ---------------------------------------

    public function test_horizontal_stepper_matrix_renders(): void
    {
        $path = __DIR__.'/../fixtures/matrices/horizontal-stepper.blade.php';
        $out = Blade::render(file_get_contents($path));

        $this->assertHasClass('tedi-horizontal-stepper', $out, 'tedi-horizontal-stepper');
        $this->assertHasClass('tedi-horizontal-stepper-item', $out, 'tedi-horizontal-stepper-item');
    }
}
