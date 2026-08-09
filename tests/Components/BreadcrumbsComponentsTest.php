<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class BreadcrumbsComponentsTest extends TestCase
{
    /** Three crumbs: two links plus the current page. */
    private function trail(int $count = 3): string
    {
        $items = [];

        for ($i = 1; $i < $count; $i++) {
            $items[] = "['label' => 'Crumb {$i}', 'href' => '#{$i}']";
        }

        $items[] = "['label' => 'Current']";

        return '['.implode(', ', $items).']';
    }

    // -- structure --------------------------------------------------------

    public function test_renders_the_custom_element_with_base_class(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');

        $this->assertStringContainsString('<tedi-breadcrumbs', $html);
        $this->assertHasClass('tedi-breadcrumbs', $html, 'tedi-breadcrumbs');
    }

    public function test_renders_a_nav_landmark_and_an_ordered_list(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');

        $this->assertStringContainsString('<nav', $html);
        $this->assertHasClass('tedi-breadcrumbs__list', $html, 'tedi-breadcrumbs__list');
    }

    public function test_renders_nothing_without_items(): void
    {
        $html = Blade::render('<tedi:breadcrumbs />');

        $this->assertStringNotContainsString('<nav', $html);
        $this->assertStringNotContainsString('tedi-breadcrumbs__list', $html);
    }

    public function test_aria_label_falls_back_to_the_translation(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');
        $this->assertStringContainsString('aria-label="Breadcrumbs"', $html);

        $html = Blade::render('<tedi:breadcrumbs aria-label="Liikumistee" :items="'.$this->trail().'" />');
        $this->assertStringContainsString('aria-label="Liikumistee"', $html);
    }

    // -- crumbs -----------------------------------------------------------

    public function test_last_crumb_is_the_current_page(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');

        $this->assertHasClass('tedi-breadcrumbs__item--current', $html, 'tedi-breadcrumbs__item--current');
        $this->assertStringContainsString('<span aria-current="page">Current</span>', $html);
    }

    public function test_only_one_crumb_is_current(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail(4).'" />');

        $this->assertSame(1, substr_count($html, 'tedi-breadcrumbs__item--current'));
        $this->assertSame(1, substr_count($html, 'aria-current="page"'));
    }

    public function test_crumb_with_href_renders_an_anchor(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');

        $this->assertStringContainsString('href="#1"', $html);
        $this->assertHasClass('tedi-link', $html, 'tedi-link');
    }

    public function test_crumb_without_href_renders_a_button(): void
    {
        $html = Blade::render(
            '<tedi:breadcrumbs :items="[[\'label\' => \'Töölaud\'], [\'label\' => \'Current\']]" />'
        );

        $this->assertStringContainsString('<button', $html);
        $this->assertStringNotContainsString('href=', $html);
    }

    public function test_per_crumb_underline_false_emits_the_no_underline_class(): void
    {
        $html = Blade::render(
            '<tedi:breadcrumbs :items="[[\'label\' => \'A\', \'href\' => \'#\', \'underline\' => false], [\'label\' => \'B\']]" />'
        );
        $this->assertHasClass('tedi-link--no-underline', $html, 'tedi-link');

        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');
        $this->assertMissingClass('tedi-link--no-underline', $html, 'tedi-link');
    }

    // -- separators -------------------------------------------------------

    public function test_separator_count_is_one_less_than_the_crumb_count(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail(4).'" />');

        $this->assertSame(3, substr_count($html, 'tedi-breadcrumbs__separator'));
    }

    public function test_default_separator_is_a_chevron_icon(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');

        $this->assertStringContainsString('>chevron_right</tedi-icon>', $html);
    }

    public function test_separator_string_replaces_the_icon(): void
    {
        $html = Blade::render('<tedi:breadcrumbs separator="/" :items="'.$this->trail().'" />');

        $this->assertStringNotContainsString('>chevron_right</tedi-icon>', $html);
        $this->assertStringContainsString('>/</span>', $html);
    }

    public function test_separator_template_slot_wins_over_the_string(): void
    {
        $html = Blade::render(
            '<tedi:breadcrumbs separator="/" :items="'.$this->trail().'">'
            .'<x-slot:separator-template>ARROW</x-slot:separator-template> '
            .'</tedi:breadcrumbs>'
        );

        $this->assertStringContainsString('ARROW', $html);
        $this->assertStringNotContainsString('>/</span>', $html);
        $this->assertStringNotContainsString('>chevron_right</tedi-icon>', $html);
    }

    public function test_separators_are_hidden_from_assistive_technology(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail().'" />');

        $this->assertStringContainsString('class="tedi-breadcrumbs__separator" aria-hidden="true"', $html);
    }

    // -- short variant ----------------------------------------------------

    public function test_short_variant_renders_only_the_parent_crumb(): void
    {
        $html = Blade::render('<tedi:breadcrumbs variant="short" :items="'.$this->trail(4).'" />');

        // items[count-2] is "Crumb 3"; nothing else renders.
        $this->assertStringContainsString('Crumb 3', $html);
        $this->assertStringNotContainsString('Crumb 1', $html);
        $this->assertStringNotContainsString('Current', $html);
        $this->assertMissingClass('tedi-breadcrumbs__separator', $html, 'tedi-breadcrumbs__separator');
    }

    public function test_short_variant_renders_a_back_arrow(): void
    {
        $html = Blade::render('<tedi:breadcrumbs variant="short" :items="'.$this->trail().'" />');

        $this->assertStringContainsString('>arrow_back</tedi-icon>', $html);
    }

    public function test_short_variant_renders_nothing_with_a_single_crumb(): void
    {
        $html = Blade::render('<tedi:breadcrumbs variant="short" :items="[[\'label\' => \'Only\']]" />');

        $this->assertStringNotContainsString('<nav', $html);
    }

    // -- collapsing -------------------------------------------------------

    public function test_no_collapse_without_max_items(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :items="'.$this->trail(6).'" />');

        $this->assertMissingClass('tedi-breadcrumbs__ellipsis', $html, 'tedi-breadcrumbs__ellipsis');
    }

    public function test_no_collapse_when_the_trail_is_within_max_items(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :max-items="4" :items="'.$this->trail(4).'" />');

        $this->assertMissingClass('tedi-breadcrumbs__ellipsis', $html, 'tedi-breadcrumbs__ellipsis');
    }

    public function test_collapse_renders_an_ellipsis_dropdown(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :max-items="4" :items="'.$this->trail(6).'" />');

        $this->assertHasClass('tedi-breadcrumbs__ellipsis', $html, 'tedi-breadcrumbs__ellipsis');
        $this->assertStringContainsString('<tedi-dropdown', $html);
        $this->assertHasClass('tedi-breadcrumbs__dropdown-item', $html, 'tedi-breadcrumbs__dropdown-item');
    }

    public function test_collapse_keeps_the_configured_head_and_tail(): void
    {
        // 6 crumbs, before=1 after=2 -> visible: Crumb 1, …, Crumb 4, Current.
        $html = Blade::render(
            '<tedi:breadcrumbs :max-items="4" :items-before-collapse="1" :items-after-collapse="2" :items="'.$this->trail(6).'" />'
        );

        $this->assertSame(3, substr_count($html, 'tedi-breadcrumbs__dropdown-item'));
        $this->assertStringContainsString('<span aria-current="page">Current</span>', $html);
    }

    public function test_collapse_never_hides_the_current_page(): void
    {
        // itemsAfterCollapse is clamped to a minimum of 1, mirroring Angular.
        $html = Blade::render(
            '<tedi:breadcrumbs :max-items="2" :items-before-collapse="1" :items-after-collapse="0" :items="'.$this->trail(6).'" />'
        );

        $this->assertStringContainsString('<span aria-current="page">Current</span>', $html);
    }

    public function test_ellipsis_button_is_not_underlined_and_is_labelled(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :max-items="4" :items="'.$this->trail(6).'" />');
        $this->assertHasClass('tedi-link--no-underline', $html, 'tedi-breadcrumbs__ellipsis');
        $this->assertStringContainsString('aria-label="Show more"', $html);

        $html = Blade::render('<tedi:breadcrumbs show-more-label="Näita rohkem" :max-items="4" :items="'.$this->trail(6).'" />');
        $this->assertStringContainsString('aria-label="Näita rohkem"', $html);
    }

    public function test_ellipsis_dropdown_wires_aria_between_trigger_and_panel(): void
    {
        $html = Blade::render('<tedi:breadcrumbs :max-items="4" :items="'.$this->trail(6).'" />');

        $this->assertMatchesRegularExpression('/id="tedi-breadcrumbs-[0-9a-f]+"/', $html);
        $this->assertStringContainsString('aria-labelledby=', $html);
    }

    public function test_merges_consumer_classes(): void
    {
        $html = Blade::render('<tedi:breadcrumbs class="my-trail" :items="'.$this->trail().'" />');

        $this->assertHasClass('my-trail', $html, 'tedi-breadcrumbs');
        $this->assertHasClass('tedi-breadcrumbs', $html, 'tedi-breadcrumbs');
    }

    // -- variant matrix smoke test ---------------------------------------

    public function test_breadcrumbs_matrix_renders(): void
    {
        $path = __DIR__.'/../fixtures/matrices/breadcrumbs.blade.php';
        $out = Blade::render(file_get_contents($path));

        $this->assertHasClass('tedi-breadcrumbs', $out, 'tedi-breadcrumbs');
        $this->assertHasClass('tedi-breadcrumbs__ellipsis', $out, 'tedi-breadcrumbs__ellipsis');
    }
}
