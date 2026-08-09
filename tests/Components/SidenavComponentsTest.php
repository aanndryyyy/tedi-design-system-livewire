<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for layout/sidenav (CONVENTIONS.md §10).
 *
 * The unions come straight from the Angular sources:
 *   SideNavItemSize = "small" | "medium" | "large"   (sidenav.component.ts)
 * everything else is boolean.
 */
class SidenavComponentsTest extends TestCase
{
    private const SIZES = ['small', 'medium', 'large'];

    /** A sidenav item with a dropdown, as a slot string. */
    private function itemWithDropdown(string $attrs = '', string $dropdownAttrs = ''): string
    {
        return <<<BLADE
        <tedi:sidenav.item label="Ravi" {$attrs}>
            Ravi
            <x-slot:dropdown>
                <tedi:sidenav.dropdown {$dropdownAttrs}>
                    <tedi:sidenav.dropdown-item href="#">Alam</tedi:sidenav.dropdown-item>
                </tedi:sidenav.dropdown>
            </x-slot:dropdown>
        </tedi:sidenav.item>
        BLADE;
    }

    // -- sidenav (root) --------------------------------------------------

    public function test_sidenav_renders_the_nav_element_with_the_angular_attribute_selector(): void
    {
        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');

        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('tedi-sidenav', $html);
        $this->assertHasClass('tedi-sidenav', $html, 'tedi-sidenav');
    }

    public function test_sidenav_size_classes(): void
    {
        foreach (self::SIZES as $size) {
            $html = Blade::render('<tedi:sidenav size="'.$size.'">x</tedi:sidenav>');

            $this->assertHasClass('tedi-sidenav--'.$size, $html, 'tedi-sidenav');

            foreach (array_diff(self::SIZES, [$size]) as $other) {
                $this->assertMissingClass('tedi-sidenav--'.$other, $html, 'tedi-sidenav');
            }
        }
    }

    public function test_sidenav_size_defaults_to_large(): void
    {
        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');

        $this->assertHasClass('tedi-sidenav--large', $html, 'tedi-sidenav');
    }

    public function test_sidenav_dividers_defaults_to_true(): void
    {
        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav--dividers', $html, 'tedi-sidenav');

        $html = Blade::render('<tedi:sidenav :dividers="false">x</tedi:sidenav>');
        $this->assertMissingClass('tedi-sidenav--dividers', $html, 'tedi-sidenav');
    }

    public function test_sidenav_collapsed_class(): void
    {
        $html = Blade::render('<tedi:sidenav :collapsed="true">x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav--collapsed', $html, 'tedi-sidenav');

        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');
        $this->assertMissingClass('tedi-sidenav--collapsed', $html, 'tedi-sidenav');
    }

    /** Angular's effect() forces isCollapsed back to false while isMobile(). */
    public function test_sidenav_collapsed_is_suppressed_on_mobile(): void
    {
        $html = Blade::render('<tedi:sidenav :collapsed="true" :mobile="true">x</tedi:sidenav>');

        $this->assertMissingClass('tedi-sidenav--collapsed', $html, 'tedi-sidenav');
        $this->assertHasClass('tedi-sidenav--mobile', $html, 'tedi-sidenav');
    }

    public function test_sidenav_mobile_classes(): void
    {
        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');
        $this->assertMissingClass('tedi-sidenav--mobile', $html, 'tedi-sidenav');
        $this->assertMissingClass('tedi-sidenav--hidden', $html, 'tedi-sidenav');
        $this->assertMissingClass('tedi-sidenav--mobile-item-open', $html, 'tedi-sidenav');

        // isMobile() && !isMobileOpen() -> --hidden
        $html = Blade::render('<tedi:sidenav :mobile="true">x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav--mobile', $html, 'tedi-sidenav');
        $this->assertHasClass('tedi-sidenav--hidden', $html, 'tedi-sidenav');

        $html = Blade::render('<tedi:sidenav :mobile="true" :mobile-open="true">x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav--mobile', $html, 'tedi-sidenav');
        $this->assertMissingClass('tedi-sidenav--hidden', $html, 'tedi-sidenav');

        $html = Blade::render('<tedi:sidenav :mobile="true" :mobile-open="true" :mobile-item-open="true">x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav--mobile-item-open', $html, 'tedi-sidenav');
    }

    public function test_sidenav_hidden_needs_mobile(): void
    {
        $html = Blade::render('<tedi:sidenav :mobile-open="false">x</tedi:sidenav>');

        $this->assertMissingClass('tedi-sidenav--hidden', $html, 'tedi-sidenav');
    }

    public function test_sidenav_list_wraps_the_slot(): void
    {
        $html = Blade::render('<tedi:sidenav>Sisu</tedi:sidenav>');

        $this->assertHasClass('tedi-sidenav__list', $html, 'tedi-sidenav__list');
        $this->assertStringContainsString('Sisu', $html);
    }

    public function test_sidenav_collapse_button_requires_collapsible_and_desktop(): void
    {
        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');
        $this->assertMissingClass('tedi-sidenav__collapse', $html, 'tedi-sidenav__collapse');

        $html = Blade::render('<tedi:sidenav :collapsible="true">x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav__collapse', $html, 'tedi-sidenav__collapse');

        // !isMobile() && collapsible()
        $html = Blade::render('<tedi:sidenav :collapsible="true" :mobile="true">x</tedi:sidenav>');
        $this->assertMissingClass('tedi-sidenav__collapse', $html, 'tedi-sidenav__collapse');
    }

    public function test_sidenav_collapse_button_icon_and_label_flip_with_collapsed(): void
    {
        $html = Blade::render('<tedi:sidenav :collapsible="true">x</tedi:sidenav>');
        $this->assertStringContainsString('>right_panel_open<', $html);
        $this->assertStringContainsString('aria-label="Close menu"', $html);

        $html = Blade::render('<tedi:sidenav :collapsible="true" :collapsed="true">x</tedi:sidenav>');
        $this->assertStringContainsString('>left_panel_open<', $html);
        $this->assertStringContainsString('aria-label="Open menu"', $html);
    }

    public function test_sidenav_back_button_is_emitted_only_in_the_mobile_branch(): void
    {
        $html = Blade::render('<tedi:sidenav>x</tedi:sidenav>');
        $this->assertMissingClass('tedi-sidenav-back', $html, 'tedi-sidenav-back');

        $html = Blade::render('<tedi:sidenav :mobile="true">x</tedi:sidenav>');
        $this->assertHasClass('tedi-sidenav-back', $html, 'tedi-sidenav-back');
        $this->assertStringContainsString('Back to main menu', $html);
        $this->assertStringContainsString('>arrow_back<', $html);
    }

    public function test_sidenav_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:sidenav class="oma-klass">x</tedi:sidenav>');

        $this->assertHasClass('oma-klass', $html, 'tedi-sidenav');
        $this->assertHasClass('tedi-sidenav', $html, 'tedi-sidenav');
    }

    /** §7 #1: desktopBreakpoint is not declared, so it leaks as a stray attribute. */
    public function test_sidenav_does_not_declare_desktop_breakpoint(): void
    {
        $html = Blade::render('<tedi:sidenav desktop-breakpoint="lg">x</tedi:sidenav>');

        $this->assertStringContainsString('desktop-breakpoint="lg"', $html);
    }

    // -- sidenav.item ----------------------------------------------------

    public function test_item_renders_the_custom_element_host_and_inner_li(): void
    {
        $html = Blade::render('<tedi:sidenav.item>Avaleht</tedi:sidenav.item>');

        $this->assertStringContainsString('<tedi-sidenav-item', $html);
        $this->assertStringContainsString('role="presentation"', $html);
        $this->assertStringContainsString('display: contents', $html);
        $this->assertHasClass('tedi-sidenav-item', $html, 'tedi-sidenav-item');
    }

    public function test_item_selected_class(): void
    {
        $html = Blade::render('<tedi:sidenav.item :selected="true">x</tedi:sidenav.item>');
        $this->assertHasClass('tedi-sidenav-item--selected', $html, 'tedi-sidenav-item');

        $html = Blade::render('<tedi:sidenav.item>x</tedi:sidenav.item>');
        $this->assertMissingClass('tedi-sidenav-item--selected', $html, 'tedi-sidenav-item');
    }

    /** isMobileItemOpen() && !dropdown?.open() -> --hidden */
    public function test_item_hidden_class(): void
    {
        $html = Blade::render('<tedi:sidenav.item :mobile-item-open="true">x</tedi:sidenav.item>');
        $this->assertHasClass('tedi-sidenav-item--hidden', $html, 'tedi-sidenav-item');

        $html = Blade::render('<tedi:sidenav.item :mobile-item-open="true" :open="true">x</tedi:sidenav.item>');
        $this->assertMissingClass('tedi-sidenav-item--hidden', $html, 'tedi-sidenav-item');

        $html = Blade::render('<tedi:sidenav.item>x</tedi:sidenav.item>');
        $this->assertMissingClass('tedi-sidenav-item--hidden', $html, 'tedi-sidenav-item');
    }

    public function test_item_renders_a_link_title_when_href_is_given(): void
    {
        $html = Blade::render('<tedi:sidenav.item href="/avaleht">Avaleht</tedi:sidenav.item>');

        $this->assertHasClass('tedi-sidenav-item__trigger', $html, 'tedi-sidenav-item__trigger');
        $this->assertHasClass('tedi-sidenav-item__title', $html, 'tedi-sidenav-item__title');
        $this->assertStringContainsString('<a class="tedi-sidenav-item__title" href="/avaleht">', $html);
    }

    /** [routerLink] has no router here: it renders as an ordinary href. */
    public function test_item_route_renders_as_an_href(): void
    {
        $html = Blade::render('<tedi:sidenav.item route="/maksed">Maksed</tedi:sidenav.item>');

        $this->assertStringContainsString('href="/maksed"', $html);
    }

    public function test_item_without_a_link_renders_a_button_title(): void
    {
        $html = Blade::render('<tedi:sidenav.item>Avaleht</tedi:sidenav.item>');

        $this->assertStringContainsString('<button', $html);
        $this->assertHasClass('tedi-sidenav-item__title', $html, 'tedi-sidenav-item__title');
        $this->assertStringNotContainsString('<a class="tedi-sidenav-item__title"', $html);
    }

    public function test_item_icon_and_text(): void
    {
        $html = Blade::render('<tedi:sidenav.item icon="home">Avaleht</tedi:sidenav.item>');

        $this->assertHasClass('tedi-sidenav-item__icon', $html, 'tedi-sidenav-item__icon');
        $this->assertHasClass('tedi-sidenav-item__text', $html, 'tedi-sidenav-item__text');
        $this->assertStringContainsString('>home<', $html);
    }

    /** The mobile drill-down suppresses this item's own icon. */
    public function test_item_icon_is_dropped_when_drilled_into_on_mobile(): void
    {
        $html = Blade::render('<tedi:sidenav.item icon="home" href="#" :mobile-item-open="true" :open="true">Avaleht</tedi:sidenav.item>');

        $this->assertMissingClass('tedi-sidenav-item__icon', $html, 'tedi-sidenav-item__icon');
        $this->assertHasClass('tedi-sidenav-item__link', $html, 'tedi-sidenav-item__link');
    }

    public function test_item_drilled_into_without_a_link_renders_a_group_title(): void
    {
        $html = Blade::render('<tedi:sidenav.item :mobile-item-open="true" :open="true">Ravi</tedi:sidenav.item>');

        $this->assertHasClass('tedi-sidenav-group-title', $html, 'tedi-sidenav-group-title');
        $this->assertMissingClass('tedi-sidenav-item__trigger', $html, 'tedi-sidenav-item__trigger');
    }

    public function test_item_drilled_into_with_a_link_renders_the_link_class(): void
    {
        $html = Blade::render('<tedi:sidenav.item href="#" :mobile-item-open="true" :open="true">Ravi</tedi:sidenav.item>');

        $this->assertHasClass('tedi-sidenav-item__link', $html, 'tedi-sidenav-item__link');
        $this->assertMissingClass('tedi-sidenav-item__title', $html, 'tedi-sidenav-item__title');
    }

    public function test_item_caret_is_emitted_only_with_a_dropdown(): void
    {
        $html = Blade::render('<tedi:sidenav.item>x</tedi:sidenav.item>');
        $this->assertMissingClass('tedi-sidenav-item__caret', $html, 'tedi-sidenav-item__caret');

        $html = Blade::render('<tedi:sidenav.item :has-dropdown="true">x</tedi:sidenav.item>');
        $this->assertHasClass('tedi-sidenav-item__caret', $html, 'tedi-sidenav-item__caret');
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('data-open="false"', $html);

        $html = Blade::render('<tedi:sidenav.item :has-dropdown="true" :open="true">x</tedi:sidenav.item>');
        $this->assertStringContainsString('aria-expanded="true"', $html);
        $this->assertStringContainsString('data-open="true"', $html);
    }

    /** A linked item with a dropdown gets a separate caret BUTTON on desktop. */
    public function test_item_linked_with_dropdown_gets_a_caret_button(): void
    {
        $html = Blade::render('<tedi:sidenav.item href="#" label="Ravi" :has-dropdown="true">Ravi</tedi:sidenav.item>');

        $this->assertHasClass('tedi-sidenav-item__caret-button', $html, 'tedi-sidenav-item__caret-button');
        $this->assertHasClass('tedi-sidenav-item__caret-container', $html, 'tedi-sidenav-item__caret-container');
        $this->assertStringContainsString('<a class="tedi-sidenav-item__title"', $html);
        $this->assertStringContainsString('Open Ravi submenu', $html);
    }

    /** …but collapses into a single button trigger while collapsed or mobile. */
    public function test_item_linked_with_dropdown_becomes_a_button_when_collapsed_or_mobile(): void
    {
        foreach (['collapsed', 'mobile'] as $state) {
            $html = Blade::render('<tedi:sidenav.item href="#" :has-dropdown="true" :'.$state.'="true">Ravi</tedi:sidenav.item>');

            $this->assertMissingClass('tedi-sidenav-item__caret-button', $html, 'tedi-sidenav-item__caret-button');
            $this->assertStringNotContainsString('<a class="tedi-sidenav-item__title"', $html);
            $this->assertHasClass('tedi-sidenav-item__caret', $html, 'tedi-sidenav-item__caret');
        }
    }

    public function test_item_tooltip_branch_is_keyed_on_collapsed(): void
    {
        $html = Blade::render('<tedi:sidenav.item icon="home" label="Avaleht" href="#">Avaleht</tedi:sidenav.item>');
        $this->assertStringNotContainsString('<tedi-tooltip-trigger', $html);

        $html = Blade::render('<tedi:sidenav.item icon="home" label="Avaleht" href="#" :collapsed="true">Avaleht</tedi:sidenav.item>');
        $this->assertStringContainsString('<tedi-tooltip-trigger', $html);
        $this->assertStringContainsString('<tedi-tooltip-content', $html);

        // isMobile() wins over isCollapsed() in Angular's branch order.
        $html = Blade::render('<tedi:sidenav.item icon="home" href="#" :collapsed="true" :mobile="true">Avaleht</tedi:sidenav.item>');
        $this->assertStringNotContainsString('<tedi-tooltip-trigger', $html);
    }

    public function test_item_merges_consumer_classes_on_the_host(): void
    {
        $html = Blade::render('<tedi:sidenav.item class="oma-klass">x</tedi:sidenav.item>');

        $this->assertHasClass('oma-klass', $html, 'oma-klass');
        $this->assertHasClass('tedi-sidenav-item', $html, 'tedi-sidenav-item');
    }

    // -- @aware: sidenav -> item -----------------------------------------

    public function test_item_inherits_collapsed_from_the_sidenav(): void
    {
        $html = Blade::render(
            '<tedi:sidenav :collapsed="true"><tedi:sidenav.item href="#" label="Ravi">Ravi</tedi:sidenav.item></tedi:sidenav>'
        );

        $this->assertStringContainsString('<tedi-tooltip-trigger', $html);
    }

    public function test_item_inherits_mobile_item_open_from_the_sidenav(): void
    {
        $html = Blade::render(
            '<tedi:sidenav :mobile="true" :mobile-open="true" :mobile-item-open="true">'
            .'<tedi:sidenav.item href="#">Avaleht</tedi:sidenav.item></tedi:sidenav>'
        );

        $this->assertHasClass('tedi-sidenav-item--hidden', $html, 'tedi-sidenav-item');
    }

    /** CONVENTIONS.md §3: an explicitly-passed default must render like the omitted one. */
    public function test_item_aware_defaults_match_the_sidenav_props(): void
    {
        $implicit = Blade::render('<tedi:sidenav><tedi:sidenav.item href="#">A</tedi:sidenav.item></tedi:sidenav>');
        $explicit = Blade::render(
            '<tedi:sidenav :collapsed="false" :mobile="false" :mobile-item-open="false">'
            .'<tedi:sidenav.item href="#">A</tedi:sidenav.item></tedi:sidenav>'
        );

        $this->assertSame(
            $this->classesOf($implicit, 'tedi-sidenav-item'),
            $this->classesOf($explicit, 'tedi-sidenav-item')
        );
    }

    // -- sidenav.group-title ---------------------------------------------

    public function test_group_title_classes(): void
    {
        $html = Blade::render('<tedi:sidenav.group-title>Menüü</tedi:sidenav.group-title>');

        $this->assertStringContainsString('<tedi-sidenav-group-title', $html);
        $this->assertHasClass('tedi-sidenav-group-title', $html, 'tedi-sidenav-group-title');
        $this->assertHasClass('tedi-sidenav-group-title__text', $html, 'tedi-sidenav-group-title__text');
        $this->assertStringContainsString('Menüü', $html);
    }

    // -- sidenav.dropdown ------------------------------------------------

    public function test_dropdown_wrapper_and_list_classes(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown>x</tedi:sidenav.dropdown>');

        $this->assertStringContainsString('<tedi-sidenav-dropdown', $html);
        $this->assertHasClass('tedi-sidenav-dropdown-wrapper', $html, 'tedi-sidenav-dropdown-wrapper');
        $this->assertHasClass('tedi-sidenav-dropdown', $html, 'tedi-sidenav-dropdown');
    }

    /** §8: the closed panel is in the DOM with its real class list. */
    public function test_dropdown_open_class(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown>x</tedi:sidenav.dropdown>');
        $this->assertMissingClass('tedi-sidenav-dropdown--open', $html, 'tedi-sidenav-dropdown');

        $html = Blade::render($this->itemWithDropdown(':has-dropdown="true" :open="true"'));
        $this->assertHasClass('tedi-sidenav-dropdown--open', $html, 'tedi-sidenav-dropdown');
    }

    public function test_dropdown_has_no_overlay_panel_markup(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown>x</tedi:sidenav.dropdown>');

        $this->assertStringNotContainsString('tedi-dropdown__panel', $html);
        $this->assertStringNotContainsString('tediOverlay', $html);
    }

    /** The generated parent link only exists in the collapsed fly-out. */
    public function test_dropdown_parent_link_requires_collapsed_and_a_link(): void
    {
        $html = Blade::render($this->itemWithDropdown('href="#"', 'parent-label="Ravi"'));
        $this->assertMissingClass('tedi-sidenav-dropdown-item--parent', $html, 'tedi-sidenav-dropdown-item--parent');

        $html = Blade::render($this->itemWithDropdown('href="/ravi" :collapsed="true"', 'parent-label="Ravi"'));
        $this->assertHasClass('tedi-sidenav-dropdown-item--parent', $html, 'tedi-sidenav-dropdown-item--parent');
        $this->assertStringContainsString('href="/ravi"', $html);

        $html = Blade::render($this->itemWithDropdown('route="/ravi" :collapsed="true"', 'parent-label="Ravi"'));
        $this->assertHasClass('tedi-sidenav-dropdown-item--parent', $html, 'tedi-sidenav-dropdown-item--parent');

        // No href and no route: nothing to link to.
        $html = Blade::render($this->itemWithDropdown(':collapsed="true"', 'parent-label="Ravi"'));
        $this->assertMissingClass('tedi-sidenav-dropdown-item--parent', $html, 'tedi-sidenav-dropdown-item--parent');
    }

    // -- sidenav.dropdown-item -------------------------------------------

    public function test_dropdown_item_host_and_classes(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown-item>Alam</tedi:sidenav.dropdown-item>');

        $this->assertStringContainsString('<tedi-sidenav-dropdown-item', $html);
        $this->assertStringContainsString('role="presentation"', $html);
        $this->assertHasClass('tedi-sidenav-dropdown-item', $html, 'tedi-sidenav-dropdown-item');
        $this->assertHasClass('tedi-sidenav-dropdown-item__trigger', $html, 'tedi-sidenav-dropdown-item__trigger');
    }

    public function test_dropdown_item_selected_class(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown-item :selected="true">x</tedi:sidenav.dropdown-item>');
        $this->assertHasClass('tedi-sidenav-dropdown-item--selected', $html, 'tedi-sidenav-dropdown-item');

        $html = Blade::render('<tedi:sidenav.dropdown-item>x</tedi:sidenav.dropdown-item>');
        $this->assertMissingClass('tedi-sidenav-dropdown-item--selected', $html, 'tedi-sidenav-dropdown-item');
    }

    public function test_dropdown_item_trigger_element_depends_on_the_link(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown-item href="/a">x</tedi:sidenav.dropdown-item>');
        $this->assertStringContainsString('<a href="/a" class="tedi-sidenav-dropdown-item__trigger">', $html);

        $html = Blade::render('<tedi:sidenav.dropdown-item route="/b">x</tedi:sidenav.dropdown-item>');
        $this->assertStringContainsString('<a href="/b" class="tedi-sidenav-dropdown-item__trigger">', $html);

        $html = Blade::render('<tedi:sidenav.dropdown-item>x</tedi:sidenav.dropdown-item>');
        $this->assertStringContainsString('<div class="tedi-sidenav-dropdown-item__trigger">', $html);
    }

    // -- sidenav.dropdown-group ------------------------------------------

    public function test_dropdown_group_renders_nothing_without_items(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown-group />');

        $this->assertHasClass('tedi-sidenav-dropdown-group', $html, 'tedi-sidenav-dropdown-group');
        $this->assertMissingClass('tedi-sidenav-dropdown-group__parent-wrapper', $html, 'tedi-sidenav-dropdown-group__parent-wrapper');
    }

    public function test_dropdown_group_first_item_becomes_the_parent_row(): void
    {
        $html = Blade::render(
            '<tedi:sidenav.dropdown-group :items="[[\'label\' => \'Raviplaanid\', \'href\' => \'/a\']]" />'
        );

        $this->assertHasClass('tedi-sidenav-dropdown-group__parent-wrapper', $html, 'tedi-sidenav-dropdown-group__parent-wrapper');
        $this->assertHasClass('tedi-sidenav-dropdown-group__parent', $html, 'tedi-sidenav-dropdown-group__parent');
        $this->assertHasClass('tedi-sidenav-dropdown-item', $html, 'tedi-sidenav-dropdown-group__parent');
        $this->assertStringContainsString('Raviplaanid', $html);

        // A single item emits no nested list.
        $this->assertMissingClass('tedi-sidenav-dropdown-group__list', $html, 'tedi-sidenav-dropdown-group__list');
    }

    public function test_dropdown_group_remaining_items_go_into_the_nested_list(): void
    {
        $html = Blade::render(
            '<tedi:sidenav.dropdown-group :items="['
            .'[\'label\' => \'Raviplaanid\', \'href\' => \'/a\'],'
            .'[\'label\' => \'Aktiivsed\', \'href\' => \'/b\', \'selected\' => true],'
            .'[\'label\' => \'Ilma lingita\'],'
            .']" />'
        );

        $this->assertHasClass('tedi-sidenav-dropdown-group__list', $html, 'tedi-sidenav-dropdown-group__list');
        $this->assertHasClass('tedi-sidenav-dropdown-group__item', $html, 'tedi-sidenav-dropdown-group__item');
        $this->assertHasClass('tedi-sidenav-dropdown-item--selected', $html, 'tedi-sidenav-dropdown-group__item');
        $this->assertStringContainsString('<span class="tedi-sidenav-dropdown-item__trigger">Ilma lingita</span>', $html);
    }

    public function test_dropdown_group_uses_route_when_there_is_no_href(): void
    {
        $html = Blade::render(
            '<tedi:sidenav.dropdown-group :items="[[\'label\' => \'A\', \'route\' => \'/r\']]" />'
        );

        $this->assertStringContainsString('href="/r"', $html);
    }

    /** The hidden ng-content container upstream is deliberately not reproduced. */
    public function test_dropdown_group_omits_the_hidden_projection_container(): void
    {
        $html = Blade::render('<tedi:sidenav.dropdown-group :items="[[\'label\' => \'A\']]" />');

        $this->assertStringNotContainsString('aria-hidden="true"', $html);
    }

    // -- sidenav.overlay -------------------------------------------------

    public function test_overlay_classes(): void
    {
        $html = Blade::render('<tedi:sidenav.overlay />');
        $this->assertStringContainsString('<tedi-sidenav-overlay', $html);
        $this->assertHasClass('tedi-sidenav-overlay', $html, 'tedi-sidenav-overlay');
        $this->assertMissingClass('tedi-sidenav-overlay--visible', $html, 'tedi-sidenav-overlay');

        // isMobile() && isMobileOpen()
        $html = Blade::render('<tedi:sidenav.overlay :mobile="true" :mobile-open="true" />');
        $this->assertHasClass('tedi-sidenav-overlay--visible', $html, 'tedi-sidenav-overlay');

        $html = Blade::render('<tedi:sidenav.overlay :mobile-open="true" />');
        $this->assertMissingClass('tedi-sidenav-overlay--visible', $html, 'tedi-sidenav-overlay');

        $html = Blade::render('<tedi:sidenav.overlay :mobile="true" />');
        $this->assertMissingClass('tedi-sidenav-overlay--visible', $html, 'tedi-sidenav-overlay');
    }

    // -- sidenav.toggle --------------------------------------------------

    public function test_toggle_classes_and_attribute_selector(): void
    {
        $html = Blade::render('<tedi:sidenav.toggle />');

        $this->assertStringContainsString('tedi-sidenav-toggle', $html);
        $this->assertHasClass('tedi-sidenav-toggle', $html, 'tedi-sidenav-toggle');
        $this->assertHasClass('tedi-sidenav-toggle__icon', $html, 'tedi-sidenav-toggle__icon');
    }

    /** !isMobile() -> --hidden */
    public function test_toggle_hidden_class(): void
    {
        $html = Blade::render('<tedi:sidenav.toggle />');
        $this->assertHasClass('tedi-sidenav-toggle--hidden', $html, 'tedi-sidenav-toggle');

        $html = Blade::render('<tedi:sidenav.toggle :mobile="true" />');
        $this->assertMissingClass('tedi-sidenav-toggle--hidden', $html, 'tedi-sidenav-toggle');
    }

    public function test_toggle_icon_and_label_flip_with_mobile_open(): void
    {
        $html = Blade::render('<tedi:sidenav.toggle :mobile="true" />');
        $this->assertStringContainsString('>menu<', $html);
        $this->assertStringContainsString('aria-label="Open menu"', $html);

        $html = Blade::render('<tedi:sidenav.toggle :mobile="true" :mobile-open="true" />');
        $this->assertStringContainsString('>close<', $html);
        $this->assertStringContainsString('aria-label="Close menu"', $html);
    }
}
