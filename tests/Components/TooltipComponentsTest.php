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

    public function test_tooltip_renders_no_sr_only_copy_of_the_content(): void
    {
        // Angular 9 (#585) dropped the sr-only mirror: the content is the description.
        $html = $this->tooltip('description-id="tip-1"');

        $this->assertStringNotContainsString('sr-only', $html);
    }

    public function test_tooltip_scopes_an_alpine_id_only_when_no_description_id_is_given(): void
    {
        $this->assertStringContainsString("x-id=\"['tedi-tooltip']\"", $this->tooltip());
        $this->assertStringNotContainsString('x-id=', $this->tooltip('description-id="tip-1"'));
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
     * identically.
     */
    public function test_trigger_aware_default_agrees_with_tooltip_props_default(): void
    {
        $this->assertSame($this->tooltip(), $this->tooltip('open-with="both"'));
    }

    public function test_text_trigger_synthesises_the_underlined_span(): void
    {
        $html = $this->tooltip('', ':text="true"');

        $this->assertHasClass('tedi-tooltip-trigger__text', $html, 'tedi-tooltip-trigger__text');
        $this->assertHasClass('tedi-tooltip-trigger--focus', $html, 'tedi-tooltip-trigger__text');
        $this->assertStringContainsString('tabindex="0"', $html);
    }

    public function test_element_trigger_synthesises_nothing(): void
    {
        $html = $this->tooltip();

        $this->assertMissingClass('tedi-tooltip-trigger__text', $html, 'tedi-tooltip-trigger__text');
        $this->assertStringNotContainsString('tabindex="0"', $html);
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

    public function test_text_trigger_span_is_a_button(): void
    {
        $html = $this->tooltip('', ':text="true"');

        $this->assertMatchesRegularExpression(
            '/<span[^>]*class="tedi-tooltip-trigger__text[^"]*"[^>]*role="button"/s', $html
        );
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

    public function test_text_trigger_span_points_at_the_description_id_from_the_tooltip(): void
    {
        $html = $this->tooltip('description-id="tip-2"', ':text="true"');
        $this->assertStringContainsString('aria-describedby="tip-2"', $html);

        // No id known server-side: bound to the Alpine id the content also uses.
        $html = $this->tooltip('', ':text="true"');
        $this->assertStringContainsString("x-bind:aria-describedby=\"\$id('tedi-tooltip')\"", $html);
        $this->assertStringContainsString("x-bind:id=\"\$id('tedi-tooltip')\"", $html);
    }

    public function test_element_trigger_resolves_aria_describedby_in_the_browser(): void
    {
        $html = $this->tooltip('description-id="tip-1"');

        // Angular sets it on the resolved focusable child, which only the
        // browser can reach; it is never put on the trigger element itself.
        $this->assertDoesNotMatchRegularExpression('/<tedi-tooltip-trigger[^>]*\saria-describedby=/s', $html);
        $this->assertStringContainsString("const id = 'tip-1';", $html);
        $this->assertStringContainsString("setAttribute('aria-describedby', id)", $html);

        $html = $this->tooltip('', 'described-by="own"');
        $this->assertStringContainsString("const id = 'own';", $html);

        $html = $this->tooltip('', ':interactive="false"');
        $this->assertStringNotContainsString('aria-describedby', $html);
    }

    public function test_element_trigger_reapplies_aria_describedby_after_a_livewire_morph(): void
    {
        $html = $this->tooltip('description-id="tip-1"');

        // Livewire's morph strips the client-set attribute and x-init does not
        // re-run, so the hook re-applies it; guarded when Livewire is absent,
        // and unregistered through the tooltip scope's destroy().
        $this->assertStringContainsString("window.Livewire?.hook?.('morph.updated'", $html);
        $this->assertStringContainsString('destroy = function () { off();', $html);

        // The text span needs no hook: its attribute is server-rendered.
        $html = $this->tooltip('description-id="tip-1"', ':text="true"');
        $this->assertStringNotContainsString('morph.updated', $html);
    }

    public function test_trigger_activates_on_enter_and_space_only_when_click_opens(): void
    {
        foreach (['click' => true, 'both' => true, 'hover' => false, 'none' => false] as $openWith => $expected) {
            $html = $this->tooltip('open-with="'.$openWith.'"');

            foreach (['x-on:keydown.enter=', 'x-on:keydown.space='] as $binding) {
                $expected
                    ? $this->assertStringContainsString($binding, $html, $openWith)
                    : $this->assertStringNotContainsString($binding, $html, $openWith);
            }
        }

        // Native buttons/links already click on those keys, so they are skipped.
        $this->assertStringContainsString("matches('button, a[href]')", $this->tooltip());
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

    public function test_content_is_the_tooltip_and_only_the_arrow_is_aria_hidden(): void
    {
        $html = $this->tooltip('description-id="tip-1"');

        $this->assertSame(1, substr_count($html, 'aria-hidden="true"'));
        $this->assertMatchesRegularExpression('/<span class="tedi-tooltip__arrow" aria-hidden="true"/', $html);
        $this->assertMatchesRegularExpression('/<tedi-tooltip-content[^>]*id="tip-1"[^>]*role="tooltip"/s', $html);
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

    public function test_info_tooltip_describes_the_button_with_its_content(): void
    {
        $html = Blade::render('<tedi:info-tooltip>Selgitus</tedi:info-tooltip>');

        preg_match('/<tedi-tooltip-content[^>]*id="(tedi-tooltip-[0-9a-f]{8})"[^>]*role="tooltip"/s', $html, $m);

        $this->assertNotEmpty($m, 'Expected the content to carry the description id.');
        $this->assertMatchesRegularExpression('/<button[^>]*aria-describedby="'.$m[1].'"/s', $html);
        $this->assertStringNotContainsString('sr-only', $html);
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
