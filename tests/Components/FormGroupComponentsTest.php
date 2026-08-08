<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Parity tests for <tedi:checkbox-group> and <tedi:radio-group>.
 *
 * The `@aware` propagation to <tedi:checkbox> / <tedi:radio> lives in
 * tests/AwareTest.php alongside every other parent/child pair.
 */
class FormGroupComponentsTest extends TestCase
{
    /**
     * The opening root <div> only. Scoping negative attribute assertions to it
     * keeps them off descendants (<tedi:feedback-text> emits aria-live) and
     * makes `aria-label=` unambiguous against `aria-labelledby=`, which it is
     * a substring of.
     */
    private function rootTag(string $html): string
    {
        $this->assertSame(1, preg_match('/<div\\b[^>]*>/', $html, $m), $html);

        return $m[0];
    }

    // -- checkbox-group -----------------------------------------------------

    public function test_checkbox_group_renders_a_div_with_the_host_class(): void
    {
        $html = Blade::render('<tedi:checkbox-group>x</tedi:checkbox-group>');

        $this->assertStringContainsString('<div', $html);
        $this->assertStringNotContainsString('<tedi-checkbox-group', $html);
        $this->assertHasClass('tedi-checkbox-group', $html, on: 'tedi-checkbox-group');
    }

    public function test_checkbox_group_direction_classes(): void
    {
        $horizontal = Blade::render('<tedi:checkbox-group direction="horizontal">x</tedi:checkbox-group>');
        $this->assertHasClass('tedi-checkbox-group__checks', $horizontal, on: 'tedi-checkbox-group__checks');
        $this->assertMissingClass('tedi-checkbox-group__checks--vertical', $horizontal, on: 'tedi-checkbox-group__checks');

        $vertical = Blade::render('<tedi:checkbox-group direction="vertical">x</tedi:checkbox-group>');
        $this->assertHasClass('tedi-checkbox-group__checks--vertical', $vertical, on: 'tedi-checkbox-group__checks');
    }

    public function test_checkbox_group_defaults_to_horizontal(): void
    {
        $html = Blade::render('<tedi:checkbox-group>x</tedi:checkbox-group>');

        $this->assertMissingClass('tedi-checkbox-group__checks--vertical', $html, on: 'tedi-checkbox-group__checks');
    }

    public function test_checkbox_group_drops_the_unstyled_label_class(): void
    {
        // tedi-checkbox-group__label has no rule in dist/tedi.css, so per
        // CONVENTIONS.md §4 the class is dropped while the <p> stays.
        $html = Blade::render('<tedi:checkbox-group label="Label">x</tedi:checkbox-group>');

        $this->assertStringContainsString('Label</p>', $html);
        $this->assertMissingClass('tedi-checkbox-group__label', $html, on: 'tedi-text--secondary');
        $this->assertHasClass('tedi-text--secondary', $html, on: 'tedi-text--secondary');
    }

    public function test_checkbox_group_omits_the_label_paragraph_when_no_label(): void
    {
        $html = Blade::render('<tedi:checkbox-group>x</tedi:checkbox-group>');

        $this->assertStringNotContainsString('<p ', $html);
    }

    public function test_checkbox_group_subtexts_wrapper_is_always_rendered_and_empty_when_unused(): void
    {
        // The SCSS hides it with `&:empty`, so not even whitespace may leak in.
        $html = Blade::render('<tedi:checkbox-group>x</tedi:checkbox-group>');

        $this->assertStringContainsString('class="tedi-checkbox-group__subtexts"></div>', $html);
    }

    public function test_checkbox_group_subtexts_slot_renders_feedback_text(): void
    {
        $html = Blade::render(
            '<tedi:checkbox-group>x'
            .'<x-slot:subtexts><tedi:feedback-text text="Hint text" /></x-slot:subtexts>'
            .'</tedi:checkbox-group>'
        );

        $this->assertStringContainsString('Hint text', $html);
        $this->assertHasClass('tedi-feedback-text', $html, on: 'tedi-feedback-text');
    }

    public function test_checkbox_group_emits_no_group_aria_when_unmanaged(): void
    {
        $root = $this->rootTag(Blade::render(
            '<tedi:checkbox-group label="Label" disabled aria-label="Group">x</tedi:checkbox-group>'
        ));

        $this->assertStringNotContainsString('role=', $root);
        $this->assertStringNotContainsString('aria-labelledby=', $root);
        $this->assertStringNotContainsString('aria-label=', $root);
        $this->assertStringNotContainsString('aria-disabled=', $root);
    }

    public function test_checkbox_group_managed_emits_role_group(): void
    {
        $html = Blade::render('<tedi:checkbox-group managed>x</tedi:checkbox-group>');

        $this->assertStringContainsString('role="group"', $html);
    }

    public function test_checkbox_group_managed_aria_labelledby_points_at_the_label_paragraph(): void
    {
        $html = Blade::render('<tedi:checkbox-group managed label="Label">x</tedi:checkbox-group>');

        $this->assertSame(1, preg_match('/<p class="[^"]*" id="([^"]+)">Label<\/p>/', $html, $m), $html);
        $this->assertStringContainsString('aria-labelledby="'.$m[1].'"', $html);
        $this->assertStringNotContainsString('aria-label=', str_replace('aria-labelledby=', '', $this->rootTag($html)));
    }

    public function test_checkbox_group_managed_falls_back_to_external_aria_labelledby(): void
    {
        $html = Blade::render('<tedi:checkbox-group managed aria-labelledby="external">x</tedi:checkbox-group>');

        $this->assertStringContainsString('aria-labelledby="external"', $html);
        $this->assertStringNotContainsString('aria-label=', str_replace('aria-labelledby=', '', $this->rootTag($html)));
    }

    public function test_checkbox_group_managed_aria_label_only_without_label_or_labelledby(): void
    {
        $withLabel = Blade::render('<tedi:checkbox-group managed label="Label" aria-label="Group">x</tedi:checkbox-group>');
        $this->assertStringNotContainsString('aria-label="Group"', $withLabel);

        $bare = Blade::render('<tedi:checkbox-group managed aria-label="Group">x</tedi:checkbox-group>');
        $this->assertStringContainsString('aria-label="Group"', $bare);
        $this->assertStringNotContainsString('aria-labelledby=', $this->rootTag($bare));
    }

    public function test_checkbox_group_managed_aria_disabled_only_when_disabled(): void
    {
        $enabled = Blade::render('<tedi:checkbox-group managed>x</tedi:checkbox-group>');
        $this->assertStringNotContainsString('aria-disabled=', $this->rootTag($enabled));

        $disabled = Blade::render('<tedi:checkbox-group managed disabled>x</tedi:checkbox-group>');
        $this->assertStringContainsString('aria-disabled="true"', $disabled);
    }

    public function test_checkbox_group_props_do_not_leak_to_the_dom(): void
    {
        $html = Blade::render('<tedi:checkbox-group :values="[\'a\']" direction="vertical" managed>x</tedi:checkbox-group>');

        $this->assertStringNotContainsString('values=', $html);
        $this->assertStringNotContainsString('direction=', $html);
        $this->assertStringNotContainsString('managed=', $html);
    }

    public function test_checkbox_group_merges_consumer_classes_and_attributes(): void
    {
        $html = Blade::render('<tedi:checkbox-group class="mine" id="cbg">x</tedi:checkbox-group>');

        $this->assertHasClass('tedi-checkbox-group', $html, on: 'tedi-checkbox-group');
        $this->assertHasClass('mine', $html, on: 'tedi-checkbox-group');
        $this->assertStringContainsString('id="cbg"', $html);
    }

    public function test_checkbox_group_consumer_role_wins_over_the_computed_one(): void
    {
        $html = Blade::render('<tedi:checkbox-group managed role="presentation">x</tedi:checkbox-group>');

        $this->assertStringContainsString('role="presentation"', $html);
        $this->assertStringNotContainsString('role="group"', $html);
    }

    // -- radio-group --------------------------------------------------------

    public function test_radio_group_renders_a_div_with_the_host_class(): void
    {
        $html = Blade::render('<tedi:radio-group>x</tedi:radio-group>');

        $this->assertStringContainsString('<div', $html);
        $this->assertStringNotContainsString('<tedi-radio-group', $html);
        $this->assertHasClass('tedi-radio-group', $html, on: 'tedi-radio-group');
    }

    public function test_radio_group_direction_classes(): void
    {
        $horizontal = Blade::render('<tedi:radio-group direction="horizontal">x</tedi:radio-group>');
        $this->assertHasClass('tedi-radio-group__checks', $horizontal, on: 'tedi-radio-group__checks');
        $this->assertMissingClass('tedi-radio-group__checks--vertical', $horizontal, on: 'tedi-radio-group__checks');

        $vertical = Blade::render('<tedi:radio-group direction="vertical">x</tedi:radio-group>');
        $this->assertHasClass('tedi-radio-group__checks--vertical', $vertical, on: 'tedi-radio-group__checks');
    }

    public function test_radio_group_defaults_to_horizontal(): void
    {
        $html = Blade::render('<tedi:radio-group>x</tedi:radio-group>');

        $this->assertMissingClass('tedi-radio-group__checks--vertical', $html, on: 'tedi-radio-group__checks');
    }

    public function test_radio_group_drops_the_unstyled_label_class(): void
    {
        $html = Blade::render('<tedi:radio-group label="Label">x</tedi:radio-group>');

        $this->assertStringContainsString('Label</p>', $html);
        $this->assertMissingClass('tedi-radio-group__label', $html, on: 'tedi-text--secondary');
        $this->assertHasClass('tedi-text--secondary', $html, on: 'tedi-text--secondary');
    }

    public function test_radio_group_subtexts_wrapper_is_always_rendered_and_empty_when_unused(): void
    {
        $html = Blade::render('<tedi:radio-group>x</tedi:radio-group>');

        $this->assertStringContainsString('class="tedi-radio-group__subtexts"></div>', $html);
    }

    public function test_radio_group_subtexts_slot_renders_feedback_text(): void
    {
        $html = Blade::render(
            '<tedi:radio-group>x'
            .'<x-slot:subtexts><tedi:feedback-text type="error" text="Feedback text" /></x-slot:subtexts>'
            .'</tedi:radio-group>'
        );

        $this->assertStringContainsString('Feedback text', $html);
        $this->assertHasClass('tedi-feedback-text--error', $html, on: 'tedi-feedback-text');
    }

    public function test_radio_group_emits_no_group_aria_when_unmanaged(): void
    {
        $root = $this->rootTag(Blade::render(
            '<tedi:radio-group label="Label" disabled aria-label="Group">x</tedi:radio-group>'
        ));

        $this->assertStringNotContainsString('role=', $root);
        $this->assertStringNotContainsString('aria-labelledby=', $root);
        $this->assertStringNotContainsString('aria-label=', $root);
        $this->assertStringNotContainsString('aria-disabled=', $root);
    }

    public function test_radio_group_managed_emits_role_radiogroup(): void
    {
        $html = Blade::render('<tedi:radio-group managed>x</tedi:radio-group>');

        $this->assertStringContainsString('role="radiogroup"', $html);
    }

    public function test_radio_group_managed_aria_labelledby_points_at_the_label_paragraph(): void
    {
        $html = Blade::render('<tedi:radio-group managed label="Label">x</tedi:radio-group>');

        $this->assertSame(1, preg_match('/<p class="[^"]*" id="([^"]+)">Label<\/p>/', $html, $m), $html);
        $this->assertStringContainsString('aria-labelledby="'.$m[1].'"', $html);
    }

    public function test_radio_group_managed_aria_label_only_without_label_or_labelledby(): void
    {
        $withLabelledby = Blade::render('<tedi:radio-group managed aria-labelledby="external" aria-label="Group">x</tedi:radio-group>');
        $this->assertStringContainsString('aria-labelledby="external"', $withLabelledby);
        $this->assertStringNotContainsString('aria-label="Group"', $withLabelledby);

        $bare = Blade::render('<tedi:radio-group managed aria-label="Group">x</tedi:radio-group>');
        $this->assertStringContainsString('aria-label="Group"', $bare);
    }

    public function test_radio_group_managed_aria_disabled_only_when_disabled(): void
    {
        $enabled = Blade::render('<tedi:radio-group managed>x</tedi:radio-group>');
        $this->assertStringNotContainsString('aria-disabled=', $this->rootTag($enabled));

        $disabled = Blade::render('<tedi:radio-group managed disabled>x</tedi:radio-group>');
        $this->assertStringContainsString('aria-disabled="true"', $disabled);
    }

    public function test_radio_group_props_do_not_leak_to_the_dom(): void
    {
        $html = Blade::render('<tedi:radio-group value="a" name="g" direction="vertical" managed>x</tedi:radio-group>');

        $this->assertStringNotContainsString('value=', $html);
        $this->assertStringNotContainsString('name=', $html);
        $this->assertStringNotContainsString('direction=', $html);
        $this->assertStringNotContainsString('managed=', $html);
    }

    public function test_radio_group_merges_consumer_classes_and_attributes(): void
    {
        $html = Blade::render('<tedi:radio-group class="mine" id="rg">x</tedi:radio-group>');

        $this->assertHasClass('tedi-radio-group', $html, on: 'tedi-radio-group');
        $this->assertHasClass('mine', $html, on: 'tedi-radio-group');
        $this->assertStringContainsString('id="rg"', $html);
    }

    public function test_radio_group_consumer_aria_label_wins_over_the_computed_one(): void
    {
        // merge() lets a consumer value win — but `aria-label` is a declared
        // prop, so it is the prop path that carries it, not the bag.
        $html = Blade::render('<tedi:radio-group managed aria-label="Mine">x</tedi:radio-group>');

        $this->assertStringContainsString('aria-label="Mine"', $html);
        $this->assertSame(1, substr_count($this->rootTag($html), 'aria-label='));
    }
}
