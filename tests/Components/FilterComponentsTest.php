<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the filter family (CONVENTIONS.md §10).
 *
 * The prop unions are taken from the Angular `export type` declarations:
 *   FilterVariant = 'primary' | 'secondary'
 *   FilterSize    = 'default' | 'large'
 *
 * Everything else on the component is boolean, an option list, or free text.
 */
class FilterComponentsTest extends TestCase
{
    private const OPTIONS = "[['label' => 'Optometrist', 'value' => '1'], "
        ."['label' => 'Silmaarst', 'value' => '2'], "
        ."['label' => 'Hambaarst', 'value' => '3', 'disabled' => true]]";

    private function withOptions(string $extra = ''): string
    {
        return Blade::render(
            '<tedi:filter text="Teenus" :options="'.self::OPTIONS.'" '.$extra.' />'
        );
    }

    // -- filter: host classes -------------------------------------------

    public function test_filter_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:filter text="T" variant="'.$variant.'" />');

            $this->assertHasClass('tedi-filter', $html, 'tedi-filter');
            $this->assertHasClass('tedi-filter--'.$variant, $html, 'tedi-filter');
        }
    }

    public function test_filter_size_classes(): void
    {
        $default = Blade::render('<tedi:filter text="T" size="default" />');
        $large = Blade::render('<tedi:filter text="T" size="large" />');

        $this->assertMissingClass('tedi-filter--large', $default, 'tedi-filter');
        $this->assertHasClass('tedi-filter--large', $large, 'tedi-filter');
    }

    public function test_filter_selected_and_disabled_modifiers(): void
    {
        $plain = Blade::render('<tedi:filter text="T" />');
        $this->assertMissingClass('tedi-filter--selected', $plain, 'tedi-filter');
        $this->assertMissingClass('tedi-filter--disabled', $plain, 'tedi-filter');

        $selected = Blade::render('<tedi:filter text="T" :selected="true" :disabled="true" />');
        $this->assertHasClass('tedi-filter--selected', $selected, 'tedi-filter');
        $this->assertHasClass('tedi-filter--disabled', $selected, 'tedi-filter');
    }

    public function test_filter_host_is_a_div_because_tedi_has_no_element_rule(): void
    {
        $html = Blade::render('<tedi:filter text="T" />');

        $this->assertStringNotContainsString('<tedi-filter', $html);
        $this->assertStringContainsString('<div class="tedi-filter', $html);
    }

    // -- filter: the plain toggle chip ----------------------------------

    public function test_plain_chip_element_classes(): void
    {
        $html = Blade::render('<tedi:filter text="T" :selected="true" />');

        $this->assertHasClass('tedi-filter__button', $html, 'tedi-filter__button');
        $this->assertHasClass('tedi-filter__text', $html, 'tedi-filter__text');
        $this->assertHasClass('tedi-filter__append', $html, 'tedi-filter__append');
        $this->assertHasClass('tedi-filter__icon', $html, 'tedi-filter__icon');
    }

    public function test_plain_chip_shows_the_check_icon_and_no_arrow(): void
    {
        $html = Blade::render('<tedi:filter text="T" :selected="true" />');

        $this->assertStringContainsString('>check</tedi-icon>', $html);
        $this->assertStringNotContainsString('arrow_drop_down', $html);
    }

    public function test_check_icon_is_cloaked_until_selected(): void
    {
        // Not selected: the icon is in the DOM (so Alpine can reveal it) but
        // [x-cloak] keeps it hidden, including when there is no JS at all.
        $this->assertStringContainsString('x-cloak', Blade::render('<tedi:filter text="T" />'));
        $this->assertStringNotContainsString('x-cloak', Blade::render('<tedi:filter text="T" :selected="true" />'));
    }

    public function test_plain_chip_uses_aria_pressed_outside_a_managed_group(): void
    {
        $html = Blade::render('<tedi:filter text="T" :selected="true" />');

        $this->assertStringContainsString('aria-pressed="true"', $html);
        $this->assertStringNotContainsString('role="radio"', $html);
    }

    public function test_plain_chip_becomes_a_radio_in_a_managed_single_select_group(): void
    {
        $html = Blade::render(
            '<tedi:filter-group :managed="true"><tedi:filter text="T" value="a" :selected="true" /></tedi:filter-group>'
        );

        $this->assertStringContainsString('role="radio"', $html);
        $this->assertStringContainsString('aria-checked="true"', $html);
        $this->assertStringNotContainsString('aria-pressed', $html);
    }

    public function test_plain_chip_keeps_aria_pressed_in_a_managed_multi_select_group(): void
    {
        // group-allow-multiple must be mirrored onto the child: the group's own
        // allowMultiple cannot cross via @aware (CONVENTIONS.md §3).
        $html = Blade::render(
            '<tedi:filter-group :managed="true" :allow-multiple="true">'
            .'<tedi:filter text="T" value="a" :group-allow-multiple="true" /></tedi:filter-group>'
        );

        $this->assertStringContainsString('aria-pressed="false"', $html);
        $this->assertStringNotContainsString('role="radio"', $html);
    }

    // -- filter: prepend / append ---------------------------------------

    public function test_prepend_is_hidden_once_selected_by_default(): void
    {
        $html = Blade::render('<tedi:filter text="T" :selected="true" />');

        $this->assertHasClass('tedi-filter__prepend--hidden', $html, 'tedi-filter__prepend');
    }

    public function test_prepend_stays_visible_when_hide_prepend_when_selected_is_false(): void
    {
        $html = Blade::render(
            '<tedi:filter text="T" :selected="true" :hide-prepend-when-selected="false" />'
        );

        $this->assertMissingClass('tedi-filter__prepend--hidden', $html, 'tedi-filter__prepend');
    }

    public function test_prepend_and_append_slots_render_into_their_wrappers(): void
    {
        $html = Blade::render(
            '<tedi:filter text="T">'
            .'<x-slot:prepend><b>P</b></x-slot:prepend> '
            .'<x-slot:append><i>A</i></x-slot:append> '
            .'</tedi:filter>'
        );

        $this->assertMatchesRegularExpression('/tedi-filter__prepend[^>]*><b>P<\/b></', $html);
        $this->assertMatchesRegularExpression('/tedi-filter__append"><i>A<\/i></', $html);
    }

    public function test_empty_prepend_and_append_wrappers_stay_empty_for_the_scss_empty_rule(): void
    {
        $html = Blade::render('<tedi:filter text="T" />');

        // `.tedi-filter__prepend:empty { display: none }` — not even whitespace.
        $this->assertStringContainsString('<div class="tedi-filter__append"></div>', $html);
    }

    // -- filter: the dropdown branch ------------------------------------

    public function test_options_switch_the_chip_to_a_dropdown_trigger(): void
    {
        $html = $this->withOptions();

        $this->assertStringContainsString('<tedi-dropdown', $html);
        $this->assertStringContainsString('aria-haspopup', $html);
        $this->assertStringContainsString('arrow_drop_down', $html);
        $this->assertStringNotContainsString('>check</tedi-icon>', $html);
    }

    public function test_dropdown_panel_classes(): void
    {
        $html = $this->withOptions();

        $this->assertHasClass('tedi-filter-dropdown', $html, 'tedi-filter-dropdown');
        $this->assertHasClass('tedi-filter-dropdown__options', $html, 'tedi-filter-dropdown__options');
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('role="listbox"', $html);
    }

    public function test_dropdown_panel_is_in_the_dom_while_closed(): void
    {
        // CONVENTIONS.md §8: x-show, not x-if — the panel must carry its real
        // class list even before Alpine boots, or nothing is assertable.
        $html = $this->withOptions();

        $this->assertHasClass('tedi-dropdown__panel', $html, 'tedi-dropdown__panel');
        $this->assertStringContainsString('x-show="open"', $html);
    }

    public function test_option_item_classes(): void
    {
        $html = $this->withOptions('value="2"');

        // Option 0: plain. Option 1: selected. Option 2: disabled.
        preg_match_all('/<div\s+class="(tedi-filter-dropdown__item[^"]*)"/', $html, $matches);

        $this->assertSame([
            'tedi-filter-dropdown__item',
            'tedi-filter-dropdown__item tedi-filter-dropdown__item--selected',
            'tedi-filter-dropdown__item tedi-filter-dropdown__item--disabled',
        ], $matches[1]);
    }

    public function test_disabled_option_is_not_clickable_and_is_marked_aria_disabled(): void
    {
        $html = $this->withOptions();

        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringNotContainsString("selectOption('3')", $html);
    }

    public function test_option_ids_match_the_active_descendant_scheme(): void
    {
        $html = $this->withOptions();

        $this->assertMatchesRegularExpression('/id="tedi-filter-[0-9a-f]+-option-0"/', $html);
        $this->assertMatchesRegularExpression('/id="tedi-filter-[0-9a-f]+-option-2"/', $html);
        $this->assertStringContainsString('x-bind:aria-activedescendant="activeDescendantId"', $html);
    }

    public function test_single_select_shows_the_selected_label_and_can_preserve_the_filter_label(): void
    {
        $plain = $this->withOptions('value="2"');
        $this->assertStringContainsString('>Silmaarst</span>', $plain);

        $preserved = $this->withOptions('value="2" :preserve-label="true"');
        $this->assertStringContainsString('>Teenus: Silmaarst</span>', $preserved);
    }

    public function test_single_select_marks_the_host_selected(): void
    {
        $this->assertMissingClass('tedi-filter--selected', $this->withOptions(), 'tedi-filter');
        $this->assertHasClass('tedi-filter--selected', $this->withOptions('value="2"'), 'tedi-filter');
    }

    // -- filter: multi-select -------------------------------------------

    private function multi(string $extra = ''): string
    {
        return Blade::render(
            '<tedi:filter text="Teenus" :allow-multiple="true" :options="'.self::OPTIONS.'" '.$extra.' />'
        );
    }

    public function test_multi_select_listbox_is_multiselectable(): void
    {
        $this->assertStringContainsString('aria-multiselectable="true"', $this->multi());
        $this->assertStringNotContainsString('aria-multiselectable', $this->withOptions());
    }

    public function test_multi_select_items_never_take_the_selected_modifier(): void
    {
        // Angular's multi-select branch binds only --disabled and --focused;
        // selection shows through the checkbox, not through the row's class.
        $html = $this->multi(':value="[\'1\']"');

        $this->assertMissingClass('tedi-filter-dropdown__item--selected', $html, 'tedi-filter-dropdown__item');
        $this->assertStringContainsString('aria-selected="true"', $html);
    }

    public function test_multi_select_renders_checkbox_item_values(): void
    {
        $html = $this->multi();

        $this->assertHasClass(
            'tedi-dropdown-item-value--checkbox', $html, 'tedi-dropdown-item-value--checkbox'
        );
    }

    public function test_count_badge_classes_and_value(): void
    {
        $html = $this->multi(':value="[\'1\', \'2\']"');

        $this->assertHasClass('tedi-filter__count', $html, 'tedi-filter__count');
        $this->assertHasClass('tedi-status-badge--color-brand', $html, 'tedi-filter__count');
        $this->assertHasClass('tedi-status-badge--variant-filled', $html, 'tedi-filter__count');
        $this->assertStringContainsString('x-text="selectedCount">2</span>', $html);
    }

    public function test_count_badge_is_hidden_but_present_when_nothing_is_selected(): void
    {
        $html = $this->multi();

        $this->assertHasClass('tedi-filter__count', $html, 'tedi-filter__count');
        $this->assertStringContainsString('style="display: none;"', $html);
    }

    public function test_count_badge_is_absent_outside_multi_select(): void
    {
        $this->assertMissingClass('tedi-filter__count', $this->withOptions(), 'tedi-filter__button');
        $this->assertStringNotContainsString('tedi-filter__count', Blade::render('<tedi:filter text="T" />'));
    }

    public function test_select_all_row_aria_checked_tracks_the_selection(): void
    {
        $none = $this->multi(':show-select-all="true"');
        $some = $this->multi(':show-select-all="true" :value="[\'1\']"');
        $all = $this->multi(':show-select-all="true" :value="[\'1\', \'2\']"');

        $this->assertStringContainsString('aria-checked="false"', $none);
        $this->assertStringContainsString('aria-checked="mixed"', $some);
        // '3' is disabled, so selecting 1 and 2 selects everything selectable.
        $this->assertStringContainsString('aria-checked="true"', $all);
    }

    public function test_select_all_row_is_absent_without_show_select_all(): void
    {
        $this->assertStringNotContainsString('role="checkbox"', $this->multi());
    }

    // -- filter: dropped upstream classes -------------------------------

    public function test_select_all_modifier_class_is_dropped(): void
    {
        // Angular emits tedi-filter-dropdown__item--select-all; dist/tedi.css
        // has no rule for it, so CONVENTIONS.md §4 says drop it.
        $html = $this->multi(':show-select-all="true"');

        $this->assertMissingClass(
            'tedi-filter-dropdown__item--select-all', $html, 'tedi-filter-dropdown__item'
        );
    }

    public function test_custom_dropdown_modifier_class_is_dropped(): void
    {
        // Same ruling for tedi-filter-dropdown--custom.
        $html = Blade::render(
            '<tedi:filter text="T"><x-slot:content><p>x</p></x-slot:content> </tedi:filter>'
        );

        $this->assertHasClass('tedi-filter-dropdown', $html, 'tedi-filter-dropdown');
        $this->assertMissingClass('tedi-filter-dropdown--custom', $html, 'tedi-filter-dropdown');
    }

    // -- filter: search, clear, custom content --------------------------

    public function test_search_field_classes_and_wiring(): void
    {
        $html = $this->withOptions(':show-search="true"');

        $this->assertHasClass('tedi-filter-dropdown__search', $html, 'tedi-filter-dropdown__search');
        $this->assertStringContainsString('role="searchbox"', $html);
        $this->assertStringContainsString('x-model="searchTerm"', $html);
        $this->assertStringContainsString('x-on:click="onSearchClear()"', $html);
    }

    public function test_search_field_is_absent_by_default(): void
    {
        $this->assertStringNotContainsString('role="searchbox"', $this->withOptions());
    }

    public function test_clear_button_classes_and_handlers(): void
    {
        $single = $this->withOptions(':show-clear="true"');

        $this->assertHasClass('tedi-filter-dropdown__clear', $single, 'tedi-filter-dropdown__clear');
        $this->assertHasClass('tedi-button--neutral', $single, 'tedi-button');
        $this->assertHasClass('tedi-button--small', $single, 'tedi-button');
        // Angular's icon-then-label button drops the left padding modifier only.
        $this->assertHasClass('tedi-button--pr', $single, 'tedi-button');
        $this->assertMissingClass('tedi-button--pl', $single, 'tedi-button');
        $this->assertStringContainsString('x-on:click="clearSingleSelection()"', $single);

        $multi = $this->multi(':show-clear="true"');
        $this->assertStringContainsString('x-on:click="clearSelection()"', $multi);
    }

    public function test_custom_content_clear_button_carries_only_the_consumer_handler(): void
    {
        $html = Blade::render(
            '<tedi:filter text="T" :show-clear="true" :clear-attributes="[\'wire:click\' => \'reset\']">'
            .'<x-slot:content><p>x</p></x-slot:content> '
            .'</tedi:filter>'
        );

        $this->assertHasClass(
            'tedi-filter-dropdown__custom-content', $html, 'tedi-filter-dropdown__custom-content'
        );
        $this->assertStringContainsString('wire:click="reset"', $html);
        $this->assertStringNotContainsString('clearSelection()', $html);
    }

    public function test_custom_content_replaces_the_option_list(): void
    {
        $html = Blade::render(
            '<tedi:filter text="T"><x-slot:content><p>x</p></x-slot:content> </tedi:filter>'
        );

        $this->assertStringNotContainsString('role="listbox"', $html);
    }

    // -- filter: interactivity contract ---------------------------------

    public function test_filter_wires_its_own_alpine_component(): void
    {
        $html = Blade::render('<tedi:filter text="T" />');

        $this->assertStringContainsString('x-data="tediFilter(', $html);
        // Not tediDropdown: the filter's keyboard layer is a listbox with
        // aria-activedescendant, not the roving-tabindex menu (CONVENTIONS.md §11).
        $this->assertStringNotContainsString('tediDropdown(', $html);
    }

    public function test_wire_model_binds_through_x_modelable_on_the_root(): void
    {
        $html = Blade::render('<tedi:filter text="T" wire:model="teenus" />');

        $this->assertStringContainsString('x-modelable="model"', $html);
        $this->assertMatchesRegularExpression(
            '/<div class="tedi-filter[^"]*"[^>]*wire:model="teenus"/s', $html
        );
    }

    public function test_the_shared_overlay_offset_lands_on_the_upstream_gap(): void
    {
        // tediOverlay's offset is extra px on an 8px base gap; upstream's
        // dropdown replaces the base with 4, so the port passes -4.
        $html = $this->withOptions();

        // Js::from() unicode-escapes the quotes it writes into the attribute.
        $this->assertStringContainsString('offset\u0022:-4', $html);
    }

    // -- filter-group ---------------------------------------------------

    public function test_filter_group_host_class(): void
    {
        $html = Blade::render('<tedi:filter-group>x</tedi:filter-group>');

        $this->assertHasClass('tedi-filter-group', $html, 'tedi-filter-group');
        $this->assertStringNotContainsString('<tedi-filter-group', $html);
    }

    public function test_filter_group_role_is_gated_on_managed(): void
    {
        $unmanaged = Blade::render('<tedi:filter-group>x</tedi:filter-group>');
        $this->assertStringNotContainsString('role=', $unmanaged);

        $radio = Blade::render('<tedi:filter-group :managed="true">x</tedi:filter-group>');
        $this->assertStringContainsString('role="radiogroup"', $radio);

        $group = Blade::render('<tedi:filter-group :managed="true" :allow-multiple="true">x</tedi:filter-group>');
        $this->assertStringContainsString('role="group"', $group);
    }

    public function test_filter_group_emits_aria_label_regardless_of_managed(): void
    {
        $html = Blade::render('<tedi:filter-group label="Tüüp">x</tedi:filter-group>');

        $this->assertStringContainsString('aria-label="Tüüp"', $html);
    }
}
