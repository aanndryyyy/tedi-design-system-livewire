<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class ButtonGroupComponentsTest extends TestCase
{
    private const ITEMS = "[['value' => '1', 'label' => 'Tabel'], ['value' => '2', 'label' => 'Loend'], ['value' => '3', 'label' => 'Kalender']]";

    // -- button-group: root ----------------------------------------------

    public function test_renders_the_custom_element_with_base_class(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');

        $this->assertStringContainsString('<tedi-button-group', $html);
        $this->assertHasClass('tedi-button-group', $html, 'tedi-button-group');
    }

    public function test_stretch_class(): void
    {
        $html = Blade::render('<tedi:button-group :stretch="true" :items="'.self::ITEMS.'" />');
        $this->assertHasClass('tedi-button-group--stretch', $html, 'tedi-button-group');

        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');
        $this->assertMissingClass('tedi-button-group--stretch', $html, 'tedi-button-group');
    }

    public function test_segmented_class_only_for_the_button_group_variants(): void
    {
        foreach (['primary-button-group', 'secondary-button-group'] as $variant) {
            $html = Blade::render('<tedi:button-group variant="'.$variant.'" :items="'.self::ITEMS.'" />');
            $this->assertHasClass('tedi-button-group--segmented', $html, 'tedi-button-group');
        }

        foreach (['primary', 'secondary', 'success', 'danger'] as $variant) {
            $html = Blade::render('<tedi:button-group variant="'.$variant.'" :items="'.self::ITEMS.'" />');
            $this->assertMissingClass('tedi-button-group--segmented', $html, 'tedi-button-group');
        }
    }

    public function test_dropdown_mode_class_and_role(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');
        $this->assertMissingClass('tedi-button-group--dropdown-mode', $html, 'tedi-button-group');
        $this->assertStringContainsString('role="group"', $html);

        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.self::ITEMS.'" />');
        $this->assertHasClass('tedi-button-group--dropdown-mode', $html, 'tedi-button-group');
        // Angular nulls the role in dropdown mode.
        $this->assertStringNotContainsString('role="group"', $html);
    }

    public function test_aria_label(): void
    {
        $html = Blade::render('<tedi:button-group aria-label="Vaate valik" :items="'.self::ITEMS.'" />');

        $this->assertStringContainsString('aria-label="Vaate valik"', $html);
    }

    public function test_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:button-group class="my-group" :items="'.self::ITEMS.'" />');

        $this->assertHasClass('my-group', $html, 'tedi-button-group');
        $this->assertHasClass('tedi-button-group', $html, 'tedi-button-group');
    }

    // -- button-group-button ---------------------------------------------

    public function test_item_renders_a_button_carrying_the_literal_attribute(): void
    {
        $html = Blade::render('<tedi:button-group-button value="1" label="Tabel" />');

        $this->assertStringContainsString('tedi-button-group-button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertHasClass('tedi-button', $html, 'tedi-button-group-button');
        $this->assertHasClass('tedi-button-group-button', $html, 'tedi-button-group-button');
    }

    public function test_item_label_is_wrapped_in_the_label_span(): void
    {
        $html = Blade::render('<tedi:button-group-button value="1" label="Tabel" />');

        $this->assertStringContainsString('<span class="tedi-button-group-button__label">Tabel</span>', $html);
    }

    public function test_item_variant_and_size_classes(): void
    {
        foreach (['primary-button-group', 'secondary-button-group', 'primary', 'secondary', 'success', 'danger'] as $variant) {
            $html = Blade::render('<tedi:button-group-button value="1" label="T" variant="'.$variant.'" />');
            $this->assertHasClass('tedi-button--'.$variant, $html, 'tedi-button-group-button');
        }

        foreach (['default', 'small'] as $size) {
            $html = Blade::render('<tedi:button-group-button value="1" label="T" size="'.$size.'" />');
            $this->assertHasClass('tedi-button--'.$size, $html, 'tedi-button-group-button');
        }
    }

    public function test_item_aria_pressed_is_always_emitted(): void
    {
        // Load-bearing: the selected appearance is keyed off [aria-pressed="true"].
        $html = Blade::render('<tedi:button-group-button value="1" label="T" :selected="true" />');
        $this->assertStringContainsString('aria-pressed="true"', $html);

        $html = Blade::render('<tedi:button-group-button value="1" label="T" />');
        $this->assertStringContainsString('aria-pressed="false"', $html);
    }

    public function test_item_disabled(): void
    {
        $html = Blade::render('<tedi:button-group-button value="1" label="T" :disabled="true" />');
        $this->assertStringContainsString('disabled', $html);

        $html = Blade::render('<tedi:button-group-button value="1" label="T" />');
        $this->assertStringNotContainsString('disabled', $html);
    }

    public function test_item_padding_modifiers_follow_the_icon_positions(): void
    {
        $html = Blade::render('<tedi:button-group-button value="1" label="T" />');
        $this->assertHasClass('tedi-button--pl', $html, 'tedi-button-group-button');
        $this->assertHasClass('tedi-button--pr', $html, 'tedi-button-group-button');
        $this->assertMissingClass('tedi-button--icon-only', $html, 'tedi-button-group-button');

        $html = Blade::render('<tedi:button-group-button value="1" label="T" icon-left="table" />');
        $this->assertMissingClass('tedi-button--pl', $html, 'tedi-button-group-button');
        $this->assertHasClass('tedi-button--pr', $html, 'tedi-button-group-button');

        $html = Blade::render('<tedi:button-group-button value="1" label="T" icon-right="arrow_forward" />');
        $this->assertHasClass('tedi-button--pl', $html, 'tedi-button-group-button');
        $this->assertMissingClass('tedi-button--pr', $html, 'tedi-button-group-button');
    }

    public function test_item_icon_only_mode(): void
    {
        $html = Blade::render('<tedi:button-group-button value="1" label="Tabel" icon="table" />');

        $this->assertHasClass('tedi-button--icon-only', $html, 'tedi-button-group-button');
        $this->assertMissingClass('tedi-button--pl', $html, 'tedi-button-group-button');
        $this->assertMissingClass('tedi-button--pr', $html, 'tedi-button-group-button');
        // The label becomes the accessible name instead of visible text.
        $this->assertStringContainsString('aria-label="Tabel"', $html);
        $this->assertStringNotContainsString('tedi-button-group-button__label', $html);
        $this->assertStringContainsString('>table</tedi-icon>', $html);
    }

    // -- the variant/size cascade (@aware) --------------------------------

    public function test_items_inherit_the_group_variant_and_size(): void
    {
        $html = Blade::render(
            '<tedi:button-group variant="secondary-button-group" size="small" :items="'.self::ITEMS.'" />'
        );

        $this->assertHasClass('tedi-button--secondary-button-group', $html, 'tedi-button-group-button');
        $this->assertHasClass('tedi-button--small', $html, 'tedi-button-group-button');
    }

    public function test_slot_items_inherit_the_group_variant_and_size_through_aware(): void
    {
        $html = Blade::render(
            '<tedi:button-group variant="secondary-button-group" size="small">'
            .'<tedi:button-group-button value="a" label="A" />'
            .'</tedi:button-group>'
        );

        $this->assertHasClass('tedi-button--secondary-button-group', $html, 'tedi-button-group-button');
        $this->assertHasClass('tedi-button--small', $html, 'tedi-button-group-button');
    }

    public function test_group_defaults_reach_slot_items_when_the_consumer_omits_them(): void
    {
        // The @aware fallbacks must equal the parent's @props defaults.
        $bare = Blade::render('<tedi:button-group><tedi:button-group-button value="a" label="A" /></tedi:button-group>');
        $explicit = Blade::render(
            '<tedi:button-group variant="primary-button-group" size="default">'
            .'<tedi:button-group-button value="a" label="A" />'
            .'</tedi:button-group>'
        );

        $this->assertSame(
            $this->classesOf($bare, 'tedi-button-group-button'),
            $this->classesOf($explicit, 'tedi-button-group-button')
        );
    }

    public function test_an_item_can_override_the_inherited_variant_and_size(): void
    {
        $html = Blade::render(
            '<tedi:button-group variant="secondary-button-group" size="small">'
            .'<tedi:button-group-button value="a" label="A" variant="danger" size="default" />'
            .'</tedi:button-group>'
        );

        $this->assertHasClass('tedi-button--danger', $html, 'tedi-button-group-button');
        $this->assertHasClass('tedi-button--default', $html, 'tedi-button-group-button');
        $this->assertMissingClass('tedi-button--secondary-button-group', $html, 'tedi-button-group-button');
        $this->assertMissingClass('tedi-button--small', $html, 'tedi-button-group-button');
    }

    public function test_per_item_variant_override_in_the_items_array(): void
    {
        $html = Blade::render(
            '<tedi:button-group variant="primary-button-group" :items="[[\'value\' => \'1\', \'label\' => \'T\', \'variant\' => \'danger\']]" />'
        );

        $this->assertHasClass('tedi-button--danger', $html, 'tedi-button-group-button');
    }

    public function test_variant_and_size_do_not_leak_into_the_dom(): void
    {
        $html = Blade::render(
            '<tedi:button-group variant="secondary-button-group" size="small">'
            .'<tedi:button-group-button value="a" label="A" variant="danger" />'
            .'</tedi:button-group>'
        );

        $this->assertStringNotContainsString('variant=', $html);
        $this->assertStringNotContainsString('size=', $html);
    }

    // -- selection ---------------------------------------------------------

    public function test_group_value_marks_the_matching_item_selected(): void
    {
        $html = Blade::render('<tedi:button-group value="2" :items="'.self::ITEMS.'" />');

        $this->assertSame(1, substr_count($html, 'aria-pressed="true"'));
        $this->assertSame(2, substr_count($html, 'aria-pressed="false"'));
    }

    public function test_multiple_mode_selects_every_listed_value(): void
    {
        $html = Blade::render(
            '<tedi:button-group :multiple="true" :value="[\'1\', \'3\']" :items="'.self::ITEMS.'" />'
        );

        $this->assertSame(2, substr_count($html, 'aria-pressed="true"'));
        $this->assertSame(1, substr_count($html, 'aria-pressed="false"'));
    }

    public function test_nothing_is_selected_without_a_value(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');

        $this->assertSame(0, substr_count($html, 'aria-pressed="true"'));
    }

    // -- live selection (CONVENTIONS.md §8) ---------------------------------

    public function test_the_group_seeds_the_alpine_state_from_value(): void
    {
        $html = Blade::render('<tedi:button-group value="2" :items="'.self::ITEMS.'" />');

        $this->assertStringContainsString('x-data=', $html);
        $this->assertStringContainsString("tediValue: '2',", $html);
        $this->assertStringContainsString('tediMultiple: false', $html);
    }

    public function test_multiple_mode_seeds_the_alpine_state_with_an_array(): void
    {
        $html = Blade::render(
            '<tedi:button-group :multiple="true" :value="[\'1\', \'3\']" :items="'.self::ITEMS.'" />'
        );

        $this->assertStringContainsString('tediMultiple: true', $html);
        $this->assertStringContainsString('tediValue: JSON.parse(', $html);
        $this->assertStringContainsString('1\\u0022,\\u00223', $html);
    }

    public function test_a_valueless_group_seeds_null_in_single_mode_and_an_empty_array_in_multiple(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');
        $this->assertStringContainsString('tediValue: null', $html);

        $html = Blade::render('<tedi:button-group :multiple="true" :items="'.self::ITEMS.'" />');
        $this->assertStringContainsString('tediValue: []', $html);
    }

    public function test_each_item_toggles_the_group_state_and_binds_its_pressed_state(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');

        $this->assertSame(3, substr_count($html, 'x-on:click="tediToggle('));
        $this->assertStringContainsString('x-on:click="tediToggle(&quot;1&quot;)"', $html);
        $this->assertStringContainsString(
            'x-bind:aria-pressed="tediIsSelected(&quot;1&quot;).toString()"',
            $html
        );
    }

    public function test_the_server_rendered_pressed_state_survives_alongside_the_binding(): void
    {
        // §8: stripping the JS must still leave the selected item painted, since
        // [aria-pressed="true"] carries the entire selected appearance.
        $html = Blade::render('<tedi:button-group value="2" :items="'.self::ITEMS.'" />');

        $this->assertSame(1, substr_count($html, 'aria-pressed="true"'));
    }

    public function test_dropdown_items_toggle_the_same_state_and_disabled_ones_do_not(): void
    {
        $items = "[['value' => '1', 'label' => 'Tabel'], ['value' => '2', 'label' => 'Loend', 'disabled' => true]]";

        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.$items.'" />');

        // .capture, so it does not collide with dropdown-item's own x-on:click.
        $this->assertStringContainsString('x-on:click.capture="tediToggle(&quot;1&quot;)"', $html);
        $this->assertStringContainsString('x-on:click.capture="null"', $html);
        $this->assertStringContainsString(
            'x-bind:class="{ &#039;tedi-dropdown-item--selected&#039;: tediIsSelected(&quot;1&quot;) }"',
            $html
        );
    }

    public function test_the_dropdown_trigger_tracks_the_selection_when_the_label_mode_is_selected(): void
    {
        $items = "[['value' => '1', 'label' => 'Tabel', 'iconLeft' => 'table'], ['value' => '2', 'label' => 'Loend', 'iconLeft' => 'list']]";

        $html = Blade::render(
            '<tedi:button-group :dropdown-mode="true" dropdown-label-mode="selected" value="2" :items="'.$items.'" />'
        );

        $this->assertStringContainsString('tediStaticLabel: false', $html);
        $this->assertStringContainsString('x-text="tediTriggerLabel()"', $html);
        $this->assertStringContainsString('tediTriggerIcon()', $html);
        // The static markup still carries the resolved label and icon.
        $this->assertStringContainsString('>Loend</span>', $html);
        $this->assertStringContainsString('>list</tedi-icon>', $html);
    }

    public function test_a_static_trigger_keeps_its_label_even_though_the_bindings_are_unconditional(): void
    {
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.self::ITEMS.'" />');

        $this->assertStringContainsString('tediStaticLabel: true', $html);
        $this->assertStringContainsString('>Menu</span>', $html);
    }

    public function test_a_plain_strip_carries_no_trigger_state(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');

        $this->assertStringNotContainsString('tediLabels', $html);
        $this->assertStringNotContainsString('tediTriggerLabel', $html);
    }

    // -- dropdown branch ---------------------------------------------------

    public function test_no_dropdown_is_rendered_unless_dropdown_mode_is_on(): void
    {
        $html = Blade::render('<tedi:button-group :items="'.self::ITEMS.'" />');

        $this->assertStringNotContainsString('<tedi-dropdown', $html);
    }

    public function test_dropdown_mode_renders_both_the_strip_and_the_dropdown(): void
    {
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.self::ITEMS.'" />');

        // Angular renders <ng-content /> unconditionally; the CSS hides the strip.
        $this->assertSame(3, substr_count($html, 'tedi-button-group-button__label'));
        $this->assertStringContainsString('<tedi-dropdown', $html);
        $this->assertHasClass('tedi-button-group__dropdown', $html, 'tedi-button-group__dropdown');
        $this->assertHasClass('tedi-button-group__dropdown-trigger', $html, 'tedi-button-group__dropdown-trigger');
    }

    public function test_dropdown_trigger_variant_maps_group_variants_to_plain_ones(): void
    {
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" variant="primary-button-group" :items="'.self::ITEMS.'" />');
        $this->assertHasClass('tedi-button--primary', $html, 'tedi-button-group__dropdown-trigger');

        $html = Blade::render('<tedi:button-group :dropdown-mode="true" variant="secondary-button-group" :items="'.self::ITEMS.'" />');
        $this->assertHasClass('tedi-button--secondary', $html, 'tedi-button-group__dropdown-trigger');

        $html = Blade::render('<tedi:button-group :dropdown-mode="true" variant="danger" :items="'.self::ITEMS.'" />');
        $this->assertHasClass('tedi-button--danger', $html, 'tedi-button-group__dropdown-trigger');
    }

    public function test_dropdown_trigger_leads_with_an_icon_so_it_drops_the_left_padding(): void
    {
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.self::ITEMS.'" />');

        $this->assertMissingClass('tedi-button--pl', $html, 'tedi-button-group__dropdown-trigger');
        $this->assertHasClass('tedi-button--pr', $html, 'tedi-button-group__dropdown-trigger');
    }

    public function test_dropdown_label_defaults_to_the_translation(): void
    {
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.self::ITEMS.'" />');
        $this->assertStringContainsString('Menu', $html);
        $this->assertStringContainsString('>menu</tedi-icon>', $html);

        $html = Blade::render('<tedi:button-group :dropdown-mode="true" dropdown-label="Alammenüü" :items="'.self::ITEMS.'" />');
        $this->assertStringContainsString('Alammenüü', $html);
    }

    public function test_dropdown_label_mode_selected_shows_the_selected_items_label_and_icon(): void
    {
        $items = "[['value' => '1', 'label' => 'Tabel', 'iconLeft' => 'table'], ['value' => '2', 'label' => 'Loend', 'iconLeft' => 'list']]";

        $html = Blade::render(
            '<tedi:button-group :dropdown-mode="true" dropdown-label-mode="selected" value="2" :items="'.$items.'" />'
        );

        $this->assertStringContainsString('Loend', $html);
        $this->assertStringContainsString('>list</tedi-icon>', $html);
    }

    public function test_dropdown_label_mode_selected_falls_back_when_nothing_is_selected(): void
    {
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" dropdown-label-mode="selected" :items="'.self::ITEMS.'" />');

        $this->assertStringContainsString('Menu', $html);
        $this->assertStringContainsString('>menu</tedi-icon>', $html);
    }

    public function test_dropdown_label_mode_selected_is_ignored_in_multiple_mode(): void
    {
        $html = Blade::render(
            '<tedi:button-group :dropdown-mode="true" dropdown-label-mode="selected" :multiple="true" :value="[\'2\']" :items="'.self::ITEMS.'" />'
        );

        $this->assertStringContainsString('Menu', $html);
    }

    public function test_dropdown_items_mirror_selection_and_disabled_state(): void
    {
        $items = "[['value' => '1', 'label' => 'Tabel'], ['value' => '2', 'label' => 'Loend', 'disabled' => true]]";

        $html = Blade::render('<tedi:button-group :dropdown-mode="true" value="1" :items="'.$items.'" />');

        $this->assertHasClass('tedi-dropdown-item--selected', $html, 'tedi-dropdown-item--selected');
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    public function test_unselected_dropdown_items_carry_no_selected_class(): void
    {
        // Angular emits no class attribute at all; Blade cannot express "attribute
        // absent" here, so an empty class="" stands in. What matters for §4 is
        // that it carries no class tokens — see the note in button-group.blade.php.
        // The name still appears in the x-bind:class expression that keeps the
        // item in sync after a click, so this asserts on the class attribute
        // rather than the raw markup.
        $html = Blade::render('<tedi:button-group :dropdown-mode="true" :items="'.self::ITEMS.'" />');

        // Same regex as classesOf(): real class attributes only, never a binding.
        preg_match_all('/(?<![-:.\w])class="([^"]*)"/', $html, $matches);

        foreach ($matches[1] as $classAttr) {
            $this->assertNotContains(
                'tedi-dropdown-item--selected',
                preg_split('/\s+/', trim($classAttr), -1, PREG_SPLIT_NO_EMPTY)
            );
        }
    }

    // -- variant matrix smoke test ---------------------------------------

    public function test_button_group_matrix_renders(): void
    {
        $path = __DIR__.'/../fixtures/matrices/button-group.blade.php';
        $out = Blade::render(file_get_contents($path));

        $this->assertHasClass('tedi-button-group', $out, 'tedi-button-group');
        $this->assertHasClass('tedi-button-group-button', $out, 'tedi-button-group-button');
    }
}
