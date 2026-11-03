<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for <tedi:table-card> and <tedi:text-group-list>, ported
 * from @tedi-design-system/react content/table-card/table-card.tsx and
 * content/text-group/text-group-list/text-group-list.tsx (CONVENTIONS.md §13).
 *
 * Expected class strings come from the two components' `cn(styles[…])` calls.
 * The card itself is this package's Angular `<tedi:card>` / `<tedi:card-content>`
 * (`tedi-card-content`, not React's `tedi-card__content`); the separator keeps
 * React's `tedi-card__content--separator` name (CONVENTIONS.md §13.7).
 */
class TableCardComponentsTest extends TestCase
{
    private const ROWS = "[['label' => 'Liik', 'value' => 'Haigusleht'], ['label' => 'Hüvitis', 'value' => '120.34 €', 'bold' => true]]";

    private function card(string $props = '', string $slot = ''): string
    {
        return Blade::render('<tedi:table-card id="tc" :rows="'.self::ROWS.'" '.$props.'>'.$slot.'</tedi:table-card>');
    }

    /** The `style` attribute of the first element carrying $class. */
    private function styleOf(string $html, string $class): string
    {
        preg_match('/<[a-z0-9-]+\s[^>]*class="[^"]*\b'.preg_quote($class, '/').'\b[^"]*"[^>]*>/s', $html, $tag);
        preg_match('/(?<![-:.\w])style="([^"]*)"/', $tag[0] ?? '', $style);

        return $style[1] ?? '';
    }

    // =====================================================================
    // Root
    // =====================================================================

    public function test_root_is_an_angular_card_with_the_table_card_class(): void
    {
        $html = $this->card('aria-label="Hüvitis" class="extra"');

        $this->assertSame(['tedi-card', 'tedi-table-card', 'extra'], $this->classesOf($html, 'tedi-table-card'));
        $this->assertMatchesRegularExpression('/<div class="tedi-card tedi-table-card extra"[^>]*id="tc"/', $html);
        $this->assertStringContainsString('aria-label="Hüvitis"', $html);
        // React's own card vocabulary is never emitted.
        $this->assertStringNotContainsString('tedi-card__content ', $html);
        $this->assertStringNotContainsString('"tedi-card__content"', $html);
    }

    public function test_id_is_generated_when_omitted(): void
    {
        $html = Blade::render('<tedi:table-card :rows="[]" />');

        $this->assertMatchesRegularExpression('/id="(tedi-table-card-[0-9a-f]{8})".*id="\1-body"/s', $html);
    }

    // =====================================================================
    // Body list (TextGroupList)
    // =====================================================================

    public function test_horizontal_layout_defaults(): void
    {
        $html = $this->card();

        $this->assertSame(
            ['tedi-text-group', 'tedi-text-group--list', 'tedi-text-group--horizontal'],
            $this->classesOf($html, 'tedi-text-group--list')
        );
        $this->assertSame(
            '--label-width: var(--text-group-label-width-sm); --tedi-text-group-list-gap: 0',
            $this->styleOf($html, 'tedi-text-group--list')
        );
        $this->assertSame(['tedi-text-group__label', 'tedi-text-group--align-right'], $this->classesOf($html, 'tedi-text-group__label'));
        $this->assertSame(['tedi-text-group__value', 'tedi-text-group__value--align-right'], $this->classesOf($html, 'tedi-text-group__value'));
    }

    /**
     * Vertical layout forces labelAlign left and passes no valueAlign / rowAlign,
     * so TextGroupList's own `left` default applies whatever the consumer says.
     */
    public function test_vertical_layout_ignores_value_and_row_alignment(): void
    {
        $html = $this->card('layout="vertical" label-align="right" value-align="right" row-align="center"');

        $list = $this->classesOf($html, 'tedi-text-group--list');
        $this->assertSame(['tedi-text-group', 'tedi-text-group--list'], $list);
        $this->assertSame(
            '--label-width: auto; --tedi-text-group-list-gap: var(--layout-grid-gutters-16)',
            $this->styleOf($html, 'tedi-text-group--list')
        );
        $this->assertHasClass('tedi-text-group--align-left', $html, 'tedi-text-group__label');
        $this->assertHasClass('tedi-text-group__value--align-left', $html, 'tedi-text-group__value');
    }

    /** `tedi-text-group--vertical` has no rule in either stylesheet: dropped (CONVENTIONS.md §4). */
    public function test_vertical_modifier_is_not_emitted(): void
    {
        $this->assertStringNotContainsString('tedi-text-group--vertical', $this->card('layout="vertical"'));
    }

    public function test_alignment_union(): void
    {
        foreach (['left', 'right'] as $label) {
            foreach (['left', 'right'] as $value) {
                $html = $this->card('label-align="'.$label.'" value-align="'.$value.'"');

                $this->assertHasClass('tedi-text-group--align-'.$label, $html, 'tedi-text-group__label');
                $this->assertHasClass('tedi-text-group__value--align-'.$value, $html, 'tedi-text-group__value');
            }
        }
    }

    public function test_row_align_center_only_in_horizontal_layout(): void
    {
        $this->assertHasClass('tedi-text-group--row-align-center', $this->card('row-align="center"'), 'tedi-text-group--list');
        $this->assertMissingClass('tedi-text-group--row-align-center', $this->card(), 'tedi-text-group--list');
    }

    public function test_columns_and_col_span(): void
    {
        $html = Blade::render('<tedi:table-card layout="vertical" :columns="2" :rows="[[\'label\' => \'A\', \'value\' => \'1\', \'colSpan\' => 5], [\'label\' => \'B\', \'value\' => \'2\']]" />');

        $this->assertHasClass('tedi-text-group--columns', $html, 'tedi-text-group--list');
        $this->assertStringContainsString('--tedi-text-group-columns: 2', $this->styleOf($html, 'tedi-text-group--list'));
        // colSpan is clamped to the column count.
        $this->assertMatchesRegularExpression('/<div class="tedi-text-group__row"\s+style="grid-column: span 2"\s*>/', $html);
        $this->assertSame(1, substr_count($html, 'grid-column'));

        $this->assertMissingClass('tedi-text-group--columns', $this->card(), 'tedi-text-group--list');
    }

    /** Upstream quirks: TableCard reads a numeric rowGap as rem; TextGroupList reads a numeric labelWidth as %. */
    public function test_numeric_row_gap_is_rem_and_numeric_label_width_is_percent(): void
    {
        $style = $this->styleOf($this->card(':row-gap="0.5" :label-width="40"'), 'tedi-text-group--list');

        $this->assertSame('--label-width: 40%; --tedi-text-group-list-gap: 0.5rem', $style);

        $standalone = Blade::render('<tedi:text-group-list :row-gap="8" :items="[]" />');
        $this->assertSame('--label-width: auto; --tedi-text-group-list-gap: 8px', $this->styleOf($standalone, 'tedi-text-group--list'));
    }

    public function test_label_size_and_bold_value(): void
    {
        $html = $this->card('label-size="small"');

        $this->assertSame(['tedi-label', 'tedi-label--secondary', 'tedi-label--small'], $this->classesOf($html, 'tedi-label'));
        $this->assertStringContainsString('<span class="text-bold">120.34 €</span>', $html);

        $this->assertMissingClass('tedi-label--small', $this->card(), 'tedi-label');
    }

    public function test_string_values_are_escaped(): void
    {
        $html = Blade::render('<tedi:table-card :rows="[[\'label\' => \'<b>L</b>\', \'value\' => \'<i>V</i>\']]" />');

        $this->assertStringContainsString('&lt;i&gt;V&lt;/i&gt;', $html);
        $this->assertStringContainsString('&lt;b&gt;L&lt;/b&gt;', $html);
    }

    // =====================================================================
    // Card blocks and separators
    // =====================================================================

    public function test_no_header_without_header_content(): void
    {
        $this->assertStringNotContainsString('tedi-table-card__header', $this->card());
    }

    public function test_body_separator_follows_summary_actions_children(): void
    {
        $body = fn ($html) => $this->classesOf($html, 'tedi-card-content');

        $this->assertNotContains('tedi-card__content--separator', $body($this->card()));
        $this->assertContains('tedi-card__content--separator', $body($this->card(':summary="[\'label\' => \'Kokku\', \'value\' => \'1\']"')));
        $this->assertContains('tedi-card__content--separator', $body($this->card('', '<x-slot:actions>Muuda</x-slot:actions>')));
        $this->assertContains('tedi-card__content--separator', $body($this->card('', 'Lisa')));
    }

    public function test_summary_block(): void
    {
        $html = $this->card(':summary="[\'label\' => \'Kokku\', \'value\' => \'1 €\']"');

        $this->assertSame(
            ['tedi-card-content', 'tedi-card-content--background--tertiary'],
            $this->classesOf($html, 'tedi-card-content--background--tertiary')
        );
        $this->assertStringContainsString('<span class="text-bold">1 €</span>', $html);

        $withActions = $this->card(':summary="[\'label\' => \'Kokku\', \'value\' => \'1 €\']"', '<x-slot:actions>Muuda</x-slot:actions>');
        $this->assertHasClass('tedi-card__content--separator', $withActions, 'tedi-card-content--background--tertiary');
    }

    public function test_actions_footer(): void
    {
        $html = $this->card('', '<x-slot:actions>Muuda</x-slot:actions>');

        $this->assertSame(
            ['tedi-card-content', 'tedi-card-content--background--primary', 'tedi-table-card__actions'],
            $this->classesOf($html, 'tedi-table-card__actions')
        );
        $this->assertStringContainsString('--card-content-padding-top: 0.5rem; --card-content-padding-right: 1rem', $this->styleOf($html, 'tedi-table-card__actions'));
        $this->assertMatchesRegularExpression('/<div\s+id="tc-body"\s+class="tedi-table-card__body--has-footer"/', $html);
    }

    // =====================================================================
    // Header
    // =====================================================================

    public function test_plain_header_is_tightened_and_has_no_separator(): void
    {
        $html = $this->card('title="ID kaart" subtitle="Dokument"', '<x-slot:end-slot>Kehtiv</x-slot:end-slot>');

        $this->assertSame(
            ['tedi-card-content', 'tedi-card-content--background--primary', 'tedi-table-card__header'],
            $this->classesOf($html, 'tedi-table-card__header')
        );
        $this->assertStringContainsString('--card-content-padding-bottom: 0rem', $this->styleOf($html, 'tedi-table-card__header'));
        $this->assertStringContainsString('<h3 class="tedi-table-card__title tedi-table-card__title--body">ID kaart</h3>', $html);
        $this->assertStringContainsString('<span class="text-small tedi-text--secondary">Dokument</span>', $html);
        $this->assertStringContainsString('<div class="tedi-table-card__end-slot">Kehtiv</div>', $html);
    }

    public function test_title_element_and_modifiers(): void
    {
        $html = $this->card('title="Pass" title-element="h4" title-modifiers="h4"');
        $this->assertStringContainsString('<h4 class="tedi-table-card__title tedi-text--h4">Pass</h4>', $html);

        $html = $this->card('title="Pass" :title-modifiers="[\'bold\', \'small\']"');
        $this->assertStringContainsString('<h3 class="tedi-table-card__title text-bold text-small">Pass</h3>', $html);
    }

    public function test_selectable_with_title_makes_the_title_the_checkbox_label(): void
    {
        $html = $this->card('title="Meelis Mägi" title-modifiers="h6" :selectable="true"');

        $this->assertSame(
            ['tedi-card-content', 'tedi-card-content--background--primary', 'tedi-table-card__header', 'tedi-table-card__header--select-title', 'tedi-card__content--separator'],
            $this->classesOf($html, 'tedi-table-card__header')
        );
        $this->assertMatchesRegularExpression('/<h3 class="tedi-table-card__heading">\s*<label class="tedi-label tedi-label--primary[^"]*"[^>]*data-name="check"/', $html);
        $this->assertMatchesRegularExpression('/<input\s+type="checkbox"\s+tedi-checkbox\s+name="tc-select"\s+value="selected"\s+id="tc-select"/', $html);
        $this->assertStringContainsString('<span class="tedi-table-card__title tedi-text--h6 tedi-text--primary">Meelis Mägi</span>', $html);
        $this->assertStringNotContainsString('sr-only', $html);
    }

    public function test_selectable_with_subtitle_uses_the_hidden_selection_label(): void
    {
        $html = $this->card('title="Meelis" subtitle="23" :selectable="true" selection-label="Vali Meelis" :default-selected="true"');

        $this->assertMissingClass('tedi-table-card__header--select-title', $html, 'tedi-table-card__header');
        $this->assertStringNotContainsString('tedi-table-card__heading', $html);
        $this->assertStringContainsString('<span class="sr-only">Vali Meelis</span>', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*tedi-checkbox[^>]*checked/s', $html);
    }

    public function test_selected_wins_over_default_selected(): void
    {
        $html = $this->card(':selectable="true" :selected="false" :default-selected="true"');

        $this->assertDoesNotMatchRegularExpression('/<input[^>]*tedi-checkbox[^>]*checked/s', $html);
    }

    // =====================================================================
    // Collapsible
    // =====================================================================

    public function test_collapsible_open(): void
    {
        $html = $this->card('title="Hambaarst" :collapsible="true"');

        $this->assertStringContainsString('x-data="{ tediTableCardOpen: true }"', $html);
        $this->assertHasClass('tedi-card__content--separator', $html, 'tedi-table-card__header');
        $this->assertStringContainsString("x-bind:class=\"{ 'tedi-card__content--separator': tediTableCardOpen }\"", $html);
        $this->assertStringContainsString('<h3 id="tc-title" tabindex="-1" class="tedi-table-card__title tedi-table-card__title--body">Hambaarst</h3>', $html);
        $this->assertMatchesRegularExpression('/<button\s+tedi-collapse-button[^>]*aria-expanded="true"[^>]*aria-controls="tc-body"[^>]*aria-labelledby="tc-title"/s', $html);
        $this->assertHasClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');
        $this->assertHasClass('tedi-collapse-button--neutral', $html, 'tedi-collapse-button');
        $this->assertStringContainsString('x-bind:hidden="! tediTableCardOpen"', $html);
        $this->assertDoesNotMatchRegularExpression('/id="tc-body"[^>]*\shidden\s/s', $html);
    }

    public function test_collapsible_closed_hides_the_body_and_drops_the_header_separator(): void
    {
        $html = $this->card('title="Hambaarst" :collapsible="true" :default-open="false" arrow-type="secondary"');

        $this->assertStringContainsString('x-data="{ tediTableCardOpen: false }"', $html);
        $this->assertMissingClass('tedi-card__content--separator', $html, 'tedi-table-card__header');
        $this->assertMatchesRegularExpression('/id="tc-body"\s+hidden\s/s', $html);
        $this->assertHasClass('tedi-collapse-button--secondary', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');
    }

    public function test_open_wins_over_default_open(): void
    {
        $this->assertStringContainsString(
            'tediTableCardOpen: true',
            $this->card('title="H" :collapsible="true" :open="true" :default-open="false"')
        );
    }

    /** With actions the header keeps its separator in both states, so nothing is bound. */
    public function test_collapsible_with_actions_has_a_static_header_separator(): void
    {
        $html = $this->card('title="H" :collapsible="true" :default-open="false"', '<x-slot:actions>Muuda</x-slot:actions>');

        $this->assertHasClass('tedi-card__content--separator', $html, 'tedi-table-card__header');
        $this->assertStringNotContainsString("'tedi-card__content--separator': tediTableCardOpen", $html);
    }

    public function test_collapsible_without_title_uses_aria_label(): void
    {
        $html = $this->card(':collapsible="true" aria-label="Kaart"');

        $this->assertMatchesRegularExpression('/<button\s+tedi-collapse-button[^>]*aria-label="Kaart"/s', $html);
        $this->assertStringNotContainsString('aria-labelledby', $html);
    }

    public function test_children_render_inside_the_collapsible_body_before_the_summary(): void
    {
        $html = $this->card(':summary="[\'label\' => \'Kokku\', \'value\' => \'1\']" :collapsible="true" title="H"', '<p id="child">Lisa</p>');

        $this->assertMatchesRegularExpression('/id="tc-body".*<dl.*id="child".*tedi-card-content--background--tertiary/s', $html);
    }

    // =====================================================================
    // TextGroupList standalone
    // =====================================================================

    public function test_text_group_list_defaults_and_row_overrides(): void
    {
        $html = Blade::render('<tedi:text-group-list :items="[[\'label\' => \'A\', \'value\' => \'1\', \'labelAlign\' => \'right\', \'valueAlign\' => \'right\', \'labelWidth\' => \'50px\']]" />');

        $this->assertSame(['tedi-text-group', 'tedi-text-group--list'], $this->classesOf($html, 'tedi-text-group--list'));
        $this->assertSame('--label-width: auto', $this->styleOf($html, 'tedi-text-group--list'));
        $this->assertMatchesRegularExpression('/<div class="tedi-text-group__row"\s+style="--label-width: 50px"\s*>/', $html);
        $this->assertHasClass('tedi-text-group--align-right', $html, 'tedi-text-group__label');
        $this->assertHasClass('tedi-text-group__value--align-right', $html, 'tedi-text-group__value');
        // A string label is wrapped in React's <Label> — here the default-size form label.
        $this->assertSame(['tedi-label', 'tedi-label--secondary'], $this->classesOf($html, 'tedi-label'));
    }
}
