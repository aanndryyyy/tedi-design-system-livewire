<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for overlay/popover — popover, popover-trigger and
 * popover-content. Unions are taken from the Angular sources:
 * `OverlayPosition` (overlay-position.util.ts) and `PopoverWidth`
 * (popover-content.component.ts).
 */
class PopoverComponentsTest extends TestCase
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

    /**
     * The tediOverlay config the root passes to Alpine. `@js()` renders it as
     * `JSON.parse('…')` with every quote unicode-escaped, so the payload is
     * unescaped (as a JSON string) and then decoded, rather than asserted as
     * a substring.
     *
     * @return array<string, mixed>
     */
    private function overlayConfig(string $html): array
    {
        $this->assertSame(1, preg_match("/tediOverlay\(JSON\.parse\('(.*?)'\)\)/", $html, $m),
            'No tediOverlay(...) config found in the rendered popover.');

        $json = json_decode('"'.$m[1].'"', true, 512, JSON_THROW_ON_ERROR);

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    // -- popover ------------------------------------------------------------

    public function test_popover_base_classes(): void
    {
        $html = Blade::render('<tedi:popover>x</tedi:popover>');

        $this->assertHasClass('tedi-popover', $html, 'tedi-popover');
        $this->assertHasClass('tedi-popover__container', $html, 'tedi-popover__container');
        // withArrow defaults to true, withBorder to false.
        $this->assertHasClass('tedi-popover__container--arrow', $html, 'tedi-popover__container');
        $this->assertMissingClass('tedi-popover__container--border', $html, 'tedi-popover__container');
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
    }

    public function test_popover_container_modifier_matrix(): void
    {
        foreach ([true, false] as $withArrow) {
            foreach ([true, false] as $withBorder) {
                $html = Blade::render(sprintf(
                    '<tedi:popover :with-arrow="%s" :with-border="%s">x</tedi:popover>',
                    $withArrow ? 'true' : 'false',
                    $withBorder ? 'true' : 'false',
                ));

                $this->assertHasClass('tedi-popover__container', $html, 'tedi-popover__container');

                $withArrow
                    ? $this->assertHasClass('tedi-popover__container--arrow', $html, 'tedi-popover__container')
                    : $this->assertMissingClass('tedi-popover__container--arrow', $html, 'tedi-popover__container');

                $withBorder
                    ? $this->assertHasClass('tedi-popover__container--border', $html, 'tedi-popover__container')
                    : $this->assertMissingClass('tedi-popover__container--border', $html, 'tedi-popover__container');
            }
        }
    }

    public function test_popover_arrow_is_rendered_only_with_arrow(): void
    {
        $html = Blade::render('<tedi:popover>x</tedi:popover>');
        $this->assertHasClass('tedi-popover__arrow', $html, 'tedi-popover__arrow');
        $this->assertStringContainsString('x-ref="arrow"', $html);

        $html = Blade::render('<tedi:popover :with-arrow="false">x</tedi:popover>');
        $this->assertStringNotContainsString('x-ref="arrow"', $html);
    }

    /**
     * `withArrow` adds 12px on top of tediOverlay's 8px base gap; Angular's
     * `arrowOffset` computed does the same (12 / 0).
     */
    public function test_popover_offset_follows_with_arrow(): void
    {
        $this->assertSame(12, $this->overlayConfig(
            Blade::render('<tedi:popover>x</tedi:popover>')
        )['offset']);

        $this->assertSame(0, $this->overlayConfig(
            Blade::render('<tedi:popover :with-arrow="false">x</tedi:popover>')
        )['offset']);
    }

    /** preventOverflow defaults to false here, unlike dropdown/tooltip. */
    public function test_popover_prevent_overflow_defaults_to_false(): void
    {
        $this->assertFalse($this->overlayConfig(
            Blade::render('<tedi:popover>x</tedi:popover>')
        )['preventOverflow']);

        $this->assertTrue($this->overlayConfig(
            Blade::render('<tedi:popover :prevent-overflow="true">x</tedi:popover>')
        )['preventOverflow']);
    }

    public function test_popover_behaviour_defaults_reach_the_overlay_engine(): void
    {
        $config = $this->overlayConfig(Blade::render('<tedi:popover>x</tedi:popover>'));

        $this->assertTrue($config['dismissible']);
        $this->assertFalse($config['hideOnScroll']);
        $this->assertFalse($config['lockScroll']);
        $this->assertSame(100, $config['hoverDelay']);
        $this->assertSame('click', $config['openWith']);
    }

    public function test_popover_behaviour_props_reach_the_overlay_engine(): void
    {
        $config = $this->overlayConfig(Blade::render(
            '<tedi:popover :dismissible="false" :hide-on-scroll="true" :lock-scroll="true"'
            .' :timeout-delay="250">x</tedi:popover>'
        ));

        $this->assertFalse($config['dismissible']);
        $this->assertTrue($config['hideOnScroll']);
        $this->assertTrue($config['lockScroll']);
        $this->assertSame(250, $config['hoverDelay']);
    }

    /**
     * Every position maps to the side tediOverlay resolves initially, so the
     * arrow's `[data-placement]` CSS matches before Alpine boots. 'auto*'
     * resolves to 'bottom', matching the engine's parsePlacement().
     */
    public function test_popover_static_data_placement_per_position(): void
    {
        foreach ($this->positions() as $position) {
            $side = str_starts_with($position, 'auto') ? 'bottom' : strtok($position, '-');

            $html = Blade::render('<tedi:popover position="'.$position.'">x</tedi:popover>');

            $this->assertStringContainsString('data-placement="'.$side.'"', $html,
                'Position ['.$position.'] should render data-placement ['.$side.'].');
            $this->assertSame($position, $this->overlayConfig($html)['placement']);
        }
    }

    public function test_popover_panel_is_in_the_dom_while_closed(): void
    {
        $html = Blade::render('<tedi:popover>Sisu</tedi:popover>');

        // x-show, not x-if: the panel must carry its real class list even
        // closed (CONVENTIONS.md §8), or parity has nothing to assert against.
        $this->assertStringContainsString('x-show="open"', $html);
        $this->assertStringContainsString('x-cloak', $html);
        $this->assertStringContainsString('Sisu', $html);
    }

    public function test_popover_aria_pairing_requires_an_explicit_container_id(): void
    {
        $paired = Blade::render(
            '<tedi:popover container-id="pop-1">'
            .'<x-slot:trigger><tedi:popover-trigger>Ava</tedi:popover-trigger></x-slot:trigger>'
            .' x</tedi:popover>'
        );

        $this->assertStringContainsString('id="pop-1"', $paired);
        $this->assertStringContainsString('id="pop-1_trigger"', $paired);
        $this->assertStringContainsString('aria-labelledby="pop-1_trigger"', $paired);
        $this->assertStringContainsString('x-bind:aria-controls="open ? \'pop-1\' : null"', $paired);

        // Unpaired: a dangling aria-labelledby is worse than none.
        $bare = Blade::render(
            '<tedi:popover>'
            .'<x-slot:trigger><tedi:popover-trigger>Ava</tedi:popover-trigger></x-slot:trigger>'
            .' x</tedi:popover>'
        );

        $this->assertStringNotContainsString('aria-labelledby', $bare);
        $this->assertStringNotContainsString('aria-controls', $bare);
    }

    public function test_popover_labelled_by_can_point_at_the_content_title(): void
    {
        $html = Blade::render(
            '<tedi:popover container-id="pop-2" labelled-by="pop-2_title">'
            .'<tedi:popover-content title="Pealkiri">x</tedi:popover-content>'
            .'</tedi:popover>'
        );

        $this->assertStringContainsString('aria-labelledby="pop-2_title"', $html);
        $this->assertStringContainsString('id="pop-2_title"', $html);
    }

    /**
     * Regression: the panel and arrow must be phrasing content.
     *
     * A `<div>` inside a `<p>` makes the HTML parser auto-close the paragraph
     * and hoist the div out — which lifts the panel out of `<tedi-popover>`
     * and therefore out of the Alpine scope, so the popover flips `open` but
     * never appears. Server-rendered HTML is well-formed either way, so every
     * class assertion passes regardless; only the browser parser rearranges
     * it. PHP's libxml parser does not implement the auto-close rule either,
     * so parsing the output here would false-green — the element names are
     * asserted directly instead, and the browser is the real proof.
     */
    public function test_panel_is_phrasing_content_so_it_survives_inside_a_paragraph(): void
    {
        $html = Blade::render('<tedi:popover>Sisu</tedi:popover>');

        $this->assertMatchesRegularExpression('/<span[^>]*class="tedi-popover__container/s', $html);
        $this->assertMatchesRegularExpression('/<span class="tedi-popover__arrow"/s', $html);
        $this->assertStringNotContainsString('<div', $html);
    }

    public function test_panel_has_no_whitespace_text_nodes_around_its_children(): void
    {
        // Inline elements render whitespace as a visible space inside a <p>.
        $html = Blade::render('<tedi:popover>Sisu</tedi:popover>');

        $this->assertStringContainsString(
            '><span class="tedi-popover__arrow" x-ref="arrow"></span>Sisu</span>', $html
        );

        // …and none between the trigger and the panel either.
        $trigger = Blade::render(
            '<tedi:popover>'
            .'<x-slot:trigger><tedi:popover-trigger>Ava</tedi:popover-trigger></x-slot:trigger>'
            .' Sisu</tedi:popover>'
        );

        $this->assertStringContainsString('</span><span', preg_replace('/\n\s*/', '', $trigger));
    }

    /** Without the arrow there is still no whitespace before the slot. */
    public function test_panel_without_arrow_has_no_leading_whitespace(): void
    {
        $html = Blade::render('<tedi:popover :with-arrow="false">Sisu</tedi:popover>');

        $this->assertStringContainsString('>Sisu</span>', $html);
        $this->assertStringNotContainsString('tedi-popover__arrow', $html);
    }

    public function test_popover_merges_consumer_classes_on_the_root(): void
    {
        $html = Blade::render('<tedi:popover class="mt-4">x</tedi:popover>');

        $this->assertHasClass('mt-4', $html, 'tedi-popover');
        $this->assertHasClass('tedi-popover', $html, 'tedi-popover');
    }

    // -- popover-trigger ----------------------------------------------------

    public function test_trigger_carries_the_literal_attribute_selector(): void
    {
        $html = Blade::render('<tedi:popover-trigger>Ava</tedi:popover-trigger>');

        // The vendored SCSS keys off [tedi-popover-trigger], not a class.
        $this->assertStringContainsString('tedi-popover-trigger', $html);
        $this->assertStringContainsString('tabindex="0"', $html);
        $this->assertStringContainsString('x-ref="trigger"', $html);
        $this->assertStringContainsString('x-on:click="toggle()"', $html);
    }

    public function test_trigger_underline_class(): void
    {
        $on = Blade::render('<tedi:popover-trigger :underline="true">Ava</tedi:popover-trigger>');
        $this->assertHasClass('tedi-popover-trigger__text', $on, 'tedi-popover-trigger__text');

        $off = Blade::render('<tedi:popover-trigger>Ava</tedi:popover-trigger>');
        $this->assertMissingClass('tedi-popover-trigger__text', $off);
    }

    public function test_trigger_interactive_aria(): void
    {
        $html = Blade::render('<tedi:popover-trigger>Ava</tedi:popover-trigger>');
        $this->assertStringContainsString('role="button"', $html);
        $this->assertStringContainsString('aria-haspopup="dialog"', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('x-bind:aria-expanded="open"', $html);

        $html = Blade::render('<tedi:popover-trigger :interactive="false">Ava</tedi:popover-trigger>');
        $this->assertStringNotContainsString('role="button"', $html);
        $this->assertStringNotContainsString('aria-haspopup', $html);
        $this->assertStringNotContainsString('aria-expanded', $html);
    }

    public function test_trigger_tag_selects_the_host_element(): void
    {
        $span = Blade::render('<tedi:popover-trigger>Ava</tedi:popover-trigger>');
        $this->assertStringContainsString('<span', $span);
        $this->assertStringNotContainsString('type="button"', $span);

        $button = Blade::render('<tedi:popover-trigger tag="button">Ava</tedi:popover-trigger>');
        $this->assertStringContainsString('<button', $button);
        $this->assertStringContainsString('type="button"', $button);
    }

    public function test_trigger_reads_container_id_through_aware(): void
    {
        $html = Blade::render(
            '<tedi:popover container-id="pop-3">'
            .'<x-slot:trigger><tedi:popover-trigger>Ava</tedi:popover-trigger></x-slot:trigger>'
            .' x</tedi:popover>'
        );

        $this->assertStringContainsString('id="pop-3_trigger"', $html);
    }

    // -- popover-content ----------------------------------------------------

    public function test_content_max_width_classes(): void
    {
        foreach (['small', 'medium', 'large'] as $maxWidth) {
            $html = Blade::render(
                '<tedi:popover-content max-width="'.$maxWidth.'">x</tedi:popover-content>'
            );

            $this->assertHasClass('tedi-popover-content', $html, 'tedi-popover-content');
            $this->assertHasClass('tedi-popover-content--'.$maxWidth, $html, 'tedi-popover-content');
        }
    }

    /** Angular suppresses the modifier for 'none', and TEDI ships no rule for it. */
    public function test_content_max_width_none_emits_no_modifier(): void
    {
        $html = Blade::render('<tedi:popover-content max-width="none">x</tedi:popover-content>');

        $this->assertHasClass('tedi-popover-content', $html, 'tedi-popover-content');
        $this->assertMissingClass('tedi-popover-content--none', $html, 'tedi-popover-content');
        $this->assertMissingClass('tedi-popover-content--small', $html, 'tedi-popover-content');
    }

    public function test_content_defaults_to_small(): void
    {
        $html = Blade::render('<tedi:popover-content>x</tedi:popover-content>');

        $this->assertHasClass('tedi-popover-content--small', $html, 'tedi-popover-content');
    }

    /** Branch 1 of 4: title + showClose — head, titled heading, default closing button. */
    public function test_content_branch_title_and_close(): void
    {
        $html = Blade::render(
            '<tedi:popover-content title="Pealkiri" :show-close="true">Sisu</tedi:popover-content>'
        );

        $this->assertHasClass('tedi-popover-content__head', $html, 'tedi-popover-content__head');
        $this->assertHasClass('tedi-popover-content__title', $html, 'tedi-popover-content__title');
        $this->assertHasClass('tedi-closing-button', $html, 'tedi-closing-button');
        $this->assertMissingClass('tedi-closing-button--small', $html, 'tedi-closing-button');
        $this->assertStringContainsString('Pealkiri', $html);
        $this->assertStringContainsString('Sisu', $html);
    }

    /** Branch 2 of 4: showClose only — head, slot wrapped in a bare div, small closing button. */
    public function test_content_branch_close_only(): void
    {
        $html = Blade::render(
            '<tedi:popover-content :show-close="true">Sisu</tedi:popover-content>'
        );

        $this->assertHasClass('tedi-popover-content__head', $html, 'tedi-popover-content__head');
        $this->assertHasClass('tedi-closing-button--small', $html, 'tedi-closing-button');
        $this->assertMissingClass('tedi-popover-content__title', $html, 'tedi-popover-content');
        $this->assertStringContainsString('<div>Sisu</div>', $html);
    }

    /** Branch 3 of 4: title only — untitled-class heading, no head wrapper, no close. */
    public function test_content_branch_title_only(): void
    {
        $html = Blade::render(
            '<tedi:popover-content title="Pealkiri">Sisu</tedi:popover-content>'
        );

        $this->assertMissingClass('tedi-popover-content__head', $html, 'tedi-popover-content');
        $this->assertMissingClass('tedi-popover-content__title', $html, 'tedi-popover-content');
        $this->assertStringContainsString('<h4 id=', $html);
        $this->assertStringNotContainsString('tedi-closing-button', $html);
    }

    /** Branch 4 of 4: neither — the slot alone. */
    public function test_content_branch_plain(): void
    {
        $html = Blade::render('<tedi:popover-content>Sisu</tedi:popover-content>');

        $this->assertMissingClass('tedi-popover-content__head', $html, 'tedi-popover-content');
        $this->assertStringNotContainsString('<h4', $html);
        $this->assertStringNotContainsString('tedi-closing-button', $html);
        $this->assertStringContainsString('Sisu', $html);
    }

    public function test_content_close_button_hides_the_popover_and_returns_focus(): void
    {
        $html = Blade::render('<tedi:popover-content :show-close="true">Sisu</tedi:popover-content>');

        $this->assertStringContainsString('x-on:click="hide(true)"', $html);
    }

    public function test_content_title_id_derives_from_container_id(): void
    {
        $html = Blade::render(
            '<tedi:popover container-id="pop-4">'
            .'<tedi:popover-content title="Pealkiri">x</tedi:popover-content>'
            .'</tedi:popover>'
        );

        $this->assertStringContainsString('id="pop-4_title"', $html);
    }
}
