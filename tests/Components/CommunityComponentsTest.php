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
}
