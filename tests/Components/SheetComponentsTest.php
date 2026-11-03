<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the React-sourced sheet (CONVENTIONS.md §13) —
 * react/src/tedi/components/overlays/sheet. Expected class strings come from
 * each subcomponent's `cn(styles[…])` call.
 */
class SheetComponentsTest extends TestCase
{
    private function sheet(string $content, string $sheetAttributes = ''): string
    {
        return Blade::render('<tedi:sheet '.$sheetAttributes.'>'
            .'<tedi:sheet-trigger><button type="button">Ava</button></tedi:sheet-trigger>'
            .$content
            .'</tedi:sheet>');
    }

    public function test_sheet_root_is_a_classless_alpine_scope(): void
    {
        $html = $this->sheet('<tedi:sheet-content>x</tedi:sheet-content>', 'id="s"');

        $this->assertMatchesRegularExpression('/<div id="s" x-data="tediSheet\(/', $html);
        $this->assertStringContainsString('x-ref="trigger"', $html);
        $this->assertStringContainsString('x-on:click="toggle()"', $html);
    }

    /** Closed: still in the DOM (CONVENTIONS.md §8), hidden, `unmounted`. */
    public function test_closed_sheet_is_in_the_dom_and_hidden(): void
    {
        $html = $this->sheet('<tedi:sheet-content>x</tedi:sheet-content>');

        $this->assertHasClass('tedi-sheet__overlay', $html, on: 'tedi-sheet__overlay');
        $this->assertHasClass('tedi-sheet__overlay--hidden', $html, on: 'tedi-sheet__overlay');
        $this->assertHasClass('tedi-sheet__panel', $html, on: 'tedi-sheet__panel');
        $this->assertMatchesRegularExpression('/role="dialog"[^>]*\shidden\s/', $html);
        $this->assertStringContainsString('data-status="unmounted"', $html);
        $this->assertStringContainsString('style="position: fixed; inset: 0; overflow: clip"', $html);
    }

    public function test_open_sheet_renders_the_open_state(): void
    {
        $html = $this->sheet('<tedi:sheet-content>x</tedi:sheet-content>', 'open');

        $this->assertMissingClass('tedi-sheet__overlay--hidden', $html, on: 'tedi-sheet__overlay');
        $this->assertStringContainsString('data-status="open"', $html);
        $this->assertDoesNotMatchRegularExpression('/role="dialog"[^>]*\shidden\s/', $html);
    }

    public function test_content_modifiers(): void
    {
        $default = $this->sheet('<tedi:sheet-content>x</tedi:sheet-content>');
        $this->assertHasClass('tedi-sheet__panel--draggable', $default, on: 'tedi-sheet__panel');
        $this->assertMissingClass('tedi-sheet__panel--padding-small', $default, on: 'tedi-sheet__panel');
        $this->assertMissingClass('tedi-sheet__overlay--no-overlay', $default, on: 'tedi-sheet__overlay');
        $this->assertStringContainsString('tedi-sheet__handle-zone', $default);

        $tuned = $this->sheet('<tedi:sheet-content padding="small" :show-handle="false" :show-overlay="false">x</tedi:sheet-content>');
        $this->assertHasClass('tedi-sheet__panel--padding-small', $tuned, on: 'tedi-sheet__panel');
        $this->assertMissingClass('tedi-sheet__panel--draggable', $tuned, on: 'tedi-sheet__panel');
        $this->assertHasClass('tedi-sheet__overlay--no-overlay', $tuned, on: 'tedi-sheet__overlay');
        $this->assertStringNotContainsString('tedi-sheet__handle-zone', $tuned);
    }

    public function test_radius_is_a_custom_property_override(): void
    {
        $this->assertStringNotContainsString('--tedi-sheet-radius', $this->sheet('<tedi:sheet-content>x</tedi:sheet-content>'));
        $this->assertStringContainsString('--tedi-sheet-radius: var(--card-radius-rounded)',
            $this->sheet('<tedi:sheet-content radius="card">x</tedi:sheet-content>'));
        $this->assertStringContainsString('--tedi-sheet-radius: 0',
            $this->sheet('<tedi:sheet-content radius="none">x</tedi:sheet-content>'));
    }

    public function test_heights_and_snap_points(): void
    {
        $max = $this->sheet('<tedi:sheet-content max-height="32rem" min-height="10rem">x</tedi:sheet-content>');
        $this->assertStringContainsString('max-height: 32rem', $max);
        $this->assertStringContainsString('min-height: 10rem', $max);

        // Snap points cap the height at the tallest and open at it by default…
        $snaps = $this->sheet('<tedi:sheet-content :snap-points="[0.9, 0.4]" max-height="32rem">x</tedi:sheet-content>');
        $this->assertStringContainsString('max-height: 90dvh', $snaps);
        $this->assertStringContainsString('height: 90dvh', $snaps);
        $this->assertStringNotContainsString('32rem', explode('x-init', $snaps)[0]);

        // …or at the requested index into the sorted list.
        $low = $this->sheet('<tedi:sheet-content :snap-points="[0.4, 0.9]" :default-snap-point="0">x</tedi:sheet-content>');
        $this->assertStringContainsString('height: 40dvh', $low);
    }

    public function test_dialog_is_named_by_the_header_title(): void
    {
        $html = $this->sheet('<tedi:sheet-content><tedi:sheet-header title="Seaded" /></tedi:sheet-content>');

        preg_match('/<h3[^>]*id="([^"]+)"/', $html, $match);
        $this->assertNotEmpty($match);
        $this->assertStringContainsString('aria-labelledby="'.$match[1].'"', $html);

        $labelled = $this->sheet('<tedi:sheet-content aria-label="Seaded">x</tedi:sheet-content>');
        $this->assertStringContainsString('aria-label="Seaded"', $labelled);
        $this->assertStringNotContainsString('aria-labelledby', $labelled);
    }

    public function test_role_and_modal_flags(): void
    {
        $alert = $this->sheet('<tedi:sheet-content>x</tedi:sheet-content>', 'role="alertdialog"');
        $this->assertStringContainsString('role="alertdialog"', $alert);
        $this->assertStringContainsString('aria-modal="true"', $alert);
        $this->assertSame(2, substr_count($alert, 'data-tedi-sheet-dismiss'));

        $docked = $this->sheet('<tedi:sheet-content :trap-focus="false">x</tedi:sheet-content>');
        $this->assertStringContainsString('aria-modal="false"', $docked);
        $this->assertStringNotContainsString('data-tedi-sheet-dismiss', $docked);
    }

    public function test_header_variants_and_title_position(): void
    {
        $brand = Blade::render('<tedi:sheet-header title="Seaded" />');
        $this->assertHasClass('tedi-sheet__header', $brand, on: 'tedi-sheet__header');
        $this->assertHasClass('tedi-sheet__header--brand', $brand, on: 'tedi-sheet__header');
        $this->assertMissingClass('tedi-sheet__header--center', $brand, on: 'tedi-sheet__header');
        $this->assertHasClass('tedi-text--h6', $brand, on: 'tedi-sheet__title');
        $this->assertStringContainsString('<h3', $brand);
        // Angular's closing button has no white variant — see the docblock.
        $this->assertHasClass('tedi-button--neutral-inverted', $brand, on: 'tedi-button');

        $default = Blade::render('<tedi:sheet-header title="Seaded" variant="default" title-position="center" heading-level="h2" />');
        $this->assertHasClass('tedi-sheet__header--default', $default, on: 'tedi-sheet__header');
        $this->assertHasClass('tedi-sheet__header--center', $default, on: 'tedi-sheet__header');
        $this->assertStringContainsString('<h2', $default);
        $this->assertStringContainsString('tedi-closing-button', $default);
    }

    public function test_header_slots_collapse_toggle_and_close_button(): void
    {
        $html = Blade::render(
            '<tedi:sheet-header title="Teated" collapsible :close-button="false">'
            .'<x-slot:start>S</x-slot:start> <x-slot:end>E</x-slot:end> <x-slot:actions>A</x-slot:actions> '
            .'</tedi:sheet-header>'
        );

        $this->assertHasClass('tedi-sheet__header-start', $html, on: 'tedi-sheet__header-start');
        $this->assertHasClass('tedi-sheet__header-actions', $html, on: 'tedi-sheet__header-actions');
        $this->assertHasClass('tedi-sheet__collapse-toggle', $html, on: 'tedi-collapse-button');
        $this->assertHasClass('tedi-collapse-button--inverted', $html, on: 'tedi-collapse-button');
        $this->assertStringContainsString('expanded = ! expanded', $html);
        $this->assertStringNotContainsString('tedi-closing-button', $html);
        $this->assertStringNotContainsString('neutral-inverted', $html);
        // Upstream's order inside the action group: end, collapse, actions, close.
        $this->assertMatchesRegularExpression('/E.*tedi-collapse-button.*A/s', $html);

        $untitled = Blade::render('<tedi:sheet-header />');
        $this->assertStringNotContainsString('tedi-sheet__title', $untitled);
        $this->assertStringNotContainsString('tedi-sheet__header-start', $untitled);
    }

    public function test_header_default_slot_replaces_the_layout(): void
    {
        $html = Blade::render('<tedi:sheet-header title="Peidus">Oma sisu</tedi:sheet-header>');

        $this->assertStringContainsString('Oma sisu', $html);
        $this->assertStringNotContainsString('Peidus', $html);
        $this->assertStringNotContainsString('tedi-sheet__header-actions', $html);
    }

    public function test_body_padding_and_collapse(): void
    {
        $body = Blade::render('<tedi:sheet-body>x</tedi:sheet-body>');
        $this->assertHasClass('tedi-sheet__body', $body, on: 'tedi-sheet__body');
        $this->assertMissingClass('tedi-sheet__body--no-padding', $body, on: 'tedi-sheet__body');
        $this->assertStringContainsString('x-bind:hidden="collapsed"', $body);

        $flush = Blade::render('<tedi:sheet-body padding="none">x</tedi:sheet-body>');
        $this->assertHasClass('tedi-sheet__body--no-padding', $flush, on: 'tedi-sheet__body');

        // Collapsed sheets render the body hidden, not removed.
        $collapsed = $this->sheet('<tedi:sheet-content><tedi:sheet-body>x</tedi:sheet-body></tedi:sheet-content>', 'open collapsed');
        $this->assertMatchesRegularExpression('/<div\s+hidden\s+class="tedi-sheet__body"/', $collapsed);
    }

    public function test_footer_alignment_union_and_split_layout(): void
    {
        foreach (['left', 'center', 'right'] as $align) {
            $html = Blade::render('<tedi:sheet-footer align="'.$align.'">x</tedi:sheet-footer>');
            $this->assertHasClass('tedi-sheet__footer--'.$align, $html, on: 'tedi-sheet__footer');
            $this->assertMissingClass('tedi-sheet__footer--split', $html, on: 'tedi-sheet__footer');
        }

        $split = Blade::render('<tedi:sheet-footer align="center" full-width>x<x-slot:end>Rohkem</x-slot:end> </tedi:sheet-footer>');
        $this->assertHasClass('tedi-sheet__footer--split', $split, on: 'tedi-sheet__footer');
        $this->assertMissingClass('tedi-sheet__footer--center', $split, on: 'tedi-sheet__footer');
        $this->assertHasClass('tedi-sheet__footer--full-width', $split, on: 'tedi-sheet__footer');
        $this->assertHasClass('tedi-sheet__footer-side--end', $split, on: 'tedi-sheet__footer-side--end');
    }

    public function test_closer_wraps_its_child_in_a_closing_click(): void
    {
        $html = Blade::render('<tedi:sheet-closer><button type="button">Tühista</button></tedi:sheet-closer>');

        $this->assertMatchesRegularExpression('/<span\s+data-tedi-contents x-on:click="hide\(\)"><button type="button">Tühista<\/button><\/span>/', $html);
    }
}
