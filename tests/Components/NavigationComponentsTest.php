<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class NavigationComponentsTest extends TestCase
{
    // -- link -----------------------------------------------------------

    public function test_link_renders_as_button_by_default(): void
    {
        $html = Blade::render('<tedi:link>Go</tedi:link>');

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertHasClass('tedi-link', $html, 'tedi-link');
    }

    public function test_link_renders_as_anchor_when_href_given(): void
    {
        $html = Blade::render('<tedi:link href="/foo">Go</tedi:link>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/foo"', $html);
    }

    public function test_link_variant_classes(): void
    {
        foreach (['default', 'inverted'] as $variant) {
            $html = Blade::render('<tedi:link variant="'.$variant.'">Go</tedi:link>');

            if ($variant === 'inverted') {
                $this->assertHasClass('tedi-link--inverted', $html, 'tedi-link');
            } else {
                $this->assertMissingClass('tedi-link--inverted', $html, 'tedi-link');
            }
        }
    }

    public function test_link_size_classes(): void
    {
        $html = Blade::render('<tedi:link size="small">Go</tedi:link>');
        $this->assertHasClass('tedi-link--small', $html, 'tedi-link');

        $html = Blade::render('<tedi:link size="default">Go</tedi:link>');
        $this->assertMissingClass('tedi-link--small', $html, 'tedi-link');
    }

    public function test_link_underline_class(): void
    {
        $html = Blade::render('<tedi:link :underline="false">Go</tedi:link>');
        $this->assertHasClass('tedi-link--no-underline', $html, 'tedi-link');

        $html = Blade::render('<tedi:link :underline="true">Go</tedi:link>');
        $this->assertMissingClass('tedi-link--no-underline', $html, 'tedi-link');
    }

    public function test_link_wraps_text_in_a_span(): void
    {
        $html = Blade::render('<tedi:link href="/foo">Go</tedi:link>');

        $this->assertStringContainsString('<span>Go</span>', $html);
    }

    public function test_link_target_blank_adds_sr_only_new_tab_text(): void
    {
        $html = Blade::render('<tedi:link href="/foo" target="_blank">Go</tedi:link>');

        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString(__('tedi::tedi.anchor.new-tab'), $html);
    }

    public function test_link_icon_start_and_end(): void
    {
        $html = Blade::render('<tedi:link href="/foo" icon-start="arrow_back" icon-end="arrow_forward">Go</tedi:link>');

        $this->assertStringContainsString('arrow_back', $html);
        $this->assertStringContainsString('arrow_forward', $html);
    }

    // -- tabs -------------------------------------------------------------

    public function test_tabs_root_has_base_class(): void
    {
        $html = Blade::render('<tedi:tabs default-value="a"></tedi:tabs>');

        $this->assertHasClass('tedi-tabs', $html, 'tedi-tabs');
    }

    public function test_tabs_trigger_selected_state_matches_default_value(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:tabs default-value="a">
                <tedi:tabs.list>
                    <tedi:tabs.trigger id="a">Tab A</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="b">Tab B</tedi:tabs.trigger>
                </tedi:tabs.list>
            </tedi:tabs>
            BLADE);

        $this->assertStringContainsString('id="a"', $html);
        $this->assertStringContainsString('aria-selected="true"', $html);
        $this->assertStringContainsString('aria-selected="false"', $html);
    }

    public function test_tabs_trigger_selected_class(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:tabs default-value="a">
                <tedi:tabs.trigger id="a">Tab A</tedi:tabs.trigger>
            </tedi:tabs>
            BLADE);
        $this->assertHasClass('tedi-tabs-trigger--selected', $html, 'tedi-tabs-trigger');

        $html = Blade::render(<<<'BLADE'
            <tedi:tabs default-value="a">
                <tedi:tabs.trigger id="b">Tab B</tedi:tabs.trigger>
            </tedi:tabs>
            BLADE);
        $this->assertMissingClass('tedi-tabs-trigger--selected', $html, 'tedi-tabs-trigger');
    }

    public function test_tabs_list_base_classes(): void
    {
        $html = Blade::render('<tedi:tabs.list></tedi:tabs.list>');

        $this->assertHasClass('tedi-tabs-list', $html, 'tedi-tabs-list');
        $this->assertHasClass('tedi-tabs-list__items', $html, 'tedi-tabs-list__items');
        $this->assertStringContainsString('role="tablist"', $html);
    }

    public function test_tabs_list_overflow_mode_scroll_class(): void
    {
        $html = Blade::render('<tedi:tabs.list overflow-mode="scroll"></tedi:tabs.list>');
        $this->assertHasClass('tedi-tabs-list__items--scroll', $html, 'tedi-tabs-list__items');

        $html = Blade::render('<tedi:tabs.list></tedi:tabs.list>');
        $this->assertMissingClass('tedi-tabs-list__items--scroll', $html, 'tedi-tabs-list__items');
    }

    public function test_tabs_list_accepts_dropdown_label_without_error(): void
    {
        // Inert: the overflow "More" dropdown it would label isn't ported (§7).
        $html = Blade::render('<tedi:tabs.list dropdown-label="Muu"></tedi:tabs.list>');

        $this->assertHasClass('tedi-tabs-list', $html, 'tedi-tabs-list');
    }

    public function test_tabs_content_base_class(): void
    {
        $html = Blade::render('<tedi:tabs.content id="a">Panel</tedi:tabs.content>');

        $this->assertHasClass('tedi-tabs-content', $html, 'tedi-tabs-content');
        $this->assertStringContainsString('role="tabpanel"', $html);
    }

    public function test_tabs_trigger_disabled_state(): void
    {
        $html = Blade::render('<tedi:tabs.trigger id="a" :disabled="true">Tab A</tedi:tabs.trigger>');

        $this->assertHasClass('tedi-tabs-trigger--disabled', $html, 'tedi-tabs-trigger');
        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
    }

    public function test_tabs_trigger_renders_as_anchor_when_href_given(): void
    {
        $html = Blade::render('<tedi:tabs.trigger id="a" href="/foo">Tab A</tedi:tabs.trigger>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/foo"', $html);
    }

    public function test_tabs_content_hidden_when_not_active(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:tabs default-value="a">
                <tedi:tabs.content id="a">Panel A</tedi:tabs.content>
                <tedi:tabs.content id="b">Panel B</tedi:tabs.content>
            </tedi:tabs>
            BLADE);

        $this->assertStringContainsString('Panel A', $html);
        $this->assertStringContainsString('Panel B', $html);

        // Panel B (inactive) carries the `hidden` attribute; panel A doesn't.
        $panelB = substr($html, strpos($html, 'id="b-panel"') - 200, 250);
        $this->assertStringContainsString('hidden', $panelB);
    }

    public function test_tabs_content_always_active_without_id(): void
    {
        $html = Blade::render('<tedi:tabs.content>Always visible</tedi:tabs.content>');

        $this->assertDoesNotMatchRegularExpression('/(?<!x-bind:)\bhidden\b(?!=)/', $html);
        $this->assertStringContainsString('Always visible', $html);
    }

    // -- pagination ---------------------------------------------------------

    public function test_pagination_renders_pager_and_page_links(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="5" :page="2" :page-url="fn (int $p) => \'?page=\'.$p" />');

        $this->assertHasClass('tedi-pagination', $html, 'tedi-pagination');
        $this->assertStringContainsString('href="?page=1"', $html);
        $this->assertStringContainsString('href="?page=3"', $html);
        $this->assertHasClass('tedi-pagination__page--selected', $html, 'tedi-pagination__page--selected');
    }

    public function test_pagination_background_classes(): void
    {
        foreach (['white', 'transparent'] as $background) {
            $html = Blade::render('<tedi:pagination :page-count="3" background="'.$background.'" />');
            $this->assertHasClass('tedi-pagination--bg-'.$background, $html, 'tedi-pagination');
        }
    }

    public function test_pagination_divider_position_classes(): void
    {
        foreach (['top', 'bottom'] as $position) {
            $html = Blade::render('<tedi:pagination :page-count="3" divider-position="'.$position.'" />');
            $this->assertHasClass('tedi-pagination--divider-'.$position, $html, 'tedi-pagination');
        }
    }

    public function test_pagination_divider_none_emits_no_dead_class(): void
    {
        // Angular emits `tedi-pagination--divider-none` unconditionally too,
        // but the vendored SCSS has no rule for it — omitting it here still
        // renders identically (neither --divider-top nor --divider-bottom
        // matches), just without a class that would style nothing.
        $html = Blade::render('<tedi:pagination :page-count="3" divider-position="none" />');

        $this->assertMissingClass('tedi-pagination--divider-none', $html, 'tedi-pagination');
        $this->assertMissingClass('tedi-pagination--divider-top', $html, 'tedi-pagination');
        $this->assertMissingClass('tedi-pagination--divider-bottom', $html, 'tedi-pagination');
    }

    public function test_pagination_status_text_interpolates_without_digit_collision(): void
    {
        // Regression: a naive sequential str_replace(['true', '0'], [$page, $total], ...)
        // re-scans its own output, so page=10/total=20 would corrupt to "120 of 20".
        $html = Blade::render('<tedi:pagination :page-count="20" :page="10" />');

        $this->assertStringContainsString('Page 10 of 20', $html);
    }

    public function test_pagination_no_pager_when_single_page(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="1" />');

        $this->assertHasClass('tedi-pagination--no-pager', $html, 'tedi-pagination');
    }

    public function test_pagination_hide_flags(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="5" :hide-pager="true" :hide-results="true" :hide-page-size="true" :hide-arrows="true" :total-items="100" :page-size-options="[10, 20]" />');

        $this->assertHasClass('tedi-pagination--no-pager', $html, 'tedi-pagination');
        $this->assertHasClass('tedi-pagination--no-results', $html, 'tedi-pagination');
        $this->assertHasClass('tedi-pagination--no-page-size', $html, 'tedi-pagination');
    }

    public function test_pagination_align_classes(): void
    {
        $between = Blade::render('<tedi:pagination :page-count="3" />');
        $this->assertMissingClass('tedi-pagination--align-left', $between, 'tedi-pagination');
        $this->assertMissingClass('tedi-pagination--align-right', $between, 'tedi-pagination');

        $left = Blade::render('<tedi:pagination :page-count="3" align="left" />');
        $this->assertHasClass('tedi-pagination--align-left', $left, 'tedi-pagination');

        $right = Blade::render('<tedi:pagination :page-count="3" align="right" />');
        $this->assertHasClass('tedi-pagination--align-right', $right, 'tedi-pagination');
    }

    public function test_pagination_results_label_when_total_items_set(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="3" :total-items="42" />');

        $this->assertStringContainsString('42', $html);
        $this->assertMissingClass('tedi-pagination--no-results', $html, 'tedi-pagination');
    }

    public function test_pagination_results_slot_overrides_default(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:pagination :page-count="3">
                <x-slot:results>1000+ results</x-slot:results>
            </tedi:pagination>
            BLADE);

        $this->assertStringContainsString('1000+ results', $html);
    }

    public function test_pagination_accepts_show_modal_title_without_error(): void
    {
        // Inert: the mobile page-jump/page-size modal it would title isn't ported (§7).
        $html = Blade::render('<tedi:pagination :page-count="3" :show-modal-title="false" />');

        $this->assertHasClass('tedi-pagination', $html, 'tedi-pagination');
    }

    public function test_pagination_page_size_select_renders_options(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="3" :page-size="20" :page-size-options="[10, 20, 50]" />');

        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('value="10"', $html);
        $this->assertStringContainsString('value="20" selected', $html);
        $this->assertStringContainsString('value="50"', $html);
    }

    public function test_pagination_disable_arrows_at_boundary_keeps_disabled_button(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="5" :page="1" :disable-arrows-at-boundary="true" />');

        $this->assertHasClass('tedi-pagination__nav-button--previous', $html, 'tedi-pagination__nav-button--previous');
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_pagination_arrow_variant(): void
    {
        $html = Blade::render('<tedi:pagination :page-count="5" arrow-variant="primary" />');

        $this->assertHasClass('tedi-button--primary', $html, 'tedi-button--primary');
    }

    // -- variant matrix smoke test ------------------------------------------

    public function test_navigation_matrix_renders(): void
    {
        $path = __DIR__.'/../fixtures/matrices/navigation.blade.php';
        $out = Blade::render(file_get_contents($path));

        $this->assertHasClass('tedi-link', $out, 'tedi-link');
        $this->assertHasClass('tedi-tabs', $out, 'tedi-tabs');
        $this->assertHasClass('tedi-pagination', $out, 'tedi-pagination');
    }
}
