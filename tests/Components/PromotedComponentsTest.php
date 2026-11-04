<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the components upstream promoted into Angular's
 * `src/tedi/` tree, which are now ported from there (CONVENTIONS.md §1):
 * floating-button and table-of-contents (formerly `community/`), and
 * skeleton, heading-with-icon, truncate and print (formerly React-only).
 *
 * The unions come from the Angular `export type` declarations:
 *   FloatingButtonVariant / Size / Axis / Position / Placement,
 *   TableOfContentsVariant / HeadingLevel, SkeletonBlockTextHeight,
 *   HeadingModifiers, PrintVisibility / PrintBreak.
 *
 * print emits core's unprefixed utilities, so IntegrityTest's `tedi-*`
 * guardrails skip it — these assertions are its only coverage.
 */
class PromotedComponentsTest extends TestCase
{
    // =====================================================================
    // Buttons / FloatingButton
    // =====================================================================

    public function test_floating_button_renders_a_button_with_the_selector_attribute(): void
    {
        $html = Blade::render('<tedi:floating-button>Tagasiside</tedi:floating-button>');

        $this->assertStringContainsString('<button', $html);
        $this->assertMatchesRegularExpression('/<button\s+tedi-floating-button\b/', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertHasClass('tedi-floating-button', $html);
    }

    public function test_floating_button_variant_and_size_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            foreach (['default', 'large'] as $size) {
                $html = Blade::render('<tedi:floating-button variant="'.$variant.'" size="'.$size.'">x</tedi:floating-button>');

                $this->assertHasClass('tedi-floating-button--'.$variant, $html);
                $this->assertHasClass('tedi-floating-button--'.$size, $html);
            }
        }
    }

    public function test_floating_button_defaults_match_angular(): void
    {
        $html = Blade::render('<tedi:floating-button>x</tedi:floating-button>');

        $this->assertHasClass('tedi-floating-button--primary', $html);
        $this->assertHasClass('tedi-floating-button--default', $html);
        $this->assertStringContainsString('position: fixed', $html);
        $this->assertStringNotContainsString('z-index', $html);
    }

    public function test_floating_button_emits_no_print_like_base_button_directive(): void
    {
        $this->assertHasClass('no-print', Blade::render('<tedi:floating-button>x</tedi:floating-button>'));
    }

    public function test_floating_button_vertical_axis_emits_a_class_and_horizontal_does_not(): void
    {
        $this->assertHasClass('tedi-floating-button--vertical', Blade::render('<tedi:floating-button axis="vertical">x</tedi:floating-button>'));
        $this->assertMissingClass('tedi-floating-button--horizontal', Blade::render('<tedi:floating-button axis="horizontal">x</tedi:floating-button>'));
    }

    public function test_floating_button_padding_modifiers_mirror_base_button_directive(): void
    {
        $plain = Blade::render('<tedi:floating-button>x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--pl', $plain);
        $this->assertHasClass('tedi-floating-button--pr', $plain);

        $start = Blade::render('<tedi:floating-button icon-start="add">x</tedi:floating-button>');
        $this->assertMissingClass('tedi-floating-button--pl', $start);
        $this->assertHasClass('tedi-floating-button--pr', $start);

        $end = Blade::render('<tedi:floating-button icon-end="add">x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--pl', $end);
        $this->assertMissingClass('tedi-floating-button--pr', $end);

        $only = Blade::render('<tedi:floating-button icon-only icon-start="add" aria-label="Lisa" />');
        $this->assertHasClass('tedi-floating-button--icon-only', $only);
        $this->assertMissingClass('tedi-floating-button--pl', $only);
        $this->assertMissingClass('tedi-floating-button--pr', $only);
    }

    public function test_floating_button_disabled(): void
    {
        $this->assertStringContainsString('disabled', Blade::render('<tedi:floating-button disabled>x</tedi:floating-button>'));
    }

    public function test_floating_button_position_union_is_written_inline(): void
    {
        foreach (['fixed', 'absolute', 'sticky', 'relative', 'static'] as $position) {
            $html = Blade::render('<tedi:floating-button position="'.$position.'">x</tedi:floating-button>');

            $this->assertStringContainsString('position: '.$position.';', $html);
        }
    }

    public function test_floating_button_placement_pins_the_named_edges_with_offsets(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:floating-button position="absolute" :placement="['vertical' => 'top', 'horizontal' => 'left']" :offset="['top' => 16, 'left' => '1rem', 'bottom' => 99]">x</tedi:floating-button>
            BLADE);

        $this->assertStringContainsString('top: 16px;', $html);
        $this->assertStringContainsString('left: 1rem;', $html);
        $this->assertStringNotContainsString('bottom:', $html);
        $this->assertStringNotContainsString('translate:', $html);
    }

    public function test_floating_button_placement_defaults_the_offset_to_zero(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:floating-button :placement="['vertical' => 'bottom', 'horizontal' => 'right']">x</tedi:floating-button>
            BLADE);

        $this->assertStringContainsString('bottom: 0px;', $html);
        $this->assertStringContainsString('right: 0px;', $html);
    }

    public function test_floating_button_center_placement_translates_the_centred_axis(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:floating-button :placement="['vertical' => 'bottom', 'horizontal' => 'center']" :offset="['bottom' => 16]">x</tedi:floating-button>
            BLADE);

        $this->assertStringContainsString('left: 50%;', $html);
        $this->assertStringContainsString('bottom: 16px;', $html);
        $this->assertStringContainsString('translate: -50% 0;', $html);

        $both = Blade::render(<<<'BLADE'
            <tedi:floating-button :placement="['vertical' => 'center', 'horizontal' => 'center']">x</tedi:floating-button>
            BLADE);
        $this->assertStringContainsString('top: 50%;', $both);
        $this->assertStringContainsString('translate: -50% -50%;', $both);
    }

    public function test_floating_button_static_position_ignores_placement(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:floating-button position="static" :placement="['vertical' => 'top', 'horizontal' => 'left']">x</tedi:floating-button>
            BLADE);

        $this->assertStringNotContainsString('top:', $html);
        $this->assertStringNotContainsString('left:', $html);
    }

    public function test_floating_button_z_index_override(): void
    {
        $this->assertStringContainsString('z-index: 5;', Blade::render('<tedi:floating-button :z-index="5">x</tedi:floating-button>'));
    }

    // =====================================================================
    // Navigation / TableOfContents
    // =====================================================================

    private function toc(string $attributes = '', string $itemAttributes = ''): string
    {
        return Blade::render(<<<BLADE
            <tedi:table-of-contents {$attributes}>
                <tedi:table-of-contents-item item-id="intro" {$itemAttributes}><a href="#intro">Sissejuhatus</a></tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="methods">
                    <a href="#methods">Meetodid</a>
                    <x-slot:sub-items>
                        <tedi:table-of-contents-item item-id="methods-1"><a href="#methods-1">Andmete kogumine</a></tedi:table-of-contents-item>
                        <tedi:table-of-contents-item item-id="methods-2"><a href="#methods-2">Analüüs</a></tedi:table-of-contents-item>
                    </x-slot:sub-items>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="results"><a href="#results">Tulemused</a></tedi:table-of-contents-item>
            </tedi:table-of-contents>
            BLADE);
    }

    public function test_table_of_contents_root_element_and_defaults(): void
    {
        $html = $this->toc();

        $this->assertStringContainsString('<tedi-table-of-contents', $html);
        $this->assertStringContainsString('data-name="table-of-contents"', $html);
        $this->assertHasClass('tedi-table-of-contents', $html, 'tedi-table-of-contents');
        $this->assertMissingClass('tedi-table-of-contents--bordered', $html, 'tedi-table-of-contents');

        // variant default → card container, sticky default true.
        $this->assertHasClass('tedi-card', $html, 'tedi-table-of-contents__container');
        $this->assertHasClass('tedi-table-of-contents--sticky', $html, 'tedi-table-of-contents__container');

        // heading default → translated title, h3, styled h4.
        $this->assertMatchesRegularExpression('/<h3 class="[^"]*tedi-text--h4[^"]*tedi-table-of-contents__heading/', $html);
        $this->assertStringContainsString('Table of contents', $html);
    }

    public function test_table_of_contents_heading_level_union(): void
    {
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $level) {
            $html = $this->toc('heading="Sisukord" heading-level="'.$level.'"');

            $this->assertMatchesRegularExpression('/<'.$level.' class="[^"]*tedi-table-of-contents__heading/', $html);
        }
    }

    public function test_table_of_contents_heading_labels_the_nav(): void
    {
        $html = $this->toc('heading="Sisukord"');

        preg_match('/id="(tedi-table-of-contents-heading-[^"]+)"/', $html, $m);
        $this->assertNotEmpty($m);
        $this->assertStringContainsString('aria-labelledby="'.$m[1].'"', $html);
        $this->assertStringNotContainsString(' aria-label=', $html);
        $this->assertMissingClass('tedi-table-of-contents__list--headless', $html, 'tedi-table-of-contents__list');
    }

    public function test_table_of_contents_headless_with_an_empty_heading(): void
    {
        $html = $this->toc('heading=""');

        $this->assertStringNotContainsString('tedi-table-of-contents__heading', $html);
        $this->assertStringContainsString('aria-label="Table of contents"', $html);
        $this->assertHasClass('tedi-table-of-contents__list--headless', $html, 'tedi-table-of-contents__list');
    }

    public function test_table_of_contents_aria_label_overrides_the_heading(): void
    {
        $html = $this->toc('heading="Sisukord" aria-label="Lehe sisukord"');

        $this->assertStringContainsString('aria-label="Lehe sisukord"', $html);
        $this->assertStringNotContainsString('aria-labelledby', $html);
    }

    public function test_table_of_contents_variant_union(): void
    {
        $transparent = $this->toc('variant="transparent"');
        $this->assertHasClass('tedi-table-of-contents--transparent', $transparent, 'tedi-table-of-contents__container');
        $this->assertStringNotContainsString('tedi-card', $transparent);

        $default = $this->toc('variant="default"');
        $this->assertMissingClass('tedi-table-of-contents--transparent', $default, 'tedi-table-of-contents__container');
    }

    public function test_table_of_contents_sticky_and_bordered(): void
    {
        $html = $this->toc(':sticky="false" :bordered="true"');

        $this->assertMissingClass('tedi-table-of-contents--sticky', $html, 'tedi-table-of-contents__container');
        $this->assertHasClass('tedi-table-of-contents--bordered', $html, 'tedi-table-of-contents');
    }

    public function test_table_of_contents_sticky_geometry_is_inline_custom_properties(): void
    {
        $html = $this->toc('sticky-offset="3rem" sticky-max-height="calc(30rem - 3rem)"');

        $this->assertStringContainsString('--tedi-table-of-contents-sticky-top: 3rem', $html);
        $this->assertStringContainsString('--tedi-table-of-contents-sticky-max-height: calc(30rem - 3rem)', $html);
        $this->assertStringNotContainsString('sticky-top', $this->toc());
    }

    public function test_table_of_contents_item_classes_and_landmark_roles(): void
    {
        $html = $this->toc();

        $this->assertStringContainsString('<tedi-table-of-contents-item', $html);
        $this->assertStringContainsString('role="listitem"', $html);
        $this->assertHasClass('tedi-table-of-contents__item', $html, 'tedi-table-of-contents__item');
        $this->assertHasClass('tedi-table-of-contents__row', $html, 'tedi-table-of-contents__row');
        $this->assertHasClass('tedi-table-of-contents__content', $html, 'tedi-table-of-contents__content');
        $this->assertStringContainsString('<ul class="tedi-table-of-contents__list" role="list">', $html);
    }

    public function test_table_of_contents_active_id_selects_the_matching_item(): void
    {
        $html = $this->toc('active-id="methods-2"');

        $this->assertSame(1, substr_count($html, ' aria-current="location"'));
        $this->assertSame(1, substr_count($html, 'tedi-table-of-contents__item--selected"'));
        $this->assertMatchesRegularExpression('/data-toc-item-id="methods-2"\s+aria-current="location" class="tedi-table-of-contents__item tedi-table-of-contents__item--selected"/', $html);
    }

    public function test_table_of_contents_nested_rows_are_level_two(): void
    {
        $html = $this->toc();

        // --level-1 is unstyled upstream and dropped; nested rows get --level-2.
        $this->assertStringNotContainsString('tedi-table-of-contents__row--level-1', $html);
        $this->assertSame(3, substr_count($html, 'class="tedi-table-of-contents__row"'));
        $this->assertSame(2, substr_count($html, 'class="tedi-table-of-contents__row tedi-table-of-contents__row--level-2"'));
        $this->assertStringContainsString('<ul
            class="tedi-table-of-contents__group"', $html);
    }

    public function test_table_of_contents_numbers_are_hierarchical(): void
    {
        $html = $this->toc(':numbered="true"');

        preg_match_all('/tedi-table-of-contents__number" aria-hidden="true">([^<]*)</', $html, $m);
        $this->assertSame(['1.', '2.', '2.1', '2.2', '3.'], $m[1]);
        $this->assertStringNotContainsString('__TEDI_TOC_', $html);
        $this->assertStringNotContainsString('tedi-table-of-contents__number', $this->toc());
    }

    public function test_table_of_contents_default_open_false_hides_branches_off_the_active_trail(): void
    {
        $closed = $this->toc(':default-open="false" active-id="intro"');
        $this->assertMatchesRegularExpression('/tedi-table-of-contents__group"\s+role="list"\s+style="display: none;"/', $closed);

        $open = $this->toc(':default-open="false" active-id="methods-1"');
        $this->assertDoesNotMatchRegularExpression('/tedi-table-of-contents__group"\s+role="list"\s+style="display: none;"/', $open);

        $default = $this->toc('active-id="intro"');
        $this->assertDoesNotMatchRegularExpression('/style="display: none;"/', $default);
    }

    public function test_table_of_contents_item_separator_and_suffix(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:table-of-contents>
                <tedi:table-of-contents-item item-id="a" :separator="true">
                    <a href="#a">A</a>
                    <x-slot:suffix><tedi:tag>43 tulemust</tedi:tag></x-slot:suffix>
                </tedi:table-of-contents-item>
            </tedi:table-of-contents>
            BLADE);

        $this->assertHasClass('tedi-table-of-contents__separator', $html, 'tedi-table-of-contents__separator');
        $this->assertHasClass('tedi-separator', $html, 'tedi-separator');
        $this->assertHasClass('tedi-table-of-contents__suffix', $html, 'tedi-table-of-contents__suffix');
    }

    public function test_table_of_contents_is_an_alpine_scope_items_bind_to(): void
    {
        $html = $this->toc('active-id="intro"');

        $this->assertStringContainsString('x-data="tediTableOfContents(', $html);
        $this->assertStringContainsString('x-modelable="activeId"', $html);
        $this->assertStringContainsString("activeId === 'intro'", $html);
    }

    public function test_table_of_contents_collapsible_bar_and_sheet(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:table-of-contents-collapsible heading="Sisukord" active-id="a" :numbered="true">
                <tedi:table-of-contents-item item-id="a"><a href="#a">A</a></tedi:table-of-contents-item>
            </tedi:table-of-contents-collapsible>
            BLADE);

        $this->assertStringContainsString('<tedi-table-of-contents-collapsible', $html);
        $this->assertHasClass('tedi-table-of-contents__bar', $html, 'tedi-table-of-contents__bar');
        $this->assertMissingClass('tedi-table-of-contents__bar--static', $html, 'tedi-table-of-contents__bar');
        $this->assertStringContainsString('aria-haspopup="dialog"', $html);
        $this->assertHasClass('tedi-table-of-contents__sheet', $html, 'tedi-table-of-contents__sheet');
        $this->assertHasClass('tedi-table-of-contents__list--headless', $html, 'tedi-table-of-contents__list');
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('aria-label="Sisukord"', $html);
        $this->assertStringContainsString('x-data="tediTableOfContentsCollapsible(', $html);
        $this->assertStringContainsString('aria-hidden="true">1.</span>', $html);
        $this->assertStringContainsString(' aria-current="location"', $html);
    }

    public function test_table_of_contents_collapsible_static_bar_bordered_sheet_and_title_fallback(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:table-of-contents-collapsible heading="" :sticky="false" :bordered="true">
                <tedi:table-of-contents-item item-id="a">A</tedi:table-of-contents-item>
            </tedi:table-of-contents-collapsible>
            BLADE);

        $this->assertHasClass('tedi-table-of-contents__bar--static', $html, 'tedi-table-of-contents__bar');
        $this->assertHasClass('tedi-table-of-contents--bordered', $html, 'tedi-table-of-contents__sheet');
        $this->assertStringContainsString('Table of contents', $html);
    }

    // =====================================================================
    // Loader / Skeleton
    // =====================================================================

    public function test_skeleton_root_element_and_empty_live_region(): void
    {
        $html = Blade::render('<tedi:skeleton><tedi:skeleton-block /></tedi:skeleton>');

        $this->assertStringContainsString('<tedi-skeleton', $html);
        $this->assertStringContainsString('aria-busy="true"', $html);
        $this->assertHasClass('tedi-skeleton', $html, 'tedi-skeleton');
        // Rendered empty; the label arrives after labelDelay.
        $this->assertMatchesRegularExpression('/<span class="sr-only" role="status" aria-live="polite" aria-atomic="true" x-text="announcement"><\/span>/', $html);
    }

    public function test_skeleton_labels_and_delay_reach_the_alpine_scope(): void
    {
        $default = Blade::render('<tedi:skeleton />');
        $this->assertStringContainsString('Loading', $default);
        $this->assertStringContainsString('Loading completed', $default);
        $this->assertStringContainsString('200', $default);

        $custom = Blade::render('<tedi:skeleton label="Laen" completed-label="Valmis" :label-delay="500" />');
        $this->assertStringContainsString('Laen', $custom);
        $this->assertStringContainsString('Valmis', $custom);
        $this->assertStringContainsString('500', $custom);
    }

    public function test_skeleton_block_is_a_hidden_custom_element(): void
    {
        $html = Blade::render('<tedi:skeleton-block />');

        $this->assertStringContainsString('<tedi-skeleton-block aria-hidden="true"', $html);
        $this->assertHasClass('tedi-skeleton-block--p', $html);
        // width "auto" writes no inline width.
        $this->assertStringNotContainsString('style=', $html);
    }

    public function test_skeleton_block_height_modifiers(): void
    {
        foreach (['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $height) {
            $this->assertHasClass('tedi-skeleton-block--'.$height, Blade::render('<tedi:skeleton-block height="'.$height.'" />'));
        }
    }

    public function test_skeleton_block_numeric_height_is_inline_px(): void
    {
        foreach (['<tedi:skeleton-block :height="100" />', '<tedi:skeleton-block height="100" />'] as $blade) {
            $html = Blade::render($blade);

            $this->assertStringContainsString('height: 100px', $html);
            $this->assertMissingClass('tedi-skeleton-block--100', $html);
        }
    }

    public function test_skeleton_block_width_resolution(): void
    {
        $this->assertStringContainsString('width: 50%', Blade::render('<tedi:skeleton-block :width="50" />'));
        $this->assertStringContainsString('width: 50%', Blade::render('<tedi:skeleton-block width="50" />'));
        $this->assertStringContainsString('width: 36px', Blade::render('<tedi:skeleton-block width="36px" />'));
        $this->assertStringNotContainsString('width', Blade::render('<tedi:skeleton-block width="auto" />'));
    }

    // =====================================================================
    // Content / HeadingWithIcon
    // =====================================================================

    public function test_heading_with_icon_structure(): void
    {
        $html = Blade::render('<tedi:heading-with-icon icon="assignment_ind">My family physician</tedi:heading-with-icon>');

        $this->assertStringContainsString('<tedi-heading-with-icon', $html);
        $this->assertHasClass('tedi-heading-with-icon', $html, 'tedi-heading-with-icon');
        $this->assertHasClass('tedi-heading-with-icon__icon', $html, 'tedi-icon');
        $this->assertMatchesRegularExpression('/<h4 class="tedi-text--primary tedi-heading-with-icon__heading">/', $html);
        $this->assertStringContainsString('--_tedi-heading-with-icon-line-height: var(--heading-h4-line-height)', $html);
    }

    public function test_heading_with_icon_element_union(): void
    {
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $element) {
            $html = Blade::render('<tedi:heading-with-icon icon="x" element="'.$element.'">T</tedi:heading-with-icon>');

            $this->assertMatchesRegularExpression('/<'.$element.' class="[^"]*tedi-heading-with-icon__heading/', $html);
            $this->assertStringContainsString('var(--heading-'.$element.'-line-height)', $html);
        }
    }

    public function test_heading_with_icon_heading_modifier_wins_the_line_height(): void
    {
        $html = Blade::render('<tedi:heading-with-icon icon="x" element="h2" :modifiers="[\'bold\', \'h4\']">T</tedi:heading-with-icon>');

        $this->assertStringContainsString('var(--heading-h4-line-height)', $html);
        $this->assertHasClass('tedi-text--h4', $html, 'tedi-heading-with-icon__heading');
        $this->assertHasClass('text-bold', $html, 'tedi-heading-with-icon__heading');
    }

    public function test_heading_with_icon_colours_size_and_variant(): void
    {
        $html = Blade::render('<tedi:heading-with-icon icon="x" heading-color="brand" icon-color="white" :icon-size="36" icon-variant="filled">T</tedi:heading-with-icon>');

        $this->assertHasClass('tedi-text--brand', $html, 'tedi-heading-with-icon__heading');
        $this->assertHasClass('tedi-icon--color-white', $html, 'tedi-icon');
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    // =====================================================================
    // Content / Truncate
    // =====================================================================

    public function test_truncate_cuts_at_max_length_and_appends_the_ellipsis(): void
    {
        $html = Blade::render('<tedi:truncate text="Tere maailm, see on pikk tekst" :max-length="10" />');

        $this->assertStringContainsString('<tedi-truncate', $html);
        $this->assertHasClass('tedi-truncate', $html, 'tedi-truncate');
        $this->assertHasClass('tedi-text--secondary', $html, 'tedi-text--secondary');
        $this->assertMissingClass('tedi-truncate__text', $html, 'tedi-text--secondary');
        $this->assertStringContainsString('>Tere maail...</span>', $html);
        $this->assertHasClass('tedi-truncate__toggle', $html, 'tedi-truncate__toggle');
        $this->assertHasClass('tedi-link', $html, 'tedi-truncate__toggle');
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('Show more', $html);
    }

    public function test_truncate_trims_trailing_whitespace_before_the_ellipsis(): void
    {
        $this->assertStringContainsString('>Tere...</span>', Blade::render('<tedi:truncate text="Tere maailm" :max-length="5" ellipsis="..." />'));
    }

    public function test_truncate_exact_length_is_not_truncated(): void
    {
        $html = Blade::render('<tedi:truncate text="12345" :max-length="5" />');

        $this->assertStringNotContainsString('tedi-truncate__toggle', $html);
        $this->assertStringContainsString('>12345</span>', $html);
    }

    public function test_truncate_counts_graphemes(): void
    {
        $html = Blade::render('<tedi:truncate text="👍🏽👍🏽👍🏽" :max-length="2" />');

        $this->assertStringContainsString('>👍🏽👍🏽...</span>', $html);
    }

    public function test_truncate_expandable_false_renders_no_toggle(): void
    {
        $html = Blade::render('<tedi:truncate text="Tere maailm" :max-length="4" :expandable="false" />');

        $this->assertStringNotContainsString('tedi-truncate__toggle', $html);
        $this->assertStringContainsString('>Tere...</span>', $html);
    }

    public function test_truncate_expanded_renders_the_full_text_and_modifier(): void
    {
        $html = Blade::render('<tedi:truncate text="Tere maailm" :max-length="4" :expanded="true" />');

        $this->assertHasClass('tedi-truncate--expanded', $html, 'tedi-truncate');
        $this->assertStringContainsString('>Tere maailm</span>', $html);
        $this->assertStringContainsString('aria-expanded="true"', $html);
        $this->assertStringContainsString('Show less', $html);
    }

    public function test_truncate_toggle_controls_the_text(): void
    {
        $html = Blade::render('<tedi:truncate text="Tere maailm" :max-length="4" />');

        preg_match('/id="(tedi-truncate-text-[^"]+)"/', $html, $m);
        $this->assertNotEmpty($m);
        $this->assertStringContainsString('aria-controls="'.$m[1].'"', $html);
    }

    // =====================================================================
    // Helpers / Print
    // =====================================================================

    public function test_print_visibility_classes(): void
    {
        $hide = Blade::render('<tedi:print visibility="hide">x</tedi:print>');
        $this->assertHasClass('no-print', $hide);
        $this->assertMissingClass('show-print', $hide);

        $show = Blade::render('<tedi:print visibility="show">x</tedi:print>');
        $this->assertHasClass('show-print', $show);
        $this->assertMissingClass('no-print', $show);
    }

    public function test_print_emits_no_visibility_class_by_default(): void
    {
        $html = Blade::render('<tedi:print>x</tedi:print>');

        $this->assertMissingClass('no-print', $html);
        $this->assertMissingClass('show-print', $html);
    }

    public function test_print_break_unions(): void
    {
        foreach (['auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'] as $break) {
            $this->assertHasClass('break-before-'.$break, Blade::render('<tedi:print break-before="'.$break.'">x</tedi:print>'));
            $this->assertHasClass('break-after-'.$break, Blade::render('<tedi:print break-after="'.$break.'">x</tedi:print>'));
            $this->assertHasClass('break-inside-'.$break, Blade::render('<tedi:print break-inside="'.$break.'">x</tedi:print>'));
        }
    }

    public function test_truncate_survives_invalid_utf8(): void
    {
        $html = Blade::render('<tedi:truncate :text="$t" :max-length="3" />', ['t' => "abc\xC3\x28def"]);

        $this->assertStringContainsString('tedi-truncate', $html);
        $this->assertStringContainsString('x-data=', $html);
    }
}
