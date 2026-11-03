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
        // DropdownTriggerAriaHasPopup = 'menu' | 'listbox' | 'dialog' | 'true' | 'false'
        foreach (['menu', 'listbox', 'dialog', 'true', 'false'] as $value) {
            $html = Blade::render('<tedi:dropdown-trigger aria-haspopup="'.$value.'">T</tedi:dropdown-trigger>');

            $this->assertStringContainsString("ariaHaspopup: '".$value."'", $html);
        }
    }

    public function test_trigger_aria_haspopup_defaults_to_the_content_role(): void
    {
        // Angular 9: no fixed default; tediDropdown.initTrigger derives it
        // from the rendered ul[role] (none for a plain `list`).
        $html = Blade::render('<tedi:dropdown-trigger>T</tedi:dropdown-trigger>');

        $this->assertStringContainsString('ariaHaspopup: null', $html);
        $this->assertStringContainsString('x-init="initTrigger($el, {', $html);
    }

    public function test_trigger_binds_the_669_activation_keys(): void
    {
        $html = Blade::render('<tedi:dropdown-trigger>T</tedi:dropdown-trigger>');

        $this->assertStringContainsString('x-on:keyup="triggerKeyup($event)"', $html);
        $this->assertStringContainsString('x-on:focusout="triggerFocusout($event)"', $html);
    }

    public function test_trigger_omits_id_wiring_without_a_container_id(): void
    {
        $html = Blade::render('<tedi:dropdown-trigger>T</tedi:dropdown-trigger>');

        $this->assertStringContainsString('containerId: null', $html);
    }

    public function test_trigger_wires_ids_from_the_dropdowns_container_id(): void
    {
        $html = Blade::render(
            '<tedi:dropdown container-id="dd1"><tedi:dropdown-trigger>T</tedi:dropdown-trigger></tedi:dropdown>'
        );

        // initTrigger sets id="dd1_trigger", and aria-controls="dd1" only while open.
        $this->assertStringContainsString("containerId: 'dd1'", $html);
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

    /** The opening `<ul …>` tag, whitespace-collapsed. */
    private function listTag(string $html): string
    {
        preg_match('/<ul\b[^>]*>/', $html, $m);
        $this->assertNotEmpty($m, 'No <ul> rendered.');

        return preg_replace('/\s+/', ' ', $m[0]);
    }

    public function test_content_role_union_lands_on_the_list(): void
    {
        // DropdownRole = 'menu' | 'listbox' | 'list'
        foreach (['menu', 'listbox', 'list'] as $role) {
            $html = Blade::render('<tedi:dropdown-content dropdown-role="'.$role.'">x</tedi:dropdown-content>');

            $this->assertStringContainsString('role="'.$role.'"', $this->listTag($html));
        }
    }

    public function test_content_defaults_to_the_menu_role(): void
    {
        $html = Blade::render('<tedi:dropdown-content>x</tedi:dropdown-content>');

        $this->assertStringContainsString('role="menu"', $this->listTag($html));
    }

    public function test_content_list_classes(): void
    {
        foreach (['menu', 'listbox'] as $role) {
            $html = Blade::render('<tedi:dropdown-content dropdown-role="'.$role.'">x</tedi:dropdown-content>');
            $this->assertHasClass('tedi-dropdown-content__list', $html, 'tedi-dropdown-content__list');
            $this->assertMissingClass('tedi-dropdown-content__list--plain', $html, 'tedi-dropdown-content__list');
        }

        $html = Blade::render('<tedi:dropdown-content dropdown-role="list">x</tedi:dropdown-content>');
        $this->assertHasClass('tedi-dropdown-content__list', $html, 'tedi-dropdown-content__list');
        $this->assertHasClass('tedi-dropdown-content__list--plain', $html, 'tedi-dropdown-content__list');
    }

    public function test_content_labels_its_list_from_the_container_id(): void
    {
        $html = Blade::render(
            '<tedi:dropdown container-id="dd1"><tedi:dropdown-content>x</tedi:dropdown-content></tedi:dropdown>'
        );

        // Angular 9 moved both onto the <ul>; the presentational host keeps neither.
        $ul = $this->listTag($html);
        $this->assertStringContainsString('id="dd1"', $ul);
        $this->assertStringContainsString('aria-labelledby="dd1_trigger"', $ul);
        $this->assertSame(1, substr_count($html, 'aria-labelledby='));
        $this->assertSame(1, substr_count($html, 'id="dd1"'));
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
            strpos($html, '<ul'),
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
        $this->assertStringNotContainsString('x-on:click="hide(true)"', $html);
    }

    public function test_disabled_item_cancels_clicks_in_the_capture_phase(): void
    {
        $html = Blade::render('<tedi:dropdown-item :disabled="true">x</tedi:dropdown-item>');
        $this->assertStringContainsString('x-on:click.capture.prevent.stop', $html);

        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertStringNotContainsString('x-on:click.capture', $html);
    }

    /** The opening `<li …>` tag. */
    private function itemTag(string $html): string
    {
        preg_match('/<li\b[^>]*>/', $html, $m);
        $this->assertNotEmpty($m, 'No <li> rendered.');

        return $m[0];
    }

    public function test_list_role_items_carry_no_host_aria(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-content dropdown-role="list">
            <tedi:dropdown-item :disabled="true"><a href="/a">A</a></tedi:dropdown-item>
        </tedi:dropdown-content>
        BLADE);

        $li = $this->itemTag(preg_replace('/^.*?(<li\b)/s', '$1', $html));
        foreach (['role=', 'aria-selected', 'aria-disabled', 'tabindex'] as $attribute) {
            $this->assertStringNotContainsString($attribute, $li);
        }
    }

    public function test_interactive_content_moves_semantics_to_the_control(): void
    {
        foreach (['menu' => 'menuitem', 'listbox' => 'option'] as $role => $itemRole) {
            $html = Blade::render(
                '<tedi:dropdown-content dropdown-role="'.$role.'">'
                .'<tedi:dropdown-item :interactive-content="true" :disabled="true" :selected="true"><button>B</button></tedi:dropdown-item>'
                .'</tedi:dropdown-content>'
            );

            $li = $this->itemTag(preg_replace('/^.*?(<li\b)/s', '$1', $html));
            $this->assertStringContainsString('role="none"', $li);
            foreach (['aria-selected=', 'aria-disabled=', 'tabindex='] as $attribute) {
                $this->assertStringNotContainsString($attribute, $li);
            }

            $this->assertStringContainsString("control.setAttribute('role', '".$itemRole."')", $html);
            $this->assertStringContainsString("control.setAttribute('aria-disabled', 'true')", $html);
        }

        $html = Blade::render(
            '<tedi:dropdown-content dropdown-role="listbox">'
            .'<tedi:dropdown-item :interactive-content="true" :selected="true"><button>B</button></tedi:dropdown-item>'
            .'</tedi:dropdown-content>'
        );
        $this->assertStringContainsString("control.setAttribute('aria-selected', 'true')", $html);
        $this->assertStringContainsString("control.setAttribute('tabindex', '-1')", $html);
    }

    public function test_interactive_content_is_inert_in_a_plain_list(): void
    {
        $html = Blade::render(
            '<tedi:dropdown-content dropdown-role="list">'
            .'<tedi:dropdown-item :interactive-content="true"><a href="/a">A</a></tedi:dropdown-item>'
            .'</tedi:dropdown-content>'
        );

        $this->assertStringNotContainsString('control.setAttribute', $html);
        $this->assertStringNotContainsString('role="none"', $html);
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
        $this->assertStringContainsString('role="listbox"', $this->listTag($html));
    }

    public function test_item_wraps_plain_content_in_a_value_and_label(): void
    {
        $html = Blade::render('<tedi:dropdown-item>Kontaktid</tedi:dropdown-item>');

        $this->assertHasClass('tedi-option-content', $html, 'tedi-option-content');
        $this->assertHasClass('tedi-option-content__label', $html, 'tedi-option-content__label');
        $this->assertStringContainsString('Kontaktid', $html);
    }

    public function test_item_clip_content_reaches_the_generated_label(): void
    {
        $html = Blade::render('<tedi:dropdown-item :clip-content="false">x</tedi:dropdown-item>');
        $this->assertHasClass('tedi-option-content__label--no-clip', $html, 'tedi-option-content__label');

        $html = Blade::render('<tedi:dropdown-item>x</tedi:dropdown-item>');
        $this->assertMissingClass('tedi-option-content__label--no-clip', $html, 'tedi-option-content__label');
    }

    public function test_item_value_slot_suppresses_the_generated_wrapper(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:dropdown-item>
            <x-slot:item-value>
                <tedi:option-content layout="vertical">
                    <tedi:option-content-label>Tallinn</tedi:option-content-label>
                    <tedi:option-content-meta>3 vaba aega</tedi:option-content-meta>
                </tedi:option-content>
            </x-slot:item-value>
        </tedi:dropdown-item>
        BLADE);

        $this->assertSame(1, substr_count($html, '<tedi-option-content '));
        $this->assertHasClass('tedi-option-content--vertical', $html, 'tedi-option-content');
    }

    // -- attribute merging (CONVENTIONS.md §6) ---------------------------

    public function test_consumer_aria_label_wins_over_the_computed_role(): void
    {
        $html = Blade::render('<tedi:dropdown-item role="none">x</tedi:dropdown-item>');

        $this->assertStringContainsString('role="none"', $html);
        $this->assertStringNotContainsString('role="menuitem"', $html);
    }
}
