<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the overlay tooltip family:
 * <tedi:tooltip>, <tedi:tooltip-trigger>, <tedi:tooltip-content> and
 * <tedi:info-tooltip>.
 *
 * Unions taken from the Angular `export type` declarations:
 *   TooltipPosition = OverlayPosition  (12 side[-align] + auto[-start|-end])
 *   TooltipOpenWith = hover | click | both | none
 *   TooltipWidth    = none | small | medium | large
 */
class TooltipComponentsTest extends TestCase
{
    /** @return string[] */
    private function positions(): array
    {
        return [
            'auto', 'auto-start', 'auto-end',
            'top', 'top-start', 'top-end',
            'bottom', 'bottom-start', 'bottom-end',
            'right', 'right-start', 'right-end',
            'left', 'left-start', 'left-end',
        ];
    }

    private function tooltip(string $attributes = '', string $triggerAttributes = '', string $contentAttributes = ''): string
    {
        return Blade::render(
            '<tedi:tooltip '.$attributes.'>'
            .'<tedi:tooltip-trigger '.$triggerAttributes.'>Trigger</tedi:tooltip-trigger>'
            .'<tedi:tooltip-content '.$contentAttributes.'>Content</tedi:tooltip-content>'
            .'</tedi:tooltip>'
        );
    }

    // -- tooltip (root) --------------------------------------------------

    public function test_tooltip_root_is_the_angular_element_selector(): void
    {
        $html = $this->tooltip();

        // CONVENTIONS.md §4: `tedi-tooltip { display: contents }` keys on the
        // element, not a class, so the root must be the literal element.
        $this->assertStringContainsString('<tedi-tooltip', $html);
        $this->assertStringContainsString('<tedi-tooltip-trigger', $html);
        $this->assertStringContainsString('<tedi-tooltip-content', $html);
    }

    public function test_tooltip_root_emits_no_class_of_its_own(): void
    {
        // Angular's TooltipComponent has no host class binding, and the SCSS
        // styles the element selector only.
        $this->assertMissingClass('tedi-tooltip', $this->tooltip());
    }

    public function test_tooltip_passes_every_position_to_the_overlay_engine(): void
    {
        foreach ($this->positions() as $position) {
            $html = $this->tooltip('position="'.$position.'"');

            $this->assertStringContainsString("placement: '".$position."'", $html);
        }
    }

    public function test_tooltip_defaults_match_angular(): void
    {
        $html = $this->tooltip();

        $this->assertStringContainsString("placement: 'top'", $html);
        $this->assertStringContainsString('offset: 4', $html);
        $this->assertStringContainsString('preventOverflow: true', $html);
        $this->assertStringContainsString("openWith: 'both'", $html);
        $this->assertStringContainsString('hoverDelay: 100', $html);
        $this->assertStringContainsString('open: false', $html);
    }

    public function test_tooltip_forwards_offset_timeout_and_prevent_overflow(): void
    {
        $html = $this->tooltip(':offset="0" :timeout-delay="250" :prevent-overflow="false" :open="true"');

        $this->assertStringContainsString('offset: 0', $html);
        $this->assertStringContainsString('hoverDelay: 250', $html);
        $this->assertStringContainsString('preventOverflow: false', $html);
        $this->assertStringContainsString('open: true', $html);
    }

    public function test_tooltip_content_is_the_accessible_description_target(): void
    {
        // Angular #585: the visible content carries role=tooltip + the shared
        // id; there is no duplicated .sr-only mirror.
        $this->assertStringNotContainsString('sr-only', $this->tooltip());

        $html = $this->tooltip('description-id="tip-1"', 'described-by="tip-1"', 'description-id="tip-1"');

        $this->assertMatchesRegularExpression(
            '/<tedi-tooltip-content[^>]*\bid="tip-1"[^>]*\brole="tooltip"/s',
            $html
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<tedi-tooltip-content[^>]*aria-hidden/s',
            $html
        );
    }

    public function test_tooltip_content_generates_a_description_id_when_omitted(): void
    {
        $html = $this->tooltip();

        $this->assertMatchesRegularExpression(
            '/<tedi-tooltip-content[^>]*\bid="tedi-tooltip-[0-9a-f]{8}"[^>]*\brole="tooltip"/s',
            $html
        );
    }

    // -- tooltip-trigger -------------------------------------------------

    public function test_trigger_clickable_class_only_for_open_with_click(): void
    {
        foreach (['hover', 'click', 'both', 'none'] as $openWith) {
            $html = $this->tooltip('open-with="'.$openWith.'"');

            if ($openWith === 'click') {
                $this->assertHasClass('tedi-tooltip-trigger--clickable', $html, 'tedi-tooltip-trigger--clickable');
            } else {
                $this->assertMissingClass('tedi-tooltip-trigger--clickable', $html);
            }
        }
    }

    public function test_trigger_binds_hover_handlers_only_for_hover_and_both(): void
    {
        foreach (['hover', 'both'] as $openWith) {
            $html = $this->tooltip('open-with="'.$openWith.'"');

            $this->assertStringContainsString('x-on:mouseenter="hoverOpen()"', $html);
            $this->assertStringContainsString('x-on:mouseleave="hoverClose()"', $html);
            $this->assertStringContainsString('x-on:focusin="hoverOpen()"', $html);
            $this->assertStringContainsString('x-on:focusout="hoverClose()"', $html);
        }

        foreach (['click', 'none'] as $openWith) {
            $html = $this->tooltip('open-with="'.$openWith.'"');

            $this->assertStringNotContainsString('hoverOpen()', $html);
        }
    }

    public function test_trigger_binds_click_only_for_click_and_both(): void
    {
        foreach (['click', 'both'] as $openWith) {
            $this->assertStringContainsString(
                'x-on:click="toggle()"', $this->tooltip('open-with="'.$openWith.'"')
            );
        }

        foreach (['hover', 'none'] as $openWith) {
            $this->assertStringNotContainsString(
                'x-on:click="toggle()"', $this->tooltip('open-with="'.$openWith.'"')
            );
        }
    }

    public function test_trigger_is_the_overlay_anchor(): void
    {
        $this->assertStringContainsString('x-ref="trigger"', $this->tooltip());
    }

    /**
     * CONVENTIONS.md §3: the child's `@aware` fallback must equal the parent's
     * `@props` default, so omitting the prop and passing the default render
     * identically on the trigger (content generates a fresh id each render).
     */
    public function test_trigger_aware_default_agrees_with_tooltip_props_default(): void
    {
        $omitted = $this->tooltip();
        $explicit = $this->tooltip('open-with="both"');

        preg_match('/<tedi-tooltip-trigger\b[^>]*>.*?<\/tedi-tooltip-trigger>/s', $omitted, $a);
        preg_match('/<tedi-tooltip-trigger\b[^>]*>.*?<\/tedi-tooltip-trigger>/s', $explicit, $b);

        $this->assertSame($a[0] ?? '', $b[0] ?? '');
        $this->assertMissingClass('tedi-tooltip-trigger--clickable', $omitted);
    }

    public function test_text_trigger_synthesises_the_underlined_span(): void
    {
        $html = $this->tooltip('', ':text="true"');

        $this->assertHasClass('tedi-tooltip-trigger__text', $html, 'tedi-tooltip-trigger__text');
        $this->assertHasClass('tedi-tooltip-trigger--focus', $html, 'tedi-tooltip-trigger__text');
        $this->assertStringContainsString('tabindex="0"', $html);
    }

    public function test_text_trigger_binds_enter_and_space_when_click_opens(): void
    {
        foreach (['click', 'both'] as $openWith) {
            $html = $this->tooltip('open-with="'.$openWith.'"', ':text="true"');

            $this->assertStringContainsString('x-on:keydown.enter.prevent="toggle()"', $html);
            $this->assertStringContainsString('x-on:keydown.space.prevent="toggle()"', $html);
        }

        foreach (['hover', 'none'] as $openWith) {
            $html = $this->tooltip('open-with="'.$openWith.'"', ':text="true"');

            $this->assertStringNotContainsString('keydown.enter', $html);
        }
    }

    public function test_element_trigger_synthesises_nothing(): void
    {
        $html = $this->tooltip();

        $this->assertMissingClass('tedi-tooltip-trigger__text', $html, 'tedi-tooltip-trigger__text');
        $this->assertStringNotContainsString('tabindex="0"', $html);
        $this->assertStringNotContainsString('keydown.enter', $html);
    }

    public function test_non_interactive_text_trigger_synthesises_nothing(): void
    {
        // Angular bails out of ngAfterContentChecked entirely when
        // `interactive` is false — no span, no tabindex, no aria-describedby.
        $html = $this->tooltip('', ':text="true" :interactive="false" described-by="tip-1"');

        $this->assertMissingClass('tedi-tooltip-trigger__text', $html, 'tedi-tooltip-trigger__text');
        $this->assertStringNotContainsString('tabindex="0"', $html);
        $this->assertStringNotContainsString('aria-describedby', $html);
    }

    public function test_described_by_lands_on_the_span_for_a_text_trigger(): void
    {
        $html = $this->tooltip('', ':text="true" described-by="tip-1"');

        // On the synthesized text span, mirroring Angular, and NOT on the
        // tedi-tooltip-trigger element itself.
        $this->assertMatchesRegularExpression(
            '/<span[^>]*class="tedi-tooltip-trigger__text[^"]*"[^>]*aria-describedby="tip-1"/s', $html
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<tedi-tooltip-trigger[^>]*aria-describedby/s', $html
        );
    }

    public function test_described_by_lands_on_the_root_for_an_element_trigger(): void
    {
        $html = $this->tooltip('', 'described-by="tip-1"');

        // No span to synthesize, so it falls back to the trigger element.
        $this->assertMatchesRegularExpression(
            '/<tedi-tooltip-trigger[^>]*aria-describedby="tip-1"/s', $html
        );
        $this->assertMissingClass('tedi-tooltip-trigger__text', $html, 'tedi-tooltip-trigger__text');
    }

    // -- tooltip-content -------------------------------------------------

    public function test_content_max_width_classes(): void
    {
        foreach (['none', 'small', 'medium', 'large'] as $maxWidth) {
            $html = $this->tooltip('', '', 'max-width="'.$maxWidth.'"');

            $this->assertHasClass('tedi-tooltip-content', $html, 'tedi-tooltip-content');
            $this->assertHasClass('tedi-tooltip-content--'.$maxWidth, $html, 'tedi-tooltip-content');
        }
    }

    public function test_content_defaults_to_medium(): void
    {
        $this->assertHasClass('tedi-tooltip-content--medium', $this->tooltip(), 'tedi-tooltip-content');
    }

    public function test_content_renders_the_panel_and_arrow(): void
    {
        $html = $this->tooltip();

        $this->assertHasClass('tedi-tooltip__container', $html, 'tedi-tooltip__container');
        $this->assertHasClass('tedi-tooltip__arrow', $html, 'tedi-tooltip__arrow');
        $this->assertStringContainsString('x-ref="panel"', $html);
        $this->assertStringContainsString('x-ref="arrow"', $html);
    }

    /**
     * CONVENTIONS.md §8/§11: the panel is x-show'n, so it is in the DOM with
     * its real class list while closed — otherwise these parity assertions
     * would have nothing to inspect.
     */
    public function test_panel_is_present_while_closed_and_cloaked(): void
    {
        $html = $this->tooltip();

        $this->assertStringContainsString('x-show="open"', $html);
        $this->assertStringContainsString('x-cloak', $html);
    }

    /**
     * Regression: the panel and arrow must be phrasing content.
     *
     * A `<div>` inside a `<p>` makes the HTML parser auto-close the paragraph
     * and hoist the div out — which lifts the panel out of `<tedi-tooltip>` and
     * therefore out of the Alpine scope, so the tooltip never opens. The
     * text-trigger story puts a tooltip inside running text, so this is the
     * primary use case, not an edge case. Caught in the browser, not by a
     * class assertion.
     */
    public function test_panel_is_phrasing_content_so_it_survives_inside_a_paragraph(): void
    {
        $html = $this->tooltip();

        $this->assertMatchesRegularExpression('/<span[^>]*class="tedi-tooltip__container"/s', $html);
        $this->assertMatchesRegularExpression('/<span class="tedi-tooltip__arrow"/s', $html);
        $this->assertStringNotContainsString('<div', $html);
    }

    public function test_panel_has_no_whitespace_text_nodes_around_its_children(): void
    {
        // Inline elements render whitespace as a visible space inside a <p>.
        $html = $this->tooltip();

        $this->assertStringContainsString(
            '><span class="tedi-tooltip__arrow" aria-hidden="true" x-ref="arrow"></span><tedi-tooltip-content', $html
        );
    }

    public function test_panel_placement_binding_follows_the_class_attribute(): void
    {
        $html = $this->tooltip();

        // CONVENTIONS.md §4: bound attributes come after the real class list.
        $this->assertLessThan(
            strpos($html, 'x-bind:data-placement'),
            strpos($html, 'class="tedi-tooltip__container"')
        );
    }

    public function test_only_the_arrow_is_aria_hidden(): void
    {
        // Angular #585: the container and content are visible to AT; only the
        // decorative arrow is aria-hidden.
        $html = $this->tooltip();

        $this->assertSame(1, substr_count($html, 'aria-hidden="true"'));
        $this->assertMatchesRegularExpression(
            '/<span class="tedi-tooltip__arrow" aria-hidden="true"/',
            $html
        );
        $this->assertDoesNotMatchRegularExpression(
            '/tedi-tooltip__container[^>]*aria-hidden/',
            $html
        );
    }

    // -- consumer attribute merging --------------------------------------

    public function test_consumer_classes_merge_on_every_part(): void
    {
        $html = $this->tooltip('class="a"', 'class="b"', 'class="c"');

        $this->assertHasClass('a', $html, 'a');
        $this->assertHasClass('b', $html, 'b');
        $this->assertHasClass('tedi-tooltip-content', $html, 'c');
    }

    // -- info-tooltip ----------------------------------------------------

    public function test_info_tooltip_root_and_class(): void
    {
        $html = Blade::render('<tedi:info-tooltip>Info</tedi:info-tooltip>');

        $this->assertStringContainsString('<tedi-info-tooltip', $html);
        $this->assertHasClass('tedi-info-tooltip', $html, 'tedi-info-tooltip');
    }

    public function test_info_tooltip_renders_the_info_button_as_trigger(): void
    {
        $html = Blade::render('<tedi:info-tooltip>Info</tedi:info-tooltip>');

        $this->assertHasClass('tedi-info-button', $html, 'tedi-info-button');
        $this->assertMissingClass('tedi-info-button--inverted', $html, 'tedi-info-button');
    }

    public function test_info_tooltip_color_classes(): void
    {
        foreach (['primary', 'inverted'] as $color) {
            $html = Blade::render('<tedi:info-tooltip color="'.$color.'">Info</tedi:info-tooltip>');

            if ($color === 'inverted') {
                $this->assertHasClass('tedi-info-button--inverted', $html, 'tedi-info-button');
            } else {
                $this->assertMissingClass('tedi-info-button--inverted', $html, 'tedi-info-button');
            }
        }
    }

    public function test_info_tooltip_max_width_classes(): void
    {
        foreach (['none', 'small', 'medium', 'large'] as $maxWidth) {
            $html = Blade::render('<tedi:info-tooltip max-width="'.$maxWidth.'">Info</tedi:info-tooltip>');

            $this->assertHasClass('tedi-tooltip-content--'.$maxWidth, $html, 'tedi-tooltip-content');
        }
    }

    public function test_info_tooltip_positions(): void
    {
        foreach ($this->positions() as $position) {
            $html = Blade::render('<tedi:info-tooltip position="'.$position.'">Info</tedi:info-tooltip>');

            $this->assertStringContainsString("placement: '".$position."'", $html);
        }
    }

    public function test_info_tooltip_open_with(): void
    {
        foreach (['hover', 'click', 'both', 'none'] as $openWith) {
            $html = Blade::render('<tedi:info-tooltip open-with="'.$openWith.'">Info</tedi:info-tooltip>');

            $this->assertStringContainsString("openWith: '".$openWith."'", $html);

            if ($openWith === 'click') {
                $this->assertHasClass('tedi-tooltip-trigger--clickable', $html, 'tedi-tooltip-trigger--clickable');
            } else {
                $this->assertMissingClass('tedi-tooltip-trigger--clickable', $html);
            }
        }
    }

    public function test_info_tooltip_aria_label(): void
    {
        $html = Blade::render('<tedi:info-tooltip aria-label="Lisainfo">Info</tedi:info-tooltip>');

        $this->assertStringContainsString('aria-label="Lisainfo"', $html);
    }

    public function test_info_tooltip_wires_description_to_the_button(): void
    {
        $html = Blade::render('<tedi:info-tooltip>Selgitus</tedi:info-tooltip>');

        preg_match('/<tedi-tooltip-content[^>]*\bid="(tedi-tooltip-[0-9a-f]{8})"[^>]*\brole="tooltip"/s', $html, $m);

        $this->assertNotEmpty($m, 'Expected content to carry role=tooltip and a shared id.');
        $this->assertStringContainsString('aria-describedby="'.$m[1].'"', $html);
        $this->assertStringNotContainsString('class="sr-only"', $html);
        $this->assertStringContainsString('Selgitus', $html);
    }

    public function test_info_tooltip_forwards_defaults_of_the_tooltip(): void
    {
        // Angular's template forwards only position and openWith; offset,
        // preventOverflow and timeoutDelay keep the tooltip's own defaults.
        $html = Blade::render('<tedi:info-tooltip>Info</tedi:info-tooltip>');

        $this->assertStringContainsString('offset: 4', $html);
        $this->assertStringContainsString('preventOverflow: true', $html);
        $this->assertStringContainsString('hoverDelay: 100', $html);
    }
}
