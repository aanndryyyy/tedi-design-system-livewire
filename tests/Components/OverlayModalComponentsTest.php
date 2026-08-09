<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for overlay/modal — the standalone (`[(open)]`) branch of
 * Angular's ModalComponent. The ModalService / CDK Dialog branch
 * (`tedi-modal--service`, `.tedi-modal-dialog*`) has no Blade equivalent and is
 * not ported, so nothing here asserts it.
 */
class OverlayModalComponentsTest extends TestCase
{
    // -- modal --------------------------------------------------------------

    public function test_modal_base_classes_with_no_props(): void
    {
        $html = Blade::render('<tedi:modal>x</tedi:modal>');

        $this->assertHasClass('tedi-modal', $html, on: 'tedi-modal');
        $this->assertHasClass('tedi-modal--default', $html, on: 'tedi-modal');
        $this->assertHasClass('tedi-modal--sm', $html, on: 'tedi-modal');
        $this->assertHasClass('tedi-modal--center', $html, on: 'tedi-modal');
        $this->assertMissingClass('tedi-modal--open', $html, on: 'tedi-modal');
        $this->assertMissingClass('tedi-modal--service', $html, on: 'tedi-modal');
    }

    public function test_modal_size_classes(): void
    {
        foreach (['default', 'small'] as $size) {
            $html = Blade::render('<tedi:modal size="'.$size.'">x</tedi:modal>');

            $this->assertHasClass('tedi-modal--'.$size, $html, on: 'tedi-modal');
        }
    }

    public function test_modal_preset_width_classes(): void
    {
        foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $width) {
            $html = Blade::render('<tedi:modal width="'.$width.'">x</tedi:modal>');

            $this->assertHasClass('tedi-modal--'.$width, $html, on: 'tedi-modal');
            $this->assertStringNotContainsString('style="width:', $html);
        }
    }

    public function test_modal_custom_width_goes_to_inline_style_and_emits_no_width_class(): void
    {
        $html = Blade::render('<tedi:modal width="800px">x</tedi:modal>');

        $this->assertStringContainsString('style="width: 800px"', $html);

        foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $preset) {
            $this->assertMissingClass('tedi-modal--'.$preset, $html, on: 'tedi-modal');
        }
    }

    public function test_modal_position_classes(): void
    {
        foreach (['center', 'left', 'right'] as $position) {
            $html = Blade::render('<tedi:modal position="'.$position.'">x</tedi:modal>');

            $this->assertHasClass('tedi-modal--'.$position, $html, on: 'tedi-modal');
        }
    }

    public function test_modal_top_position_emits_center_and_top(): void
    {
        $html = Blade::render('<tedi:modal position="top">x</tedi:modal>');

        $this->assertHasClass('tedi-modal--center', $html, on: 'tedi-modal');
        $this->assertHasClass('tedi-modal--top', $html, on: 'tedi-modal');
    }

    /**
     * Angular emits `tedi-modal--bottom`; the vendored SCSS has no rule for it,
     * so CONVENTIONS §4's stylesheet guardrail drops it. `position="bottom"`
     * therefore emits no position class at all.
     */
    public function test_modal_bottom_position_emits_no_position_class(): void
    {
        $html = Blade::render('<tedi:modal position="bottom">x</tedi:modal>');

        foreach (['bottom', 'center', 'top', 'left', 'right'] as $position) {
            $this->assertMissingClass('tedi-modal--'.$position, $html, on: 'tedi-modal');
        }
    }

    public function test_modal_open_emits_open_class_statically(): void
    {
        $html = Blade::render('<tedi:modal :open="true">x</tedi:modal>');

        $this->assertHasClass('tedi-modal--open', $html, on: 'tedi-modal');
        $this->assertStringContainsString('tediModal({ open: true', $html);
    }

    public function test_modal_close_on_backdrop_click_reaches_alpine(): void
    {
        $open = Blade::render('<tedi:modal>x</tedi:modal>');
        $this->assertStringContainsString('closeOnBackdropClick: true', $open);

        $closed = Blade::render('<tedi:modal :close-on-backdrop-click="false">x</tedi:modal>');
        $this->assertStringContainsString('closeOnBackdropClick: false', $closed);
    }

    public function test_modal_renders_backdrop_and_dialog(): void
    {
        $html = Blade::render('<tedi:modal>x</tedi:modal>');

        $this->assertHasClass('tedi-modal__backdrop', $html, on: 'tedi-modal__backdrop');
        $this->assertHasClass('tedi-modal__dialog', $html, on: 'tedi-modal__dialog');
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('aria-modal="true"', $html);
        $this->assertStringContainsString('x-ref="dialog"', $html);
    }

    public function test_modal_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:modal class="my-modal">x</tedi:modal>');

        $this->assertHasClass('my-modal', $html, on: 'tedi-modal');
        $this->assertHasClass('tedi-modal', $html, on: 'tedi-modal');
    }

    // -- modal-header -------------------------------------------------------

    public function test_modal_header_base_class_and_close_button(): void
    {
        $html = Blade::render('<tedi:modal-header><h1>Title</h1></tedi:modal-header>');

        $this->assertHasClass('tedi-modal-header', $html, on: 'tedi-modal-header');
        $this->assertHasClass('tedi-modal-header__head', $html, on: 'tedi-modal-header__head');
        $this->assertHasClass('tedi-modal-header__close', $html, on: 'tedi-closing-button');
        $this->assertMissingClass('tedi-closing-button--small', $html, on: 'tedi-closing-button');
        $this->assertStringContainsString('Title', $html);
    }

    public function test_modal_header_show_close_false_omits_the_button(): void
    {
        $html = Blade::render('<tedi:modal-header :show-close="false"><h1>Title</h1></tedi:modal-header>');

        $this->assertStringNotContainsString('tedi-closing-button', $html);
    }

    public function test_modal_header_close_button_size_override(): void
    {
        $html = Blade::render('<tedi:modal-header close-button-size="small"><h1>T</h1></tedi:modal-header>');

        $this->assertHasClass('tedi-closing-button--small', $html, on: 'tedi-closing-button');
    }

    /**
     * Angular derives the close-button size from MODAL_SIZE; Blade from @aware.
     * The @aware fallback and <tedi:modal>'s @props default are both 'default',
     * so the two paths agree (CONVENTIONS §3).
     */
    public function test_modal_header_close_button_size_tracks_modal_size(): void
    {
        $small = Blade::render('<tedi:modal size="small"><tedi:modal-header><h1>T</h1></tedi:modal-header></tedi:modal>');
        $this->assertHasClass('tedi-closing-button--small', $small, on: 'tedi-closing-button');

        $default = Blade::render('<tedi:modal size="default"><tedi:modal-header><h1>T</h1></tedi:modal-header></tedi:modal>');
        $this->assertMissingClass('tedi-closing-button--small', $default, on: 'tedi-closing-button');

        $implicit = Blade::render('<tedi:modal><tedi:modal-header><h1>T</h1></tedi:modal-header></tedi:modal>');
        $this->assertMissingClass('tedi-closing-button--small', $implicit, on: 'tedi-closing-button');
    }

    public function test_modal_header_explicit_size_wins_over_modal_size(): void
    {
        $html = Blade::render('<tedi:modal size="small"><tedi:modal-header close-button-size="default"><h1>T</h1></tedi:modal-header></tedi:modal>');

        $this->assertMissingClass('tedi-closing-button--small', $html, on: 'tedi-closing-button');
    }

    public function test_modal_header_description_slot_renders_after_head(): void
    {
        $html = Blade::render('<tedi:modal-header><h1>T</h1><x-slot:description><p tedi-modal-description>Selgitus</p></x-slot:description></tedi:modal-header>');

        $this->assertStringContainsString('tedi-modal-description', $html);
        $this->assertGreaterThan(
            strpos($html, 'tedi-modal-header__head'),
            strpos($html, 'tedi-modal-description'),
            'The description must be rendered after the __head row, as in Angular.'
        );
    }

    // -- modal-content / modal-footer ---------------------------------------

    public function test_modal_content_class(): void
    {
        $html = Blade::render('<tedi:modal-content>Sisu</tedi:modal-content>');

        $this->assertHasClass('tedi-modal-content', $html, on: 'tedi-modal-content');
        $this->assertStringContainsString('Sisu', $html);
    }

    public function test_modal_footer_class_and_style_merge(): void
    {
        $html = Blade::render('<tedi:modal-footer style="justify-content: space-between">Nupud</tedi:modal-footer>');

        $this->assertHasClass('tedi-modal-footer', $html, on: 'tedi-modal-footer');
        $this->assertStringContainsString('justify-content: space-between', $html);
    }

    /**
     * CONVENTIONS §4's element-selector rule: the Angular selectors are element
     * selectors, so the Blade roots must be the literal custom elements.
     */
    public function test_roots_render_the_angular_custom_elements(): void
    {
        $html = Blade::render(
            '<tedi:modal><tedi:modal-header><h1>T</h1></tedi:modal-header>'
            .'<tedi:modal-content>C</tedi:modal-content>'
            .'<tedi:modal-footer>F</tedi:modal-footer></tedi:modal>'
        );

        foreach (['<tedi-modal', '<tedi-modal-header', '<tedi-modal-content', '<tedi-modal-footer'] as $tag) {
            $this->assertStringContainsString($tag, $html);
        }
    }
}
