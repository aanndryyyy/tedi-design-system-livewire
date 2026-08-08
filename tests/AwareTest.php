<?php

namespace Tedi\Livewire\Tests;

use Illuminate\Support\Facades\Blade;

/**
 * Laravel gotcha guarded here: `@aware` reads the parent's *attribute bag*,
 * which contains only what the consumer explicitly passed. A `@props` default on
 * the parent that the consumer omitted does NOT reach the child.
 *
 * The convention that keeps this safe (CONVENTIONS.md §3) is that the child's
 * `@aware` fallback must equal the parent's `@props` default, so both paths
 * agree. These tests pin that agreement for every parent/child pair.
 */
class AwareTest extends TestCase
{
    /** Matches the bare `disabled` attribute without also matching `aria-disabled`. */
    private const DISABLED_ATTR = '/\sdisabled[\s\/>]/';

    public function test_explicit_parent_attribute_reaches_the_child(): void
    {
        $html = Blade::render(
            '<tedi:radio-card-group :grouped="true">'
            .'<tedi:radio-card name="g" value="a">A</tedi:radio-card>'
            .'</tedi:radio-card-group>'
        );

        $this->assertHasClass('tedi-radio-card-group--grouped', $html, 'tedi-radio-card-group');
        $this->assertHasClass('tedi-radio-card--grouped', $html, 'tedi-radio-card');
    }

    public function test_child_fallback_matches_parent_default_when_omitted(): void
    {
        $html = Blade::render(
            '<tedi:radio-card-group>'
            .'<tedi:radio-card name="g" value="a">A</tedi:radio-card>'
            .'</tedi:radio-card-group>'
        );

        // Parent default is grouped=false, so neither element gets the modifier.
        $this->assertMissingClass('tedi-radio-card-group--grouped', $html, 'tedi-radio-card-group');
        $this->assertMissingClass('tedi-radio-card--grouped', $html, 'tedi-radio-card');
    }

    public function test_child_renders_standalone_without_a_parent(): void
    {
        $html = Blade::render('<tedi:radio-card name="g" value="a">A</tedi:radio-card>');

        $this->assertHasClass('tedi-radio-card', $html, 'tedi-radio-card');
        $this->assertMissingClass('tedi-radio-card--grouped', $html, 'tedi-radio-card');
    }

    // -- tabs.trigger / tabs.content @aware(['value', 'defaultValue']) ------

    public function test_tabs_trigger_reads_default_value_through_the_intermediate_list(): void
    {
        // tabs > tabs.list > tabs.trigger — two levels up, proving @aware
        // walks the whole ancestor stack, not just the direct parent.
        $html = Blade::render(
            '<tedi:tabs default-value="a">'
            .'<tedi:tabs.list>'
            .'<tedi:tabs.trigger id="a">A</tedi:tabs.trigger>'
            .'</tedi:tabs.list>'
            .'</tedi:tabs>'
        );

        $this->assertHasClass('tedi-tabs-trigger--selected', $html, 'tedi-tabs-trigger');
    }

    public function test_tabs_child_fallback_matches_root_default_when_value_omitted(): void
    {
        // Root default is defaultValue='', so a trigger for any non-empty id
        // must not be selected.
        $html = Blade::render(
            '<tedi:tabs>'
            .'<tedi:tabs.trigger id="a">A</tedi:tabs.trigger>'
            .'</tedi:tabs>'
        );

        $this->assertMissingClass('tedi-tabs-trigger--selected', $html, 'tedi-tabs-trigger');
    }

    public function test_tabs_content_renders_standalone_without_a_parent(): void
    {
        $html = Blade::render('<tedi:tabs.content id="a">A</tedi:tabs.content>');

        $this->assertHasClass('tedi-tabs-content', $html, 'tedi-tabs-content');
        $this->assertStringContainsString('hidden', $html);
    }

    // -- card-content/card-header/card-icon @aware(['background', 'padding']) --

    public function test_card_content_reads_explicit_card_background_and_padding(): void
    {
        $html = Blade::render(
            '<tedi:card background="secondary" :padding="2">'
            .'<tedi:card-content>x</tedi:card-content>'
            .'</tedi:card>'
        );

        $this->assertHasClass('tedi-card-content--background--secondary', $html, 'tedi-card-content');
        $this->assertStringContainsString('--card-content-padding-top: 2rem', $html);
    }

    public function test_card_content_fallback_matches_card_default_when_omitted(): void
    {
        // Card's own background/padding defaults are both null, matching
        // card-content's @aware fallback, so card-content lands on its own
        // default (primary / 1rem) either way.
        $html = Blade::render('<tedi:card><tedi:card-content>x</tedi:card-content></tedi:card>');

        $this->assertHasClass('tedi-card-content--background--primary', $html, 'tedi-card-content');
        $this->assertStringContainsString('--card-content-padding-top: 1rem', $html);
    }

    public function test_card_header_only_aware_of_padding_never_background(): void
    {
        $html = Blade::render(
            '<tedi:card background="secondary" :padding="2">'
            .'<tedi:card-header>x</tedi:card-header>'
            .'</tedi:card>'
        );

        $this->assertHasClass('tedi-card-header--background--brand-primary', $html, 'tedi-card-header');
        $this->assertMissingClass('tedi-card-header--background--secondary', $html, 'tedi-card-header');
        $this->assertStringContainsString('--card-content-padding-top: 2rem', $html);
    }

    public function test_card_icon_reads_padding_but_derives_background_from_own_type(): void
    {
        $html = Blade::render(
            '<tedi:card background="brand-secondary" :padding="2">'
            .'<tedi:card-icon>x</tedi:card-icon>'
            .'</tedi:card>'
        );

        // type=default -> 'secondary' background regardless of the card's background.
        $this->assertHasClass('tedi-card-icon--background--secondary', $html, 'tedi-card-icon');
        $this->assertMissingClass('tedi-card-icon--background--brand-secondary', $html, 'tedi-card-icon');
        $this->assertStringContainsString('--card-content-padding-top: 2rem', $html);
    }

    public function test_card_children_render_standalone_without_a_card(): void
    {
        $content = Blade::render('<tedi:card-content>x</tedi:card-content>');
        $this->assertHasClass('tedi-card-content--background--primary', $content, 'tedi-card-content');

        $header = Blade::render('<tedi:card-header>x</tedi:card-header>');
        $this->assertHasClass('tedi-card-header--background--brand-primary', $header, 'tedi-card-header');

        $icon = Blade::render('<tedi:card-icon>x</tedi:card-icon>');
        $this->assertHasClass('tedi-card-icon--background--secondary', $icon, 'tedi-card-icon');
    }

    // -- accordion-item @aware(['defaultExpanded']) --------------------------

    public function test_accordion_item_reads_explicit_group_default_expanded(): void
    {
        $html = Blade::render(
            '<tedi:accordion default-expanded>'
            .'<tedi:accordion-item>x</tedi:accordion-item>'
            .'</tedi:accordion>'
        );

        $this->assertStringContainsString('expanded: true', $html);
    }

    public function test_accordion_item_fallback_matches_accordion_default_when_omitted(): void
    {
        // Accordion's own defaultExpanded default is null, matching the
        // item's @aware fallback, so the item lands on its own default (false).
        $html = Blade::render(
            '<tedi:accordion>'
            .'<tedi:accordion-item>x</tedi:accordion-item>'
            .'</tedi:accordion>'
        );

        $this->assertStringContainsString('expanded: false', $html);
    }

    public function test_accordion_item_renders_standalone_without_an_accordion(): void
    {
        $html = Blade::render('<tedi:accordion-item>x</tedi:accordion-item>');

        $this->assertHasClass('tedi-accordion__item', $html, 'tedi-accordion__item');
        $this->assertStringContainsString('expanded: false', $html);
    }

    // -- accordion-item-header/content @aware(['itemId', 'disabled', 'showIconCard']) --

    public function test_accordion_item_header_and_content_read_explicit_item_attributes(): void
    {
        $html = Blade::render(
            '<tedi:accordion-item item-id="terms" disabled show-icon-card>'
            .'<tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header>'
            .'<tedi:accordion-item-content>c</tedi:accordion-item-content>'
            .'</tedi:accordion-item>'
        );

        $this->assertHasClass('tedi-accordion-item-header--disabled', $html, 'tedi-accordion-item-header');
        $this->assertHasClass('tedi-accordion-item-header--with-icon-card', $html, 'tedi-accordion-item-header');
        $this->assertHasClass('tedi-accordion-item-content--with-icon-card', $html, 'tedi-accordion-item-content');
        $this->assertStringContainsString('id="terms-header"', $html);
        $this->assertStringContainsString('id="terms-content"', $html);
    }

    public function test_accordion_item_header_and_content_fallback_match_item_defaults_when_omitted(): void
    {
        // Item's own itemId/disabled/showIconCard defaults are null/false/false,
        // matching the header/content @aware fallbacks.
        $html = Blade::render(
            '<tedi:accordion-item>'
            .'<tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header>'
            .'<tedi:accordion-item-content>c</tedi:accordion-item-content>'
            .'</tedi:accordion-item>'
        );

        $this->assertMissingClass('tedi-accordion-item-header--disabled', $html, 'tedi-accordion-item-header');
        $this->assertMissingClass('tedi-accordion-item-header--with-icon-card', $html, 'tedi-accordion-item-header');
        $this->assertMissingClass('tedi-accordion-item-content--with-icon-card', $html, 'tedi-accordion-item-content');
    }

    public function test_accordion_item_header_and_content_render_standalone_without_an_item(): void
    {
        $header = Blade::render('<tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-accordion-item-header', $header, 'tedi-accordion-item-header');

        $content = Blade::render('<tedi:accordion-item-content>c</tedi:accordion-item-content>');
        $this->assertHasClass('tedi-accordion-item-content', $content, 'tedi-accordion-item-content');
    }

    // -- carousel-slide @aware(['slidesPerView', 'gap']) ---------------------

    public function test_carousel_slide_reads_explicit_slides_per_view_and_gap(): void
    {
        $html = Blade::render(
            '<tedi:carousel-content :slides-per-view="2" :gap="8">'
            .'<tedi:carousel-slide>x</tedi:carousel-slide>'
            .'</tedi:carousel-content>'
        );

        $this->assertStringContainsString('flex: 0 0 calc((100% - 8px) / 2)', $html);
    }

    public function test_carousel_slide_fallback_matches_content_default_when_omitted(): void
    {
        // carousel-content's own slidesPerView/gap defaults (1 / 16) match
        // carousel-slide's @aware fallback.
        $html = Blade::render(
            '<tedi:carousel-content>'
            .'<tedi:carousel-slide>x</tedi:carousel-slide>'
            .'</tedi:carousel-content>'
        );

        $this->assertStringContainsString('flex: 0 0 100%', $html);
    }

    public function test_carousel_slide_renders_standalone_without_carousel_content(): void
    {
        $html = Blade::render('<tedi:carousel-slide>x</tedi:carousel-slide>');

        $this->assertHasClass('tedi-carousel__slide', $html, 'tedi-carousel__slide');
        $this->assertStringContainsString('flex: 0 0 100%', $html);
    }

    // -- checkbox-group -> checkbox @aware(['disabled']) ---------------------

    public function test_checkbox_reads_explicit_group_disabled(): void
    {
        $html = Blade::render(
            '<tedi:checkbox-group :disabled="true">'
            .'<tedi:checkbox value="a" />'
            .'</tedi:checkbox-group>'
        );

        // Blade's @disabled emits the bare attribute, and `disabled` is a
        // substring of `aria-disabled`, so match it as a whole attribute.
        $this->assertMatchesRegularExpression(self::DISABLED_ATTR, $html);
    }

    public function test_checkbox_fallback_matches_group_default_when_disabled_omitted(): void
    {
        // checkbox-group's own default is disabled=false, matching the
        // checkbox's @aware fallback.
        $html = Blade::render(
            '<tedi:checkbox-group>'
            .'<tedi:checkbox value="a" />'
            .'</tedi:checkbox-group>'
        );

        $this->assertDoesNotMatchRegularExpression(self::DISABLED_ATTR, $html);
    }

    public function test_checkbox_renders_standalone_without_a_group(): void
    {
        $html = Blade::render('<tedi:checkbox value="a" />');

        $this->assertStringContainsString('tedi-checkbox', $html);
        $this->assertDoesNotMatchRegularExpression(self::DISABLED_ATTR, $html);
    }

    public function test_checkbox_own_disabled_wins_over_an_enabled_group(): void
    {
        $html = Blade::render(
            '<tedi:checkbox-group>'
            .'<tedi:checkbox value="a" :disabled="true" />'
            .'</tedi:checkbox-group>'
        );

        $this->assertMatchesRegularExpression(self::DISABLED_ATTR, $html);
    }

    // -- radio-group -> radio @aware(['disabled', 'name']) -------------------

    public function test_radio_reads_explicit_group_disabled_and_name(): void
    {
        $html = Blade::render(
            '<tedi:radio-group name="g" :disabled="true">'
            .'<tedi:radio value="a" />'
            .'</tedi:radio-group>'
        );

        $this->assertMatchesRegularExpression(self::DISABLED_ATTR, $html);
        $this->assertStringContainsString('name="g"', $html);
    }

    public function test_radio_fallback_matches_group_defaults_when_omitted(): void
    {
        // radio-group's own defaults are disabled=false / name=null, matching
        // the radio's @aware fallbacks. Angular's auto-generated group name
        // deliberately does not cross the @aware boundary.
        $html = Blade::render(
            '<tedi:radio-group>'
            .'<tedi:radio value="a" />'
            .'</tedi:radio-group>'
        );

        $this->assertDoesNotMatchRegularExpression(self::DISABLED_ATTR, $html);
        $this->assertStringNotContainsString('name=', $html);
    }

    public function test_radio_renders_standalone_without_a_group(): void
    {
        $html = Blade::render('<tedi:radio value="a" />');

        $this->assertStringContainsString('tedi-radio', $html);
        $this->assertDoesNotMatchRegularExpression(self::DISABLED_ATTR, $html);
        $this->assertStringNotContainsString('name=', $html);
    }

    public function test_radio_own_name_wins_over_the_group_name(): void
    {
        $html = Blade::render(
            '<tedi:radio-group name="g">'
            .'<tedi:radio value="a" name="own" />'
            .'</tedi:radio-group>'
        );

        $this->assertStringContainsString('name="own"', $html);
        $this->assertStringNotContainsString('name="g"', $html);
    }
}
