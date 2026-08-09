<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the overlay/dropdown family (CONVENTIONS.md §10).
 *
 * The prop unions are taken from the Angular `export type` declarations:
 *   DropdownRole                 = 'menu' | 'listbox'
 *   DropdownTriggerAriaHasPopup  = 'menu' | 'listbox' | 'dialog' | 'true'
 *   DropdownItemValueType        = 'default' | 'checkbox' | 'radio'
 *   DropdownItemValueLayout      = 'horizontal' | 'vertical'
 *
 * `li[tedi-dropdown-item]` deliberately carries no classes — Angular's host
 * emits only role/aria/tabindex — so its assertions are attribute-based.
 */
class DropdownComponentsTest extends TestCase
{
    // -- dropdown -------------------------------------------------------

    public function test_dropdown_renders_the_angular_element_selector(): void
    {
        $html = Blade::render('<tedi:dropdown>x</tedi:dropdown>');

        $this->assertStringContainsString('<tedi-dropdown', $html);
    }

    public function test_dropdown_wires_the_shared_overlay_engine(): void
    {
        $html = Blade::render('<tedi:dropdown>x</tedi:dropdown>');

        // tediDropdown is tediOverlay plus the keyboard layer (CONVENTIONS.md §11);
        // the dropdown is the only component that takes the composed one.
        $this->assertStringContainsString('x-data="tediDropdown(', $html);
        $this->assertStringContainsString('matchTriggerWidth: true', $html);
    }

    public function test_dropdown_default_position_is_bottom_start(): void
    {
        $html = Blade::render('<tedi:dropdown>x</tedi:dropdown>');

        $this->assertStringContainsString("placement: 'bottom-start'", $html);
    }

    public function test_dropdown_position_union_reaches_the_engine(): void
    {
        $positions = [
            'auto', 'auto-start', 'auto-end',
            'top', 'top-start', 'top-end',
            'bottom', 'bottom-start', 'bottom-end',
            'right', 'right-start', 'right-end',
            'left', 'left-start', 'left-end',
        ];

        foreach ($positions as $position) {
            $html = Blade::render('<tedi:dropdown position="'.$position.'">x</tedi:dropdown>');

            $this->assertStringContainsString("placement: '".$position."'", $html);
        }
    }

    /**
     * Upstream's dropdown REPLACES the 8px base gap rather than adding to it
     * (Math.sign(offsetY) * offset), so the engine's `offset` is the component's
     * offset minus the base gap. The default 4 must arrive as -4.
     */
    public function test_dropdown_offset_replaces_the_base_gap(): void
    {
        $html = Blade::render('<tedi:dropdown>x</tedi:dropdown>');
        $this->assertStringContainsString('offset: -4,', $html);

        $html = Blade::render('<tedi:dropdown :offset="12">x</tedi:dropdown>');
        $this->assertStringContainsString('offset: 4,', $html);

        $html = Blade::render('<tedi:dropdown :offset="0">x</tedi:dropdown>');
        $this->assertStringContainsString('offset: -8,', $html);
    }

    public function test_dropdown_boolean_props_default_to_the_angular_defaults(): void
    {
        $html = Blade::render('<tedi:dropdown>x</tedi:dropdown>');

        $this->assertStringContainsString('preventOverflow: true,', $html);
        $this->assertStringContainsString('hideOnScroll: false,', $html);

        $html = Blade::render('<tedi:dropdown :prevent-overflow="false" :hide-on-scroll="true">x</tedi:dropdown>');

        $this->assertStringContainsString('preventOverflow: false,', $html);
        $this->assertStringContainsString('hideOnScroll: true,', $html);
    }

    public function test_dropdown_does_not_leak_props_to_the_dom(): void
    {
        $html = Blade::render('<tedi:dropdown value="tartu" :offset="4" container-id="dd">x</tedi:dropdown>');

        $this->assertStringNotContainsString('value="tartu"', $html);
        $this->assertStringNotContainsString('container-id=', $html);
    }

    // -- dropdown-trigger -----------------------------------------------

    public function test_trigger_renders_the_styled_element_and_positioning_ref(): void
    {
        $html = Blade::render('<tedi:dropdown-trigger>Trigger</tedi:dropdown-trigger>');

        $this->assertStringContainsString('<tedi-dropdown-trigger', $html);
        $this->assertStringContainsString('x-ref="trigger"', $html);
        $this->assertStringContainsString('x-on:click="toggle()"', $html);
    }

    public function test_trigger_binds_the_arrow_key_open_shortcuts(): void
    {
        $html = Blade::render('<tedi:dropdown-trigger>Trigger</tedi:dropdown-trigger>');

        $this->assertStringContainsString('x-on:keydown="triggerKeydown($event)"', $html);
    }

    public function test_trigger_aria_haspopup_union(): void
    {
        foreach (['menu', 'listbox', 'dialog', 'true'] as $value) {
            $html = Blade::render('<tedi:dropdown-trigger aria-haspopup="'.$value.'">T</tedi:dropdown-trigger>');

            $this->assertStringContainsString("setAttribute('aria-haspopup', '".$value."')", $html);
        }
    }

    public function test_trigger_defaults_aria_haspopup_to_menu(): void
    {
        $html = Blade::render('<tedi:dropdown-trigger>T</tedi:dropdown-trigger>');

        $this->assertStringContainsString("setAttribute('aria-haspopup', 'menu')", $html);
    }

    public function test_trigger_omits_id_wiring_without_a_container_id(): void
    {
        $html = Blade::render('<tedi:dropdown-trigger>T</tedi:dropdown-trigger>');

        $this->assertStringNotContainsString('aria-controls', $html);
    }

    public function test_trigger_wires_ids_from_the_dropdowns_container_id(): void
    {
        $html = Blade::render(
            '<tedi:dropdown container-id="dd1"><tedi:dropdown-trigger>T</tedi:dropdown-trigger></tedi:dropdown>'
        );

        $this->assertStringContainsString("setAttribute('id', 'dd1_trigger')", $html);
        $this->assertStringContainsString("setAttribute('aria-controls', 'dd1')", $html);
    }

    // -- dropdown-content -----------------------------------------------

    public function test_content_renders_the_panel_wrapper_and_element(): void
    {
        $html = Blade::render('<tedi:dropdown-content>x</tedi:dropdown-content>');

        $this->assertHasClass('tedi-dropdown__panel', $html, 'tedi-dropdown__panel');
        $this->assertHasClass('tedi-dropdown-content', $html, 'tedi-dropdown-content');
        $this->assertStringContainsString('<tedi-dropdown-content', $html);
        $this->assertStringContainsString('role="presentation"', $html);
    }

    public function test_content_panel_carries_the_overlay_refs(): void
    {
        $html = Blade::render('<tedi:dropdown-content>x</tedi:dropdown-content>');

        $this->assertStringContainsString('x-ref="panel"', $html);
        $this->assertStringContainsString('x-show="open"', $html);
        $this->assertStringContainsString('x-cloak', $html);
        $this->assertStringContainsString('x-bind:data-placement="side"', $html);
    }

    public function test_content_panel_delegates_the_item_keyboard_layer(): void
    {
        $html = Blade::render('<tedi:dropdown-content>x</tedi:dropdown-content>');

        // One listener on the panel, not one per <li> — see CONVENTIONS.md §11.
        $this->assertStringContainsString('x-on:keydown="menuKeydown($event)"', $html);
        $this->assertSame(1, substr_count($html, 'menuKeydown'));
    }

    public function test_content_role_union_lands_on_the_list(): void
    {
        foreach (['menu', 'listbox'] as $role) {
            $html = Blade::render('<tedi:dropdown-content dropdown-role="'.$role.'">x</tedi:dropdown-content>');

            $this->assertStringContainsString('<ul role="'.$role.'">', $html);
        }
    }

    public function test_content_defaults_to_the_menu_role(): void
    {
        $html = Blade::render('<tedi:dropdown-content>x</tedi:dropdown-content>');

        $this->assertStringContainsString('<ul role="menu">', $html);
    }

    public function test_content_labels_itself_from_the_container_id(): void
    {
        $html = Blade::render(
            '<tedi:dropdown container-id="dd1"><tedi:dropdown-content>x</tedi:dropdown-content></tedi:dropdown>'
        );

        $this->assertStringContainsString('id="dd1"', $html);
        $this->assertStringContainsString('aria-labelledby="dd1_trigger"', $html);
    }

    public function test_content_omits_aria_labelledby_without_a_container_id(): void
    {
        $html = Blade::render('<tedi:dropdown-content>x</tedi:dropdown-content>');

        $this->assertStringNotContainsString('aria-labelledby', $html);
    }

    public function test_content_before_slot_renders_above_the_list(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-content>
            <x-slot:before><p>Ülemine sisu</p></x-slot:before>
            <li>item</li>
        </tedi:dropdown-content>
        BLADE);

        $this->assertLessThan(
            strpos($html, '<ul role='),
            strpos($html, 'Ülemine sisu'),
            'The `before` slot must render above the item list, like Angular\'s untargeted <ng-content />.'
        );
    }

    // -- dropdown-item --------------------------------------------------

    public function test_item_renders_the_attribute_selector_root(): void
    {
        $html = Blade::render('<tedi:dropdown-item>Kontaktid</tedi:dropdown-item>');

        $this->assertStringContainsString('<li', $html);
        $this->assertStringContainsString('tedi-dropdown-item', $html);
    }

    /**
     * Angular's host emits no classes on the item. `.tedi-dropdown-item`,
     * `--selected` and `--disabled` exist in the vendored SCSS as the
     * class-based alternative used by select, and are NOT Angular parity.
     */
    public function test_item_emits_no_classes_of_its_own(): void
    {
        $html = Blade::render('<tedi:dropdown-item :disabled="true" :selected="true">x</tedi:dropdown-item>');

        $this->assertMissingClass('tedi-dropdown-item', $html);
        $this->assertMissingClass('tedi-dropdown-item--disabled', $html);
        $this->assertMissingClass('tedi-dropdown-item--selected', $html);
    }

    public function test_item_role_follows_the_content_role(): void
    {
        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertStringContainsString('role="menuitem"', $html);

        $html = Blade::render(
            '<tedi:dropdown-content dropdown-role="listbox"><tedi:dropdown-item>x</tedi:dropdown-item></tedi:dropdown-content>'
        );
        $this->assertStringContainsString('role="option"', $html);
    }

    public function test_item_aria_selected_only_in_listbox_role(): void
    {
        $html = Blade::render('<tedi:dropdown-item :selected="true">x</tedi:dropdown-item>');
        $this->assertStringNotContainsString('aria-selected', $html);

        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-content dropdown-role="listbox">
            <tedi:dropdown-item value="tallinn">Tallinn</tedi:dropdown-item>
            <tedi:dropdown-item value="tartu" :selected="true">Tartu</tedi:dropdown-item>
        </tedi:dropdown-content>
        BLADE);

        $this->assertStringContainsString('aria-selected="false"', $html);
        $this->assertStringContainsString('aria-selected="true"', $html);
        $this->assertSame(1, substr_count($html, 'aria-selected="true"'));
    }

    public function test_item_aria_disabled(): void
    {
        $html = Blade::render('<tedi:dropdown-item :disabled="true">x</tedi:dropdown-item>');
        $this->assertStringContainsString('aria-disabled="true"', $html);

        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertStringNotContainsString('aria-disabled', $html);
    }

    /**
     * Angular: menu -> always '-1'; listbox -> null when disabled, else '-1'.
     */
    public function test_item_tabindex_matrix(): void
    {
        $html = Blade::render('<tedi:dropdown-item :disabled="true">x</tedi:dropdown-item>');
        $this->assertStringContainsString('tabindex="-1"', $html);

        $html = Blade::render(
            '<tedi:dropdown-content dropdown-role="listbox"><tedi:dropdown-item>x</tedi:dropdown-item></tedi:dropdown-content>'
        );
        $this->assertStringContainsString('tabindex="-1"', $html);

        $html = Blade::render(
            '<tedi:dropdown-content dropdown-role="listbox"><tedi:dropdown-item :disabled="true">x</tedi:dropdown-item></tedi:dropdown-content>'
        );
        // Scoped to the <li> itself: the auto-wrapped item value may legitimately
        // render its own tabindex="-1" indicator further down.
        preg_match('/<li\b[^>]*>/', $html, $li);
        $this->assertNotEmpty($li);
        $this->assertStringNotContainsString('tabindex', $li[0]);
    }

    public function test_item_closes_the_dropdown_on_click_unless_told_not_to(): void
    {
        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertStringContainsString('x-on:click="hide(true)"', $html);

        $html = Blade::render('<tedi:dropdown-item :close-on-select="false">x</tedi:dropdown-item>');
        $this->assertStringNotContainsString('x-on:click', $html);

        $html = Blade::render('<tedi:dropdown-item :disabled="true">x</tedi:dropdown-item>');
        $this->assertStringNotContainsString('x-on:click', $html);
    }

    public function test_item_guards_mouse_focus_only_when_disabled(): void
    {
        // Angular's @HostListener('mousedown'): a disabled item keeps its roving
        // tabindex so it stays discoverable, but must not take focus on a press.
        $html = Blade::render('<tedi:dropdown-item :disabled="true">x</tedi:dropdown-item>');
        $this->assertStringContainsString('x-on:mousedown.prevent', $html);

        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertStringNotContainsString('x-on:mousedown', $html);
    }

    /**
     * The keyboard layer reads its item registry and their state off the DOM
     * rather than component instances (CONVENTIONS.md §11), so these attributes
     * are load-bearing, not decorative.
     */
    public function test_item_attributes_the_keyboard_layer_reads_are_present(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-content dropdown-role="listbox">
            <tedi:dropdown-item :selected="true">a</tedi:dropdown-item>
            <tedi:dropdown-item :disabled="true">b</tedi:dropdown-item>
        </tedi:dropdown-content>
        BLADE);

        $this->assertSame(2, preg_match_all('/<li\b[^>]*\btedi-dropdown-item\b/', $html));
        $this->assertStringContainsString('aria-selected="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('<ul role="listbox"', $html);
    }

    public function test_item_wraps_plain_content_in_a_value_and_label(): void
    {
        $html = Blade::render('<tedi:dropdown-item>Kontaktid</tedi:dropdown-item>');

        $this->assertHasClass('tedi-dropdown-item-value', $html, 'tedi-dropdown-item-value');
        $this->assertHasClass('tedi-dropdown-item-value__label', $html, 'tedi-dropdown-item-value__label');
        $this->assertStringContainsString('Kontaktid', $html);
    }

    public function test_item_clip_content_reaches_the_generated_label(): void
    {
        $html = Blade::render('<tedi:dropdown-item :clip-content="false">x</tedi:dropdown-item>');
        $this->assertHasClass('tedi-dropdown-item-value__label--no-clip', $html, 'tedi-dropdown-item-value__label');

        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertMissingClass('tedi-dropdown-item-value__label--no-clip', $html, 'tedi-dropdown-item-value__label');
    }

    public function test_item_value_slot_suppresses_the_generated_wrapper(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-item>
            <x-slot:item-value>
                <tedi:dropdown-item-value layout="vertical">
                    <tedi:dropdown-item-value-label>Tallinn</tedi:dropdown-item-value-label>
                    <tedi:dropdown-item-value-meta>3 vaba aega</tedi:dropdown-item-value-meta>
                </tedi:dropdown-item-value>
            </x-slot:item-value>
        </tedi:dropdown-item>
        BLADE);

        $this->assertSame(1, substr_count($html, '<tedi-dropdown-item-value '));
        $this->assertHasClass('tedi-dropdown-item-value--vertical', $html, 'tedi-dropdown-item-value');
    }

    // -- dropdown-item-value --------------------------------------------

    public function test_item_value_layout_union_classes(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value>x</tedi:dropdown-item-value>');
        $this->assertHasClass('tedi-dropdown-item-value', $html, 'tedi-dropdown-item-value');
        $this->assertHasClass('tedi-dropdown-item-value--horizontal', $html, 'tedi-dropdown-item-value');
        $this->assertMissingClass('tedi-dropdown-item-value--vertical', $html, 'tedi-dropdown-item-value');

        $html = Blade::render('<tedi:dropdown-item-value layout="vertical">x</tedi:dropdown-item-value>');
        $this->assertHasClass('tedi-dropdown-item-value--vertical', $html, 'tedi-dropdown-item-value');
        $this->assertMissingClass('tedi-dropdown-item-value--horizontal', $html, 'tedi-dropdown-item-value');
    }

    public function test_item_value_type_union_classes(): void
    {
        $expected = [
            'default' => null,
            'checkbox' => 'tedi-dropdown-item-value--checkbox',
            'radio' => 'tedi-dropdown-item-value--radio',
        ];

        foreach ($expected as $type => $class) {
            $html = Blade::render('<tedi:dropdown-item-value type="'.$type.'">x</tedi:dropdown-item-value>');

            foreach (array_filter($expected) as $candidate) {
                if ($candidate === $class) {
                    $this->assertHasClass($candidate, $html, 'tedi-dropdown-item-value');
                } else {
                    $this->assertMissingClass($candidate, $html, 'tedi-dropdown-item-value');
                }
            }
        }
    }

    public function test_item_value_renders_the_matching_indicator(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value>x</tedi:dropdown-item-value>');
        $this->assertStringNotContainsString('type="checkbox"', $html);
        $this->assertStringNotContainsString('type="radio"', $html);

        $html = Blade::render('<tedi:dropdown-item-value type="checkbox">x</tedi:dropdown-item-value>');
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('tedi-checkbox', $html);
        $this->assertHasClass('tedi-dropdown-item-value__checkbox', $html, 'tedi-dropdown-item-value__checkbox');
        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);

        $html = Blade::render('<tedi:dropdown-item-value type="radio">x</tedi:dropdown-item-value>');
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertHasClass('tedi-dropdown-item-value__radio', $html, 'tedi-dropdown-item-value__radio');
    }

    public function test_item_value_indicator_reflects_selected_and_disabled(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value type="checkbox" :selected="true" :disabled="true">x</tedi:dropdown-item-value>');
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('disabled', $html);

        $html = Blade::render('<tedi:dropdown-item-value type="radio">x</tedi:dropdown-item-value>');
        $this->assertStringNotContainsString('checked', $html);
        $this->assertStringNotContainsString('disabled', $html);
    }

    public function test_item_value_indeterminate_is_set_as_a_dom_property(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value type="checkbox" :indeterminate="true">x</tedi:dropdown-item-value>');
        $this->assertStringContainsString('x-init="$el.indeterminate = true"', $html);

        $html = Blade::render('<tedi:dropdown-item-value type="checkbox">x</tedi:dropdown-item-value>');
        $this->assertStringNotContainsString('indeterminate', $html);
    }

    public function test_item_value_content_wrapper_and_slots(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-item-value>
            <x-slot:icon><tedi:icon name="edit" :size="18" /></x-slot:icon>
            <tedi:dropdown-item-value-label>Muuda</tedi:dropdown-item-value-label>
            <x-slot:after><span id="trailing">→</span></x-slot:after>
        </tedi:dropdown-item-value>
        BLADE);

        $this->assertHasClass('tedi-dropdown-item-value__content', $html, 'tedi-dropdown-item-value__content');

        $this->assertLessThan(
            strpos($html, 'tedi-dropdown-item-value__content'),
            strpos($html, 'tedi-icon'),
            'The icon slot must render before the content wrapper, as in Angular.'
        );

        $this->assertLessThan(
            strpos($html, 'id="trailing"'),
            strpos($html, 'tedi-dropdown-item-value__content'),
            'The after slot must render below the content wrapper, as in Angular.'
        );
    }

    // -- dropdown-item-value-label / -meta -------------------------------

    public function test_label_classes_and_element(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value-label>Tallinn</tedi:dropdown-item-value-label>');
        $this->assertStringContainsString('<tedi-dropdown-item-value-label', $html);
        $this->assertHasClass('tedi-dropdown-item-value__label', $html, 'tedi-dropdown-item-value__label');
        $this->assertMissingClass('tedi-dropdown-item-value__label--no-clip', $html, 'tedi-dropdown-item-value__label');

        $html = Blade::render('<tedi:dropdown-item-value-label :clip-content="false">Tallinn</tedi:dropdown-item-value-label>');
        $this->assertHasClass('tedi-dropdown-item-value__label--no-clip', $html, 'tedi-dropdown-item-value__label');
    }

    public function test_meta_classes_and_element(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value-meta>3 vaba aega</tedi:dropdown-item-value-meta>');

        $this->assertStringContainsString('<tedi-dropdown-item-value-meta', $html);
        $this->assertHasClass('tedi-dropdown-item-value__meta', $html, 'tedi-dropdown-item-value__meta');
        $this->assertStringContainsString('3 vaba aega', $html);
    }

    // -- attribute merging (CONVENTIONS.md §6) ---------------------------

    public function test_consumer_classes_merge_instead_of_replacing(): void
    {
        $html = Blade::render('<tedi:dropdown-item-value class="my-own">x</tedi:dropdown-item-value>');

        $this->assertHasClass('tedi-dropdown-item-value', $html, 'tedi-dropdown-item-value');
        $this->assertHasClass('my-own', $html, 'tedi-dropdown-item-value');
    }

    public function test_consumer_aria_label_wins_over_the_computed_role(): void
    {
        $html = Blade::render('<tedi:dropdown-item role="none">x</tedi:dropdown-item>');

        $this->assertStringContainsString('role="none"', $html);
        $this->assertStringNotContainsString('role="menuitem"', $html);
    }
}
