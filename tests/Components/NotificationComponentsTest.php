<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class NotificationComponentsTest extends TestCase
{
    /**
     * Assert an exact class token never appears in ANY element's class
     * attribute in the given HTML. TestCase::assertMissingClass() only
     * inspects one selected element (the first, or the first whose class
     * attribute contains `on`); this checks every class attribute in the
     * document, for cases like "this optional element isn't rendered at
     * all". Still exact-token based — never a substring check.
     */
    private function assertClassNeverRendered(string $class, string $html): void
    {
        preg_match_all('/class="([^"]*)"/', $html, $matches);

        foreach ($matches[1] as $classAttr) {
            $tokens = preg_split('/\s+/', trim($classAttr), -1, PREG_SPLIT_NO_EMPTY);
            $this->assertNotContains($class, $tokens, sprintf(
                'Did not expect class [%s] to be rendered anywhere.', $class
            ));
        }
    }

    // --- alert -----------------------------------------------------------

    public function test_alert_type_classes(): void
    {
        foreach (['info', 'success', 'warning', 'danger'] as $type) {
            $html = Blade::render('<tedi:alert type="'.$type.'">Body</tedi:alert>');
            $this->assertHasClass('tedi-alert--'.$type, $html);
        }
    }

    public function test_alert_size_classes(): void
    {
        foreach (['default', 'small'] as $size) {
            $html = Blade::render('<tedi:alert size="'.$size.'">Body</tedi:alert>');
            $this->assertHasClass('tedi-alert--size-'.$size, $html);
        }
    }

    public function test_alert_variant_classes(): void
    {
        $html = Blade::render('<tedi:alert variant="global">Body</tedi:alert>');
        $this->assertHasClass('tedi-alert--global', $html);

        $html = Blade::render('<tedi:alert variant="noSideBorders">Body</tedi:alert>');
        $this->assertHasClass('tedi-alert--no-side-borders', $html);

        $html = Blade::render('<tedi:alert variant="default">Body</tedi:alert>');
        $this->assertMissingClass('tedi-alert--global', $html);
        $this->assertMissingClass('tedi-alert--no-side-borders', $html);
    }

    public function test_alert_role_drives_aria_live_and_role_attribute(): void
    {
        $html = Blade::render('<tedi:alert role="alert">Body</tedi:alert>');
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="assertive"', $html);

        $html = Blade::render('<tedi:alert role="status">Body</tedi:alert>');
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);

        $html = Blade::render('<tedi:alert role="none">Body</tedi:alert>');
        $this->assertStringNotContainsString('role="none"', $html);
        $this->assertStringContainsString('aria-live="off"', $html);
    }

    public function test_alert_title_sets_heading_tag_and_head_wrapper(): void
    {
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
            $html = Blade::render('<tedi:alert title="Pealkiri" title-element="'.$tag.'">Body</tedi:alert>');
            $this->assertStringContainsString('<'.$tag.' class="tedi-alert__title">Pealkiri</'.$tag.'>', $html);
            $this->assertHasClass('tedi-alert__head', $html, on: 'tedi-alert__head');
        }

        $html = Blade::render('<tedi:alert title="Pealkiri" title-element="div">Body</tedi:alert>');
        $this->assertStringContainsString('<div class="tedi-alert__title">Pealkiri</div>', $html);
    }

    public function test_alert_without_title_uses_content_wrapper(): void
    {
        $html = Blade::render('<tedi:alert icon="info">Body</tedi:alert>');
        $this->assertHasClass('tedi-alert__content', $html, on: 'tedi-alert__content');
        $this->assertHasClass('tedi-alert__content-icon', $html, on: 'tedi-alert__content-icon');
        $this->assertClassNeverRendered('tedi-alert__head', $html);
    }

    public function test_alert_open_controls_display_style(): void
    {
        $html = Blade::render('<tedi:alert>Body</tedi:alert>');
        $this->assertStringContainsString('display: flex', $html);

        $html = Blade::render('<tedi:alert :open="false">Body</tedi:alert>');
        $this->assertStringContainsString('display: none', $html);
    }

    public function test_alert_merges_consumer_class_and_does_not_duplicate_aria_attributes(): void
    {
        $html = Blade::render('<tedi:alert class="mine" aria-label="Custom label" title="T" type="info">Body</tedi:alert>');
        $this->assertHasClass('tedi-alert', $html);
        $this->assertHasClass('tedi-alert--info', $html);
        $this->assertHasClass('tedi-alert--size-default', $html);
        $this->assertHasClass('mine', $html);
        $this->assertSame(1, substr_count($html, 'aria-label='));
        $this->assertStringContainsString('aria-label="Custom label"', $html);
        $this->assertSame(1, substr_count($html, ' role='));
    }

    public function test_alert_show_close_renders_closing_button_with_bound_attributes(): void
    {
        $html = Blade::render('<tedi:alert :show-close="true" :close-attributes="[\'wire:click\' => \'dismiss\']">Body</tedi:alert>');
        $this->assertHasClass('tedi-closing-button', $html, on: 'tedi-closing-button');
        $this->assertHasClass('tedi-alert__close', $html, on: 'tedi-alert__close');
        $this->assertStringContainsString('wire:click="dismiss"', $html);

        $html = Blade::render('<tedi:alert>Body</tedi:alert>');
        $this->assertClassNeverRendered('tedi-closing-button', $html);
    }

    public function test_alert_close_delay_prop_is_accepted_for_api_parity(): void
    {
        $html = Blade::render('<tedi:alert :close-delay="300">Body</tedi:alert>');
        $this->assertHasClass('tedi-alert', $html);
    }

    // --- toast -------------------------------------------------------------

    public function test_toast_renders_alert_with_forced_close_and_wrapper_class(): void
    {
        $html = Blade::render('<tedi:toast title="Salvestatud" type="success">Sisu</tedi:toast>');
        $this->assertHasClass('tedi-toast__wrapper', $html);
        $this->assertHasClass('tedi-alert--success', $html, on: 'tedi-alert--success');
        $this->assertHasClass('tedi-closing-button', $html, on: 'tedi-closing-button');
    }

    public function test_toast_progress_bar_renders_only_when_enabled_and_duration_positive(): void
    {
        $html = Blade::render('<tedi:toast :show-progress-bar="true" :duration="4000">Sisu</tedi:toast>');
        $this->assertHasClass('tedi-toast__progress', $html, on: 'tedi-toast__progress');
        $this->assertHasClass('tedi-toast__progress--info', $html, on: 'tedi-toast__progress--info');
        $this->assertStringContainsString('animation-duration: 4000ms', $html);

        $html = Blade::render('<tedi:toast :show-progress-bar="false">Sisu</tedi:toast>');
        $this->assertClassNeverRendered('tedi-toast__progress', $html);

        $html = Blade::render('<tedi:toast :show-progress-bar="true" :duration="0">Sisu</tedi:toast>');
        $this->assertClassNeverRendered('tedi-toast__progress', $html);
    }

    public function test_toast_pause_on_hover_prop_is_accepted_for_api_parity(): void
    {
        $html = Blade::render('<tedi:toast :pause-on-hover="false">Sisu</tedi:toast>');
        $this->assertHasClass('tedi-toast__wrapper', $html);
    }

    public function test_toast_progress_bar_type_and_paused_classes(): void
    {
        foreach (['info', 'success', 'warning', 'danger'] as $type) {
            $html = Blade::render('<tedi:toast type="'.$type.'" :show-progress-bar="true">Sisu</tedi:toast>');
            $this->assertHasClass('tedi-toast__progress--'.$type, $html, on: 'tedi-toast__progress--'.$type);
        }

        $html = Blade::render('<tedi:toast :show-progress-bar="true" :paused="true">Sisu</tedi:toast>');
        $this->assertHasClass('tedi-toast__progress--paused', $html, on: 'tedi-toast__progress--paused');
    }

    // --- status-indicator ----------------------------------------------------

    public function test_status_indicator_type_classes(): void
    {
        foreach (['success', 'danger', 'warning', 'inactive'] as $type) {
            $html = Blade::render('<tedi:status-indicator type="'.$type.'" />');
            $this->assertHasClass('tedi-status-indicator--'.$type, $html);
        }
    }

    public function test_status_indicator_size_classes(): void
    {
        $html = Blade::render('<tedi:status-indicator size="sm" />');
        $this->assertHasClass('tedi-status-indicator--sm', $html);

        $html = Blade::render('<tedi:status-indicator size="lg" />');
        $this->assertHasClass('tedi-status-indicator--lg', $html);
    }

    public function test_status_indicator_bordered_and_position_classes(): void
    {
        $html = Blade::render('<tedi:status-indicator :has-border="true" position="top-right" />');
        $this->assertHasClass('tedi-status-indicator--bordered', $html);
        $this->assertHasClass('tedi-status-indicator--top-right', $html);

        $html = Blade::render('<tedi:status-indicator />');
        $this->assertMissingClass('tedi-status-indicator--bordered', $html);
        $this->assertMissingClass('tedi-status-indicator--top-right', $html);
    }

    public function test_status_indicator_label_toggles_role_and_aria(): void
    {
        $html = Blade::render('<tedi:status-indicator label="Aktiivne" />');
        $this->assertStringContainsString('role="img"', $html);
        $this->assertStringContainsString('aria-label="Aktiivne"', $html);
        $this->assertStringNotContainsString('aria-hidden', $html);

        $html = Blade::render('<tedi:status-indicator />');
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    // --- status-badge --------------------------------------------------------

    public function test_status_badge_color_classes(): void
    {
        foreach (['neutral', 'brand', 'accent', 'success', 'danger', 'warning', 'transparent'] as $color) {
            $html = Blade::render('<tedi:status-badge color="'.$color.'" text="Silt" />');
            $this->assertHasClass('tedi-status-badge--color-'.$color, $html);
        }
    }

    public function test_status_badge_variant_classes(): void
    {
        foreach (['filled', 'filled-bordered', 'bordered'] as $variant) {
            $html = Blade::render('<tedi:status-badge variant="'.$variant.'" text="Silt" />');
            $this->assertHasClass('tedi-status-badge--variant-'.$variant, $html);
        }
    }

    public function test_status_badge_large_class(): void
    {
        $html = Blade::render('<tedi:status-badge size="large" text="Silt" />');
        $this->assertHasClass('tedi-status-badge--large', $html);

        $html = Blade::render('<tedi:status-badge size="default" text="Silt" />');
        $this->assertMissingClass('tedi-status-badge--large', $html);
    }

    public function test_status_badge_icon_only_class(): void
    {
        $html = Blade::render('<tedi:status-badge icon="check" />');
        $this->assertHasClass('tedi-status-badge__icon-only', $html);

        $html = Blade::render('<tedi:status-badge icon="check" text="Silt" />');
        $this->assertMissingClass('tedi-status-badge__icon-only', $html);
    }

    public function test_status_badge_renders_abbr_when_title_given(): void
    {
        $html = Blade::render('<tedi:status-badge text="KKK" title="Korduma kippuvad küsimused" />');
        $this->assertStringContainsString('<abbr', $html);
        $this->assertStringContainsString('title="Korduma kippuvad küsimused"', $html);

        $html = Blade::render('<tedi:status-badge text="Silt" />');
        $this->assertStringNotContainsString('<abbr', $html);
        $this->assertStringContainsString('<div', $html);
    }

    public function test_status_badge_role_drives_aria_live(): void
    {
        $html = Blade::render('<tedi:status-badge text="Silt" role="alert" />');
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="assertive"', $html);

        $html = Blade::render('<tedi:status-badge text="Silt" role="status" />');
        $this->assertStringContainsString('aria-live="polite"', $html);
    }

    public function test_status_badge_status_renders_status_indicator(): void
    {
        $html = Blade::render('<tedi:status-badge text="Silt" status="danger" />');
        $this->assertHasClass('tedi-status-indicator--danger', $html, on: 'tedi-status-indicator--danger');
        $this->assertHasClass('tedi-status-indicator--top-right', $html, on: 'tedi-status-indicator--top-right');
        $this->assertHasClass('tedi-status-indicator--bordered', $html, on: 'tedi-status-indicator--bordered');

        $html = Blade::render('<tedi:status-badge text="Silt" status="danger" size="large" />');
        $this->assertHasClass('tedi-status-indicator--lg', $html, on: 'tedi-status-indicator--lg');
    }

    public function test_status_badge_icon_renders_icon_with_badge_class(): void
    {
        $html = Blade::render('<tedi:status-badge text="Silt" icon="check" />');
        $this->assertHasClass('tedi-status-badge__icon', $html, on: 'tedi-status-badge__icon');
    }

    public function test_status_badge_merges_consumer_class_and_id_without_duplicating(): void
    {
        $html = Blade::render('<tedi:status-badge text="Silt" class="mine" id="custom-id" role="alert" />');
        $this->assertHasClass('tedi-status-badge', $html);
        $this->assertHasClass('tedi-status-badge--color-neutral', $html);
        $this->assertHasClass('tedi-status-badge--variant-filled', $html);
        $this->assertHasClass('mine', $html);
        $this->assertSame(1, substr_count($html, ' id='));
        $this->assertStringContainsString('id="custom-id"', $html);
        $this->assertSame(1, substr_count($html, ' role='));
    }
}
