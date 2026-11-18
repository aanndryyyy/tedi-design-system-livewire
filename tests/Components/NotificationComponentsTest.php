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

    public function test_alert_role_is_the_only_live_region_attribute(): void
    {
        // Angular 8+ dropped the host's aria-live and generated aria-label:
        // role="alert"/"status" carry their own live-region semantics.
        $html = Blade::render('<tedi:alert role="alert" title="T">Body</tedi:alert>');
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringNotContainsString('aria-live=', $html);
        $this->assertStringNotContainsString('aria-label=', $html);

        $html = Blade::render('<tedi:alert role="status">Body</tedi:alert>');
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringNotContainsString('aria-live=', $html);

        $html = Blade::render('<tedi:alert role="none">Body</tedi:alert>');
        $this->assertStringNotContainsString('role=', $html);
        $this->assertStringNotContainsString('aria-live=', $html);
    }

    public function test_alert_close_aria_label_names_the_close_button(): void
    {
        // Angular 10 (#900): the closing button renders its label once, as title.
        $html = Blade::render('<tedi:alert :show-close="true" close-aria-label="Sulge teade: Salvestatud">Body</tedi:alert>');
        $this->assertStringContainsString('title="Sulge teade: Salvestatud"', $html);
        $this->assertStringNotContainsString('aria-label=', $html);

        $html = Blade::render('<tedi:alert :show-close="true">Body</tedi:alert>');
        $this->assertStringContainsString('title="'.__('tedi::tedi.close').'"', $html);
    }

    public function test_closing_button_renders_its_label_once(): void
    {
        // Angular 10 (#900): title while showTitle, else aria-label — never both.
        $titled = Blade::render('<tedi:closing-button aria-label="Sulge dialoog" />');
        $this->assertStringContainsString('title="Sulge dialoog"', $titled);
        $this->assertStringNotContainsString('aria-label=', $titled);

        $untitled = Blade::render('<tedi:closing-button :show-title="false" />');
        $this->assertStringContainsString('aria-label="'.__('tedi::tedi.close').'"', $untitled);
        $this->assertStringNotContainsString('title=', $untitled);

        // #814: the icon follows the button's per-state text colour.
        $this->assertHasClass('tedi-icon--color-inherit', $titled, on: 'tedi-icon');
    }

    public function test_alert_close_and_action_sit_outside_the_live_region(): void
    {
        // Angular 10 (#898): the role moves from the host to __body, and the
        // close button is described by the title.
        $html = Blade::render('<tedi:alert role="alert" title="Salvestatud" :show-close="true">Body<x-slot:action><tedi:button>Tegevus</tedi:button></x-slot:action></tedi:alert>');
        $this->assertMatchesRegularExpression('/<div class="tedi-alert__body"\s+role="alert"\s*>/', $html);
        $this->assertDoesNotMatchRegularExpression('/<tedi-alert[^>]*role=/', $html);
        $this->assertSame(1, preg_match('/<h2 class="tedi-alert__title" id="([^"]+)">/', $html, $m));
        $this->assertSame(1, preg_match('/<button[^>]*tedi-closing-button[^>]*>/s', $html, $close));
        $this->assertStringContainsString('aria-describedby="'.$m[1].'"', $close[0]);
        // Body closes before the action and close button.
        $this->assertMatchesRegularExpression('/tedi-alert__body.*<\/div>\s*<div class="tedi-alert__action">/s', $html);

        $untitled = Blade::render('<tedi:alert :show-close="true">Body</tedi:alert>');
        $this->assertStringNotContainsString('aria-describedby', $untitled);
    }

    public function test_alert_title_sets_heading_tag_and_head_wrapper(): void
    {
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
            $html = Blade::render('<tedi:alert title="Pealkiri" title-element="'.$tag.'">Body</tedi:alert>');
            $this->assertMatchesRegularExpression('/<'.$tag.' class="tedi-alert__title" id="tedi-alert-title-[0-9a-f]+">Pealkiri<\/'.$tag.'>/', $html);
            $this->assertHasClass('tedi-alert__head', $html, on: 'tedi-alert__head');
        }

        $html = Blade::render('<tedi:alert title="Pealkiri" title-element="div">Body</tedi:alert>');
        $this->assertMatchesRegularExpression('/<div class="tedi-alert__title" id="tedi-alert-title-[0-9a-f]+">Pealkiri<\/div>/', $html);
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

    public function test_toast_is_not_a_live_region_itself(): void
    {
        // Angular 8+: the toast's alert is always role="none"; `role` only
        // picks the politeness of the container's announcement.
        foreach (['status', 'alert', 'none'] as $role) {
            $html = Blade::render('<tedi:toast role="'.$role.'" title="T">Sisu</tedi:toast>');
            // `\s` before `role`: the toast's own `data-role` is not ARIA.
            $this->assertDoesNotMatchRegularExpression('/\srole="(alert|status)"/', $html);
            $this->assertStringNotContainsString('aria-live=', $html);
        }
    }

    public function test_toast_close_button_is_titled_close_and_described_by_the_title(): void
    {
        // Angular 10: "Close" with or without a title; the title describes it.
        $html = Blade::render('<tedi:toast title="Salvestatud">Sisu</tedi:toast>');
        $this->assertStringContainsString('title="'.__('tedi::tedi.close').'"', $html);
        $this->assertStringContainsString('aria-describedby="tedi-alert-title-', $html);
        $this->assertStringNotContainsString('Salvestatud"', $html);

        $html = Blade::render('<tedi:toast>Sisu</tedi:toast>');
        $this->assertStringContainsString('title="'.__('tedi::tedi.close').'"', $html);
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

    public function test_toast_carries_its_container_config_as_data_attributes(): void
    {
        // Upstream's ToastService reads role / duration / pauseOnHover /
        // position from the toast's config; the container behaviour reads
        // them off these attributes when it adopts a server-rendered toast.
        $html = Blade::render('<tedi:toast title="T">Sisu</tedi:toast>');
        $this->assertStringContainsString('data-duration="6000"', $html);
        $this->assertStringContainsString('data-role="status"', $html);
        $this->assertStringContainsString('data-pause-on-hover="true"', $html);
        $this->assertStringNotContainsString('data-position=', $html);

        $html = Blade::render('<tedi:toast title="T" role="alert" :duration="0" :pause-on-hover="false" position="top-left">Sisu</tedi:toast>');
        $this->assertStringContainsString('data-duration="0"', $html);
        $this->assertStringContainsString('data-role="alert"', $html);
        $this->assertStringContainsString('data-pause-on-hover="false"', $html);
        $this->assertStringContainsString('data-position="top-left"', $html);
    }

    public function test_toast_consumer_attributes_win_over_its_data_attributes(): void
    {
        $html = Blade::render('<tedi:toast id="saved" data-role="none" title="T">Sisu</tedi:toast>');
        $this->assertStringContainsString('id="saved"', $html);
        $this->assertSame(1, substr_count($html, 'data-role='));
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

    // --- toast-container -------------------------------------------------------

    public function test_toast_container_root_is_the_angular_element_with_its_host_class(): void
    {
        $html = Blade::render('<tedi:toast-container class="extra" />');
        $this->assertMatchesRegularExpression('/^<tedi-toast-container\b/', trim($html));
        $this->assertHasClass('tedi-toast-container', $html, on: 'tedi-toast-container');
        $this->assertHasClass('extra', $html, on: 'tedi-toast-container');
        $this->assertStringContainsString('x-data="tediToastContainer"', $html);
        $this->assertStringContainsString('wire:ignore', $html);
    }

    public function test_toast_container_is_never_a_live_region(): void
    {
        // Announcements go through the root-level announcer regions only.
        $html = Blade::render('<tedi:toast-container><tedi:toast title="T" role="alert">Sisu</tedi:toast></tedi:toast-container>');
        $this->assertStringNotContainsString('aria-live=', $html);
        $this->assertDoesNotMatchRegularExpression('/\srole="(alert|status)"/', $html);
    }

    public function test_empty_toast_container_renders_no_position_group(): void
    {
        // Upstream renders a group only when it has a toast.
        $html = Blade::render('<tedi:toast-container />');
        $this->assertClassNeverRendered('tedi-toast-container__position', $this->outsideTemplate($html));
    }

    public function test_toast_container_renders_slotted_toasts_in_the_default_bottom_right_group(): void
    {
        $html = Blade::render('<tedi:toast-container><tedi:toast title="Salvestatud" type="success" position="top-left">Sisu</tedi:toast></tedi:toast-container>');
        $outside = $this->outsideTemplate($html);

        $this->assertHasClass('tedi-toast-container__position', $outside, on: 'tedi-toast-container__position');
        $this->assertHasClass('tedi-toast-container__position--bottom-right', $outside, on: 'tedi-toast-container__position');
        $this->assertMatchesRegularExpression(
            '/tedi-toast-container__position--bottom-right">\s*<tedi-toast\b[^>]*data-position="top-left"/',
            $outside,
        );
    }

    public function test_toast_container_prototype_is_a_full_toast(): void
    {
        // Spawned toasts are clones of this, so it must carry every part the
        // behaviour fills in: the titled branch, an icon, the progress bar.
        $html = Blade::render('<tedi:toast-container />');
        $this->assertMatchesRegularExpression('/<template x-ref="prototype">\s*<tedi-toast\b/', $html);

        preg_match('/<template x-ref="prototype">(.*)<\/template>/s', $html, $m);
        $prototype = $m[1];
        $this->assertHasClass('tedi-alert__head', $prototype, on: 'tedi-alert__head');
        $this->assertHasClass('tedi-alert__title', $prototype, on: 'tedi-alert__title');
        $this->assertHasClass('tedi-icon', $prototype, on: 'tedi-icon');
        $this->assertHasClass('tedi-toast__progress', $prototype, on: 'tedi-toast__progress');
        $this->assertHasClass('tedi-alert__close', $prototype, on: 'tedi-alert__close');
        $this->assertStringContainsString('title="'.__('tedi::tedi.close').'"', $prototype);
    }

    private function outsideTemplate(string $html): string
    {
        return preg_replace('/<template\b.*?<\/template>/s', '', $html);
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

    // --- tag -------------------------------------------------------------------

    public function test_tag_type_and_state_classes(): void
    {
        $html = Blade::render('<tedi:tag>Silt</tedi:tag>');
        $this->assertSame(['tedi-tag'], $this->classesOf($html, 'tedi-tag'));

        foreach (['secondary', 'danger'] as $type) {
            $html = Blade::render('<tedi:tag type="'.$type.'">Silt</tedi:tag>');
            $this->assertHasClass('tedi-tag--'.$type, $html, 'tedi-tag');
        }

        $html = Blade::render('<tedi:tag loading closable ellipsis="end">Silt</tedi:tag>');
        $this->assertHasClass('tedi-tag--loading', $html, 'tedi-tag');
        $this->assertHasClass('tedi-tag--closable', $html, 'tedi-tag');
        $this->assertHasClass('tedi-tag--ellipsis', $html, 'tedi-tag');
    }

    public function test_tag_announces_loading_from_a_persistent_status_region(): void
    {
        // Angular 8+: the sr-only status region is always rendered and only
        // carries text while loading.
        $html = Blade::render('<tedi:tag>Silt</tedi:tag>');
        $this->assertMatchesRegularExpression('#<span class="sr-only" role="status" aria-live="polite" aria-atomic="true"></span>#', $html);

        $html = Blade::render('<tedi:tag loading>Silt</tedi:tag>');
        $this->assertStringContainsString('aria-atomic="true">'.__('tedi::tedi.loading').'</span>', $html);
    }

    public function test_forwarded_button_attributes_are_html_escaped(): void
    {
        // A value forwarded through close-attributes must be escaped; the bag's
        // __toString() does not escape, only merge() does.
        $html = Blade::render('<tedi:alert :close-attributes="$attrs" :show-close="true">x</tedi:alert>', [
            'attrs' => ['title' => '"><script>alert(1)</script>'],
        ]);

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
