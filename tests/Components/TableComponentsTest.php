<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for content/table (CONVENTIONS.md §10).
 *
 * The unions come straight from table.types.ts:
 *   TableSize            = "medium" | "small"
 *   TableSelectionMode   = "multiple" | "single"
 *   TableExpandTrigger   = "button" | "row"
 *   TableColumnMeta.align  = "left" | "center" | "right"
 *   TableColumnMeta.vAlign = "top" | "middle" | "bottom"
 *   rowGroupDividers     = "all" | "between" | "none"
 */
class TableComponentsTest extends TestCase
{
    /** Two columns + two rows, enough to exercise every cell branch. */
    private function fixture(string $tableAttributes = '', string $slot = ''): string
    {
        $close = $slot === '' ? ' />' : '>'.$slot.'</tedi:table>';

        return Blade::render(<<<BLADE
        @php
        \$columns = [
            ['key' => 'name', 'header' => 'Nimi'],
            ['key' => 'city', 'header' => 'Linn'],
        ];
        \$rows = [
            ['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'city' => 'Tallinn']],
            ['id' => 'r2', 'cells' => ['name' => 'Jüri Kask', 'city' => 'Tartu']],
        ];
        @endphp
        <tedi:table :columns="\$columns" :rows="\$rows" {$tableAttributes}{$close}
        BLADE);
    }

    // -- table: host classes -------------------------------------------------

    public function test_table_host_base_classes(): void
    {
        $html = $this->fixture();

        $this->assertHasClass('tedi-table', $html, on: 'tedi-table');
        $this->assertMissingClass('tedi-table--striped', $html, on: 'tedi-table');
        $this->assertMissingClass('tedi-table--row-hover', $html, on: 'tedi-table');
        $this->assertStringContainsString('data-name="tedi-table"', $html);
    }

    /** TableSize. `--medium` has no rule in the vendored SCSS, so it is dropped (§4). */
    public function test_table_size_classes(): void
    {
        $medium = $this->fixture('size="medium"');
        $small = $this->fixture('size="small"');

        $this->assertMissingClass('tedi-table--medium', $medium, on: 'tedi-table');
        $this->assertHasClass('tedi-table--small', $small, on: 'tedi-table');
        $this->assertMissingClass('tedi-table--medium', $small, on: 'tedi-table');
    }

    public function test_table_appearance_modifier_classes(): void
    {
        $flags = [
            'striped' => 'tedi-table--striped',
            'vertical-borders' => 'tedi-table--vertical-borders',
            'borderless' => 'tedi-table--borderless',
            'sticky-first-column' => 'tedi-table--sticky-first-column',
            'sticky-header' => 'tedi-table--sticky-header',
            'fixed-layout' => 'tedi-table--fixed-layout',
        ];

        foreach ($flags as $prop => $class) {
            $on = $this->fixture(':'.$prop.'="true"');
            $off = $this->fixture(':'.$prop.'="false"');

            $this->assertHasClass($class, $on, on: 'tedi-table');
            $this->assertMissingClass($class, $off, on: 'tedi-table');
        }
    }

    /** hostClasses(): rowHover ?? (interactive || expandTrigger === 'row'). */
    public function test_table_row_hover_class(): void
    {
        $this->assertMissingClass('tedi-table--row-hover', $this->fixture(), on: 'tedi-table');
        $this->assertHasClass('tedi-table--row-hover', $this->fixture(':row-hover="true"'), on: 'tedi-table');
        $this->assertHasClass('tedi-table--row-hover', $this->fixture(':interactive="true"'), on: 'tedi-table');
        $this->assertHasClass(
            'tedi-table--row-hover',
            $this->fixture(':expandable="true" expand-trigger="row"'),
            on: 'tedi-table'
        );
        $this->assertMissingClass(
            'tedi-table--row-hover',
            $this->fixture(':interactive="true" :row-hover="false"'),
            on: 'tedi-table'
        );
    }

    /** rowGroupDividers is only emitted when the table is grouped and != "all". */
    public function test_table_group_divider_classes(): void
    {
        foreach (['between', 'none'] as $value) {
            $grouped = $this->fixture(':grouped="true" row-group-dividers="'.$value.'"');
            $ungrouped = $this->fixture('row-group-dividers="'.$value.'"');

            $this->assertHasClass('tedi-table--group-dividers-'.$value, $grouped, on: 'tedi-table');
            $this->assertMissingClass('tedi-table--group-dividers-'.$value, $ungrouped, on: 'tedi-table');
        }

        $this->assertMissingClass(
            'tedi-table--group-dividers-all',
            $this->fixture(':grouped="true" row-group-dividers="all"'),
            on: 'tedi-table'
        );
    }

    public function test_table_pagination_host_classes_follow_slot_presence(): void
    {
        $none = $this->fixture();
        $bottom = $this->fixture('', '<x-slot:pagination><tedi:pagination :page-count="2" /></x-slot:pagination> ');
        $top = $this->fixture('', '<x-slot:pagination-top><tedi:pagination :page-count="2" /></x-slot:pagination-top> ');

        $this->assertMissingClass('tedi-table--has-pagination', $none, on: 'tedi-table');

        $this->assertHasClass('tedi-table--has-pagination', $bottom, on: 'tedi-table');
        $this->assertHasClass('tedi-table--has-pagination-bottom', $bottom, on: 'tedi-table');
        $this->assertMissingClass('tedi-table--has-pagination-top', $bottom, on: 'tedi-table');
        $this->assertHasClass('tedi-table__pagination--bottom', $bottom, on: 'tedi-table__pagination');

        $this->assertHasClass('tedi-table--has-pagination', $top, on: 'tedi-table');
        $this->assertHasClass('tedi-table--has-pagination-top', $top, on: 'tedi-table');
        $this->assertMissingClass('tedi-table--has-pagination-bottom', $top, on: 'tedi-table');
        $this->assertHasClass('tedi-table__pagination--top', $top, on: 'tedi-table__pagination');
    }

    /** §4: classes Angular emits that the vendored SCSS never styles. */
    public function test_table_unstyled_angular_classes_are_dropped(): void
    {
        $html = $this->fixture(':interactive="true" :expandable="true"');

        foreach ([
            'tedi-table--medium',
            'tedi-table--clickable-rows',
            'tedi-table--grouped-headers',
            'tedi-table--draggable',
        ] as $class) {
            $this->assertMissingClass($class, $html, on: 'tedi-table');
        }

        $this->assertStringNotContainsString('tedi-table__header-cell--group', $html);
        $this->assertStringNotContainsString('tedi-table__drag-handle', $html);
        $this->assertStringNotContainsString('--picked-up', $html);
        $this->assertStringNotContainsString('cdk-drag', $html);
    }

    // -- table: structural elements -----------------------------------------

    public function test_table_structural_element_classes(): void
    {
        $html = $this->fixture('caption="Töötajad"');

        $this->assertHasClass('tedi-table__scroll', $html, on: 'tedi-table__scroll');
        $this->assertHasClass('tedi-table__table', $html, on: 'tedi-table__table');
        $this->assertHasClass('tedi-table__caption', $html, on: 'tedi-table__caption');
        $this->assertHasClass('tedi-table__head', $html, on: 'tedi-table__head');
        $this->assertHasClass('tedi-table__body', $html, on: 'tedi-table__body');
        $this->assertHasClass('tedi-table__row', $html, on: 'tedi-table__row');
        $this->assertHasClass('tedi-table__header-cell', $html, on: 'tedi-table__header-cell');
        $this->assertHasClass('tedi-table__header-content', $html, on: 'tedi-table__header-content');
        $this->assertHasClass('tedi-table__cell', $html, on: 'tedi-table__cell');
        $this->assertStringContainsString('Töötajad', $html);
    }

    public function test_table_placeholder_row_when_empty(): void
    {
        $html = Blade::render('<tedi:table :columns="[[\'key\' => \'a\', \'header\' => \'A\']]" />');

        $this->assertHasClass('tedi-table__cell--placeholder', $html, on: 'tedi-table__cell--placeholder');
        $this->assertStringContainsString('colspan="1"', $html);
        $this->assertStringContainsString(__('tedi::tedi.table.no-data'), $html);
    }

    public function test_table_placeholder_role_wrapper(): void
    {
        foreach (['alert', 'status'] as $role) {
            $html = Blade::render('<tedi:table placeholder="Tühi" placeholder-role="'.$role.'" />');

            $this->assertStringContainsString('<div role="'.$role.'">', $html);
        }
    }

    public function test_table_footer_row_is_rendered_only_when_a_column_declares_one(): void
    {
        $without = $this->fixture();
        $with = Blade::render(<<<'BLADE'
        <tedi:table
            :columns="[['key' => 'a', 'header' => 'A', 'footer' => 'Kokku']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
        />
        BLADE);

        $this->assertStringNotContainsString('tedi-table__foot', $without);
        $this->assertHasClass('tedi-table__foot', $with, on: 'tedi-table__foot');
        $this->assertHasClass('tedi-table__cell--footer', $with, on: 'tedi-table__cell--footer');
    }

    // -- table: alignment ----------------------------------------------------

    /** TableColumnMeta.align — applied to header, body and footer cells. */
    public function test_table_column_align_classes(): void
    {
        foreach (['left', 'center', 'right'] as $align) {
            $html = Blade::render(<<<BLADE
            <tedi:table
                :columns="[['key' => 'a', 'header' => 'A', 'align' => '{$align}', 'footer' => 'F']]"
                :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
            />
            BLADE);

            $this->assertHasClass('tedi-table__cell--align-'.$align, $html, on: 'tedi-table__header-cell');
            $this->assertHasClass('tedi-table__cell--align-'.$align, $html, on: 'tedi-table__cell');
            $this->assertHasClass('tedi-table__cell--align-'.$align, $html, on: 'tedi-table__cell--footer');
        }
    }

    /** TableColumnMeta.vAlign. */
    public function test_table_column_valign_classes(): void
    {
        foreach (['top', 'middle', 'bottom'] as $vAlign) {
            $html = Blade::render(<<<BLADE
            <tedi:table
                :columns="[['key' => 'a', 'header' => 'A', 'vAlign' => '{$vAlign}', 'footer' => 'F']]"
                :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
            />
            BLADE);

            $this->assertHasClass('tedi-table__cell--valign-'.$vAlign, $html, on: 'tedi-table__header-cell');
            $this->assertHasClass('tedi-table__cell--valign-'.$vAlign, $html, on: 'tedi-table__cell');
        }
    }

    public function test_table_cell_align_overrides_the_column(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :columns="[['key' => 'a', 'header' => 'A', 'align' => 'left']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => ['value' => '1', 'align' => 'right']]]]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__cell--align-left', $html, on: 'tedi-table__header-cell');
        $this->assertHasClass('tedi-table__cell--align-right', $html, on: 'tedi-table__cell');
    }

    // -- table: row state ----------------------------------------------------

    public function test_table_row_state_classes(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            active-row-id="r2"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[
                ['id' => 'r1', 'cells' => ['a' => '1'], 'selected' => true],
                ['id' => 'r2', 'cells' => ['a' => '2']],
                ['id' => 'r3', 'cells' => ['a' => '3'], 'subRow' => true],
                ['id' => 'r4', 'cells' => ['a' => '4'], 'groupStart' => true],
            ]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__row--selected', $html, on: 'tedi-table__row--selected');
        $this->assertHasClass('tedi-table__row--active', $html, on: 'tedi-table__row--active');
        $this->assertHasClass('tedi-table__row--sub-row', $html, on: 'tedi-table__row--sub-row');
        $this->assertHasClass('tedi-table__row--group-start', $html, on: 'tedi-table__row--group-start');
    }

    public function test_table_selected_row_highlight_can_be_switched_off(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :selected-row-highlight="false"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'selected' => true]]"
        />
        BLADE);

        $this->assertMissingClass('tedi-table__row--selected', $html, on: 'tedi-table__row');
    }

    public function test_table_interactive_rows_are_clickable_buttons(): void
    {
        $html = $this->fixture(':interactive="true"');

        $this->assertHasClass('tedi-table__row--clickable', $html, on: 'tedi-table__row--clickable');
        $this->assertStringContainsString('role="button"', $html);
        $this->assertStringContainsString('tabindex="0"', $html);
    }

    public function test_table_non_interactive_rows_are_not_clickable(): void
    {
        $this->assertMissingClass('tedi-table__row--clickable', $this->fixture(), on: 'tedi-table__row');
    }

    // -- table: selection ----------------------------------------------------

    /** TableSelectionMode = multiple → checkbox + select-all header control. */
    public function test_table_multiple_selection_renders_checkboxes(): void
    {
        $html = $this->fixture(':enable-row-selection="true" selection-mode="multiple"');

        $this->assertSame(3, substr_count($html, 'tedi-checkbox'), 'select-all + one per row');
        $this->assertStringNotContainsString('tedi-radio', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
    }

    /** TableSelectionMode = single → radios sharing one name, no header control. */
    public function test_table_single_selection_renders_radios(): void
    {
        $html = $this->fixture(':enable-row-selection="true" selection-mode="single"');

        $this->assertSame(2, substr_count($html, 'tedi-radio'), 'one per row, none in the header');
        $this->assertStringNotContainsString('type="checkbox"', $html);
        $this->assertSame(2, substr_count($html, '-select-row"'), 'radios share one name');
    }

    public function test_table_selection_control_column_classes(): void
    {
        $html = $this->fixture(':enable-row-selection="true"');

        $this->assertHasClass('tedi-table__cell--align-center', $html, on: 'tedi-table__header-cell');
        $this->assertHasClass('tedi-table__cell--valign-top', $html, on: 'tedi-table__header-cell');
        $this->assertHasClass('tedi-table__sr-only', $html, on: 'tedi-table__sr-only');
        $this->assertStringContainsString(__('tedi::tedi.table.select-column'), $html);
    }

    public function test_table_select_attributes_reach_the_native_control(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :enable-row-selection="true"
            :select-all-attributes="['wire:model' => 'all']"
            :select-all-indeterminate="true"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'selectAttributes' => ['wire:model' => 'picked']]]"
        />
        BLADE);

        $this->assertStringContainsString('wire:model="all"', $html);
        $this->assertStringContainsString('wire:model="picked"', $html);
        $this->assertStringContainsString('$el.indeterminate = true', $html);
    }

    public function test_table_select_disabled_row(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :enable-row-selection="true"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'selectDisabled' => true]]"
        />
        BLADE);

        $this->assertStringContainsString('disabled', $html);
    }

    // -- table: expansion ----------------------------------------------------

    public function test_table_expand_control_column_classes(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'expandable' => true, 'sub' => 'Detail']]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__expand-toggle', $html, on: 'tedi-table__expand-toggle');
        $this->assertHasClass('tedi-table__expand-toggle--icon-only', $html, on: 'tedi-table__expand-toggle');
        $this->assertHasClass('tedi-collapse-button--secondary', $html, on: 'tedi-collapse-button');
        $this->assertStringContainsString(__('tedi::tedi.table.expand-column'), $html);
    }

    /** expandButtonLabel set → label mode, so the icon-only reservation is dropped. */
    public function test_table_expand_button_label_mode(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            :expand-button-label="['open' => 'Ava', 'close' => 'Sulge']"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'expandable' => true, 'sub' => 'Detail']]"
        />
        BLADE);

        $this->assertMissingClass('tedi-table__expand-toggle--icon-only', $html, on: 'tedi-table__expand-toggle');
        $this->assertMissingClass('tedi-collapse-button--icon-only', $html, on: 'tedi-collapse-button');
        $this->assertStringContainsString('Ava', $html);
    }

    public function test_table_expand_button_variant_override(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            expand-button-variant="default"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'expandable' => true, 'sub' => 'Detail']]"
        />
        BLADE);

        $this->assertHasClass('tedi-collapse-button--neutral', $html, on: 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--secondary', $html, on: 'tedi-collapse-button');
    }

    /** §8: the sub-component row is in the DOM with its real class list even while closed. */
    public function test_table_sub_component_row_classes(): void
    {
        $closed = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'expandable' => true, 'sub' => 'Detail']]"
        />
        BLADE);

        $open = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'expandable' => true, 'expanded' => true, 'sub' => 'Detail']]"
        />
        BLADE);

        foreach ([$closed, $open] as $html) {
            $this->assertHasClass('tedi-table__row--sub-component', $html, on: 'tedi-table__row--sub-component');
            $this->assertHasClass('tedi-table__cell--sub-component', $html, on: 'tedi-table__cell--sub-component');
            $this->assertHasClass('tedi-table__sub-component-wrapper', $html, on: 'tedi-table__sub-component-wrapper');
            $this->assertHasClass('tedi-table__sub-component-content', $html, on: 'tedi-table__sub-component-content');
            $this->assertHasClass('tedi-table__sub-component-inner', $html, on: 'tedi-table__sub-component-inner');
            $this->assertStringContainsString('Detail', $html);
        }

        $this->assertMissingClass('tedi-table__row--sub-component-open', $closed, on: 'tedi-table__row--sub-component');
        $this->assertHasClass('tedi-table__row--sub-component-open', $open, on: 'tedi-table__row--sub-component');

        $this->assertStringContainsString('x-bind:class', $closed);
        $this->assertStringContainsString('tediTableExpanded', $closed);
    }

    /** TableExpandTrigger = row → the row itself is clickable and toggles Alpine state. */
    public function test_table_expand_trigger_row(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            expand-trigger="row"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1'], 'expandable' => true, 'sub' => 'Detail']]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__row--clickable', $html, on: 'tedi-table__row--clickable');
        $this->assertStringContainsString('x-on:click', $html);
        $this->assertStringContainsString('x-on:keydown.enter.prevent', $html);
    }

    public function test_table_non_expandable_rows_get_the_empty_toggle_spacer(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :expandable="true"
            :columns="[['key' => 'a', 'header' => 'A']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__expand-toggle', $html, on: 'tedi-table__expand-toggle');
        $this->assertStringNotContainsString('tedi-collapse-button', $html);
    }

    // -- table: control column order ----------------------------------------

    public function test_table_control_column_order_is_honoured(): void
    {
        $default = $this->fixture(':enable-row-selection="true" :expandable="true"');
        $swapped = $this->fixture(
            ':enable-row-selection="true" :expandable="true" :control-column-order="[\'expand\', \'select\']"'
        );

        $selectFirst = strpos($default, __('tedi::tedi.table.select-column'))
            < strpos($default, __('tedi::tedi.table.expand-column'));
        $expandFirst = strpos($swapped, __('tedi::tedi.table.expand-column'))
            < strpos($swapped, __('tedi::tedi.table.select-column'));

        $this->assertTrue($selectFirst, 'select precedes expand by default');
        $this->assertTrue($expandFirst, 'controlColumnOrder reorders the control columns');
    }

    /** `drag` stays accepted for API parity but never renders (reorder is not ported). */
    public function test_table_drag_control_column_is_never_rendered(): void
    {
        $html = $this->fixture(':control-column-order="[\'drag\', \'select\']" :enable-row-selection="true"');

        $this->assertStringNotContainsString(__('tedi::tedi.table.reorder-column'), $html);
        $this->assertStringNotContainsString('tedi-table__drag-handle', $html);
    }

    // -- table: sticky first column -----------------------------------------

    public function test_table_sticky_first_column_classes_and_offsets(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :sticky-first-column="true"
            :enable-row-selection="true"
            :columns="[['key' => 'a', 'header' => 'A', 'width' => 200], ['key' => 'b', 'header' => 'B']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1', 'b' => '2']]]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__cell--sticky-left', $html, on: 'tedi-table__cell--sticky-left');
        $this->assertHasClass('tedi-table__cell--sticky-left-start', $html, on: 'tedi-table__cell--sticky-left-start');
        $this->assertHasClass('tedi-table__cell--sticky-left-edge', $html, on: 'tedi-table__cell--sticky-left-edge');

        // Control column is 60px wide (upstream CONTROL_COLUMN_WIDTH), so the
        // frozen content column starts at 60.
        $this->assertStringContainsString('left: 0px', $html);
        $this->assertStringContainsString('left: 60px', $html);
        $this->assertStringNotContainsString('left: 260px', $html, 'only the FIRST content column is frozen');
    }

    public function test_table_sticky_classes_absent_without_the_flag(): void
    {
        $html = $this->fixture();

        $this->assertStringNotContainsString('sticky-left', $html);
        $this->assertMissingClass('tedi-table--sticky-last-column', $html);
    }

    public function test_table_sticky_last_column_host_class(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :sticky-last-column="true"
            :columns="[['key' => 'a', 'header' => 'A'], ['key' => 'b', 'header' => 'B']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1', 'b' => '2']]]"
        />
        BLADE);

        $this->assertHasClass('tedi-table--sticky-last-column', $html, on: 'tedi-table');
        $this->assertMissingClass('tedi-table--sticky-first-column', $html, on: 'tedi-table');
    }

    // -- table: sorting ------------------------------------------------------

    /** sortIcon(): asc → arrow_upward, desc → arrow_downward, none → unfold_more. */
    public function test_table_sort_state_icons_and_aria(): void
    {
        $expected = [
            'none' => ['unfold_more', 'none'],
            'asc' => ['arrow_upward', 'ascending'],
            'desc' => ['arrow_downward', 'descending'],
        ];

        foreach ($expected as $sort => [$icon, $ariaSort]) {
            $html = Blade::render(<<<BLADE
            <tedi:table
                :columns="[['key' => 'a', 'header' => 'A', 'sortable' => true, 'sort' => '{$sort}']]"
                :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
            />
            BLADE);

            $this->assertStringContainsString('>'.$icon.'</tedi-icon>', $html);
            $this->assertStringContainsString('aria-sort="'.$ariaSort.'"', $html);

            if ($sort === 'none') {
                $this->assertMissingClass('tedi-table-header-button--selected', $html, on: 'tedi-table-header-button');
            } else {
                $this->assertHasClass('tedi-table-header-button--selected', $html, on: 'tedi-table-header-button');
            }
        }
    }

    public function test_table_non_sortable_column_has_no_button_and_no_aria_sort(): void
    {
        $html = $this->fixture();

        $this->assertStringNotContainsString('tedi-table-header-button', $html);
        $this->assertStringNotContainsString('aria-sort', $html);
    }

    public function test_table_sort_attributes_reach_the_button(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :columns="[['key' => 'a', 'header' => 'A', 'sortable' => true, 'sortAttributes' => ['wire:click' => 'sort(\'a\')']]]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
        />
        BLADE);

        $this->assertStringContainsString('wire:click="sort(\'a\')"', $html);
    }

    // -- table: filter row ---------------------------------------------------

    public function test_table_filter_row_classes(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :enable-column-filters="true"
            :columns="[['key' => 'a', 'header' => 'A', 'filterable' => true], ['key' => 'b', 'header' => 'B']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1', 'b' => '2']]]"
        />
        BLADE);

        $this->assertHasClass('tedi-table__row--filter', $html, on: 'tedi-table__row--filter');
        $this->assertHasClass('tedi-form-field--small', $html, on: 'tedi-form-field');
        $this->assertHasClass('tedi-text-field', $html, on: 'tedi-text-field');
        $this->assertSame(1, substr_count($html, '<input'), 'only the filterable column gets an input');
    }

    public function test_table_filter_row_absent_by_default(): void
    {
        $this->assertStringNotContainsString('tedi-table__row--filter', $this->fixture());
    }

    public function test_table_custom_filter_content_replaces_the_default_input(): void
    {
        $html = Blade::render(<<<'BLADE'
        @php $filter = new \Illuminate\Support\HtmlString('<span class="custom-filter">x</span>'); @endphp
        <tedi:table
            :enable-column-filters="true"
            :columns="[['key' => 'a', 'header' => 'A', 'filterable' => true, 'filter' => $filter]]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '1']]]"
        />
        BLADE);

        $this->assertStringContainsString('custom-filter', $html);
        $this->assertStringNotContainsString('tedi-text-field', $html);
    }

    // -- table: cells --------------------------------------------------------

    public function test_table_cell_values_are_escaped_unless_htmlable(): void
    {
        $html = Blade::render(<<<'BLADE'
        @php $raw = new \Illuminate\Support\HtmlString('<b>rasvane</b>'); @endphp
        <tedi:table
            :columns="[['key' => 'a', 'header' => 'A'], ['key' => 'b', 'header' => 'B']]"
            :rows="[['id' => 'r1', 'cells' => ['a' => '<b>escaped</b>', 'b' => $raw]]]"
        />
        BLADE);

        $this->assertStringContainsString('&lt;b&gt;escaped&lt;/b&gt;', $html);
        $this->assertStringContainsString('<b>rasvane</b>', $html);
    }

    public function test_table_cell_rowspan_and_skip(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table
            :columns="[['key' => 'a', 'header' => 'A'], ['key' => 'b', 'header' => 'B']]"
            :rows="[
                ['id' => 'r1', 'cells' => ['a' => ['value' => 'span', 'rowspan' => 2], 'b' => '1']],
                ['id' => 'r2', 'cells' => ['a' => ['value' => '', 'rowspan' => 0], 'b' => '2']],
            ]"
        />
        BLADE);

        $this->assertStringContainsString('rowspan="2"', $html);
        $this->assertSame(3, substr_count($html, '<td'), 'the rowspan=0 cell is skipped entirely');
    }

    public function test_table_max_height_style(): void
    {
        $numeric = $this->fixture(':max-height="400"');
        $string = $this->fixture('max-height="50vh"');

        $this->assertStringContainsString('max-height: 400px; overflow-y: auto', $numeric);
        $this->assertStringContainsString('max-height: 50vh; overflow-y: auto', $string);
    }

    public function test_table_fixed_layout_only_sizes_authored_columns(): void
    {
        $auto = Blade::render(<<<'BLADE'
        <tedi:table :columns="[['key' => 'a', 'header' => 'A']]" :rows="[]" />
        BLADE);

        $fixed = Blade::render(<<<'BLADE'
        <tedi:table :fixed-layout="true" :columns="[['key' => 'a', 'header' => 'A'], ['key' => 'b', 'header' => 'B', 'width' => 300]]" :rows="[]" />
        BLADE);

        $this->assertStringContainsString('width: 150px', $auto, 'TanStack default size');
        $this->assertStringNotContainsString('width: 150px', $fixed);
        $this->assertStringContainsString('width: 300px', $fixed);
    }

    // -- table-toolbar -------------------------------------------------------

    public function test_table_toolbar_classes(): void
    {
        $html = Blade::render('<tedi:table-toolbar>x</tedi:table-toolbar>');

        $this->assertHasClass('tedi-table-toolbar', $html);
        $this->assertStringContainsString('<tedi-table-toolbar', $html);
        $this->assertStringContainsString('data-name="tedi-table-toolbar"', $html);
    }

    // -- table-header-button -------------------------------------------------

    public function test_table_header_button_classes(): void
    {
        $plain = Blade::render('<tedi:table-header-button icon="unfold_more">A</tedi:table-header-button>');
        $selected = Blade::render('<tedi:table-header-button icon="arrow_upward" :selected="true">A</tedi:table-header-button>');

        $this->assertHasClass('tedi-table-header-button', $plain, on: 'tedi-table-header-button');
        $this->assertMissingClass('tedi-table-header-button--selected', $plain, on: 'tedi-table-header-button');
        $this->assertHasClass('tedi-table-header-button--selected', $selected, on: 'tedi-table-header-button');

        $this->assertStringContainsString('tedi-table-header-button', $plain);
        $this->assertStringContainsString('type="button"', $plain);
    }

    public function test_table_header_button_icon_variant_and_size(): void
    {
        $outlined = Blade::render('<tedi:table-header-button icon="filter_alt">A</tedi:table-header-button>');
        $filled = Blade::render('<tedi:table-header-button icon="filter_alt" :filled="true">A</tedi:table-header-button>');

        $this->assertHasClass('material-symbols--outlined', $outlined, on: 'tedi-icon');
        $this->assertHasClass('tedi-icon--filled', $filled, on: 'tedi-icon');
        $this->assertStringContainsString('--icon-03', $outlined, 'iconSize defaults to 18');
    }

    public function test_table_header_button_disabled_and_aria_label(): void
    {
        $html = Blade::render('<tedi:table-header-button icon="filter_alt" :disabled="true" aria-label="Filtreeri" />');

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('aria-label="Filtreeri"', $html);
    }

    // -- table-columns-menu --------------------------------------------------

    public function test_table_columns_menu_classes(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table-columns-menu :columns="[
            ['id' => 'a', 'label' => 'A'],
            ['id' => 'b', 'label' => 'B'],
        ]" />
        BLADE);

        $this->assertHasClass('tedi-table-columns-menu', $html, on: 'tedi-table-columns-menu');
        $this->assertStringContainsString('<tedi-table-columns-menu', $html);
        $this->assertStringContainsString('data-name="tedi-table-columns-menu"', $html);
        $this->assertStringContainsString(__('tedi::tedi.table.columns'), $html);
        $this->assertHasClass('tedi-dropdown-item-value--checkbox', $html, on: 'tedi-dropdown-item-value');
    }

    /** Parity with Angular's template: `__option` is styled but never emitted. */
    public function test_table_columns_menu_option_class_is_not_emitted(): void
    {
        $html = Blade::render('<tedi:table-columns-menu :columns="[[\'id\' => \'a\']]" />');

        $this->assertStringNotContainsString('tedi-table-columns-menu__option', $html);
    }

    /** isLastVisible: the only visible column cannot be hidden. */
    public function test_table_columns_menu_last_visible_column_is_disabled(): void
    {
        $one = Blade::render(<<<'BLADE'
        <tedi:table-columns-menu :columns="[
            ['id' => 'a', 'label' => 'A'],
            ['id' => 'b', 'label' => 'B', 'visible' => false],
        ]" />
        BLADE);

        $two = Blade::render(<<<'BLADE'
        <tedi:table-columns-menu :columns="[
            ['id' => 'a', 'label' => 'A'],
            ['id' => 'b', 'label' => 'B'],
        ]" />
        BLADE);

        $this->assertSame(1, substr_count($one, 'aria-disabled="true"'));
        $this->assertStringNotContainsString('aria-disabled="true"', $two);
    }

    public function test_table_columns_menu_trigger_label_override_and_attributes(): void
    {
        $html = Blade::render(<<<'BLADE'
        <tedi:table-columns-menu
            trigger-label="Veerud (2)"
            :columns="[
                ['id' => 'a', 'label' => 'A', 'attributes' => ['wire:click' => 'toggle(\'a\')']],
                ['id' => 'b', 'label' => 'B'],
            ]"
        />
        BLADE);

        $this->assertStringContainsString('Veerud (2)', $html);
        $this->assertStringContainsString('wire:click="toggle(&#039;a&#039;)"', $html);
    }
}
