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

    // -- tooltip / tooltip-trigger @aware(['openWith']) ---------------------

    private const TOOLTIP = '<tedi:tooltip%s>'
        .'<tedi:tooltip-trigger>x</tedi:tooltip-trigger>'
        .'<tedi:tooltip-content>c</tedi:tooltip-content>'
        .'</tedi:tooltip>';

    /**
     * The trigger emits no base class — Angular's host has none — so
     * `classesOf()`'s class-token scoping has nothing to key on. Slice the
     * element out and assert against that instead, which keeps the assertion
     * exact-token rather than falling back to a substring match (§10).
     */
    private function triggerTag(string $html): string
    {
        preg_match('/<tedi-tooltip-trigger\b[^>]*>/s', $html, $m);

        return $m[0] ?? '';
    }

    public function test_tooltip_open_with_reaches_the_trigger(): void
    {
        $html = Blade::render(sprintf(self::TOOLTIP, ' open-with="click"'));

        // Angular adds --clickable only for openWith === 'click'.
        $this->assertHasClass('tedi-tooltip-trigger--clickable', $this->triggerTag($html));
    }

    public function test_tooltip_trigger_fallback_matches_parent_default_when_omitted(): void
    {
        $omitted = Blade::render(sprintf(self::TOOLTIP, ''));
        $explicit = Blade::render(sprintf(self::TOOLTIP, ' open-with="both"'));

        $this->assertMissingClass('tedi-tooltip-trigger--clickable', $this->triggerTag($omitted));
        $this->assertSame($explicit, $omitted,
            'Omitting open-with must render identically to passing its default.');
    }

    public function test_tooltip_trigger_renders_standalone_without_a_parent(): void
    {
        $html = Blade::render('<tedi:tooltip-trigger>x</tedi:tooltip-trigger>');

        $this->assertStringContainsString('<tedi-tooltip-trigger', $html);
        $this->assertMissingClass('tedi-tooltip-trigger--clickable', $this->triggerTag($html));
    }

    // -- popover / popover-trigger + popover-content @aware(['containerId']) --

    private const POPOVER = '<tedi:popover%s>'
        .'<x-slot:trigger><tedi:popover-trigger>t</tedi:popover-trigger></x-slot:trigger> '
        .'<tedi:popover-content title="T">c</tedi:popover-content>'
        .'</tedi:popover>';

    public function test_popover_container_id_reaches_trigger_and_content(): void
    {
        $html = Blade::render(sprintf(self::POPOVER, ' container-id="p1"'));

        $this->assertStringContainsString('id="p1_trigger"', $html);
        $this->assertStringContainsString('id="p1_title"', $html);
    }

    public function test_popover_children_fall_back_to_the_parent_default_when_omitted(): void
    {
        $html = Blade::render(sprintf(self::POPOVER, ''));

        // Default is null: rather than dangle a reference to an id that the
        // trigger never received, both attributes are omitted entirely.
        $this->assertStringNotContainsString('_trigger"', $html);
        $this->assertStringNotContainsString('aria-labelledby', $html);
    }

    public function test_popover_children_render_standalone_without_a_parent(): void
    {
        $trigger = Blade::render('<tedi:popover-trigger>t</tedi:popover-trigger>');
        $content = Blade::render('<tedi:popover-content title="T">c</tedi:popover-content>');

        $this->assertStringContainsString('tedi-popover-trigger', $trigger);
        $this->assertHasClass('tedi-popover-content', $content, 'tedi-popover-content');
    }

    // -- modal / modal-header @aware(['size']) ------------------------------

    private const MODAL = '<tedi:modal%s><tedi:modal-header><h3>H</h3></tedi:modal-header></tedi:modal>';

    public function test_modal_size_reaches_the_header_close_button(): void
    {
        $html = Blade::render(sprintf(self::MODAL, ' size="small"'));

        // A small modal shrinks its close button without the consumer saying so.
        $this->assertHasClass('tedi-closing-button--small', $html, 'tedi-closing-button');
    }

    public function test_modal_header_fallback_matches_parent_default_when_omitted(): void
    {
        $omitted = Blade::render(sprintf(self::MODAL, ''));
        $explicit = Blade::render(sprintf(self::MODAL, ' size="default"'));

        $this->assertMissingClass('tedi-closing-button--small', $omitted, 'tedi-closing-button');
        $this->assertSame($explicit, $omitted,
            'Omitting size must render identically to passing its default.');
    }

    public function test_modal_header_renders_standalone_without_a_parent(): void
    {
        $html = Blade::render('<tedi:modal-header><h3>H</h3></tedi:modal-header>');

        $this->assertHasClass('tedi-modal-header', $html, 'tedi-modal-header');
        $this->assertMissingClass('tedi-closing-button--small', $html, 'tedi-closing-button');
    }

    // -- dropdown @aware(['containerId']) + content/item @aware(['dropdownRole']) --

    private const DROPDOWN = '<tedi:dropdown%s>'
        .'<tedi:dropdown-trigger><button>b</button></tedi:dropdown-trigger>'
        .'<tedi:dropdown-content%s>'
        .'<tedi:dropdown-item value="a">A</tedi:dropdown-item>'
        .'</tedi:dropdown-content>'
        .'</tedi:dropdown>';

    public function test_dropdown_container_id_reaches_trigger_and_content(): void
    {
        $html = Blade::render(sprintf(self::DROPDOWN, ' container-id="d1"', ''));

        $this->assertStringContainsString("setAttribute('id', 'd1_trigger')", $html);
        $this->assertStringContainsString('aria-labelledby="d1_trigger"', $html);
        $this->assertStringContainsString('id="d1"', $html);
    }

    public function test_dropdown_children_fall_back_to_the_parent_default_when_omitted(): void
    {
        $html = Blade::render(sprintf(self::DROPDOWN, '', ''));

        $this->assertStringNotContainsString('_trigger', $html);
        $this->assertStringNotContainsString('aria-labelledby', $html);
    }

    /**
     * dropdownRole crosses one level — content to item — and changes the ARIA
     * role of both the list and every row inside it.
     */
    public function test_dropdown_role_reaches_the_item(): void
    {
        $listbox = Blade::render(sprintf(self::DROPDOWN, '', ' dropdown-role="listbox"'));

        $this->assertStringContainsString('<ul role="listbox">', $listbox);
        $this->assertStringContainsString('role="option"', $listbox);
    }

    public function test_dropdown_item_fallback_matches_content_default_when_omitted(): void
    {
        $omitted = Blade::render(sprintf(self::DROPDOWN, '', ''));
        $explicit = Blade::render(sprintf(self::DROPDOWN, '', ' dropdown-role="menu"'));

        $this->assertStringContainsString('<ul role="menu">', $omitted);
        $this->assertStringContainsString('role="menuitem"', $omitted);
        $this->assertSame($explicit, $omitted,
            'Omitting dropdown-role must render identically to passing its default.');
    }

    public function test_dropdown_item_renders_standalone_without_a_parent(): void
    {
        $html = Blade::render('<tedi:dropdown-item value="a">A</tedi:dropdown-item>');

        // Falls back to the content default, so it is a menuitem, not an option.
        $this->assertStringContainsString('role="menuitem"', $html);
        $this->assertStringNotContainsString('role="option"', $html);
    }

    // -- vertical-stepper-item @aware(['compact', 'enumerated']) -----------

    private const STEPPER = '<tedi:vertical-stepper%s>'
        .'<tedi:vertical-stepper-item title="Samm" />'
        .'</tedi:vertical-stepper>';

    public function test_vertical_stepper_compact_and_enumerated_reach_the_item(): void
    {
        $html = Blade::render(sprintf(self::STEPPER, ' :compact="true" :enumerated="true"'));

        $this->assertHasClass('tedi-vertical-stepper-item--compact', $html, 'tedi-vertical-stepper-item');
        $this->assertHasClass('tedi-vertical-stepper-item--enumerated', $html, 'tedi-vertical-stepper-item');
    }

    public function test_vertical_stepper_item_fallback_matches_stepper_defaults_when_omitted(): void
    {
        $omitted = Blade::render(sprintf(self::STEPPER, ''));
        $explicit = Blade::render(sprintf(self::STEPPER, ' :compact="false" :enumerated="false"'));

        $this->assertMissingClass('tedi-vertical-stepper-item--compact', $omitted, 'tedi-vertical-stepper-item');
        $this->assertMissingClass('tedi-vertical-stepper-item--enumerated', $omitted, 'tedi-vertical-stepper-item');
        $this->assertSame($explicit, $omitted,
            'Omitting compact/enumerated must render identically to passing their defaults.');
    }

    public function test_vertical_stepper_item_renders_standalone_without_a_stepper(): void
    {
        $html = Blade::render('<tedi:vertical-stepper-item title="Samm" />');

        $this->assertHasClass('tedi-vertical-stepper-item', $html, 'tedi-vertical-stepper-item');
        $this->assertMissingClass('tedi-vertical-stepper-item--compact', $html, 'tedi-vertical-stepper-item');
        $this->assertMissingClass('tedi-vertical-stepper-item--enumerated', $html, 'tedi-vertical-stepper-item');
    }

    /**
     * `sub-item` is deliberately NOT @aware: the child declares it itself, and
     * Laravel resolves the child's own data first (CONVENTIONS.md §3), so an
     * @aware would always read the child's value and never the parent's.
     */
    public function test_sub_item_is_an_explicit_prop_not_inherited(): void
    {
        $html = Blade::render(
            '<tedi:vertical-stepper-item title="Samm">'
            .'<x-slot:sub-items><tedi:vertical-stepper-item title="Alam" /></x-slot:sub-items>'
            .'</tedi:vertical-stepper-item>'
        );

        // Nesting alone does not make the child a sub-item — the consumer must say so.
        $this->assertMissingClass('tedi-vertical-stepper-item--sub-item', $html, 'tedi-vertical-stepper-item');

        $explicit = Blade::render(
            '<tedi:vertical-stepper-item title="Samm">'
            .'<x-slot:sub-items><tedi:vertical-stepper-item title="Alam" :sub-item="true" /></x-slot:sub-items>'
            .'</tedi:vertical-stepper-item>'
        );

        $this->assertHasClass('tedi-vertical-stepper-item--sub-item', $explicit, 'tedi-vertical-stepper-item--sub-item');
    }

    // -- filter-group / filter @aware(['disabled', 'managed', 'allowMultiple']) --

    public function test_filter_inherits_disabled_from_its_group(): void
    {
        $html = Blade::render(
            '<tedi:filter-group :disabled="true"><tedi:filter text="Kõik" value="all" /></tedi:filter-group>'
        );

        $this->assertHasClass('tedi-filter--disabled', $html, 'tedi-filter');
        $this->assertMatchesRegularExpression(self::DISABLED_ATTR, $html);
    }

    public function test_filter_inherits_managed_from_its_group(): void
    {
        // managed + single-select -> the child becomes a role="radio".
        $radio = Blade::render(
            '<tedi:filter-group :managed="true"><tedi:filter text="Kõik" value="all" /></tedi:filter-group>'
        );
        $this->assertStringContainsString('role="radio"', $radio);
    }

    /**
     * The group's `allowMultiple` is deliberately NOT @aware: the child declares
     * a prop of that name for its own dropdown mode, and Laravel resolves the
     * child's own data first (CONVENTIONS.md §3). It is mirrored with the
     * explicit `group-allow-multiple` prop instead.
     */
    public function test_group_allow_multiple_is_an_explicit_prop_not_inherited(): void
    {
        // The group alone does not reach the child — it still renders as a radio.
        $inherited = Blade::render(
            '<tedi:filter-group :managed="true" :allow-multiple="true">'
            .'<tedi:filter text="Kõik" value="all" /></tedi:filter-group>'
        );
        $this->assertStringContainsString('role="radio"', $inherited);

        // Mirrored on the child, the group ARIA switches to aria-pressed.
        $mirrored = Blade::render(
            '<tedi:filter-group :managed="true" :allow-multiple="true">'
            .'<tedi:filter text="Kõik" value="all" :group-allow-multiple="true" /></tedi:filter-group>'
        );
        $this->assertStringContainsString('aria-pressed="false"', $mirrored);
        $this->assertStringNotContainsString('role="radio"', $mirrored);
    }

    public function test_filter_child_fallbacks_match_the_group_defaults_when_omitted(): void
    {
        $explicit = Blade::render(
            '<tedi:filter-group :managed="false" :disabled="false">'
            .'<tedi:filter text="Kõik" /></tedi:filter-group>'
        );
        $omitted = Blade::render(
            '<tedi:filter-group><tedi:filter text="Kõik" /></tedi:filter-group>'
        );

        // The generated ids differ per render; compare with them normalised.
        $normalise = fn (string $html) => preg_replace('/tedi-filter-[0-9a-f]+/', 'ID', $html);

        $this->assertSame($normalise($explicit), $normalise($omitted),
            'Omitting managed/allowMultiple/disabled must render identically to passing their defaults.');
    }

    public function test_filter_renders_standalone_without_a_group(): void
    {
        $html = Blade::render('<tedi:filter text="Kõik" />');

        $this->assertHasClass('tedi-filter', $html, 'tedi-filter');
        $this->assertMissingClass('tedi-filter--disabled', $html, 'tedi-filter');
        $this->assertStringContainsString('aria-pressed="false"', $html);
    }

    // -- top-nav -> top-nav-item @aware(['submenuFit', 'panelId']) ----------
    //    top-nav -> top-nav-submenu @aware(['panelId', 'maxWidth'])
    //    (CONVENTIONS.md §13 — the React-sourced components.)

    public function test_top_nav_submenu_fit_and_panel_id_reach_the_item(): void
    {
        $html = Blade::render(
            '<tedi:top-nav submenu-fit="content" panel-id="peamenüü">'
            .'<tedi:top-nav-item key="a">A<x-slot:submenu>p</x-slot:submenu> </tedi:top-nav-item>'
            .'</tedi:top-nav>'
        );

        // submenuFit=content is what makes the item render its own panel…
        $this->assertHasClass('tedi-top-nav__item--has-inline-submenu', $html, 'tedi-top-nav__item');
        // …and panelId is what names it.
        $this->assertStringContainsString('id="peamenüü-a"', $html);
        $this->assertStringContainsString('aria-controls="peamenüü-a"', $html);
    }

    public function test_top_nav_item_fallbacks_match_the_nav_defaults_when_omitted(): void
    {
        $item = '<tedi:top-nav-item key="a">A<x-slot:submenu>p</x-slot:submenu> </tedi:top-nav-item>';

        $omitted = Blade::render('<tedi:top-nav>'.$item.'</tedi:top-nav>');
        $explicit = Blade::render(
            '<tedi:top-nav submenu-fit="full" panel-id="top-nav-submenu">'.$item.'</tedi:top-nav>'
        );

        $this->assertSame($explicit, $omitted,
            'Omitting submenu-fit/panel-id must render identically to passing their defaults.');
        $this->assertMissingClass('tedi-top-nav__item--has-inline-submenu', $omitted, 'tedi-top-nav__item');
    }

    public function test_top_nav_panel_id_and_max_width_reach_the_submenu(): void
    {
        $html = Blade::render(
            '<tedi:top-nav panel-id="peamenüü" max-width="lg">'
            .'<x-slot:submenu><tedi:top-nav-submenu for="a">p</tedi:top-nav-submenu></x-slot:submenu> '
            .'</tedi:top-nav>'
        );

        $this->assertStringContainsString('id="peamenüü-a"', $html);
        // 62rem is the lg breakpoint's min-width, and it must reach the panel's
        // inner as well as the item list.
        $this->assertSame(2, substr_count($html, 'max-width: 62rem'));
    }

    public function test_top_nav_submenu_fallbacks_match_the_nav_defaults_when_omitted(): void
    {
        $panel = '<x-slot:submenu><tedi:top-nav-submenu for="a">p</tedi:top-nav-submenu></x-slot:submenu> ';

        $omitted = Blade::render('<tedi:top-nav>'.$panel.'</tedi:top-nav>');
        $explicit = Blade::render(
            '<tedi:top-nav panel-id="top-nav-submenu" max-width="xxl">'.$panel.'</tedi:top-nav>'
        );

        $this->assertSame($explicit, $omitted,
            'Omitting panel-id/max-width must render identically to passing their defaults.');
    }

    public function test_top_nav_children_render_standalone_without_a_nav(): void
    {
        $item = Blade::render('<tedi:top-nav-item key="a">A</tedi:top-nav-item>');
        $this->assertHasClass('tedi-top-nav__item', $item, 'tedi-top-nav__item');
        $this->assertStringContainsString('aria-controls="top-nav-submenu-a"', $item);

        $panel = Blade::render('<tedi:top-nav-submenu for="a">p</tedi:top-nav-submenu>');
        $this->assertHasClass('tedi-top-nav__submenu', $panel, 'tedi-top-nav__submenu');
        $this->assertStringContainsString('id="top-nav-submenu-a"', $panel);
        $this->assertStringContainsString('max-width: 87.5rem', $panel);
    }
}
