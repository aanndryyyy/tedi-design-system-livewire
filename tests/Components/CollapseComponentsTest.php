<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class CollapseComponentsTest extends TestCase
{
    // -- collapse-button ----------------------------------------------------

    public function test_collapse_button_base_classes(): void
    {
        $html = Blade::render('<tedi:collapse-button />');

        $this->assertHasClass('tedi-collapse-button', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--small', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--inverted', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--icon-only', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--neutral', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--secondary', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--no-underline', $html, 'tedi-collapse-button');
    }

    public function test_collapse_button_renders_attribute_selector_and_type(): void
    {
        $html = Blade::render('<tedi:collapse-button />');

        // `button[tedi-collapse-button]` is an attribute selector — the literal
        // attribute must be on the root (CONVENTIONS.md §4).
        $this->assertSame(1, preg_match('/<button\s[^>]*(?<![-\w])tedi-collapse-button(?![-\w=])/s', $html));
        $this->assertStringContainsString('type="button"', $html);
    }

    public function test_collapse_button_open_modifier(): void
    {
        foreach ([true, false] as $open) {
            $html = Blade::render('<tedi:collapse-button :open="'.($open ? 'true' : 'false').'" />');

            if ($open) {
                $this->assertHasClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');
            } else {
                $this->assertMissingClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');
            }
        }
    }

    public function test_collapse_button_size_union(): void
    {
        foreach (['default', 'small'] as $size) {
            $html = Blade::render('<tedi:collapse-button size="'.$size.'" />');

            if ($size === 'small') {
                $this->assertHasClass('tedi-collapse-button--small', $html, 'tedi-collapse-button');
            } else {
                $this->assertMissingClass('tedi-collapse-button--small', $html, 'tedi-collapse-button');
            }
        }
    }

    /**
     * hostClasses(): `inverted` is ignored when arrowType === 'secondary'.
     */
    public function test_collapse_button_inverted_is_ignored_for_secondary_arrow(): void
    {
        $html = Blade::render('<tedi:collapse-button :inverted="true" arrow-type="default" />');
        $this->assertHasClass('tedi-collapse-button--inverted', $html, 'tedi-collapse-button');

        $html = Blade::render('<tedi:collapse-button :inverted="true" arrow-type="secondary" />');
        $this->assertMissingClass('tedi-collapse-button--inverted', $html, 'tedi-collapse-button');
    }

    public function test_collapse_button_icon_only_arrow_type_union(): void
    {
        $html = Blade::render('<tedi:collapse-button :hide-text="true" arrow-type="default" />');
        $this->assertHasClass('tedi-collapse-button--icon-only', $html, 'tedi-collapse-button');
        $this->assertHasClass('tedi-collapse-button--neutral', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--secondary', $html, 'tedi-collapse-button');

        $html = Blade::render('<tedi:collapse-button :hide-text="true" arrow-type="secondary" />');
        $this->assertHasClass('tedi-collapse-button--icon-only', $html, 'tedi-collapse-button');
        $this->assertHasClass('tedi-collapse-button--secondary', $html, 'tedi-collapse-button');
        $this->assertMissingClass('tedi-collapse-button--neutral', $html, 'tedi-collapse-button');
    }

    /**
     * The arrow-type modifiers only exist in icon-only mode.
     */
    public function test_collapse_button_arrow_type_is_inert_with_visible_text(): void
    {
        foreach (['default', 'secondary'] as $arrowType) {
            $html = Blade::render('<tedi:collapse-button arrow-type="'.$arrowType.'" />');

            $this->assertMissingClass('tedi-collapse-button--icon-only', $html, 'tedi-collapse-button');
            $this->assertMissingClass('tedi-collapse-button--neutral', $html, 'tedi-collapse-button');
            $this->assertMissingClass('tedi-collapse-button--secondary', $html, 'tedi-collapse-button');
        }
    }

    public function test_collapse_button_no_underline_modifier(): void
    {
        $html = Blade::render('<tedi:collapse-button :underline="false" />');
        $this->assertHasClass('tedi-collapse-button--no-underline', $html, 'tedi-collapse-button');

        $html = Blade::render('<tedi:collapse-button :underline="true" />');
        $this->assertMissingClass('tedi-collapse-button--no-underline', $html, 'tedi-collapse-button');

        // No effect in icon-only mode.
        $html = Blade::render('<tedi:collapse-button :underline="false" :hide-text="true" />');
        $this->assertMissingClass('tedi-collapse-button--no-underline', $html, 'tedi-collapse-button');
    }

    public function test_collapse_button_element_classes(): void
    {
        $html = Blade::render('<tedi:collapse-button open-text="Ava" />');
        $this->assertHasClass('tedi-collapse-button__text', $html, 'tedi-collapse-button__text');
        $this->assertHasClass('tedi-collapse-button__icon-pad', $html, 'tedi-collapse-button__icon-pad');
        $this->assertHasClass('tedi-collapse-button__icon', $html, 'tedi-collapse-button__icon');

        $html = Blade::render('<tedi:collapse-button :hide-text="true" arrow-type="secondary" />');
        $this->assertHasClass('tedi-collapse-button__icon-wrapper', $html, 'tedi-collapse-button__icon-wrapper');

        $html = Blade::render('<tedi:collapse-button :hide-text="true" arrow-type="default" />');
        $this->assertMissingClass('tedi-collapse-button__icon-wrapper', $html, 'tedi-collapse-button__icon');
    }

    /** iconSize()/iconVariant(): 24/filled in icon-only mode, 16/outlined otherwise. */
    public function test_collapse_button_icon_size_and_variant(): void
    {
        $html = Blade::render('<tedi:collapse-button />');
        $this->assertHasClass('tedi-icon', $html, 'tedi-collapse-button__icon');
        $this->assertMissingClass('tedi-icon--filled', $html, 'tedi-collapse-button__icon');
        $this->assertStringContainsString('--_tedi-icon-size: var(--icon-02)', $html);

        $html = Blade::render('<tedi:collapse-button :hide-text="true" />');
        $this->assertHasClass('tedi-icon--filled', $html, 'tedi-collapse-button__icon');
        $this->assertStringContainsString('--_tedi-icon-size: var(--icon-05)', $html);
    }

    public function test_collapse_button_label_follows_open_state(): void
    {
        $html = Blade::render('<tedi:collapse-button open-text="Ava" close-text="Sulge" />');
        $this->assertStringContainsString('>Ava</span>', $html);

        $html = Blade::render('<tedi:collapse-button :open="true" open-text="Ava" close-text="Sulge" />');
        $this->assertStringContainsString('>Sulge</span>', $html);
    }

    public function test_collapse_button_aria(): void
    {
        $html = Blade::render('<tedi:collapse-button aria-controls="panel-1" />');
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('aria-controls="panel-1"', $html);
        // Visible text present → no aria-label (WCAG 2.5.3).
        $this->assertStringNotContainsString('aria-label=', $html);

        $html = Blade::render('<tedi:collapse-button :open="true" />');
        $this->assertStringContainsString('aria-expanded="true"', $html);

        // Icon-only falls back to the resolved open/close label.
        $html = Blade::render('<tedi:collapse-button :hide-text="true" open-text="Ava" />');
        $this->assertStringContainsString('aria-label="Ava"', $html);

        $html = Blade::render('<tedi:collapse-button :hide-text="true" aria-label="Toggle" open-text="Ava" />');
        $this->assertStringContainsString('aria-label="Toggle"', $html);
    }

    public function test_collapse_button_declares_own_alpine_state_by_default(): void
    {
        $html = Blade::render('<tedi:collapse-button />');
        $this->assertStringContainsString('x-data="{ tediCollapseButtonOpen: false }"', $html);

        $html = Blade::render('<tedi:collapse-button state="tediCollapseOpen" />');
        $this->assertStringNotContainsString('x-data=', $html);
        $this->assertStringContainsString('tediCollapseOpen = ! tediCollapseOpen', $html);
    }

    public function test_collapse_button_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:collapse-button class="my-own" />');

        $this->assertHasClass('my-own', $html, 'tedi-collapse-button');
        $this->assertHasClass('tedi-collapse-button', $html, 'tedi-collapse-button');
    }

    // -- collapse -----------------------------------------------------------

    public function test_collapse_base_classes(): void
    {
        $html = Blade::render('<tedi:collapse>Sisu</tedi:collapse>');

        $this->assertHasClass('tedi-collapse', $html, 'tedi-collapse');
        $this->assertMissingClass('tedi-collapse--open', $html, 'tedi-collapse');
        $this->assertMissingClass('tedi-collapse--inverted', $html, 'tedi-collapse');
        $this->assertHasClass('tedi-collapse__content', $html, 'tedi-collapse__content');
        $this->assertHasClass('tedi-collapse__extender', $html, 'tedi-collapse__extender');
    }

    public function test_collapse_renders_the_literal_custom_element(): void
    {
        $html = Blade::render('<tedi:collapse>Sisu</tedi:collapse>');

        $this->assertStringContainsString('<tedi-collapse', $html);
        $this->assertStringContainsString('</tedi-collapse>', $html);
    }

    public function test_collapse_default_open_modifier(): void
    {
        $html = Blade::render('<tedi:collapse :default-open="true">Sisu</tedi:collapse>');
        $this->assertHasClass('tedi-collapse--open', $html, 'tedi-collapse');
        $this->assertHasClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');

        $html = Blade::render('<tedi:collapse :default-open="false">Sisu</tedi:collapse>');
        $this->assertMissingClass('tedi-collapse--open', $html, 'tedi-collapse');
        $this->assertMissingClass('tedi-collapse-button--open', $html, 'tedi-collapse-button');
    }

    /** isInvertedActive(): inverted && arrowType !== 'secondary'. */
    public function test_collapse_inverted_is_ignored_for_secondary_arrow(): void
    {
        $html = Blade::render('<tedi:collapse :inverted="true" arrow-type="default">Sisu</tedi:collapse>');
        $this->assertHasClass('tedi-collapse--inverted', $html, 'tedi-collapse');
        $this->assertHasClass('tedi-collapse-button--inverted', $html, 'tedi-collapse-button');

        $html = Blade::render('<tedi:collapse :inverted="true" arrow-type="secondary">Sisu</tedi:collapse>');
        $this->assertMissingClass('tedi-collapse--inverted', $html, 'tedi-collapse');
        $this->assertMissingClass('tedi-collapse-button--inverted', $html, 'tedi-collapse-button');
    }

    public function test_collapse_forwards_size_to_the_button(): void
    {
        foreach (['default', 'small'] as $size) {
            $html = Blade::render('<tedi:collapse size="'.$size.'">Sisu</tedi:collapse>');

            if ($size === 'small') {
                $this->assertHasClass('tedi-collapse-button--small', $html, 'tedi-collapse-button');
            } else {
                $this->assertMissingClass('tedi-collapse-button--small', $html, 'tedi-collapse-button');
            }
        }
    }

    public function test_collapse_forwards_hide_collapse_text_and_arrow_type(): void
    {
        $html = Blade::render('<tedi:collapse :hide-collapse-text="true" arrow-type="default">Sisu</tedi:collapse>');
        $this->assertHasClass('tedi-collapse-button--icon-only', $html, 'tedi-collapse-button');
        $this->assertHasClass('tedi-collapse-button--neutral', $html, 'tedi-collapse-button');

        $html = Blade::render('<tedi:collapse :hide-collapse-text="true" arrow-type="secondary">Sisu</tedi:collapse>');
        $this->assertHasClass('tedi-collapse-button--secondary', $html, 'tedi-collapse-button');
    }

    public function test_collapse_pairs_button_and_content_with_a_generated_id(): void
    {
        $html = Blade::render('<tedi:collapse>Sisu</tedi:collapse>');

        $this->assertSame(1, preg_match('/aria-controls="(collapse-content-[^"]+)"/', $html, $m));
        $this->assertStringContainsString('id="'.$m[1].'"', $html);
    }

    public function test_collapse_content_is_always_in_the_dom(): void
    {
        $html = Blade::render('<tedi:collapse>Peidetud sisu</tedi:collapse>');

        $this->assertStringContainsString('Peidetud sisu', $html);
        $this->assertStringNotContainsString('x-if', $html);
    }

    public function test_collapse_button_binds_to_the_parent_alpine_scope(): void
    {
        $html = Blade::render('<tedi:collapse>Sisu</tedi:collapse>');

        $this->assertStringContainsString('x-data="{ tediCollapseOpen: false }"', $html);
        $this->assertStringNotContainsString('tediCollapseButtonOpen', $html);
    }

    public function test_collapse_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:collapse class="my-own">Sisu</tedi:collapse>');

        $this->assertHasClass('my-own', $html, 'tedi-collapse');
        $this->assertHasClass('tedi-collapse', $html, 'tedi-collapse');
    }

    // -- variant matrix smoke test ------------------------------------------

    public function test_collapse_matrix_renders(): void
    {
        $path = __DIR__.'/../fixtures/matrices/collapse.blade.php';
        $out = Blade::render(file_get_contents($path));

        $this->assertHasClass('tedi-collapse', $out, 'tedi-collapse');
        $this->assertHasClass('tedi-collapse-button', $out, 'tedi-collapse-button');
    }
}
