<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class HelperComponentsTest extends TestCase
{
    // -- attachment ---------------------------------------------------------

    public function test_attachment_base_class_and_name(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" />');

        $this->assertHasClass('tedi-attachment', $html);
        $this->assertStringContainsString('file.pdf', $html);
        $this->assertMissingClass('tedi-attachment--error', $html);
        $this->assertMissingClass('tedi-attachment--vertical', $html);
    }

    public function test_attachment_error_implies_error_visual_and_feedback_text(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" error="Too large" />');

        $this->assertHasClass('tedi-attachment--error', $html);
        $this->assertHasClass('tedi-attachment__feedback', $html, on: 'tedi-feedback-text');
        $this->assertHasClass('tedi-feedback-text--error', $html, on: 'tedi-feedback-text');
        $this->assertStringContainsString('Too large', $html);
    }

    public function test_attachment_invalid_shows_error_visual_without_feedback_text(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" :invalid="true" />');

        $this->assertHasClass('tedi-attachment--error', $html);
        // The feedback-text element isn't rendered at all in this case (no
        // element to scope a class-token check to), so its complete absence
        // is verified by substring — "tedi-feedback-text" has no other class
        // in this component that contains it, so there's no collision risk.
        $this->assertStringNotContainsString('tedi-feedback-text', $html);
    }

    public function test_attachment_vertical_direction_class(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" direction="vertical" />');

        $this->assertHasClass('tedi-attachment--vertical', $html);
    }

    public function test_attachment_has_progress_class_when_progress_slot_used(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf"><x-slot:progress><tedi:progress-bar :value="40" /></x-slot:progress></tedi:attachment>');

        $this->assertHasClass('tedi-attachment--has-progress', $html);
        $this->assertMissingClass('tedi-attachment__progress--empty', $html, on: 'tedi-attachment__progress');
    }

    public function test_attachment_progress_empty_without_progress_slot(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" />');

        $this->assertHasClass('tedi-attachment__progress--empty', $html, on: 'tedi-attachment__progress');
        $this->assertMissingClass('tedi-attachment--has-progress', $html);
    }

    public function test_attachment_renders_actions_slot(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf"><x-slot:actions><button>Delete</button></x-slot:actions></tedi:attachment>');

        $this->assertHasClass('tedi-attachment__actions', $html, on: 'tedi-attachment__actions');
        $this->assertHasClass('tedi-attachment-actions', $html, on: 'tedi-attachment-actions');
        $this->assertStringContainsString('Delete', $html);
    }

    public function test_attachment_actions_padded_class(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" :padded="true"><x-slot:actions><button>Delete</button></x-slot:actions></tedi:attachment>');
        $this->assertHasClass('tedi-attachment-actions--padded', $html, on: 'tedi-attachment-actions');

        $html = Blade::render('<tedi:attachment name="file.pdf"><x-slot:actions><button>Delete</button></x-slot:actions></tedi:attachment>');
        $this->assertMissingClass('tedi-attachment-actions--padded', $html, on: 'tedi-attachment-actions');
    }

    public function test_attachment_padded_without_actions_slot_renders_no_wrapper(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" :padded="true" />');

        // No actions slot content means Angular's ng-content selector never
        // matches, so no tedi-attachment-actions element exists at all.
        $this->assertStringNotContainsString('tedi-attachment-actions', $html);
    }

    public function test_attachment_vertical_below_is_accepted_and_does_not_leak(): void
    {
        $html = Blade::render('<tedi:attachment name="file.pdf" vertical-below="md" />');

        $this->assertStringNotContainsString('vertical-below', $html);
        $this->assertStringNotContainsString('verticalBelow', $html);
    }

    // -- empty-state ----------------------------------------------------------

    public function test_empty_state_type_and_size_classes(): void
    {
        foreach (['separate', 'attached', 'inside'] as $type) {
            $html = Blade::render('<tedi:empty-state type="'.$type.'">Nothing here</tedi:empty-state>');
            $this->assertHasClass('tedi-empty-state--'.$type, $html);
        }

        foreach (['default', 'small'] as $size) {
            $html = Blade::render('<tedi:empty-state size="'.$size.'">Nothing here</tedi:empty-state>');
            $this->assertHasClass('tedi-empty-state--'.$size, $html);
        }
    }

    public function test_empty_state_default_icon_and_heading(): void
    {
        $html = Blade::render('<tedi:empty-state heading="No results">Try a different search</tedi:empty-state>');

        $this->assertHasClass('tedi-empty-state__icon', $html, on: 'tedi-empty-state__icon');
        $this->assertStringContainsString('spa', $html);
        $this->assertHasClass('tedi-empty-state__heading', $html, on: 'tedi-empty-state__heading');
        $this->assertStringContainsString('No results', $html);
        $this->assertStringContainsString('Try a different search', $html);
    }

    public function test_empty_state_icon_can_be_hidden(): void
    {
        $html = Blade::render('<tedi:empty-state icon="">Nothing here</tedi:empty-state>');

        // The icon element isn't rendered at all, so there's no element to
        // scope a class-token check to; "tedi-empty-state__icon" has no
        // sibling class that contains it, so a substring check is safe here.
        $this->assertStringNotContainsString('tedi-empty-state__icon', $html);
    }

    public function test_empty_state_renders_actions_slot(): void
    {
        $html = Blade::render('<tedi:empty-state>Nothing here<x-slot:actions><button>Retry</button></x-slot:actions></tedi:empty-state>');

        $this->assertHasClass('tedi-empty-state__actions', $html, on: 'tedi-empty-state__actions');
        $this->assertStringContainsString('Retry', $html);
    }

    // -- row/col --------------------------------------------------------------

    public function test_row_cols_classes(): void
    {
        foreach ([1, 6, 12, 'auto'] as $cols) {
            $html = Blade::render('<tedi:row cols="'.$cols.'">content</tedi:row>');
            $this->assertHasClass('tedi-row--cols-'.$cols, $html);
        }
    }

    public function test_row_justify_and_align_items_classes(): void
    {
        $html = Blade::render('<tedi:row justify-items="center" align-items="stretch">content</tedi:row>');

        $this->assertHasClass('tedi-row--justify-items-center', $html);
        $this->assertHasClass('tedi-row--align-items-stretch', $html);
    }

    public function test_row_gap_classes(): void
    {
        $html = Blade::render('<tedi:row :gap="3">content</tedi:row>');
        $this->assertHasClass('g-3', $html);

        $html = Blade::render('<tedi:row :gap-x="2" :gap-y="4">content</tedi:row>');
        $this->assertHasClass('gx-2', $html);
        $this->assertHasClass('gy-4', $html);
    }

    public function test_row_auto_cols_sets_css_vars(): void
    {
        $html = Blade::render('<tedi:row :min-col-width="250">content</tedi:row>');

        $this->assertStringContainsString('--_grid-col-width: 250px', $html);
        $this->assertStringContainsString('--_grid-gap:', $html);
    }

    public function test_col_width_classes(): void
    {
        foreach ([1, 6, 12] as $width) {
            $html = Blade::render('<tedi:col :width="'.$width.'">content</tedi:col>');
            $this->assertHasClass('tedi-col--width-'.$width, $html);
        }
    }

    public function test_col_justify_and_align_self_classes(): void
    {
        $html = Blade::render('<tedi:col justify-self="end" align-self="start">content</tedi:col>');

        $this->assertHasClass('tedi-col--justify-self-end', $html);
        $this->assertHasClass('tedi-col--align-self-start', $html);
    }

    // -- scroll-fade ------------------------------------------------------------

    public function test_scroll_fade_base_structure(): void
    {
        $html = Blade::render('<tedi:scroll-fade>content</tedi:scroll-fade>');

        $this->assertHasClass('tedi-scroll-fade', $html);
        $this->assertHasClass('tedi-scroll-fade__inner', $html, on: 'tedi-scroll-fade__inner');
        $this->assertHasClass('tedi-scroll-fade__inner--custom-scroll', $html, on: 'tedi-scroll-fade__inner');
        $this->assertStringContainsString('content', $html);
    }

    public function test_scroll_fade_default_scrollbar_omits_custom_class(): void
    {
        $html = Blade::render('<tedi:scroll-fade scroll-bar="default">content</tedi:scroll-fade>');

        $this->assertMissingClass('tedi-scroll-fade__inner--custom-scroll', $html, on: 'tedi-scroll-fade__inner');
    }

    public function test_scroll_fade_aria_label_default_and_override(): void
    {
        $html = Blade::render('<tedi:scroll-fade>content</tedi:scroll-fade>');
        $this->assertStringContainsString('aria-label="Scrollable content"', $html);

        $html = Blade::render('<tedi:scroll-fade aria-label="Custom label">content</tedi:scroll-fade>');
        $this->assertStringContainsString('aria-label="Custom label"', $html);
    }

    // -- separator --------------------------------------------------------------

    public function test_separator_axis_and_color_classes(): void
    {
        foreach (['horizontal', 'vertical'] as $axis) {
            $html = Blade::render('<tedi:separator axis="'.$axis.'" />');
            $this->assertHasClass('tedi-separator--'.$axis, $html);
        }

        foreach (['primary', 'secondary', 'accent'] as $color) {
            $html = Blade::render('<tedi:separator color="'.$color.'" />');
            $this->assertHasClass('tedi-separator--'.$color, $html);
        }
    }

    public function test_separator_variant_classes(): void
    {
        // "dotted" is a substring of "dotted-small" — exact-token assertions
        // matter here, not substring checks.
        foreach (['dotted', 'dotted-small', 'dot-only'] as $variant) {
            $html = Blade::render('<tedi:separator variant="'.$variant.'" />');
            $this->assertHasClass('tedi-separator--'.$variant, $html);
        }
    }

    public function test_separator_dot_only_filled_and_outlined(): void
    {
        $html = Blade::render('<tedi:separator variant="dot-only" :dot-filled="true" />');
        $this->assertHasClass('tedi-separator--dot-only-filled', $html);

        $html = Blade::render('<tedi:separator variant="dot-only" :dot-filled="false" />');
        $this->assertHasClass('tedi-separator--dot-only-outlined', $html);
    }

    public function test_separator_dot_size_class(): void
    {
        $html = Blade::render('<tedi:separator variant="dot-only" dot-size="large" />');

        $this->assertHasClass('tedi-separator--dot-only-large', $html);
    }

    public function test_separator_thickness_class(): void
    {
        $html = Blade::render('<tedi:separator :thickness="2" />');

        $this->assertHasClass('tedi-separator--thickness-2', $html);
    }

    public function test_separator_numeric_spacing_class(): void
    {
        $html = Blade::render('<tedi:separator :spacing="1.5" />');

        $this->assertHasClass('tedi-separator--spacing-1-5', $html);
    }

    public function test_separator_object_spacing_classes(): void
    {
        $html = Blade::render('<tedi:separator axis="vertical" :spacing="[\'top\' => 1, \'left\' => 0.5]" />');

        $this->assertHasClass('tedi-separator--top-1', $html);
        $this->assertHasClass('tedi-separator--left-0-5', $html);
    }

    public function test_separator_horizontal_ignores_left_right_spacing(): void
    {
        $html = Blade::render('<tedi:separator axis="horizontal" :spacing="[\'left\' => 1, \'right\' => 1]" />');

        $this->assertMissingClass('tedi-separator--left-1', $html);
        $this->assertMissingClass('tedi-separator--right-1', $html);
    }

    public function test_separator_size_styles_by_axis(): void
    {
        $html = Blade::render('<tedi:separator axis="horizontal" size="50%" />');
        $this->assertStringContainsString('width: 50%', $html);
        $this->assertStringContainsString('height: 0px', $html);

        $html = Blade::render('<tedi:separator axis="vertical" size="50%" />');
        $this->assertStringContainsString('height: 50%', $html);
        $this->assertStringContainsString('width: 0px', $html);
    }

    public function test_separator_dot_only_omits_width_height_styles(): void
    {
        $html = Blade::render('<tedi:separator variant="dot-only" />');

        $this->assertStringNotContainsString('width:', $html);
        $this->assertStringNotContainsString('height:', $html);
    }

    // -- timeline / timeline-item ------------------------------------------------

    public function test_timeline_base_class_and_variant(): void
    {
        // "tedi-timeline" is a substring of "tedi-timeline--card" — exact
        // token assertions matter here, not substring checks.
        $html = Blade::render('<tedi:timeline>content</tedi:timeline>');
        $this->assertHasClass('tedi-timeline', $html);
        $this->assertMissingClass('tedi-timeline--card', $html);

        $html = Blade::render('<tedi:timeline variant="card">content</tedi:timeline>');
        $this->assertHasClass('tedi-timeline--card', $html);
    }

    public function test_timeline_card_padding_style(): void
    {
        $html = Blade::render('<tedi:timeline variant="card" :card-padding="1.5">content</tedi:timeline>');

        $this->assertStringContainsString('--_timeline-card-padding: 1.5rem', $html);
    }

    public function test_timeline_item_root_is_custom_element(): void
    {
        $html = Blade::render('<tedi:timeline-item>content</tedi:timeline-item>');

        // The literal custom-element tag name, not a class — substring check
        // is the correct tool here.
        $this->assertStringContainsString('<tedi-timeline-item', $html);
        $this->assertHasClass('tedi-timeline-item', $html);
    }

    public function test_timeline_item_inherits_active_index_from_parent(): void
    {
        $html = Blade::render('
            <tedi:timeline :active-index="1">
                <tedi:timeline-item :index="0"><x-slot:title>Past</x-slot:title></tedi:timeline-item>
                <tedi:timeline-item :index="1" last><x-slot:title>Current</x-slot:title></tedi:timeline-item>
            </tedi:timeline>
        ');

        // Past item: accent color, filled, medium dot.
        $this->assertHasClass('tedi-separator--accent', $html, on: 'tedi-separator--accent');
        // Current item: large dot marker.
        $this->assertHasClass('tedi-timeline__marker--large', $html, on: 'tedi-timeline__marker--large');
        $this->assertHasClass('tedi-separator--dot-only-large', $html, on: 'tedi-separator--dot-only-large');
    }

    public function test_timeline_item_without_active_index_is_future(): void
    {
        $html = Blade::render('<tedi:timeline-item :index="0"><x-slot:title>Item</x-slot:title></tedi:timeline-item>');

        $this->assertHasClass('tedi-separator--secondary', $html, on: 'tedi-separator--secondary');
        $this->assertHasClass('tedi-separator--dot-only-outlined', $html, on: 'tedi-separator--dot-only-outlined');
    }

    public function test_timeline_item_last_omits_connecting_separator(): void
    {
        $html = Blade::render('<tedi:timeline-item last>content</tedi:timeline-item>');

        // The connecting separator isn't rendered at all in this case (no
        // element to scope a class-token check to); "tedi-separator--vertical"
        // has no sibling class that contains it, so a substring check is safe.
        $this->assertStringNotContainsString('tedi-separator--vertical', $html);
    }

    public function test_timeline_item_not_last_renders_connecting_separator(): void
    {
        $html = Blade::render('<tedi:timeline-item>content</tedi:timeline-item>');

        $this->assertHasClass('tedi-separator--vertical', $html, on: 'tedi-separator--vertical');
    }

    public function test_timeline_item_title_and_description_slots(): void
    {
        $html = Blade::render('
            <tedi:timeline-item>
                <x-slot:title>My title</x-slot:title>
                <x-slot:description>My description</x-slot:description>
                <button>Action</button>
            </tedi:timeline-item>
        ');

        // Literal custom-element tag names, not classes.
        $this->assertStringContainsString('<tedi-timeline-title>', $html);
        $this->assertStringContainsString('My title', $html);
        $this->assertStringContainsString('<tedi-timeline-description>', $html);
        $this->assertStringContainsString('My description', $html);
        $this->assertHasClass('tedi-timeline__content', $html, on: 'tedi-timeline__content');
        $this->assertStringContainsString('Action', $html);
    }

    public function test_timeline_item_timings_render(): void
    {
        $html = Blade::render('<tedi:timeline-item :timings="[\'1990\', \'14. detsember\']">content</tedi:timeline-item>');

        $this->assertHasClass('tedi-timeline__time', $html, on: 'tedi-timeline__time');
        $this->assertStringContainsString('1990', $html);
        $this->assertStringContainsString('14. detsember', $html);
    }

    // -- vertical-spacing ---------------------------------------------------

    /** The VerticalSpacingSize union, verbatim from vertical-spacing.directive.ts. */
    private const VERTICAL_SPACING_SIZES = [0, 0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2, 2.5, 3, 4, 5];

    public function test_vertical_spacing_base_class_and_default_size(): void
    {
        $html = Blade::render('<tedi:vertical-spacing><p>a</p></tedi:vertical-spacing>');

        $this->assertHasClass('tedi-vertical-spacing', $html);
        $this->assertMissingClass('tedi-vertical-spacing__item', $html);
        // Angular's default input value is 0 and it always writes the property.
        $this->assertStringContainsString('--vertical-spacing-internal: 0em', $html);
        $this->assertStringContainsString('<p>a</p>', $html);
    }

    public function test_vertical_spacing_emits_every_size_of_the_union(): void
    {
        foreach (self::VERTICAL_SPACING_SIZES as $size) {
            $html = Blade::render('<tedi:vertical-spacing :size="'.$size.'">x</tedi:vertical-spacing>');

            $this->assertHasClass('tedi-vertical-spacing', $html);
            $this->assertStringContainsString(
                '--vertical-spacing-internal: '.$size.'em', $html,
                "size {$size} did not render the expected custom property."
            );
        }
    }

    public function test_vertical_spacing_item_base_class_and_every_size(): void
    {
        $html = Blade::render('<tedi:vertical-spacing-item>a</tedi:vertical-spacing-item>');

        $this->assertHasClass('tedi-vertical-spacing__item', $html);
        $this->assertMissingClass('tedi-vertical-spacing', $html);
        $this->assertStringContainsString('--vertical-spacing-internal: 0em', $html);

        foreach (self::VERTICAL_SPACING_SIZES as $size) {
            $html = Blade::render('<tedi:vertical-spacing-item :size="'.$size.'">x</tedi:vertical-spacing-item>');

            $this->assertHasClass('tedi-vertical-spacing__item', $html);
            $this->assertStringContainsString(
                '--vertical-spacing-internal: '.$size.'em', $html,
                "item size {$size} did not render the expected custom property."
            );
        }
    }

    /**
     * Angular calls setAttribute('style', …), which clobbers. The port merges
     * instead, per CONVENTIONS.md §6 — pinned here because it is a deliberate
     * divergence documented in the component's header.
     */
    public function test_vertical_spacing_merges_consumer_class_and_style(): void
    {
        $html = Blade::render(
            '<tedi:vertical-spacing :size="1.5" class="my-stack" style="padding:1rem">x</tedi:vertical-spacing>'
        );

        $this->assertHasClass('tedi-vertical-spacing', $html);
        $this->assertHasClass('my-stack', $html);
        $this->assertStringContainsString('padding:1rem', $html);
        $this->assertStringContainsString('--vertical-spacing-internal: 1.5em', $html);
    }

    // -- hide-at / show-at --------------------------------------------------

    /**
     * These emit no classes at all — TEDI ships no display utilities, so per
     * CONVENTIONS.md §4 none are invented. The server-side handle on the
     * behaviour is the Alpine config, so that is what is asserted.
     */
    public function test_hide_at_emits_no_classes_and_binds_the_shared_factory(): void
    {
        $html = Blade::render('<tedi:hide-at breakpoint="md">x</tedi:hide-at>');

        $this->assertSame([], $this->classesOf($html));
        $this->assertStringContainsString(
            "tediBreakpoint({ breakpoint: 'md', mode: 'hide' })", $html
        );
        $this->assertStringContainsString('x-show="visible"', $html);
    }

    public function test_show_at_binds_the_shared_factory_in_show_mode(): void
    {
        $html = Blade::render('<tedi:show-at breakpoint="md">x</tedi:show-at>');

        $this->assertSame([], $this->classesOf($html));
        $this->assertStringContainsString(
            "tediBreakpoint({ breakpoint: 'md', mode: 'show' })", $html
        );
        $this->assertStringContainsString('x-show="visible"', $html);
    }

    public function test_hide_at_and_show_at_accept_every_breakpoint_name(): void
    {
        foreach (['xs', 'sm', 'md', 'lg', 'xl', 'xxl'] as $bp) {
            $hide = Blade::render('<tedi:hide-at breakpoint="'.$bp.'">x</tedi:hide-at>');
            $show = Blade::render('<tedi:show-at breakpoint="'.$bp.'">x</tedi:show-at>');

            $this->assertStringContainsString("breakpoint: '".$bp."'", $hide);
            $this->assertStringContainsString("breakpoint: '".$bp."'", $show);
        }
    }

    /**
     * §8: the content must be in the DOM statically, so a consumer without the
     * JS bundle sees it. Only `display` is toggled — Angular's structural
     * (*hideAt) removal is the documented divergence.
     */
    public function test_hide_at_keeps_its_content_in_the_static_markup(): void
    {
        $html = Blade::render('<tedi:hide-at breakpoint="sm"><p>Kitsas vaade</p></tedi:hide-at>');

        $this->assertStringContainsString('<p>Kitsas vaade</p>', $html);
        $this->assertStringNotContainsString('display:none', $html);
        $this->assertStringNotContainsString('x-cloak', $html);
    }

    /**
     * The rem values the Alpine module maps the names onto are core's
     * $grid-breakpoints. Pinned here so a drift in either is caught.
     */
    public function test_breakpoint_module_mirrors_core_grid_breakpoints(): void
    {
        $js = file_get_contents(__DIR__.'/../../resources/js/src/breakpoint.js');

        foreach (['xs: 0', 'sm: 36', 'md: 48', 'lg: 62', 'xl: 75', 'xxl: 87.5'] as $pair) {
            $this->assertStringContainsString($pair, $js,
                "breakpoint.js no longer mirrors core's \$grid-breakpoints: {$pair}");
        }
    }
}
