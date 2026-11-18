<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class and attribute parity for the React community DockPanel
 * (CONVENTIONS.md §13) — react/src/community/components/dock-panel/dock-panel.tsx.
 * Expected markup comes from the `panel` and `sheet` JSX there.
 */
class DockPanelComponentsTest extends TestCase
{
    private const LABELS = "['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']";

    private function panel(string $attributes = '', string $content = '<p>Sisu</p>'): string
    {
        return Blade::render('<tedi:dock-panel title="Details" :labels="'.self::LABELS.'" '.$attributes.'>'.$content.'</tedi:dock-panel>');
    }

    /** The opening tag of the first element whose attributes match. */
    private function tag(string $html, string $pattern): string
    {
        $this->assertSame(1, preg_match('/<[a-z0-9-]+\b(?=[^>]*'.$pattern.')[^>]*>/s', $html, $match), 'No element matches '.$pattern);

        return $match[0];
    }

    public function test_layout_markup(): void
    {
        $html = Blade::render('<tedi:dock-panel-layout class="mine" style="height: 36rem"><p>Workspace</p><x-slot:panel><span id="the-panel"></span></x-slot:panel></tedi:dock-panel-layout>');

        $this->assertSame(['tedi-dock-panel-layout', 'mine'], $this->classesOf($html, 'tedi-dock-panel-layout'));
        $this->assertSame(['tedi-dock-panel-layout__content'], $this->classesOf($html, 'tedi-dock-panel-layout__content'));
        $this->assertMatchesRegularExpression('#<div class="tedi-dock-panel-layout__content"><p>Workspace</p></div>\s*<span id="the-panel"></span>\s*</div>#', $html);
        $this->assertStringContainsString('style="height: 36rem;"', $html);
    }

    public function test_docked_panel_root_matches_upstream(): void
    {
        $html = $this->panel('id="dp" class="mine"');
        $root = $this->tag($html, 'data-tedi-dock-panel=');

        $this->assertSame(['tedi-dock-panel', 'mine'], $this->classesOf($root));
        foreach ([
            'id="dp"', 'role="region"', 'aria-labelledby="dp-title"', 'data-placement="right"',
            'data-size="default"', 'data-collapsed="false"', 'data-resizable="false"',
        ] as $attribute) {
            $this->assertStringContainsString($attribute, $root);
        }
        $this->assertStringNotContainsString('style=', $root);
        $this->assertStringNotContainsString('data-minimize-counter', $root);
    }

    public function test_placement_size_and_collapsed_attributes(): void
    {
        foreach (['left', 'right', 'top', 'bottom'] as $placement) {
            foreach (['default', 'small'] as $size) {
                foreach ([false, true] as $collapsed) {
                    $root = $this->tag($this->panel('placement="'.$placement.'" size="'.$size.'" :collapsed="'.($collapsed ? 'true' : 'false').'"'), 'data-tedi-dock-panel=');

                    $this->assertStringContainsString('data-placement="'.$placement.'"', $root);
                    $this->assertStringContainsString('data-size="'.$size.'"', $root);
                    $this->assertStringContainsString('data-collapsed="'.($collapsed ? 'true' : 'false').'"', $root);
                }
            }
        }
    }

    public function test_header_structure_and_classes(): void
    {
        $html = $this->panel('id="dp" description="Lisainfo" closable', '<p>Sisu</p><x-slot:header-actions><b>A</b></x-slot:header-actions> <x-slot:footer><i>F</i></x-slot:footer>');

        foreach ([
            'tedi-dock-panel__header', 'tedi-dock-panel__header-row', 'tedi-dock-panel__title',
            'tedi-dock-panel__header-actions', 'tedi-dock-panel__controls', 'tedi-dock-panel__description',
            'tedi-dock-panel__body', 'tedi-dock-panel__scroll-area', 'tedi-dock-panel__content',
            'tedi-dock-panel__footer',
        ] as $class) {
            $this->assertHasClass($class, $html, on: $class);
        }

        $this->assertSame(['tedi-dock-panel__title'], $this->classesOf($html, 'tedi-dock-panel__title'));
        $this->assertMatchesRegularExpression('#<h3 id="dp-title" tabindex="-1" class="tedi-dock-panel__title"[^>]*>Details</h3>#', $html);
        $this->assertStringContainsString('id="dp-body" class="tedi-dock-panel__body"', $html);
    }

    public function test_heading_element_defaults_follow_size(): void
    {
        $this->assertMatchesRegularExpression('#<h3 [^>]*class="tedi-dock-panel__title#', $this->panel());
        $this->assertMatchesRegularExpression('#<h4 [^>]*class="tedi-dock-panel__title#', $this->panel('size="small"'));
        $this->assertMatchesRegularExpression('#<h2 [^>]*class="tedi-dock-panel__title#', $this->panel('size="small" heading-element="h2"'));
    }

    public function test_toggle_button_expanded(): void
    {
        $html = $this->panel('id="dp"');
        $toggle = $this->tag($html, 'data-tedi-dock-panel-toggle="docked"');

        $this->assertSame(
            ['tedi-button', 'no-print', 'tedi-button--neutral', 'tedi-button--default', 'tedi-button--icon-only', 'tedi-dock-panel__toggle'],
            $this->classesOf($toggle)
        );
        $this->assertStringContainsString('aria-expanded="true"', $toggle);
        $this->assertStringContainsString('aria-controls="dp-body"', $toggle);
        $this->assertStringContainsString('aria-label="Minimize panel"', $toggle);
        $this->assertStringContainsString('title="Minimize panel"', $toggle);
        $this->assertStringContainsString('>zoom_in_map</tedi-icon>', $html);
    }

    public function test_collapsed_state_hides_content_and_restores_label(): void
    {
        $html = $this->panel('id="dp" collapsed description="Lisainfo"', '<p>Sisu</p><x-slot:header-actions><b>A</b></x-slot:header-actions> <x-slot:footer><i>F</i></x-slot:footer>');
        $toggle = $this->tag($html, 'data-tedi-dock-panel-toggle="docked"');

        $this->assertStringContainsString('aria-expanded="false"', $toggle);
        $this->assertStringContainsString('aria-label="Restore panel"', $toggle);
        $this->assertStringContainsString('>zoom_out_map</tedi-icon>', $html);
        $this->assertSame(['tedi-dock-panel__title', 'text-small'], $this->classesOf($html, 'tedi-dock-panel__title'));
        $this->assertMatchesRegularExpression('#class="tedi-dock-panel__body"[^>]*\shidden\b#', $html);
        $this->assertMatchesRegularExpression('#class="tedi-dock-panel__description"[^>]*\shidden\b#', $html);
        $this->assertMatchesRegularExpression('#class="tedi-dock-panel__header-actions"[^>]*\shidden\b#', $html);
    }

    public function test_close_button_only_when_closable(): void
    {
        $this->assertSame([], $this->classesOf($this->panel(), 'tedi-dock-panel__close'));

        $html = $this->panel('closable');
        $close = $this->tag($html, 'tedi-dock-panel__close');
        $this->assertSame(
            ['tedi-button', 'no-print', 'tedi-button--neutral', 'tedi-button--default', 'tedi-button--icon-only', 'tedi-dock-panel__close'],
            $this->classesOf($close)
        );
        $this->assertStringContainsString('aria-label="Close panel"', $close);
        $this->assertStringContainsString('x-on:click="close()"', $close);
        // The sheet's close control is the closing button.
        $this->assertHasClass('tedi-closing-button', $html, on: 'tedi-closing-button');
    }

    public function test_optional_parts_are_absent_when_not_given(): void
    {
        $html = $this->panel();

        foreach (['tedi-dock-panel__description', 'tedi-dock-panel__header-actions', 'tedi-dock-panel__footer', 'tedi-dock-panel__sheet-actions'] as $class) {
            $this->assertSame([], $this->classesOf($html, $class), $class);
        }
    }

    public function test_dimensions_resolve_to_custom_properties(): void
    {
        $root = fn (string $attributes) => $this->tag($this->panel($attributes), 'data-tedi-dock-panel=');

        foreach (['xs' => '25%', 'sm' => '35%', 'md' => '50%', 'lg' => '65%', 'xl' => '80%'] as $preset => $percentage) {
            $this->assertStringContainsString('--dock-panel-width: '.$percentage, $root('width="'.$preset.'"'));
            $this->assertStringContainsString('--dock-panel-height: '.$percentage, $root('height="'.$preset.'"'));
        }

        $this->assertStringContainsString('--dock-panel-width: 420px', $root(':width="420"'));
        $this->assertStringContainsString('--dock-panel-height: 70%', $root('height="70%"'));
        $this->assertStringContainsString('--dock-panel-height: auto', $root('height="auto"'));
        $this->assertStringContainsString('--dock-panel-max-height: 512px', $root(':max-height="512"'));
        $this->assertStringContainsString('--dock-panel-max-width: 600px', $root(':max-width="600"'));
        // maxWidth applies to side panels only.
        $this->assertStringNotContainsString('--dock-panel-max-width', $root('placement="bottom" :max-width="600"'));
    }

    public function test_short_title_renders_the_fitting_title(): void
    {
        $html = $this->panel('short-title="Lühike"');

        $this->assertMatchesRegularExpression(
            '#<span class="tedi-dock-panel__fitting-title"[^>]*data-title="Details" data-short-title="Lühike">Details<span aria-hidden="true" class="tedi-dock-panel__title-measure">Details</span></span>#',
            $html
        );
    }

    public function test_content_is_wrapped_in_slot_nodes_unless_mobile_is_false(): void
    {
        $slotted = $this->panel('', '<p>Sisu</p><x-slot:footer><i>F</i></x-slot:footer>');
        $this->assertStringContainsString('<div class="tedi-dock-panel__slot" data-tedi-dock-panel-slot="content"><p>Sisu</p></div>', $slotted);
        $this->assertStringContainsString('<div class="tedi-dock-panel__slot" data-tedi-dock-panel-slot="footer"><i>F</i></div>', $slotted);

        $inline = $this->panel(':mobile="false"', '<p>Sisu</p><x-slot:footer><i>F</i></x-slot:footer>');
        $this->assertSame([], $this->classesOf($inline, 'tedi-dock-panel__slot'));
        $this->assertStringContainsString('data-tedi-dock-panel-host="content"><p>Sisu</p></div>', $inline);
    }

    public function test_sheet_shell_is_rendered_beside_the_panel(): void
    {
        $html = $this->panel('id="dp" class="mine" closable description="Lisainfo"', '<p>Sisu</p><x-slot:header-actions><b>A</b></x-slot:header-actions> <x-slot:footer><i>F</i></x-slot:footer>');

        // The panel, the sheet wrapper and the spacer are siblings.
        $this->assertMatchesRegularExpression('#</div>\s*<div style="display: contents" data-tedi-dock-panel-sheet="dp" x-data="tediDockPanelSheet\(#', $html);
        $this->assertStringContainsString('<div class="tedi-dock-panel__sheet-spacer" style="display: none" data-tedi-dock-panel-spacer="dp"></div>', $html);

        // Sheet.Content: no overlay, no focus trap, closed until the client presents it.
        $this->assertSame(['tedi-sheet__overlay', 'tedi-sheet__overlay--no-overlay', 'tedi-sheet__overlay--hidden'], $this->classesOf($html, 'tedi-sheet__overlay'));
        $this->assertSame(['tedi-sheet__panel', 'tedi-sheet__panel--draggable', 'mine'], $this->classesOf($html, 'tedi-sheet__panel'));
        $this->assertStringContainsString('aria-modal="false"', $html);
        $this->assertStringNotContainsString('data-tedi-sheet-dismiss', $html);

        // Sheet.Header variant default, no built-in close; DockPanel's own controls.
        $this->assertSame(['tedi-sheet__header', 'tedi-sheet__header--default'], $this->classesOf($html, 'tedi-sheet__header'));
        $this->assertSame(['tedi-dock-panel__sheet-actions'], $this->classesOf($html, 'tedi-dock-panel__sheet-actions'));
        $toggle = $this->tag($html, 'data-tedi-dock-panel-toggle="sheet"');
        $this->assertHasClass('tedi-dock-panel__sheet-toggle', $toggle);
        $this->assertHasClass('tedi-collapse-button--icon-only', $toggle);
        $this->assertStringContainsString('aria-controls="dp"', $toggle);
        $this->assertStringContainsString('aria-label="Minimize panel"', $toggle);

        $this->assertSame(['tedi-sheet__body', 'tedi-dock-panel__sheet-body'], $this->classesOf($html, 'tedi-dock-panel__sheet-body'));
        $this->assertSame(['tedi-dock-panel__description', 'tedi-dock-panel__sheet-description'], $this->classesOf($html, 'tedi-dock-panel__sheet-description'));
        $this->assertHasClass('tedi-sheet__footer--full-width', $html, on: 'tedi-sheet__footer');

        // The id stays on the docked panel on the server; the sheet content host is empty.
        $this->assertSame(1, substr_count($html, 'id="dp"'));
        $this->assertStringContainsString('<div data-tedi-dock-panel-host="content"></div>', $html);
    }

    public function test_small_size_passes_sheet_padding(): void
    {
        $this->assertHasClass('tedi-sheet__panel--padding-small', $this->panel('size="small"'), on: 'tedi-sheet__panel');
    }

    public function test_no_sheet_when_mobile_is_false(): void
    {
        $html = $this->panel(':mobile="false"');

        $this->assertStringNotContainsString('tediDockPanelSheet', $html);
        $this->assertSame([], $this->classesOf($html, 'tedi-dock-panel__sheet-spacer'));
        $this->assertSame([], $this->classesOf($html, 'tedi-sheet__panel'));
    }

    public function test_alpine_config(): void
    {
        $html = $this->panel('id="dp" :mobile="true" collapsed :minimize-counter="3"');
        $root = $this->tag($html, 'data-tedi-dock-panel=');

        $this->assertStringContainsString('x-data="tediDockPanel(', $root);
        // @js() encodes the quotes, so match around them.
        $this->assertMatchesRegularExpression('/mobile.{1,6}:true/', $root);
        $this->assertMatchesRegularExpression('/collapsed.{1,6}:true/', $root);
        $this->assertStringContainsString('data-minimize-counter="3"', $root);
        $this->assertStringContainsString('x-on:click="toggle()"', $html);
    }

    public function test_generated_ids_link_title_and_body(): void
    {
        $html = $this->panel();
        $this->assertSame(1, preg_match('/data-tedi-dock-panel="([^"]+)"/', $html, $match));
        $id = $match[1];

        $this->assertStringStartsWith('tedi-dock-panel', $id);
        $this->assertStringContainsString('aria-labelledby="'.$id.'-title"', $html);
        $this->assertStringContainsString('aria-controls="'.$id.'-body"', $html);
    }

    public function test_title_and_labels_are_required(): void
    {
        foreach ([
            '<tedi:dock-panel :labels="'.self::LABELS.'">x</tedi:dock-panel>',
            '<tedi:dock-panel title="Details">x</tedi:dock-panel>',
            '<tedi:dock-panel title="Details" :labels="[\'collapse\' => \'a\']">x</tedi:dock-panel>',
        ] as $template) {
            try {
                Blade::render($template);
                $this->fail('Expected an exception for '.$template);
            } catch (\Throwable $e) {
                $this->assertStringContainsString('tedi:dock-panel requires', $e->getMessage());
            }
        }
    }

    public function test_title_is_escaped(): void
    {
        $html = Blade::render('<tedi:dock-panel title="<b>x</b>" short-title="<i>y</i>" :labels="'.self::LABELS.'">z</tedi:dock-panel>');

        $this->assertStringNotContainsString('<b>x</b>', $html);
        $this->assertStringNotContainsString('<i>y</i>', $html);
        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
    }
}
