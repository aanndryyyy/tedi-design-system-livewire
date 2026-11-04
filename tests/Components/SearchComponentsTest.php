<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class SearchComponentsTest extends TestCase
{
    private const PEOPLE = "[['label' => 'Mari Maasikas', 'description' => 'Tootejuht'], ['label' => 'Marelle Mets', 'description' => 'Disainer', 'disabled' => true], ['label' => 'Kalle Kask', 'description' => 'Testija']]";

    // -- plain search ---------------------------------------------------------

    public function test_search_root_is_a_div_with_the_host_class_and_role(): void
    {
        $html = Blade::render('<tedi:search />');

        $this->assertHasClass('tedi-search', $html, on: 'tedi-search');
        $this->assertStringContainsString('role="search"', $html);
    }

    public function test_search_renders_with_no_props_and_generates_an_input_id(): void
    {
        $html = Blade::render('<tedi:search />');

        $this->assertMatchesRegularExpression('/id="tedi-search-[a-z0-9]+"/', $html);
    }

    public function test_search_input_is_a_text_field_with_the_searchbox_role(): void
    {
        $html = Blade::render('<tedi:search input-id="s" />');

        $this->assertHasClass('tedi-text-field', $html, on: 'tedi-text-field');
        $this->assertStringContainsString('role="searchbox"', $html);
        $this->assertStringContainsString('id="s"', $html);
    }

    public function test_search_field_height_custom_property_per_size(): void
    {
        $expected = [
            'small' => 'var(--form-field-height-sm)',
            'default' => 'var(--form-field-height)',
            'large' => 'var(--form-field-height-lg)',
        ];

        foreach ($expected as $size => $value) {
            $html = Blade::render('<tedi:search size="'.$size.'" />');

            $this->assertStringContainsString('--tedi-search-field-height: '.$value, $html);
        }
    }

    public function test_search_button_icon_only_modifier(): void
    {
        $none = Blade::render('<tedi:search />');
        $this->assertMissingClass('tedi-search--button-icon-only', $none, on: 'tedi-search');

        $iconOnly = Blade::render('<tedi:search :button="[]" />');
        $this->assertHasClass('tedi-search--button-icon-only', $iconOnly, on: 'tedi-search');

        $withText = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertMissingClass('tedi-search--button-icon-only', $withText, on: 'tedi-search');
    }

    public function test_search_wraps_the_form_field_in_the_field_element(): void
    {
        $html = Blade::render('<tedi:search />');

        $this->assertHasClass('tedi-search__field', $html, on: 'tedi-search__field');
        $this->assertHasClass('tedi-form-field', $html, on: 'tedi-form-field');
    }

    public function test_search_shows_the_inline_icon_only_without_a_button(): void
    {
        $withIcon = Blade::render('<tedi:search />');
        $this->assertHasClass('tedi-form-field__icon', $withIcon, on: 'tedi-form-field__icon');

        $withButton = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertMissingClass('tedi-form-field__icon', $withButton, on: 'tedi-form-field__icon');
    }

    public function test_search_has_button_modifier_sits_on_the_host(): void
    {
        // Angular 8 moved the hook from the input box (the old
        // tedi-search__input--has-button inputClass) to the host.
        $plain = Blade::render('<tedi:search />');
        $this->assertMissingClass('tedi-search--has-button', $plain, on: 'tedi-search');

        $withButton = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertHasClass('tedi-search--has-button', $withButton, on: 'tedi-search');
        $this->assertMissingClass('tedi-search__input--has-button', $withButton);

        $iconOnly = Blade::render('<tedi:search :button="[]" />');
        $this->assertHasClass('tedi-search--has-button', $iconOnly, on: 'tedi-search');
    }

    public function test_search_button_classes_and_variant(): void
    {
        $html = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\', \'variant\' => \'secondary\']" />');

        $this->assertHasClass('tedi-search__button', $html, on: 'tedi-search__button');
        $this->assertHasClass('tedi-button--secondary', $html, on: 'tedi-search__button');
        // Icon-then-text: only the right-hand padding modifier survives.
        $this->assertHasClass('tedi-button--pr', $html, on: 'tedi-search__button');
        $this->assertMissingClass('tedi-button--pl', $html, on: 'tedi-search__button');
        $this->assertStringContainsString('Otsi', $html);
    }

    public function test_search_icon_only_button_classes(): void
    {
        $html = Blade::render('<tedi:search :button="[\'ariaLabel\' => \'Otsi\']" />');

        $this->assertHasClass('tedi-button--icon-only', $html, on: 'tedi-search__button');
        $this->assertMissingClass('tedi-button--pl', $html, on: 'tedi-search__button');
        $this->assertMissingClass('tedi-button--pr', $html, on: 'tedi-search__button');
        $this->assertStringContainsString('aria-label="Otsi"', $html);
    }

    public function test_search_button_size_follows_the_field_size(): void
    {
        $this->assertHasClass('tedi-button--small',
            Blade::render('<tedi:search size="small" :button="[]" />'), on: 'tedi-search__button');

        foreach (['default', 'large'] as $size) {
            $this->assertHasClass('tedi-button--default',
                Blade::render('<tedi:search size="'.$size.'" :button="[]" />'), on: 'tedi-search__button');
        }
    }

    public function test_search_button_icon_size_follows_the_field_size(): void
    {
        // buttonIconSize(): 24 (--icon-05) for large, 18 (--icon-03) otherwise.
        // Only exercised on the icon-only branch, where this component renders
        // the icon itself rather than delegating to <tedi:button>'s icon-start.
        $this->assertStringContainsString('--_tedi-icon-size: var(--icon-05)',
            Blade::render('<tedi:search size="large" :button="[]" />'));

        foreach (['default', 'small'] as $size) {
            $this->assertStringContainsString('--_tedi-icon-size: var(--icon-03)',
                Blade::render('<tedi:search size="'.$size.'" :button="[]" />'));
        }
    }

    public function test_search_aria_label_falls_back_through_label_then_placeholder(): void
    {
        $this->assertStringContainsString('aria-label="Otsi tooteid"',
            Blade::render('<tedi:search aria-label="Otsi tooteid" label="Otsing" placeholder="Trüki" />'));

        $this->assertStringContainsString('aria-label="Otsing"',
            Blade::render('<tedi:search label="Otsing" placeholder="Trüki" />'));

        $this->assertStringContainsString('aria-label="Trüki"',
            Blade::render('<tedi:search placeholder="Trüki" />'));

        $this->assertStringContainsString('aria-label="Search"', Blade::render('<tedi:search />'));
    }

    public function test_search_input_aria_label_is_suppressed_when_a_visible_label_exists(): void
    {
        $labelled = Blade::render('<tedi:search input-id="s" label="Otsing" />');
        $this->assertStringContainsString('for="s"', $labelled);
        // The input is named by the visible <label>, so aria-label would override it.
        $this->assertDoesNotMatchRegularExpression('/<input\s+tedi-text-field[^>]*aria-label=/s', $labelled);

        $unlabelled = Blade::render('<tedi:search input-id="s" placeholder="Trüki" />');
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*aria-label="Trüki"/s', $unlabelled);
    }

    public function test_search_feedback_text_is_wired_by_id(): void
    {
        $html = Blade::render('<tedi:search input-id="s" :feedback-text="[\'text\' => \'Vihjetekst\']" />');

        $this->assertStringContainsString('aria-describedby="s-feedback"', $html);
        $this->assertStringContainsString('id="s-feedback"', $html);
        $this->assertStringContainsString('Vihjetekst', $html);
    }

    public function test_search_without_feedback_text_emits_no_describedby(): void
    {
        $this->assertStringNotContainsString('aria-describedby', Blade::render('<tedi:search />'));
    }

    public function test_search_feedback_type_drives_the_form_field_state(): void
    {
        $error = Blade::render('<tedi:search :feedback-text="[\'text\' => \'Viga\', \'type\' => \'error\']" />');
        $this->assertHasClass('tedi-field-surface--invalid', $error, on: 'tedi-field-surface');

        $valid = Blade::render('<tedi:search :feedback-text="[\'text\' => \'Sobib\', \'type\' => \'valid\']" />');
        $this->assertHasClass('tedi-field-surface--valid', $valid, on: 'tedi-field-surface');

        $hint = Blade::render('<tedi:search :feedback-text="[\'text\' => \'Vihje\']" />');
        $this->assertMissingClass('tedi-field-surface--invalid', $hint, on: 'tedi-field-surface');
        $this->assertMissingClass('tedi-field-surface--valid', $hint, on: 'tedi-field-surface');
    }

    public function test_search_size_reaches_the_form_field(): void
    {
        $this->assertHasClass('tedi-form-field--small',
            Blade::render('<tedi:search size="small" />'), on: 'tedi-form-field');
        $this->assertHasClass('tedi-form-field--large',
            Blade::render('<tedi:search size="large" />'), on: 'tedi-form-field');
    }

    public function test_search_disabled_reaches_the_form_field_and_the_input(): void
    {
        $html = Blade::render('<tedi:search :disabled="true" />');

        $this->assertHasClass('tedi-field-surface--disabled', $html, on: 'tedi-field-surface');
        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field\s+disabled/', $html);
    }

    public function test_search_clear_button_appears_only_when_the_field_has_a_value(): void
    {
        $empty = Blade::render('<tedi:search />');
        $this->assertHasClass('tedi-form-field__buttons--hidden', $empty, on: 'tedi-form-field__buttons--hidden');

        $filled = Blade::render('<tedi:search value="Lorem ipsum" />');
        $this->assertMissingClass('tedi-form-field__buttons--hidden', $filled, on: 'tedi-form-field__buttons');
        $this->assertStringContainsString('value="Lorem ipsum"', $filled);
    }

    public function test_search_clearable_false_drops_the_clear_button(): void
    {
        $html = Blade::render('<tedi:search value="Lorem" :clearable="false" />');

        $this->assertMissingClass('tedi-form-field__clear', $html, on: 'tedi-form-field__clear');
    }

    public function test_search_forwards_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:search wire:model="query" />');

        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*wire:model="query"/s', $html);
    }

    public function test_search_forwards_button_and_clear_attributes(): void
    {
        $html = Blade::render(
            '<tedi:search value="Lorem"'
            .' :button="[\'text\' => \'Otsi\']"'
            .' :button-attributes="[\'wire:click\' => \'search\']"'
            .' :clear-attributes="[\'wire:click\' => \'reset\']" />'
        );

        $this->assertMatchesRegularExpression('/tedi-search__button[^>]*wire:click="search"/s', $html);
        $this->assertMatchesRegularExpression('/tedi-form-field__clear[^>]*wire:click="reset"/s', $html);
    }

    public function test_search_show_clear_on_interaction_reaches_the_form_field(): void
    {
        // Angular 9.0 (#569): the form-field host gets the modifier when the
        // option is on and the clear button is showing.
        $on = Blade::render('<tedi:search value="x" :show-clear-on-interaction="true" />');
        $this->assertHasClass('tedi-form-field--clear-on-interaction', $on, on: 'tedi-form-field');

        $off = Blade::render('<tedi:search value="x" />');
        $this->assertMissingClass('tedi-form-field--clear-on-interaction', $off, on: 'tedi-form-field');
    }

    public function test_search_show_clear_on_interaction_reaches_the_form_field_only_when_set(): void
    {
        // Default false: nothing is passed down, so no stray attribute can leak.
        $this->assertStringNotContainsString('show-clear-on-interaction', Blade::render('<tedi:search value="x" />'));
    }

    public function test_plain_search_carries_no_alpine_and_no_panel(): void
    {
        $html = Blade::render('<tedi:search input-id="s" />');

        // The form-field's clear button carries its own tediFormField scope;
        // plain mode must not pull in the autocomplete behaviour.
        $this->assertStringNotContainsString('tediSearch', $html);
        $this->assertStringNotContainsString('aria-autocomplete', $html);
        $this->assertStringNotContainsString('aria-expanded', $html);
        $this->assertMissingClass('tedi-search__panel', $html);
    }

    public function test_search_always_renders_the_polite_live_region(): void
    {
        $html = Blade::render('<tedi:search input-id="s" />');

        // tedi-search__announcement has no rule in the vendored SCSS: dropped.
        $this->assertMatchesRegularExpression('/<div\s+class="sr-only"\s+role="status"\s+aria-live="polite"\s+aria-atomic="true"\s*>\s*<\/div>/', $html);
        $this->assertMissingClass('tedi-search__announcement', $html);
    }

    // -- autocomplete ---------------------------------------------------------

    public function test_autocomplete_turns_the_input_into_a_closed_combobox(): void
    {
        $html = Blade::render('<tedi:search input-id="s" :suggestions="'.self::PEOPLE.'" />');

        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*role="combobox"/s', $html);
        $this->assertStringContainsString('aria-autocomplete="list"', $html);
        // The string "false", not a dropped boolean attribute.
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringNotContainsString(' aria-controls=', $html);
        $this->assertStringContainsString('x-data="tediSearch(', $html);
        $this->assertStringContainsString('x-ref="trigger"', $html);
    }

    public function test_an_empty_suggestion_list_still_means_combobox(): void
    {
        $html = Blade::render('<tedi:search input-id="s" :suggestions="[]" />');

        $this->assertStringContainsString('role="combobox"', $html);
        $this->assertHasClass('tedi-search__panel', $html, on: 'tedi-search__panel');
    }

    public function test_autocomplete_panel_is_in_the_dom_and_cloaked_while_closed(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="Mar" :suggestions="'.self::PEOPLE.'" />');

        $this->assertMatchesRegularExpression('/class="tedi-search__panel"[^>]*x-show="panelVisible"\s+x-cloak/s', $html);
        $this->assertStringContainsString('data-tedi-search-state="results"', $html);
        $this->assertMatchesRegularExpression('/<ul class="tedi-search__panel-list" role="listbox" id="s-listbox">/', $html);
    }

    public function test_open_panel_expands_and_controls_the_listbox(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="Mar" :panel-open="true" :suggestions="'.self::PEOPLE.'" />');

        $this->assertStringContainsString('aria-expanded="true"', $html);
        $this->assertStringContainsString('aria-controls="s-listbox"', $html);
        $this->assertMatchesRegularExpression('/role="status"[^>]*>\s*3 results<\/div>/', $html);
    }

    public function test_min_query_length_keeps_the_panel_shut(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="Ma" :min-query-length="3" :panel-open="true" :suggestions="'.self::PEOPLE.'" />');

        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('\\u0022minQueryLength\\u0022:3', $html);
    }

    public function test_an_open_empty_query_with_no_results_stays_shut(): void
    {
        // panelVisible(): loading || hasSuggestions || query.length > 0.
        $html = Blade::render('<tedi:search input-id="s" :panel-open="true" :suggestions="[]" />');

        $this->assertStringContainsString('aria-expanded="false"', $html);
    }

    public function test_suggestion_rows_have_upstream_ids_and_option_markup(): void
    {
        $html = Blade::render('<tedi:search input-id="s" :suggestions="'.self::PEOPLE.'" />');

        foreach ([0, 1, 2] as $i) {
            $this->assertMatchesRegularExpression('/<li\s+tedi-search-suggestion\s+role="option"\s+tabindex="-1"\s+id="s-opt-'.$i.'" aria-selected="false"/', $html);
        }

        $this->assertMatchesRegularExpression('/id="s-opt-1" aria-selected="false" aria-disabled="true"/', $html);
        $this->assertHasClass('tedi-search-suggestion--disabled', $html, on: 'tedi-search-suggestion--disabled');
        $this->assertSame(1, substr_count($html, 'aria-disabled="true"'));
    }

    public function test_suggestion_label_bolds_the_matched_segment_with_no_gaps(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value=" ari " :suggestions="[\'Mari Maasikas\']" />');

        $this->assertStringContainsString(
            '<span class="tedi-search-suggestion__label"><span class="tedi-text--primary">M</span><span class="tedi-text--primary text-bold">ari</span><span class="tedi-text--primary"> Maasikas</span></span>',
            $html
        );
    }

    public function test_highlight_is_case_insensitive_and_multibyte_safe(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="MÄN" :suggestions="[\'Margit Mänd\']" />');

        $this->assertStringContainsString('<span class="tedi-text--primary">Margit </span><span class="tedi-text--primary text-bold">Män</span><span class="tedi-text--primary">d</span>', $html);
    }

    public function test_no_match_renders_the_label_as_one_segment(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="zzz" :suggestions="[\'Kalle Kask\']" />');

        $this->assertStringContainsString('<span class="tedi-search-suggestion__label"><span class="tedi-text--primary">Kalle Kask</span></span>', $html);
    }

    public function test_description_needs_bind_description(): void
    {
        $without = Blade::render('<tedi:search input-id="s" :suggestions="'.self::PEOPLE.'" />');
        $this->assertMissingClass('tedi-search-suggestion__description', $without);

        $with = Blade::render('<tedi:search input-id="s" bind-description="description" :suggestions="'.self::PEOPLE.'" />');
        $this->assertSame(['tedi-search-suggestion__description', 'tedi-text--tertiary', 'text-small'],
            $this->classesOf($with, 'tedi-search-suggestion__description'));
        $this->assertStringContainsString('>Tootejuht</span>', $with);
    }

    public function test_bind_label_and_bind_disabled_resolve_from_records_and_objects(): void
    {
        $html = Blade::render(
            '<tedi:search input-id="s" bind-label="name" bind-disabled="off" :suggestions="$items" />',
            ['items' => [['name' => 'Laura Kask', 'off' => true], (object) ['name' => 'Laur Lepik']]]
        );

        $this->assertStringContainsString('data-tedi-search-label="Laura Kask"', $html);
        $this->assertStringContainsString('data-tedi-search-label="Laur Lepik"', $html);
        $this->assertSame(1, substr_count($html, 'aria-disabled="true"'));
    }

    public function test_built_in_rows_carry_the_item_as_json_for_the_select_event(): void
    {
        $html = Blade::render(
            '<tedi:search input-id="s" bind-label="name" :suggestions="$items" />',
            ['items' => [['name' => 'Laura Kask', 'code' => '48611230123']]]
        );

        $this->assertStringContainsString(
            'data-tedi-search-item="'.e(json_encode(['name' => 'Laura Kask', 'code' => '48611230123'])).'"',
            $html
        );
    }

    public function test_panel_state_lives_on_a_hidden_child_so_a_morph_can_patch_it(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="Mar" :suggestions="[\'Mari\']" />');

        $this->assertMatchesRegularExpression('/<span hidden data-tedi-search-state="results" data-tedi-search-announcement="1 result"><\/span>/', $html);
        $this->assertDoesNotMatchRegularExpression('/class="tedi-search__panel"[^>]*data-tedi-search-state/s', $html);
    }

    public function test_loading_replaces_the_results_with_a_status_row(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="Mar" :loading="true" :suggestions="'.self::PEOPLE.'" />');

        $this->assertStringContainsString('data-tedi-search-state="loading"', $html);
        $this->assertHasClass('tedi-search__panel-status', $html, on: 'tedi-search__panel-status');
        $this->assertHasClass('tedi-spinner', $html, on: 'tedi-spinner');
        $this->assertStringContainsString('Searching...', $html);
        $this->assertStringNotContainsString('role="listbox"', $html);
        $this->assertStringNotContainsString(' aria-controls=', $html);
    }

    public function test_no_results_row_and_its_override(): void
    {
        $html = Blade::render('<tedi:search input-id="s" value="xyz" :suggestions="[]" />');
        $this->assertStringContainsString('data-tedi-search-state="empty"', $html);
        $this->assertStringContainsString('No results found', $html);

        $custom = Blade::render('<tedi:search input-id="s" value="xyz" no-results-text="Ei leitud" loading-text="Laen" :suggestions="[]" />');
        $this->assertStringContainsString('Ei leitud', $custom);
    }

    public function test_results_count_text_accepts_a_closure_or_a_count_string(): void
    {
        $closure = Blade::render(
            '<tedi:search input-id="s" value="a" :panel-open="true" :suggestions="[\'a\', \'b\']" :results-count-text="$fn" />',
            ['fn' => fn ($count) => $count.' vastet']
        );
        $this->assertStringContainsString('data-tedi-search-announcement="2 vastet"', $closure);

        $string = Blade::render('<tedi:search input-id="s" value="a" :suggestions="[\'a\']" results-count-text=":count tulemust" />');
        $this->assertStringContainsString('data-tedi-search-announcement="1 tulemust"', $string);

        $default = Blade::render('<tedi:search input-id="s" value="a" :suggestions="[\'a\']" />');
        $this->assertStringContainsString('data-tedi-search-announcement="1 result"', $default);
    }

    public function test_rows_slot_replaces_the_built_in_rows(): void
    {
        $html = Blade::render(
            '<tedi:search input-id="s" value="Lau" :suggestions="[\'x\']">'
            .'<x-slot:rows>'
            .'<tedi:search-suggestion id="s-opt-0" label="Laura Kask" :custom="true"><b>Laura</b></tedi:search-suggestion>'
            .'</x-slot:rows>'
            .'</tedi:search>'
        );

        $this->assertHasClass('tedi-search-suggestion--custom', $html, on: 'tedi-search-suggestion');
        $this->assertStringContainsString('<b>Laura</b>', $html);
        $this->assertStringContainsString('data-tedi-search-label="Laura Kask"', $html);
        $this->assertStringNotContainsString('data-tedi-search-label="x"', $html);
        $this->assertMissingClass('tedi-search-suggestion__label', $html);
    }

    public function test_footer_slot_renders_in_the_panel(): void
    {
        $html = Blade::render(
            '<tedi:search input-id="s" :suggestions="[]">'
            .'<x-slot:footer><tedi:search-footer-actions><tedi:button variant="secondary">Isik teadmata</tedi:button></tedi:search-footer-actions></x-slot:footer>'
            .'</tedi:search>'
        );

        $this->assertMatchesRegularExpression('/<div class="tedi-search__footer" x-ref="footer">\s*<tedi-search-footer-actions class="tedi-search-footer-actions">/', $html);
    }

    public function test_autocomplete_search_button_emits_the_search_event(): void
    {
        $html = Blade::render('<tedi:search :suggestions="[]" :button="[\'text\' => \'Otsi\']" />');
        $this->assertMatchesRegularExpression('/tedi-search__button[^>]*x-on:click="emitSearch\(\)"/s', $html);

        $plain = Blade::render('<tedi:search :button="[\'text\' => \'Otsi\']" />');
        $this->assertStringNotContainsString('emitSearch', $plain);
    }

    public function test_consumer_input_attributes_still_win_in_combobox_mode(): void
    {
        $html = Blade::render('<tedi:search input-id="s" wire:model.live="query" x-on:tedi-search:select="pick($event.detail)" :suggestions="[]" />');

        $this->assertMatchesRegularExpression('/<input\s+tedi-text-field[^>]*wire:model\.live="query"/s', $html);
        $this->assertStringContainsString('x-on:tedi-search:select="pick($event.detail)"', $html);
    }

    // -- sub-components --------------------------------------------------------

    public function test_search_suggestion_renders_standalone(): void
    {
        $html = Blade::render('<ul><tedi:search-suggestion label="Mari" /></ul>');

        $this->assertSame(['tedi-search-suggestion'], $this->classesOf($html, 'tedi-search-suggestion'));
        $this->assertMatchesRegularExpression('/id="tedi-search-suggestion-[a-z0-9]+"/', $html);
    }

    public function test_search_suggestion_class_matrix(): void
    {
        $cases = [
            ['', ['tedi-search-suggestion']],
            [':custom="true"', ['tedi-search-suggestion', 'tedi-search-suggestion--custom']],
            [':disabled="true"', ['tedi-search-suggestion', 'tedi-search-suggestion--disabled']],
            [':custom="true" :disabled="true"', ['tedi-search-suggestion', 'tedi-search-suggestion--custom', 'tedi-search-suggestion--disabled']],
        ];

        foreach ($cases as [$props, $expected]) {
            $html = Blade::render('<tedi:search-suggestion label="x" '.$props.'>y</tedi:search-suggestion>');

            $this->assertSame($expected, $this->classesOf($html, 'tedi-search-suggestion'), $props);
        }
    }

    public function test_search_footer_actions_is_the_literal_element(): void
    {
        $html = Blade::render('<tedi:search-footer-actions class="x">A</tedi:search-footer-actions>');

        $this->assertMatchesRegularExpression('/^<tedi-search-footer-actions class="tedi-search-footer-actions x">/', trim($html));
    }
}
