<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the five components ported from Angular's
 * `community/` entry point (CONVENTIONS.md §12).
 *
 * The prop unions come from the community `export type` declarations:
 *   FloatingButtonVariant / Size / Axis, ChoicegroupVariant,
 *   TableOfContentsPosition / Breakpoint, ValidationState.
 *
 * table-of-contents is the one component here whose classes are not
 * `tedi-`-prefixed, so IntegrityTest's stylesheet guardrails skip it — these
 * assertions are its only coverage.
 */
class CommunityComponentsTest extends TestCase
{
    // -- floating-button ------------------------------------------------

    public function test_floating_button_renders_a_button_with_the_selector_attribute(): void
    {
        $html = Blade::render('<tedi:floating-button>Tagasiside</tedi:floating-button>');

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('tedi-floating-button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertHasClass('tedi-floating-button', $html, 'tedi-floating-button');
    }

    public function test_floating_button_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:floating-button variant="'.$variant.'">x</tedi:floating-button>');

            $this->assertHasClass('tedi-floating-button--'.$variant, $html);
        }
    }

    public function test_floating_button_size_classes(): void
    {
        foreach (['default', 'large'] as $size) {
            $html = Blade::render('<tedi:floating-button size="'.$size.'">x</tedi:floating-button>');

            $this->assertHasClass('tedi-floating-button--'.$size, $html);
        }
    }

    public function test_floating_button_defaults_match_angular(): void
    {
        $html = Blade::render('<tedi:floating-button>x</tedi:floating-button>');

        $this->assertHasClass('tedi-floating-button--primary', $html);
        $this->assertHasClass('tedi-floating-button--default', $html);
    }

    public function test_floating_button_vertical_axis_emits_a_class_and_horizontal_does_not(): void
    {
        $vertical = Blade::render('<tedi:floating-button axis="vertical">x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--vertical', $vertical);

        // Dropped: TEDI ships no `--horizontal` rule (CONVENTIONS.md §4).
        $horizontal = Blade::render('<tedi:floating-button axis="horizontal">x</tedi:floating-button>');
        $this->assertMissingClass('tedi-floating-button--horizontal', $horizontal);
        $this->assertMissingClass('tedi-floating-button--vertical', $horizontal);
    }

    public function test_floating_button_padding_modifiers_mirror_base_button_directive(): void
    {
        $plain = Blade::render('<tedi:floating-button>x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--pl', $plain);
        $this->assertHasClass('tedi-floating-button--pr', $plain);

        $start = Blade::render('<tedi:floating-button icon-start="add">x</tedi:floating-button>');
        $this->assertMissingClass('tedi-floating-button--pl', $start);
        $this->assertHasClass('tedi-floating-button--pr', $start);

        $end = Blade::render('<tedi:floating-button icon-end="arrow_forward">x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--pl', $end);
        $this->assertMissingClass('tedi-floating-button--pr', $end);

        $only = Blade::render('<tedi:floating-button icon-only icon-start="add" aria-label="Lisa" />');
        $this->assertHasClass('tedi-floating-button--icon-only', $only);
        $this->assertMissingClass('tedi-floating-button--pl', $only);
        $this->assertMissingClass('tedi-floating-button--pr', $only);
    }

    public function test_floating_button_disabled(): void
    {
        $html = Blade::render('<tedi:floating-button disabled>x</tedi:floating-button>');

        $this->assertStringContainsString('disabled', $html);
    }

    // -- choicegroup ----------------------------------------------------

    public function test_choicegroup_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:choicegroup variant="'.$variant.'">x</tedi:choicegroup>');

            $this->assertHasClass('tedi-choicegroup', $html, 'tedi-choicegroup');
            $this->assertHasClass('tedi-choicegroup--'.$variant, $html, 'tedi-choicegroup');
        }
    }

    public function test_choicegroup_stacked_only_at_zero_spacing(): void
    {
        $stacked = Blade::render('<tedi:choicegroup :spacing="0">x</tedi:choicegroup>');
        $this->assertHasClass('tedi-choicegroup--stacked', $stacked, 'tedi-choicegroup');

        foreach (['<tedi:choicegroup>x</tedi:choicegroup>', '<tedi:choicegroup :spacing="8">x</tedi:choicegroup>'] as $template) {
            $this->assertMissingClass('tedi-choicegroup--stacked', Blade::render($template), 'tedi-choicegroup');
        }
    }

    public function test_choicegroup_plain_when_indicator_is_off(): void
    {
        $plain = Blade::render('<tedi:choicegroup :has-indicator="false">x</tedi:choicegroup>');
        $this->assertHasClass('tedi-choicegroup--plain', $plain, 'tedi-choicegroup');

        $default = Blade::render('<tedi:choicegroup>x</tedi:choicegroup>');
        $this->assertMissingClass('tedi-choicegroup--plain', $default, 'tedi-choicegroup');
    }

    // -- file-dropzone --------------------------------------------------

    public function test_file_dropzone_renders_the_custom_element_and_a_native_input(): void
    {
        $html = Blade::render('<tedi:file-dropzone />');

        $this->assertStringContainsString('<tedi-file-dropzone', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*type="file"/', $html);
        $this->assertHasClass('tedi-file-dropzone__input', $html, 'tedi-file-dropzone__input');
        $this->assertHasClass('tedi-file-dropzone', $html, 'tedi-file-dropzone');
    }

    public function test_file_dropzone_binds_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:file-dropzone wire:model="attachment" />');

        $this->assertMatchesRegularExpression('/<input[^>]*wire:model="attachment"/', $html,
            'wire:model must be on the <input>, not the wrapper.');
    }

    public function test_file_dropzone_state_classes(): void
    {
        foreach (['valid', 'invalid'] as $state) {
            $html = Blade::render('<tedi:file-dropzone state="'.$state.'" />');

            $this->assertHasClass('tedi-file-dropzone--'.$state, $html, 'tedi-file-dropzone');
        }

        $none = Blade::render('<tedi:file-dropzone state="none" />');
        $this->assertMissingClass('tedi-file-dropzone--valid', $none, 'tedi-file-dropzone');
        $this->assertMissingClass('tedi-file-dropzone--invalid', $none, 'tedi-file-dropzone');
        $this->assertMissingClass('tedi-file-dropzone--none', $none, 'tedi-file-dropzone');
    }

    public function test_file_dropzone_has_error_overrides_the_state(): void
    {
        $html = Blade::render('<tedi:file-dropzone has-error state="valid" />');

        $this->assertHasClass('tedi-file-dropzone--invalid', $html, 'tedi-file-dropzone');
        $this->assertMissingClass('tedi-file-dropzone--valid', $html, 'tedi-file-dropzone');
    }

    public function test_file_dropzone_disabled_class_and_attribute(): void
    {
        $html = Blade::render('<tedi:file-dropzone disabled />');

        $this->assertHasClass('tedi-file-dropzone--disabled', $html, 'tedi-file-dropzone');
        $this->assertMatchesRegularExpression('/<input[^>]*disabled/', $html);
    }

    public function test_file_dropzone_native_attributes(): void
    {
        $html = Blade::render('<tedi:file-dropzone accept=".pdf,.docx" multiple upload-folder name="fail" />');

        $this->assertStringContainsString('accept=".pdf,.docx"', $html);
        $this->assertStringContainsString('multiple', $html);
        $this->assertStringContainsString('webkitdirectory', $html);
        $this->assertStringContainsString('name="fail"', $html);
    }

    public function test_file_dropzone_hint_is_generated_from_accept_and_max_size(): void
    {
        // getDefaultHelpers() + formatBytes(): IEC by default, so 5 MiB.
        $html = Blade::render('<tedi:file-dropzone accept=".pdf,.docx" :max-size="5242880" />');

        $this->assertStringContainsString('.pdf, .docx', $html);
        $this->assertStringContainsString('5 MiB', $html);

        $si = Blade::render('<tedi:file-dropzone :max-size="5242880" size-display-standard="SI" />');
        $this->assertStringContainsString('5.24 MB', $si);
    }

    public function test_file_dropzone_renders_no_hint_without_accept_or_max_size(): void
    {
        $html = Blade::render('<tedi:file-dropzone />');

        $this->assertStringNotContainsString('tedi-feedback-text', $html);
    }

    public function test_file_dropzone_file_list(): void
    {
        $html = Blade::render(
            '<tedi:file-dropzone :files="[[\'name\' => \'avaldus.pdf\', \'size\' => 943718]]" />'
        );

        $this->assertHasClass('tedi-file-dropzone__file-list', $html, 'tedi-file-dropzone__file-list');
        $this->assertStringContainsString('avaldus.pdf', $html);
        $this->assertStringContainsString('921.6 KiB', $html);
    }

    public function test_file_dropzone_error_message(): void
    {
        $html = Blade::render('<tedi:file-dropzone error="Fail on liiga suur" />');

        $this->assertStringContainsString('Fail on liiga suur', $html);
    }

    // -- table-of-contents ----------------------------------------------

    public function test_table_of_contents_base_and_position_classes(): void
    {
        foreach (['default', 'fixed', 'sticky'] as $position)  {
            $html = Blade::render('<tedi:table-of-contents heading="Sisukord" position="'.$position.'" />');

            $this->assertHasClass('table-of-contents', $html, 'table-of-contents');
            $this->assertHasClass('table-of-contents--position-'.$position, $html, 'table-of-contents');
        }
    }

    public function test_table_of_contents_modal_breakpoint_classes(): void
    {
        foreach (['mobile', 'tablet', 'desktop'] as $breakpoint) {
            $html = Blade::render('<tedi:table-of-contents heading="Sisukord" modal-breakpoint="'.$breakpoint.'" />');

            $this->assertHasClass('table-of-contents--modal-breakpoint-'.$breakpoint, $html, 'table-of-contents');
            $this->assertHasClass('table-of-contents__footer--modal-breakpoint-'.$breakpoint, $html, 'table-of-contents__footer');
        }
    }

    public function test_table_of_contents_never_breakpoint_emits_no_nav_modifier(): void
    {
        $html = Blade::render('<tedi:table-of-contents heading="Sisukord" modal-breakpoint="never" />');

        $tokens = $this->classesOf($html, 'table-of-contents');

        foreach (['mobile', 'tablet', 'desktop', 'never'] as $breakpoint) {
            $this->assertNotContains('table-of-contents--modal-breakpoint-'.$breakpoint, $tokens);
        }
    }

    public function test_table_of_contents_heading_and_aria_label(): void
    {
        $html = Blade::render('<tedi:table-of-contents heading="Sisukord" />');
        $this->assertStringContainsString('Sisukord', $html);
        $this->assertStringContainsString('aria-label="Table of contents"', $html);

        $custom = Blade::render('<tedi:table-of-contents heading="Sisukord" aria-label="Peatükid" />');
        $this->assertStringContainsString('aria-label="Peatükid"', $custom);
    }

    public function test_table_of_contents_item_classes_and_target(): void
    {
        $html = Blade::render('<tedi:table-of-contents-item id-to="ptk-1">Üldsätted</tedi:table-of-contents-item>');

        $this->assertHasClass('table-of-contents__item', $html, 'table-of-contents__item');
        $this->assertMissingClass('table-of-contents__item--active', $html, 'table-of-contents__item');
        $this->assertStringContainsString('data-toc-id="ptk-1"', $html);
        $this->assertStringContainsString('href="#ptk-1"', $html);
        $this->assertHasClass('table-of-contents__item-anchor', $html, 'table-of-contents__item-anchor');
    }

    public function test_table_of_contents_item_active_class(): void
    {
        $html = Blade::render('<tedi:table-of-contents-item id-to="ptk-1" :selected="true">x</tedi:table-of-contents-item>');

        $this->assertHasClass('table-of-contents__item--active', $html, 'table-of-contents__item');
    }

    public function test_table_of_contents_item_anchor_carries_the_neutral_button_classes(): void
    {
        // Angular renders <a tedi-button variant="neutral">, so the anchor gets
        // the full ButtonComponent class list.
        $html = Blade::render('<tedi:table-of-contents-item id-to="ptk-1">x</tedi:table-of-contents-item>');

        foreach (['tedi-button', 'tedi-button--neutral', 'tedi-button--default', 'tedi-button--pl', 'tedi-button--pr'] as $class) {
            $this->assertHasClass($class, $html, 'table-of-contents__item-anchor');
        }
    }
}
