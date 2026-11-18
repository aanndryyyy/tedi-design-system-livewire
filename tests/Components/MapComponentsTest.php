<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class and attribute parity for React's community map components
 * (CONVENTIONS.md §13): react/src/community/components/map-components/
 * resizer/resizer.tsx and split-pane/split-pane.tsx. The split-pane cases
 * follow split-pane.spec.tsx where they concern markup.
 */
class MapComponentsTest extends TestCase
{
    /** The opening tag of the first element whose attributes match. */
    private function tag(string $html, string $pattern): string
    {
        $this->assertSame(1, preg_match('/<[a-z0-9-]+\b(?=[^>]*'.$pattern.')[^>]*>/s', $html, $match), 'No element matches '.$pattern);

        return $match[0];
    }

    private function splitPane(string $attributes = 'direction="horizontal"'): string
    {
        return Blade::render('<tedi:split-pane '.$attributes.'><x-slot:first><p>Esimene</p></x-slot:first> <x-slot:second><p>Teine</p></x-slot:second> </tedi:split-pane>');
    }

    // -- Resizer ----------------------------------------------------------------

    public function test_resizer_markup_matches_upstream(): void
    {
        $html = Blade::render('<tedi:resizer class="mine"><p>Sisu</p></tedi:resizer>');

        $this->assertSame(['tedi-resize__wrapper', 'mine'], $this->classesOf($html, 'tedi-resize__wrapper'));
        $wrapper = $this->tag($html, 'tedi-resize__wrapper');
        $this->assertStringContainsString('style="width: 350px; height: auto; position: relative; display: block;"', $wrapper);
        $this->assertStringContainsString('x-data="tediResizer(', $wrapper);

        $this->assertMatchesRegularExpression('#<div class="tedi-resize__content"><p>Sisu</p></div>#', $html);
        $this->assertSame(['tedi-resize__handle-wrapper', 'direction-right'], $this->classesOf($html, 'tedi-resize__handle-wrapper'));
        $handle = $this->tag($html, 'tedi-resize__handle-wrapper');
        $this->assertStringContainsString('data-show-indicator="false"', $handle);
        $this->assertStringContainsString('x-on:mousedown="start($event)"', $handle);
        $this->assertSame(['tedi-resize__drag-handle'], $this->classesOf($html, 'tedi-resize__drag-handle'));
        $this->assertSame(['tedi-resize__drag-indicator'], $this->classesOf($html, 'tedi-resize__drag-indicator'));
    }

    public function test_resizer_icons_are_spans_with_the_icon_class_list(): void
    {
        $html = Blade::render('<tedi:resizer />');

        // The vendored module styles them as `… > span`.
        $this->assertSame(2, preg_match_all('#<span role="img" aria-hidden="true" style="--_tedi-icon-size: var\(--icon-03\)" class="([^"]+)">drag_handle</span>#', $html, $matches));
        $this->assertSame('notranslate material-symbols material-symbols--outlined tedi-icon tedi-icon--color-primary', $matches[1][0]);
        $this->assertSame('notranslate material-symbols material-symbols--outlined tedi-icon tedi-icon--color-brand', $matches[1][1]);
        $this->assertStringNotContainsString('<tedi-icon', $html);
    }

    public function test_resizer_show_indicator_drops_the_indicator_tab(): void
    {
        $html = Blade::render('<tedi:resizer show-indicator />');

        $this->assertStringContainsString('data-show-indicator="true"', $this->tag($html, 'tedi-resize__handle-wrapper'));
        $this->assertSame([], $this->classesOf($html, 'tedi-resize__drag-indicator'));
        $this->assertSame(['tedi-resize__drag-handle'], $this->classesOf($html, 'tedi-resize__drag-handle'));
    }

    public function test_resizer_handle_positions_and_inline_size(): void
    {
        foreach (['right' => 'auto', 'left' => 'auto', 'top' => '118px', 'bottom' => '118px'] as $position => $height) {
            $html = Blade::render('<tedi:resizer handle-position="'.$position.'" :initial-width="400" :initial-height="118" />');

            $this->assertHasClass('direction-'.$position, $html, 'tedi-resize__handle-wrapper');
            // Width is always set, as upstream's style prop sets it.
            $this->assertStringContainsString('style="width: 400px; height: '.$height.'; position: relative; display: block;"', $this->tag($html, 'tedi-resize__wrapper'));
        }
    }

    public function test_resizer_alpine_config_carries_the_limits(): void
    {
        $wrapper = $this->tag(Blade::render('<tedi:resizer handle-position="top" :min-height="118" :max-height="400" />'), 'tedi-resize__wrapper');

        foreach (['handlePosition.{1,16}top', 'minWidth.{1,8}300', 'maxWidth.{1,8}600', 'minHeight.{1,8}118', 'maxHeight.{1,8}400', 'width.{1,8}350', 'height.{1,8}350'] as $pattern) {
            $this->assertMatchesRegularExpression('/'.$pattern.'/', $wrapper);
        }
    }

    // -- SplitPane --------------------------------------------------------------

    public function test_split_pane_markup_matches_upstream(): void
    {
        $html = $this->splitPane();

        $this->assertSame(['tedi-split-pane', 'tedi-split-pane--horizontal'], $this->classesOf($html, 'tedi-split-pane'));
        $this->assertStringContainsString('x-data="tediSplitPane(', $this->tag($html, 'tedi-split-pane--'));
        $this->assertSame(2, preg_match_all('#<div class="tedi-split-pane__pane" style="flex-grow: 50"#', $html));
        $this->assertMatchesRegularExpression('#data-tedi-split-pane="first">\s*<p>Esimene</p>#', $html);
        $this->assertMatchesRegularExpression('#data-tedi-split-pane="second">\s*<p>Teine</p>#', $html);
        $this->assertSame(['tedi-split-pane__divider'], $this->classesOf($html, 'tedi-split-pane__divider'));
        $this->assertSame([], $this->classesOf($html, 'tedi-split-pane__ring'));
        $this->assertSame([], $this->classesOf($html, 'tedi-split-pane__close'));
    }

    public function test_split_pane_separator_semantics(): void
    {
        $separator = $this->tag($this->splitPane(), 'role="separator"');

        $this->assertSame(['tedi-split-pane__separator'], $this->classesOf($separator));
        foreach ([
            'tabindex="0"', 'aria-orientation="vertical"', 'aria-valuemin="20"', 'aria-valuemax="80"',
            'aria-valuenow="50"', 'aria-label="Resize panes"',
            'x-on:mousedown="startDrag($event)"', 'x-on:touchstart="startDrag()"', 'x-on:keydown="onKeydown($event)"',
        ] as $attribute) {
            $this->assertStringContainsString($attribute, $separator);
        }

        $vertical = $this->tag($this->splitPane('direction="vertical"'), 'role="separator"');
        $this->assertStringContainsString('aria-orientation="horizontal"', $vertical);
    }

    public function test_split_pane_handle_icon_follows_direction(): void
    {
        foreach (['horizontal' => 'code', 'vertical' => 'unfold_more'] as $direction => $icon) {
            $html = $this->splitPane('direction="'.$direction.'"');
            $this->assertMatchesRegularExpression(
                '#<span aria-hidden="true" class="tedi-split-pane__handle"><span role="img" aria-hidden="true" style="--_tedi-icon-size: var\(--icon-03\)" class="notranslate material-symbols material-symbols--outlined tedi-icon tedi-icon--color-primary">'.$icon.'</span></span>#',
                $html
            );
        }
    }

    public function test_split_pane_label_is_translated(): void
    {
        app()->setLocale('et');
        $this->assertStringContainsString('aria-label="Muuda paanide suurust"', $this->tag($this->splitPane(), 'role="separator"'));
        app()->setLocale('ru');
        $this->assertStringContainsString('aria-label="Изменить размер панелей"', $this->tag($this->splitPane(), 'role="separator"'));
    }

    public function test_split_pane_initial_ratio_is_clamped(): void
    {
        $cases = [
            'initial-ratio="70"' => 70,
            'initial-ratio="5"' => 20,
            'initial-ratio="95"' => 80,
            'initial-ratio="5" min-ratio="10"' => 10,
        ];
        foreach ($cases as $attributes => $ratio) {
            $html = $this->splitPane('direction="horizontal" '.$attributes);
            $this->assertStringContainsString('aria-valuenow="'.$ratio.'"', $this->tag($html, 'role="separator"'), $attributes);
            $this->assertStringContainsString('style="flex-grow: '.$ratio.'" data-tedi-split-pane="first"', $html);
            $this->assertStringContainsString('style="flex-grow: '.(100 - $ratio).'" data-tedi-split-pane="second"', $html);
        }
    }

    public function test_split_pane_ring_omits_the_edge_facing_the_divider(): void
    {
        $cases = [
            ['horizontal', 'first', 'right'], ['horizontal', 'second', 'left'],
            ['vertical', 'first', 'bottom'], ['vertical', 'second', 'top'],
        ];
        foreach ($cases as [$direction, $side, $edge]) {
            $html = $this->splitPane('direction="'.$direction.'" highlighted-side="'.$side.'"');

            $this->assertSame(['tedi-split-pane__ring', 'tedi-split-pane__ring--omit-'.$edge], $this->classesOf($html, 'tedi-split-pane__ring'));
            $this->assertStringContainsString('aria-hidden="true"', $this->tag($html, 'tedi-split-pane__ring'));
            // The ring sits inside the highlighted pane, after its content.
            $this->assertMatchesRegularExpression('#data-tedi-split-pane="'.$side.'">(?:(?!data-tedi-split-pane).)*tedi-split-pane__ring#s', $html);
        }
    }

    public function test_split_pane_close_button_is_a_sibling_of_the_separator(): void
    {
        $html = $this->splitPane('direction="horizontal" closable');

        $this->assertSame(['tedi-split-pane__close'], $this->classesOf($html, 'tedi-split-pane__close'));
        $this->assertMatchesRegularExpression('#role="separator"(?:(?!</div>).)*</span>\s*</div>\s*<div class="tedi-split-pane__close">#s', $html);
        $button = $this->tag($html, 'tedi-button--icon-only');
        $this->assertSame(['tedi-button', 'no-print', 'tedi-button--secondary', 'tedi-button--default', 'tedi-button--icon-only'], $this->classesOf($button));
        $this->assertStringContainsString('aria-label="Close"', $button);
        $this->assertStringContainsString('type="button"', $button);
        $this->assertStringContainsString('x-on:click="close()"', $button);
    }

    public function test_split_pane_requires_direction(): void
    {
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessageMatches('/requires direction/');
        Blade::render('<tedi:split-pane><x-slot:first>a</x-slot:first> <x-slot:second>b</x-slot:second> </tedi:split-pane>');
    }

    public function test_split_pane_alpine_config(): void
    {
        $root = $this->tag($this->splitPane('direction="vertical" initial-ratio="30" min-ratio="25" max-ratio="75"'), 'tedi-split-pane--');

        foreach (['direction.{1,16}vertical', 'ratio.{1,8}30', 'minRatio.{1,8}25', 'maxRatio.{1,8}75'] as $pattern) {
            $this->assertMatchesRegularExpression('/'.$pattern.'/', $root);
        }
    }
}
