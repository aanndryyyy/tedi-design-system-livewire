<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for helpers/option-content (CONVENTIONS.md §10), formerly
 * overlay/dropdown/dropdown-item-value (renamed upstream in Angular 9, #491).
 *
 * The prop unions are taken from the Angular `export type` declarations:
 *   OptionContentType   = 'default' | 'checkbox' | 'radio'
 *   OptionContentLayout = 'horizontal' | 'vertical'
 */
class OptionContentComponentsTest extends TestCase
{
    // -- option-content --------------------------------------------

    public function test_option_content_layout_union_classes(): void
    {
        $html = Blade::render('<tedi:option-content>x</tedi:option-content>');
        $this->assertHasClass('tedi-option-content', $html, 'tedi-option-content');
        $this->assertHasClass('tedi-option-content--horizontal', $html, 'tedi-option-content');
        $this->assertMissingClass('tedi-option-content--vertical', $html, 'tedi-option-content');

        $html = Blade::render('<tedi:option-content layout="vertical">x</tedi:option-content>');
        $this->assertHasClass('tedi-option-content--vertical', $html, 'tedi-option-content');
        $this->assertMissingClass('tedi-option-content--horizontal', $html, 'tedi-option-content');
    }

    public function test_option_content_type_union_classes(): void
    {
        $expected = [
            'default' => null,
            'checkbox' => 'tedi-option-content--checkbox',
            'radio' => 'tedi-option-content--radio',
        ];

        foreach ($expected as $type => $class) {
            $html = Blade::render('<tedi:option-content type="'.$type.'">x</tedi:option-content>');

            foreach (array_filter($expected) as $candidate) {
                if ($candidate === $class) {
                    $this->assertHasClass($candidate, $html, 'tedi-option-content');
                } else {
                    $this->assertMissingClass($candidate, $html, 'tedi-option-content');
                }
            }
        }
    }

    public function test_option_content_renders_the_matching_indicator(): void
    {
        $html = Blade::render('<tedi:option-content>x</tedi:option-content>');
        $this->assertStringNotContainsString('type="checkbox"', $html);
        $this->assertStringNotContainsString('type="radio"', $html);

        $html = Blade::render('<tedi:option-content type="checkbox">x</tedi:option-content>');
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('tedi-checkbox', $html);
        $this->assertHasClass('tedi-option-content__checkbox', $html, 'tedi-option-content__checkbox');
        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);

        $html = Blade::render('<tedi:option-content type="radio">x</tedi:option-content>');
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertHasClass('tedi-option-content__radio', $html, 'tedi-option-content__radio');
    }

    public function test_option_content_indicator_reflects_selected_and_disabled(): void
    {
        $html = Blade::render('<tedi:option-content type="checkbox" :selected="true" :disabled="true">x</tedi:option-content>');
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('disabled', $html);

        $html = Blade::render('<tedi:option-content type="radio">x</tedi:option-content>');
        $this->assertStringNotContainsString('checked', $html);
        $this->assertStringNotContainsString('disabled', $html);
    }

    public function test_option_content_indeterminate_is_set_as_a_dom_property(): void
    {
        $html = Blade::render('<tedi:option-content type="checkbox" :indeterminate="true">x</tedi:option-content>');
        $this->assertStringContainsString('x-init="$el.indeterminate = true"', $html);

        $html = Blade::render('<tedi:option-content type="checkbox">x</tedi:option-content>');
        $this->assertStringNotContainsString('indeterminate', $html);
    }

    public function test_option_content_content_wrapper_and_slots(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:option-content>
            <x-slot:icon><tedi:icon name="edit" :size="18" /></x-slot:icon>
            <tedi:option-content-label>Muuda</tedi:option-content-label>
            <x-slot:after><span id="trailing">→</span></x-slot:after>
        </tedi:option-content>
        BLADE);

        $this->assertHasClass('tedi-option-content__content', $html, 'tedi-option-content__content');

        $this->assertLessThan(
            strpos($html, 'tedi-option-content__content'),
            strpos($html, 'tedi-icon'),
            'The icon slot must render before the content wrapper, as in Angular.'
        );

        $this->assertLessThan(
            strpos($html, 'id="trailing"'),
            strpos($html, 'tedi-option-content__content'),
            'The after slot must render below the content wrapper, as in Angular.'
        );
    }

    // -- option-content-label / -meta -------------------------------

    public function test_label_classes_and_element(): void
    {
        $html = Blade::render('<tedi:option-content-label>Tallinn</tedi:option-content-label>');
        $this->assertStringContainsString('<tedi-option-content-label', $html);
        $this->assertHasClass('tedi-option-content__label', $html, 'tedi-option-content__label');
        $this->assertMissingClass('tedi-option-content__label--no-clip', $html, 'tedi-option-content__label');

        $html = Blade::render('<tedi:option-content-label :clip-content="false">Tallinn</tedi:option-content-label>');
        $this->assertHasClass('tedi-option-content__label--no-clip', $html, 'tedi-option-content__label');
    }

    public function test_meta_classes_and_element(): void
    {
        $html = Blade::render('<tedi:option-content-meta>3 vaba aega</tedi:option-content-meta>');

        $this->assertStringContainsString('<tedi-option-content-meta', $html);
        $this->assertHasClass('tedi-option-content__meta', $html, 'tedi-option-content__meta');
        $this->assertStringContainsString('3 vaba aega', $html);
    }

    public function test_consumer_classes_merge_instead_of_replacing(): void
    {
        $html = Blade::render('<tedi:option-content class="my-own">x</tedi:option-content>');

        $this->assertHasClass('tedi-option-content', $html, 'tedi-option-content');
        $this->assertHasClass('my-own', $html, 'tedi-option-content');
    }
}
