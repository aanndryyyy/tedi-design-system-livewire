<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class ContentComponentsTest extends TestCase
{
    // -- card-button --------------------------------------------------------

    public function test_card_button_renders_as_button_by_default(): void
    {
        $html = Blade::render('<tedi:card-button><tedi:card>x</tedi:card></tedi:card-button>');

        $this->assertStringContainsString('<button', $html);
        $this->assertHasClass('tedi-card-button', $html, on: 'tedi-card-button');
        $this->assertStringContainsString('type="button"', $html);
    }

    public function test_card_button_renders_as_anchor_when_href_given(): void
    {
        $html = Blade::render('<tedi:card-button href="/path"><tedi:card>x</tedi:card></tedi:card-button>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/path"', $html);
        $this->assertHasClass('tedi-card-button', $html, on: 'tedi-card-button');
    }

    public function test_card_button_disabled(): void
    {
        $button = Blade::render('<tedi:card-button disabled><tedi:card>x</tedi:card></tedi:card-button>');
        $this->assertStringContainsString('disabled', $button);

        $anchor = Blade::render('<tedi:card-button href="/path" disabled><tedi:card>x</tedi:card></tedi:card-button>');
        $this->assertStringContainsString('aria-disabled="true"', $anchor);
    }

    // -- info-button ----------------------------------------------------------

    public function test_info_button_color_classes(): void
    {
        foreach (['primary', 'inverted'] as $color) {
            $html = Blade::render("<tedi:info-button color=\"{$color}\" />");
            $this->assertHasClass('tedi-info-button', $html, on: 'tedi-info-button');

            if ($color === 'inverted') {
                $this->assertHasClass('tedi-info-button--inverted', $html, on: 'tedi-info-button');
            } else {
                $this->assertMissingClass('tedi-info-button--inverted', $html, on: 'tedi-info-button');
            }
        }
    }

    public function test_info_button_default_aria_label(): void
    {
        $html = Blade::render('<tedi:info-button />');
        $this->assertStringContainsString('aria-label="More information"', $html);
        // Exactly one aria-label attribute — the @props(['ariaLabel']) route
        // must not leave a raw $attributes-forwarded copy behind too.
        $this->assertSame(1, substr_count($html, 'aria-label='));
    }

    public function test_info_button_custom_aria_label_wins(): void
    {
        $kebab = Blade::render('<tedi:info-button aria-label="Custom" />');
        $this->assertStringContainsString('aria-label="Custom"', $kebab);
        $this->assertSame(1, substr_count($kebab, 'aria-label='));

        $dynamic = Blade::render('<tedi:info-button :aria-label="\'Custom\'" />');
        $this->assertStringContainsString('aria-label="Custom"', $dynamic);
        $this->assertSame(1, substr_count($dynamic, 'aria-label='));
    }

    // -- list -------------------------------------------------------------------

    public function test_list_element_and_bullet_color(): void
    {
        foreach (['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'warning-dark', 'danger', 'white'] as $color) {
            $html = Blade::render("<tedi:list color=\"{$color}\"><li>x</li></tedi:list>");
            $this->assertStringContainsString('<ul', $html);
            $this->assertHasClass('tedi-list--bullet-color-'.$color, $html, on: 'tedi-list');
        }

        $ol = Blade::render('<tedi:list as="ol"><li>x</li></tedi:list>');
        $this->assertStringContainsString('<ol', $ol);
    }

    public function test_list_unstyled(): void
    {
        $styled = Blade::render('<tedi:list><li>x</li></tedi:list>');
        $this->assertMissingClass('tedi-list--unstyled', $styled, on: 'tedi-list');

        $unstyled = Blade::render('<tedi:list :styled="false"><li>x</li></tedi:list>');
        $this->assertHasClass('tedi-list--unstyled', $unstyled, on: 'tedi-list');
    }

    // -- text-group ---------------------------------------------------------

    public function test_text_group_type_classes(): void
    {
        foreach (['vertical', 'horizontal'] as $type) {
            $html = Blade::render(<<<BLADE
<tedi:text-group type="{$type}">
    <x-slot:label>Name</x-slot:label>
    <x-slot:value>John</x-slot:value>
</tedi:text-group>
BLADE);
            $this->assertHasClass('tedi-text-group--'.$type, $html, on: 'tedi-text-group');
            $this->assertHasClass('tedi-label', $html, on: 'tedi-label');
            $this->assertStringContainsString('tedi-text-group-label', $html);
            $this->assertStringContainsString('tedi-text-group-value', $html);
        }
    }

    public function test_text_group_fixed_label_when_label_width_given(): void
    {
        $withWidth = Blade::render(<<<'BLADE'
<tedi:text-group label-width="30%">
    <x-slot:label>Name</x-slot:label>
    <x-slot:value>John</x-slot:value>
</tedi:text-group>
BLADE);
        $this->assertHasClass('tedi-text-group--fixed-label', $withWidth, on: 'tedi-text-group');
        $this->assertStringContainsString('--_label-width: 30%', $withWidth);

        $withoutWidth = Blade::render(<<<'BLADE'
<tedi:text-group>
    <x-slot:label>Name</x-slot:label>
    <x-slot:value>John</x-slot:value>
</tedi:text-group>
BLADE);
        $this->assertMissingClass('tedi-text-group--fixed-label', $withoutWidth, on: 'tedi-text-group');
    }

    // -- card -----------------------------------------------------------------

    public function test_card_base_class(): void
    {
        $html = Blade::render('<tedi:card>x</tedi:card>');
        $this->assertHasClass('tedi-card', $html, on: 'tedi-card');
    }

    public function test_card_border_placement_and_color(): void
    {
        $plain = Blade::render('<tedi:card border="brand-primary">x</tedi:card>');
        $this->assertHasClass('tedi-card--border--brand-primary', $plain, on: 'tedi-card');
        $this->assertMissingClass('tedi-card--border-top', $plain, on: 'tedi-card');
        $this->assertMissingClass('tedi-card--border-left', $plain, on: 'tedi-card');

        $top = Blade::render('<tedi:card border="top-brand-primary">x</tedi:card>');
        $this->assertHasClass('tedi-card--border-top', $top, on: 'tedi-card');
        $this->assertHasClass('tedi-card--border--brand-primary', $top, on: 'tedi-card');

        $left = Blade::render('<tedi:card border="left-danger-primary">x</tedi:card>');
        $this->assertHasClass('tedi-card--border-left', $left, on: 'tedi-card');
        $this->assertHasClass('tedi-card--border--danger-primary', $left, on: 'tedi-card');
    }

    public function test_card_borderless(): void
    {
        $html = Blade::render('<tedi:card borderless>x</tedi:card>');
        $this->assertHasClass('tedi-card--borderless', $html, on: 'tedi-card');
    }

    public function test_card_border_radius_variants(): void
    {
        $default = Blade::render('<tedi:card>x</tedi:card>');
        foreach (['tl', 'tr', 'br', 'bl'] as $corner) {
            $this->assertMissingClass('tedi-card--no-radius-'.$corner, $default, on: 'tedi-card');
        }

        $allSquare = Blade::render('<tedi:card :border-radius="false">x</tedi:card>');
        foreach (['tl', 'tr', 'br', 'bl'] as $corner) {
            $this->assertHasClass('tedi-card--no-radius-'.$corner, $allSquare, on: 'tedi-card');
        }

        $topSquare = Blade::render("<tedi:card :border-radius=\"['top' => false]\">x</tedi:card>");
        $this->assertHasClass('tedi-card--no-radius-tl', $topSquare, on: 'tedi-card');
        $this->assertHasClass('tedi-card--no-radius-tr', $topSquare, on: 'tedi-card');
        $this->assertMissingClass('tedi-card--no-radius-br', $topSquare, on: 'tedi-card');
        $this->assertMissingClass('tedi-card--no-radius-bl', $topSquare, on: 'tedi-card');

        $cornerOverride = Blade::render("<tedi:card :border-radius=\"['top' => false, 'topLeft' => true]\">x</tedi:card>");
        $this->assertMissingClass('tedi-card--no-radius-tl', $cornerOverride, on: 'tedi-card');
        $this->assertHasClass('tedi-card--no-radius-tr', $cornerOverride, on: 'tedi-card');
    }

    // -- card-content ---------------------------------------------------------

    public function test_card_content_background_default_and_all_values(): void
    {
        $default = Blade::render('<tedi:card-content>x</tedi:card-content>');
        $this->assertHasClass('tedi-card-content--background--primary', $default, on: 'tedi-card-content');

        foreach ([
            'primary', 'secondary', 'tertiary', 'accent',
            'brand-primary', 'brand-secondary', 'brand-tertiary', 'brand-quaternary',
            'danger-primary', 'danger-secondary', 'success-primary', 'success-secondary',
            'info-primary', 'info-secondary', 'warning-primary', 'warning-secondary',
            'neutral-primary', 'neutral-secondary',
        ] as $bg) {
            $html = Blade::render("<tedi:card-content background=\"{$bg}\">x</tedi:card-content>");
            $this->assertHasClass('tedi-card-content--background--'.$bg, $html, on: 'tedi-card-content');
        }
    }

    public function test_card_content_inherits_background_from_card(): void
    {
        $html = Blade::render('<tedi:card background="secondary"><tedi:card-content>x</tedi:card-content></tedi:card>');
        $this->assertHasClass('tedi-card-content--background--secondary', $html, on: 'tedi-card-content');

        $override = Blade::render('<tedi:card background="secondary"><tedi:card-content background="tertiary">x</tedi:card-content></tedi:card>');
        $this->assertHasClass('tedi-card-content--background--tertiary', $override, on: 'tedi-card-content');
    }

    public function test_card_content_padding_css_variables(): void
    {
        $numeric = Blade::render('<tedi:card-content :padding="2">x</tedi:card-content>');
        $this->assertStringContainsString('--card-content-padding-top: 2rem', $numeric);
        $this->assertStringContainsString('--card-content-padding-left: 2rem', $numeric);

        $vertical = Blade::render("<tedi:card-content :padding=\"['vertical' => 1.5, 'horizontal' => 0.5]\">x</tedi:card-content>");
        $this->assertStringContainsString('--card-content-padding-top: 1.5rem', $vertical);
        $this->assertStringContainsString('--card-content-padding-right: 0.5rem', $vertical);

        $sides = Blade::render("<tedi:card-content :padding=\"['top' => 2, 'left' => 1]\">x</tedi:card-content>");
        $this->assertStringContainsString('--card-content-padding-top: 2rem', $sides);
        $this->assertStringContainsString('--card-content-padding-left: 1rem', $sides);
        $this->assertStringContainsString('--card-content-padding-right: 0rem', $sides);
    }

    public function test_card_content_inherits_padding_from_card_and_defaults_to_one(): void
    {
        $default = Blade::render('<tedi:card-content>x</tedi:card-content>');
        $this->assertStringContainsString('--card-content-padding-top: 1rem', $default);

        $inherited = Blade::render('<tedi:card :padding="2.5"><tedi:card-content>x</tedi:card-content></tedi:card>');
        $this->assertStringContainsString('--card-content-padding-top: 2.5rem', $inherited);
    }

    public function test_card_content_auto_width(): void
    {
        $html = Blade::render('<tedi:card-content auto-width>x</tedi:card-content>');
        $this->assertHasClass('tedi-card-content--auto-width', $html, on: 'tedi-card-content');
    }

    public function test_card_content_background_image_styles(): void
    {
        $html = Blade::render('<tedi:card-content background-image="/x.png" background-position="center" background-size="cover" background-repeat="no-repeat">x</tedi:card-content>');
        $this->assertStringContainsString('background-image: url(/x.png)', $html);
        $this->assertStringContainsString('background-position: center', $html);
        $this->assertStringContainsString('background-size: cover', $html);
        $this->assertStringContainsString('background-repeat: no-repeat', $html);
    }

    // -- card-header ------------------------------------------------------------

    public function test_card_header_defaults_to_brand_primary_and_never_inherits_card_background(): void
    {
        $standalone = Blade::render('<tedi:card-header>x</tedi:card-header>');
        $this->assertHasClass('tedi-card-header--background--brand-primary', $standalone, on: 'tedi-card-header');

        $nested = Blade::render('<tedi:card background="secondary"><tedi:card-header>x</tedi:card-header></tedi:card>');
        $this->assertHasClass('tedi-card-header--background--brand-primary', $nested, on: 'tedi-card-header');
        $this->assertMissingClass('tedi-card-header--background--secondary', $nested, on: 'tedi-card-header');
    }

    public function test_card_header_inherits_padding_from_card(): void
    {
        $html = Blade::render('<tedi:card :padding="2"><tedi:card-header>x</tedi:card-header></tedi:card>');
        $this->assertStringContainsString('--card-content-padding-top: 2rem', $html);
    }

    // -- card-icon --------------------------------------------------------------

    public function test_card_icon_type_background(): void
    {
        $default = Blade::render('<tedi:card-icon>x</tedi:card-icon>');
        $this->assertHasClass('tedi-card-icon--background--secondary', $default, on: 'tedi-card-icon');

        $brand = Blade::render('<tedi:card-icon type="brand">x</tedi:card-icon>');
        $this->assertHasClass('tedi-card-icon--background--brand-primary', $brand, on: 'tedi-card-icon');
    }

    public function test_card_icon_size_default_padding(): void
    {
        $default = Blade::render('<tedi:card-icon>x</tedi:card-icon>');
        $this->assertStringContainsString('--card-content-padding-top: 1rem', $default);

        $small = Blade::render('<tedi:card-icon size="small">x</tedi:card-icon>');
        $this->assertStringContainsString('--card-content-padding-top: 0.75rem', $small);
    }

    public function test_card_icon_padding_inherits_from_card_before_size_default(): void
    {
        $html = Blade::render('<tedi:card :padding="3"><tedi:card-icon size="small">x</tedi:card-icon></tedi:card>');
        $this->assertStringContainsString('--card-content-padding-top: 3rem', $html);
    }

    // -- card-row -----------------------------------------------------------

    public function test_card_row_class(): void
    {
        $html = Blade::render('<tedi:card-row>x</tedi:card-row>');
        $this->assertHasClass('tedi-card-row', $html, on: 'tedi-card-row');
    }

    // -- accordion --------------------------------------------------------------

    public function test_accordion_base_class_and_item_gap(): void
    {
        $html = Blade::render('<tedi:accordion :item-gap="1.5">x</tedi:accordion>');
        $this->assertHasClass('tedi-accordion', $html, on: 'tedi-accordion');
        $this->assertStringContainsString('--tedi-accordion-item-gap: 1.5rem', $html);
    }

    // -- accordion-item -----------------------------------------------------

    public function test_accordion_item_modifier_classes(): void
    {
        $html = Blade::render('<tedi:accordion-item selected show-icon-card>x</tedi:accordion-item>');
        $this->assertHasClass('tedi-accordion__item--selected', $html, on: 'tedi-accordion__item');
        $this->assertHasClass('tedi-accordion__item--with-icon-card', $html, on: 'tedi-accordion__item');
    }

    public function test_accordion_item_disabled_has_no_stylesheet_backed_class(): void
    {
        // Angular's accordion-item.component.html does bind
        // [class.tedi-accordion__item--disabled], but no SCSS rule styles it
        // (upstream gap) — this port omits the class, see the Blade comment
        // in accordion-item.blade.php. `disabled` still reaches descendants.
        $html = Blade::render('<tedi:accordion-item disabled>x</tedi:accordion-item>');
        $this->assertMissingClass('tedi-accordion__item--disabled', $html, on: 'tedi-accordion__item');
        $this->assertStringContainsString('disabled: true', $html);
    }

    public function test_accordion_item_default_expanded_own_value_wins_over_group(): void
    {
        $ownFalse = Blade::render('<tedi:accordion default-expanded><tedi:accordion-item :default-expanded="false">x</tedi:accordion-item></tedi:accordion>');
        $this->assertStringContainsString('expanded: false', $ownFalse);

        $group = Blade::render('<tedi:accordion default-expanded><tedi:accordion-item>x</tedi:accordion-item></tedi:accordion>');
        $this->assertStringContainsString('expanded: true', $group);
    }

    // -- accordion-item-header -----------------------------------------------

    public function test_accordion_item_header_base_and_hoverable(): void
    {
        $clickable = Blade::render('<tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-accordion-item-header', $clickable, on: 'tedi-accordion-item-header');
        // Reactive booleans bound via x-bind:class (not a static class="..."
        // attribute), so this stays a raw source-text assertion.
        $this->assertStringContainsString("'tedi-accordion-item-header--hoverable': true", $clickable);

        $notClickable = Blade::render('<tedi:accordion-item-header :header-clickable="false"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertStringContainsString("'tedi-accordion-item-header--hoverable': false", $notClickable);
    }

    public function test_accordion_item_header_title_layout(): void
    {
        $fill = Blade::render('<tedi:accordion-item-header title-layout="fill"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-accordion-item-header__title--grow', $fill, on: 'tedi-accordion-item-header__title');

        $hug = Blade::render('<tedi:accordion-item-header title-layout="hug"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertMissingClass('tedi-accordion-item-header__title--grow', $hug, on: 'tedi-accordion-item-header__title');
    }

    public function test_accordion_item_header_expand_action_position(): void
    {
        $start = Blade::render('<tedi:accordion-item-header expand-action-position="start"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-accordion-item-header__expand-indicator', $start, on: 'tedi-accordion-item-header__expand-indicator');

        $none = Blade::render('<tedi:accordion-item-header :show-default-expand-action="false"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertMissingClass('tedi-accordion-item-header__expand-indicator', $none, on: 'tedi-accordion-item-header__expand-indicator');
    }

    public function test_accordion_item_header_heading_level_wraps_trigger(): void
    {
        foreach ([1, 2, 3, 4, 5, 6] as $level) {
            $html = Blade::render("<tedi:accordion-item-header heading-level=\"{$level}\"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>");
            $this->assertStringContainsString("<h{$level}", $html);
            $this->assertHasClass('tedi-accordion-item-header__heading-wrapper', $html, on: 'tedi-accordion-item-header__heading-wrapper');
        }

        $none = Blade::render('<tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertStringNotContainsString('heading-wrapper', $none);
    }

    public function test_accordion_item_header_non_clickable_renders_inlined_collapse_button(): void
    {
        $default = Blade::render('<tedi:accordion-item-header :header-clickable="false"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-collapse-button', $default, on: 'tedi-collapse-button');

        $iconOnlyDefault = Blade::render('<tedi:accordion-item-header :header-clickable="false" :show-expand-label="false"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-collapse-button--neutral', $iconOnlyDefault, on: 'tedi-collapse-button');

        $secondary = Blade::render('<tedi:accordion-item-header :header-clickable="false" :show-expand-label="false" expand-action-arrow-type="secondary"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-collapse-button--secondary', $secondary, on: 'tedi-collapse-button');

        $iconOnlyFalse = Blade::render('<tedi:accordion-item-header :header-clickable="false" :show-expand-label="false"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-collapse-button--icon-only', $iconOnlyFalse, on: 'tedi-collapse-button');

        $inverted = Blade::render('<tedi:accordion-item-header :header-clickable="false" :expand-action-inverted="true"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-collapse-button--inverted', $inverted, on: 'tedi-collapse-button');

        $underline = Blade::render('<tedi:accordion-item-header :header-clickable="false" :expand-action-underline="false"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('tedi-collapse-button--no-underline', $underline, on: 'tedi-collapse-button');
    }

    public function test_accordion_item_header_disabled_and_with_icon_card_aware_from_item(): void
    {
        $html = Blade::render('<tedi:accordion-item disabled show-icon-card><tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header></tedi:accordion-item>');
        $this->assertHasClass('tedi-accordion-item-header--disabled', $html, on: 'tedi-accordion-item-header');
        $this->assertHasClass('tedi-accordion-item-header--with-icon-card', $html, on: 'tedi-accordion-item-header');
        // The genuine DOM disabled marker (mirrors Angular's [disabled]="disabled()"
        // on the trigger button) — not just the reactive Alpine flag.
        $this->assertMatchesRegularExpression(
            '/<button[^>]*id="tedi-accordion-item-header-[^"]*"[^>]*\bdisabled\b/',
            $html
        );
    }

    public function test_accordion_item_header_custom_header_class_merges(): void
    {
        $html = Blade::render('<tedi:accordion-item-header header-class="custom-class"><x-slot:title>t</x-slot:title></tedi:accordion-item-header>');
        $this->assertHasClass('custom-class', $html, on: 'tedi-accordion-item-header');
    }

    public function test_accordion_item_header_aria_pairing_requires_item_id(): void
    {
        $withId = Blade::render('<tedi:accordion-item item-id="terms"><tedi:accordion-item-header><x-slot:title>t</x-slot:title></tedi:accordion-item-header><tedi:accordion-item-content>c</tedi:accordion-item-content></tedi:accordion-item>');
        $this->assertStringContainsString('id="terms-header"', $withId);
        $this->assertStringContainsString('aria-controls="terms-content"', $withId);
        $this->assertStringContainsString('id="terms-content"', $withId);
        $this->assertStringContainsString('aria-labelledby="terms-header"', $withId);
    }

    // -- accordion-item-content ----------------------------------------------

    public function test_accordion_item_content_with_icon_card_and_custom_class(): void
    {
        $html = Blade::render('<tedi:accordion-item show-icon-card><tedi:accordion-item-content content-class="extra">c</tedi:accordion-item-content></tedi:accordion-item>');
        $this->assertHasClass('tedi-accordion-item-content--with-icon-card', $html, on: 'tedi-accordion-item-content');
        $this->assertHasClass('extra', $html, on: 'tedi-accordion-item-content');
        $this->assertStringContainsString('tedi-accordion-item-content__inner', $html);
    }

    // -- carousel family ------------------------------------------------------

    public function test_carousel_and_subcomponents_render_element_tags(): void
    {
        $html = Blade::render(<<<'BLADE'
<tedi:carousel>
    <tedi:carousel-header>h</tedi:carousel-header>
    <tedi:carousel-content>
        <tedi:carousel-slide>s1</tedi:carousel-slide>
    </tedi:carousel-content>
    <tedi:carousel-footer>
        <tedi:carousel-indicators />
        <tedi:carousel-navigation />
    </tedi:carousel-footer>
</tedi:carousel>
BLADE);

        $this->assertStringContainsString('<tedi-carousel', $html);
        $this->assertStringContainsString('<tedi-carousel-header', $html);
        $this->assertStringContainsString('<tedi-carousel-footer', $html);
        $this->assertStringContainsString('<tedi-carousel-navigation', $html);
        $this->assertStringContainsString('<tedi-carousel-indicators', $html);
        $this->assertHasClass('tedi-carousel__content', $html, on: 'tedi-carousel__content');
        $this->assertHasClass('tedi-carousel__track', $html, on: 'tedi-carousel__track');
        $this->assertHasClass('tedi-carousel__slide', $html, on: 'tedi-carousel__slide');
    }

    public function test_carousel_content_fade_classes(): void
    {
        $fadeRight = Blade::render('<tedi:carousel-content fade :slides-per-view="2">x</tedi:carousel-content>');
        $this->assertHasClass('tedi-carousel__content--fade-right', $fadeRight, on: 'tedi-carousel__content');

        $fadeX = Blade::render('<tedi:carousel-content fade :slides-per-view="1">x</tedi:carousel-content>');
        $this->assertHasClass('tedi-carousel__content--fade-x', $fadeX, on: 'tedi-carousel__content');

        $none = Blade::render('<tedi:carousel-content :slides-per-view="1">x</tedi:carousel-content>');
        $this->assertMissingClass('tedi-carousel__content--fade-right', $none, on: 'tedi-carousel__content');
        $this->assertMissingClass('tedi-carousel__content--fade-x', $none, on: 'tedi-carousel__content');
    }

    public function test_carousel_indicators_variant_and_arrows(): void
    {
        $dots = Blade::render('<tedi:carousel-indicators variant="dots" />');
        $this->assertStringContainsString('tedi-carousel__indicator', $dots);

        $numbers = Blade::render('<tedi:carousel-indicators variant="numbers" />');
        $this->assertStringNotContainsString('tedi-carousel__indicator', $numbers);

        $withArrows = Blade::render('<tedi:carousel-indicators with-arrows />');
        $this->assertHasClass('tedi-button', $withArrows, on: 'tedi-button');
    }
}
