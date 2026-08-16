<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the components ported from @tedi-design-system/react
 * (CONVENTIONS.md §13).
 *
 * The unions come from the React `export type` / prop declarations, and the
 * expected class strings from each component's `cn(styles[…])` call — which is
 * the authority here the same way `classes()` is for an Angular port.
 *
 * Two React-specific things get their own cases, because they are where a
 * React port goes wrong in a way an Angular port cannot:
 *
 *  - `styles[…]` returns undefined for a name the stylesheet does not define,
 *    so upstream silently drops it. `tedi-affix--sticky` is the live example.
 *  - React emits classes for rules that were never written
 *    (`tedi-file-upload__label`, `tedi-multi-value-field`), which CONVENTIONS.md
 *    §4 says to drop. Those are pinned as absences so a future re-sync has to
 *    make the decision again deliberately.
 */
class ReactComponentsTest extends TestCase
{
    // =====================================================================
    // Loader / Skeleton
    // =====================================================================

    public function test_skeleton_renders_its_only_class_and_a_live_region(): void
    {
        $html = Blade::render('<tedi:skeleton>x</tedi:skeleton>');

        $this->assertHasClass('tedi-skeleton', $html);
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringContainsString('Loading', $html);
    }

    public function test_skeleton_label_can_be_suppressed_with_an_empty_string(): void
    {
        $html = Blade::render('<tedi:skeleton label="">x</tedi:skeleton>');

        $this->assertStringNotContainsString('role="status"', $html);
    }

    public function test_skeleton_block_height_modifiers(): void
    {
        foreach (['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $height) {
            $html = Blade::render('<tedi:skeleton-block height="'.$height.'" />');

            $this->assertHasClass('tedi-skeleton-block', $html);
            $this->assertHasClass('tedi-skeleton-block--'.$height, $html);
        }
    }

    /**
     * A numeric height is an inline px value, NOT a modifier — and it is the
     * one case where no height modifier is emitted at all.
     */
    public function test_skeleton_block_numeric_height_is_inline_and_emits_no_modifier(): void
    {
        $html = Blade::render('<tedi:skeleton-block :height="12" />');

        $this->assertStringContainsString('height: 12px', $html);
        $this->assertMissingClass('tedi-skeleton-block--12', $html);
        $this->assertMissingClass('tedi-skeleton-block--p', $html);
    }

    /**
     * Upstream's three-way width resolution, including the asymmetry with
     * height: a bare number is a PERCENT here and PIXELS there.
     */
    public function test_skeleton_block_width_resolution(): void
    {
        $this->assertStringContainsString('width: 60%',
            Blade::render('<tedi:skeleton-block :width="60" />'));

        $this->assertStringContainsString('width: 80px',
            Blade::render('<tedi:skeleton-block width="80px" />'));

        $this->assertStringContainsString('width: auto',
            Blade::render('<tedi:skeleton-block />'));
    }

    // =====================================================================
    // Content / HeadingWithIcon
    // =====================================================================

    public function test_heading_with_icon_renders_the_requested_heading_element(): void
    {
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $element) {
            $html = Blade::render('<tedi:heading-with-icon element="'.$element.'" name="info">P</tedi:heading-with-icon>');

            $this->assertMatchesRegularExpression('/<'.$element.'[^>]*>/', $html);
            $this->assertHasClass('tedi-heading-with-icon', $html);
        }
    }

    /**
     * React's Heading passes `element` as the tag only — the typography
     * modifier comes from `modifiers`, which nothing sets by default. So a
     * default heading-with-icon carries NO tedi-text--h4.
     */
    public function test_heading_with_icon_emits_no_typography_modifier_by_default(): void
    {
        $html = Blade::render('<tedi:heading-with-icon name="info">P</tedi:heading-with-icon>');

        $this->assertHasClass('tedi-text--primary', $html);
        $this->assertMissingClass('tedi-text--h4', $html);
    }

    public function test_heading_with_icon_omits_the_icon_when_no_name_is_given(): void
    {
        $html = Blade::render('<tedi:heading-with-icon>P</tedi:heading-with-icon>');

        $this->assertStringNotContainsString('<tedi-icon', $html);
    }

    public function test_heading_with_icon_colours_are_independent(): void
    {
        $html = Blade::render('<tedi:heading-with-icon name="info" heading-color="danger" icon-color="success">P</tedi:heading-with-icon>');

        $this->assertHasClass('tedi-text--danger', $html);
        $this->assertHasClass('tedi-icon--color-success', $html, on: 'tedi-icon');
    }

    // =====================================================================
    // Content / Section
    // =====================================================================

    public function test_section_element_union(): void
    {
        foreach (['section', 'article', 'aside', 'div'] as $as) {
            $html = Blade::render('<tedi:section as="'.$as.'">x</tedi:section>');

            $this->assertStringContainsString('<'.$as.' ', $html);
            $this->assertHasClass('tedi-section', $html);
        }
    }

    // =====================================================================
    // Helpers / StretchContent
    // =====================================================================

    public function test_stretch_content_direction_union(): void
    {
        foreach (['both', 'horizontal', 'vertical'] as $direction) {
            $html = Blade::render('<tedi:stretch-content direction="'.$direction.'">x</tedi:stretch-content>');

            $this->assertHasClass('tedi-stretch-content--'.$direction, $html);
        }
    }

    /**
     * Upstream emits ONLY the directional modifier. A base class would be an
     * invention (CONVENTIONS.md §4), and the stylesheet defines no such rule.
     */
    public function test_stretch_content_emits_no_base_class(): void
    {
        $html = Blade::render('<tedi:stretch-content>x</tedi:stretch-content>');

        $this->assertMissingClass('tedi-stretch-content', $html);
        $this->assertHasClass('tedi-stretch-content--both', $html);
    }

    // =====================================================================
    // Helpers / Affix
    // =====================================================================

    /**
     * `styles['tedi-affix--sticky']` is undefined upstream, so cn() drops it.
     * The default position therefore emits the base class alone.
     */
    public function test_affix_sticky_emits_no_position_modifier(): void
    {
        $html = Blade::render('<tedi:affix>x</tedi:affix>');

        $this->assertHasClass('tedi-affix', $html);
        $this->assertMissingClass('tedi-affix--sticky', $html);
        $this->assertMissingClass('tedi-affix--fixed', $html);
    }

    /**
     * The `top` modifier is guarded by `position === 'fixed'` upstream; the
     * other three sides are not. This asymmetry is the component.
     */
    public function test_affix_top_modifier_is_fixed_mode_only(): void
    {
        $sticky = Blade::render('<tedi:affix :top="1">x</tedi:affix>');
        $this->assertMissingClass('tedi-affix--top-1', $sticky);

        $fixed = Blade::render('<tedi:affix position="fixed" :top="1">x</tedi:affix>');
        $this->assertHasClass('tedi-affix--top-1', $fixed);

        $stickyBottom = Blade::render('<tedi:affix :bottom="1">x</tedi:affix>');
        $this->assertHasClass('tedi-affix--bottom-1', $stickyBottom);
    }

    public function test_affix_offset_scale_including_the_decimal_spelling(): void
    {
        $expected = ['0' => '0', '0.5' => '0-5', '1' => '1', '1.5' => '1-5', '2' => '2', 'unset' => 'unset'];

        foreach ($expected as $value => $suffix) {
            foreach (['top', 'bottom', 'left', 'right'] as $side) {
                $html = Blade::render('<tedi:affix position="fixed" '.$side.'="'.$value.'">x</tedi:affix>');

                $this->assertHasClass('tedi-affix--'.$side.'-'.$suffix, $html);
            }
        }
    }

    /**
     * A value off the scale has no rule, so emitting it would be dead markup.
     */
    public function test_affix_rejects_an_offset_outside_the_scale(): void
    {
        $html = Blade::render('<tedi:affix position="fixed" :top="3">x</tedi:affix>');

        $this->assertMissingClass('tedi-affix--top-3', $html);
    }

    public function test_affix_writes_the_sticky_position_inline_and_fixed_mode_does_not(): void
    {
        $sticky = Blade::render('<tedi:affix :top="1.5">x</tedi:affix>');
        $this->assertStringContainsString('position: sticky', $sticky);
        $this->assertStringContainsString('top: 1.5rem', $sticky);

        $fixed = Blade::render('<tedi:affix position="fixed">x</tedi:affix>');
        $this->assertStringNotContainsString('position: sticky', $fixed);
        $this->assertStringNotContainsString('style=""', $fixed);
    }

    // =====================================================================
    // Helpers / ScrollVisibility
    // =====================================================================

    public function test_scroll_visibility_animation_direction_union(): void
    {
        foreach (['left', 'right', 'up', 'down', 'center'] as $direction) {
            $html = Blade::render('<tedi:scroll-visibility animation-direction="'.$direction.'">x</tedi:scroll-visibility>');

            $this->assertHasClass('tedi-scroll-visibility', $html);
            $this->assertHasClass('tedi-scroll-visibility--'.$direction, $html);
        }
    }

    /**
     * CONVENTIONS.md §8: the hidden state is a bound class, never a
     * server-rendered one — the static markup must be visible.
     */
    public function test_scroll_visibility_is_not_hidden_on_the_server(): void
    {
        $html = Blade::render('<tedi:scroll-visibility>x</tedi:scroll-visibility>');

        $this->assertMissingClass('tedi-scroll-visibility--hidden', $html);
        $this->assertStringContainsString('tediScrollVisibility(', $html);
    }

    // =====================================================================
    // Content / Truncate
    // =====================================================================

    public function test_truncate_cuts_at_max_length_and_appends_the_ellipsis(): void
    {
        $html = Blade::render('<tedi:truncate content="'.str_repeat('a', 50).'" :max-length="10" />');

        $this->assertStringContainsString('aaaaaaaaaa...', $html);
        $this->assertHasClass('tedi-text--secondary', $html);
    }

    /**
     * Upstream compares with `>=`, so a string of exactly maxLength counts as
     * truncatable even though slicing it changes nothing.
     */
    public function test_truncate_treats_an_exact_length_match_as_truncatable(): void
    {
        $html = Blade::render('<tedi:truncate content="'.str_repeat('a', 10).'" :max-length="10" />');

        $this->assertStringContainsString('Show more', $html);
    }

    public function test_truncate_shorter_than_max_length_renders_no_toggle(): void
    {
        $html = Blade::render('<tedi:truncate content="lühike" :max-length="200" />');

        $this->assertStringNotContainsString('Show more', $html);
        $this->assertStringNotContainsString('x-data', $html);
    }

    public function test_truncate_expandable_false_renders_the_cut_text_without_a_toggle(): void
    {
        $html = Blade::render('<tedi:truncate content="'.str_repeat('a', 50).'" :max-length="10" :expandable="false" />');

        $this->assertStringContainsString('aaaaaaaaaa...', $html);
        $this->assertStringNotContainsString('Show more', $html);
    }

    /**
     * The server-rendered text is the collapsed one — the JS only swaps it.
     */
    public function test_truncate_server_render_is_the_collapsed_state(): void
    {
        $html = Blade::render('<tedi:truncate content="'.str_repeat('a', 50).'" :max-length="10" />');

        $this->assertStringContainsString('aria-expanded="false"', $html);
    }

    // =====================================================================
    // Misc / Print
    // =====================================================================

    public function test_print_visibility_classes(): void
    {
        $hide = Blade::render('<tedi:print visibility="hide">x</tedi:print>');
        $this->assertHasClass('no-print', $hide);
        $this->assertMissingClass('show-print', $hide);

        $show = Blade::render('<tedi:print visibility="show">x</tedi:print>');
        $this->assertHasClass('show-print', $show);
        $this->assertMissingClass('no-print', $show);
    }

    public function test_print_emits_no_visibility_class_by_default(): void
    {
        $html = Blade::render('<tedi:print>x</tedi:print>');

        $this->assertMissingClass('no-print', $html);
        $this->assertMissingClass('show-print', $html);
    }

    public function test_print_break_unions(): void
    {
        $breaks = ['auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'];

        foreach ($breaks as $break) {
            $this->assertHasClass('break-before-'.$break,
                Blade::render('<tedi:print break-before="'.$break.'">x</tedi:print>'));

            $this->assertHasClass('break-after-'.$break,
                Blade::render('<tedi:print break-after="'.$break.'">x</tedi:print>'));

            $this->assertHasClass('break-inside-'.$break,
                Blade::render('<tedi:print break-inside="'.$break.'">x</tedi:print>'));
        }
    }

    // =====================================================================
    // Navigation / HashTrigger
    // =====================================================================

    public function test_hash_trigger_puts_the_id_on_the_root_and_wires_the_behaviour(): void
    {
        $html = Blade::render('<tedi:hash-trigger id="ptk-1">x</tedi:hash-trigger>');

        $this->assertStringContainsString('id="ptk-1"', $html);
        $this->assertStringContainsString('tediHashTrigger(', $html);
    }

    // =====================================================================
    // Layout / TopNav
    // =====================================================================

    public function test_top_nav_structure_and_landmark(): void
    {
        $html = Blade::render('<tedi:top-nav aria-label="Peamenüü"><tedi:top-nav-item href="/">A</tedi:top-nav-item></tedi:top-nav>');

        $this->assertHasClass('tedi-top-nav', $html);
        $this->assertHasClass('tedi-top-nav__bar', $html, on: 'tedi-top-nav__bar');
        $this->assertHasClass('tedi-top-nav__list', $html, on: 'tedi-top-nav__list');
        $this->assertStringContainsString('aria-label="Peamenüü"', $html);
    }

    /**
     * Upstream resolves a breakpoint NAME to that breakpoint's min-width, a
     * bare number to px, and 'none'/0 to no constraint at all.
     */
    public function test_top_nav_max_width_resolution(): void
    {
        $widths = ['sm' => '36rem', 'md' => '48rem', 'lg' => '62rem', 'xl' => '75rem', 'xxl' => '87.5rem'];

        foreach ($widths as $name => $length) {
            $this->assertStringContainsString('max-width: '.$length,
                Blade::render('<tedi:top-nav max-width="'.$name.'">x</tedi:top-nav>'));
        }

        $this->assertStringContainsString('max-width: 1440px',
            Blade::render('<tedi:top-nav :max-width="1440">x</tedi:top-nav>'));

        $this->assertStringContainsString('max-width: 90rem',
            Blade::render('<tedi:top-nav max-width="90rem">x</tedi:top-nav>'));

        $this->assertStringNotContainsString('max-width',
            Blade::render('<tedi:top-nav max-width="none">x</tedi:top-nav>'));
    }

    /**
     * `isToggle = !href && hasSubmenu` — the branch decides the element AND
     * the whole ARIA set.
     */
    public function test_top_nav_item_renders_a_link_or_a_toggle(): void
    {
        $link = Blade::render('<tedi:top-nav-item href="/x">A</tedi:top-nav-item>');
        $this->assertStringContainsString('<a', $link);
        $this->assertStringNotContainsString('aria-haspopup', $link);

        $toggle = Blade::render('<tedi:top-nav-item key="a">A</tedi:top-nav-item>');
        $this->assertStringContainsString('<button', $toggle);
        $this->assertStringContainsString('aria-haspopup="true"', $toggle);
        $this->assertStringContainsString('aria-controls="top-nav-submenu-a"', $toggle);
    }

    /**
     * An item with BOTH href and a submenu stays a link — upstream's `!href`.
     */
    public function test_top_nav_item_with_href_and_submenu_stays_a_link(): void
    {
        $html = Blade::render('<tedi:top-nav-item href="/x" key="a">A</tedi:top-nav-item>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringNotContainsString('aria-haspopup', $html);
        // …and still gets the chevron, because it has a submenu.
        $this->assertStringContainsString('keyboard_arrow_down', $html);
    }

    public function test_top_nav_item_active_and_disabled_states(): void
    {
        $active = Blade::render('<tedi:top-nav-item href="/x" is-active>A</tedi:top-nav-item>');
        $this->assertHasClass('tedi-top-nav__link--active', $active, on: 'tedi-top-nav__link');
        $this->assertStringContainsString('aria-current="page"', $active);

        $disabled = Blade::render('<tedi:top-nav-item href="/x" disabled>A</tedi:top-nav-item>');
        $this->assertStringContainsString('aria-disabled="true"', $disabled);
        $this->assertStringNotContainsString('href=', $disabled);

        $disabledToggle = Blade::render('<tedi:top-nav-item key="a" disabled>A</tedi:top-nav-item>');
        $this->assertStringContainsString('disabled', $disabledToggle);
    }

    public function test_top_nav_inline_submenu_only_renders_under_the_content_fit(): void
    {
        $content = Blade::render(
            '<tedi:top-nav submenu-fit="content"><tedi:top-nav-item key="a">A'
            .'<x-slot:submenu>panel</x-slot:submenu> </tedi:top-nav-item></tedi:top-nav>'
        );
        $this->assertHasClass('tedi-top-nav__item--has-inline-submenu', $content, on: 'tedi-top-nav__item');
        $this->assertHasClass('tedi-top-nav__submenu--inline', $content, on: 'tedi-top-nav__submenu');

        $full = Blade::render(
            '<tedi:top-nav><tedi:top-nav-item key="a">A'
            .'<x-slot:submenu>panel</x-slot:submenu> </tedi:top-nav-item></tedi:top-nav>'
        );
        $this->assertMissingClass('tedi-top-nav__item--has-inline-submenu', $full, on: 'tedi-top-nav__item');
    }

    /**
     * The panel is in the DOM with its real class list while closed
     * (CONVENTIONS.md §8) — x-show, never x-if.
     */
    public function test_top_nav_submenu_panel_exists_while_closed(): void
    {
        $html = Blade::render(
            '<tedi:top-nav><x-slot:submenu><tedi:top-nav-submenu for="a">panel</tedi:top-nav-submenu></x-slot:submenu> </tedi:top-nav>'
        );

        $this->assertHasClass('tedi-top-nav__submenu', $html, on: 'tedi-top-nav__submenu');
        $this->assertHasClass('tedi-top-nav__submenu-inner', $html, on: 'tedi-top-nav__submenu-inner');
        $this->assertStringContainsString('x-show="openKey === \'a\'"', $html);
        $this->assertStringContainsString('x-cloak', $html);
    }

    /**
     * The panel id and the trigger's aria-controls must be built the same way,
     * from the shared `panelId` prefix and the item's key.
     */
    public function test_top_nav_panel_id_matches_the_trigger_aria_controls(): void
    {
        $html = Blade::render(
            '<tedi:top-nav panel-id="peamenüü"><tedi:top-nav-item key="a">A</tedi:top-nav-item>'
            .'<x-slot:submenu><tedi:top-nav-submenu for="a">panel</tedi:top-nav-submenu></x-slot:submenu> </tedi:top-nav>'
        );

        $this->assertStringContainsString('aria-controls="peamenüü-a"', $html);
        $this->assertStringContainsString('id="peamenüü-a"', $html);
    }

    public function test_top_nav_group_omits_the_heading_and_its_icon_when_untitled(): void
    {
        $titled = Blade::render('<tedi:top-nav-group title="Rühm" icon="folder" heading-level="h2">x</tedi:top-nav-group>');
        $this->assertStringContainsString('<h2', $titled);
        $this->assertHasClass('tedi-top-nav__group-title', $titled, on: 'tedi-top-nav__group-title');
        $this->assertHasClass('tedi-top-nav__group-icon', $titled, on: 'tedi-top-nav__group-icon');

        $untitled = Blade::render('<tedi:top-nav-group icon="folder">x</tedi:top-nav-group>');
        $this->assertStringNotContainsString('tedi-top-nav__group-title', $untitled);
        $this->assertStringNotContainsString('tedi-top-nav__group-icon', $untitled);
        $this->assertHasClass('tedi-top-nav__group-list', $untitled, on: 'tedi-top-nav__group-list');
    }

    public function test_top_nav_subitem_active_state(): void
    {
        $html = Blade::render('<tedi:top-nav-subitem href="/x" is-active>A</tedi:top-nav-subitem>');

        $this->assertHasClass('tedi-top-nav__subitem', $html, on: 'tedi-top-nav__subitem');
        $this->assertHasClass('tedi-top-nav__subitem-link--active', $html, on: 'tedi-top-nav__subitem-link');
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function test_top_nav_separator_is_a_presentational_list_item(): void
    {
        $html = Blade::render('<tedi:top-nav-separator />');

        $this->assertHasClass('tedi-top-nav__separator', $html, on: 'tedi-top-nav__separator');
        $this->assertHasClass('tedi-top-nav__separator-line', $html, on: 'tedi-top-nav__separator-line');
        $this->assertStringContainsString('role="separator"', $html);
        $this->assertStringContainsString('aria-orientation="vertical"', $html);
    }

    // =====================================================================
    // Form / FileUpload
    // =====================================================================

    public function test_file_upload_state_modifiers(): void
    {
        $error = Blade::render('<tedi:file-upload name="f" :helper="[\'text\' => \'V\', \'type\' => \'error\']" />');
        $this->assertHasClass('tedi-file-upload--error', $error, on: 'tedi-file-upload__container');

        $valid = Blade::render('<tedi:file-upload name="f" :helper="[\'text\' => \'K\', \'type\' => \'valid\']" />');
        $this->assertHasClass('tedi-file-upload--valid', $valid, on: 'tedi-file-upload__container');

        $disabled = Blade::render('<tedi:file-upload name="f" disabled />');
        $this->assertHasClass('tedi-file-upload--disabled', $disabled, on: 'tedi-file-upload__container');
    }

    /**
     * showFiles(): one file is truncating text, several are a tag list.
     */
    public function test_file_upload_switches_list_shape_on_file_count(): void
    {
        $one = Blade::render('<tedi:file-upload name="f" :files="[[\'name\' => \'a.pdf\']]" />');
        $this->assertHasClass('tedi-file-upload__items--truncate', $one, on: 'tedi-file-upload__items');

        $two = Blade::render('<tedi:file-upload name="f" :files="[[\'name\' => \'a.pdf\'], [\'name\' => \'b.pdf\']]" />');
        $this->assertMissingClass('tedi-file-upload__items--truncate', $two, on: 'tedi-file-upload__items');
        $this->assertStringContainsString('<ul class="tedi-file-upload__items"', $two);

        $none = Blade::render('<tedi:file-upload name="f" />');
        $this->assertStringNotContainsString('tedi-file-upload__items', $none);
    }

    public function test_file_upload_marks_an_invalid_file(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" :files="[[\'name\' => \'a.pdf\'], [\'name\' => \'b.pdf\', \'is_valid\' => false]]" />');

        $this->assertStringContainsString('tedi-tag--danger', $html);
        $this->assertStringContainsString('File upload failed', $html);
    }

    public function test_file_upload_read_only_renders_the_list_without_controls(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" read-only :files="[[\'name\' => \'a.pdf\'], [\'name\' => \'b.pdf\']]" />');

        $this->assertStringNotContainsString('type="file"', $html);
        $this->assertStringNotContainsString('tedi-file-upload__container', $html);
        $this->assertStringContainsString('tedi-file-upload__items', $html);
    }

    /**
     * CONVENTIONS.md §6: wire:model must land on the control.
     */
    public function test_file_upload_binds_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" wire:model="failid" />');

        $this->assertMatchesRegularExpression('/<input[^>]*wire:model="failid"/', $html);
    }

    /**
     * The clear control must stay out of the shared picker-icon hover rule in
     * _field-icon-button.scss, which keys on this attribute.
     */
    public function test_file_upload_clear_button_is_marked_for_the_field_icon_rule(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" :files="[[\'name\' => \'a.pdf\']]" />');

        $this->assertStringContainsString('data-name="closing-button"', $html);
    }

    public function test_file_upload_clear_button_hides_without_files_or_when_disabled(): void
    {
        $this->assertStringNotContainsString('data-name="closing-button"',
            Blade::render('<tedi:file-upload name="f" />'));

        $this->assertStringNotContainsString('data-name="closing-button"',
            Blade::render('<tedi:file-upload name="f" disabled :files="[[\'name\' => \'a.pdf\']]" />'));

        $this->assertStringNotContainsString('data-name="closing-button"',
            Blade::render('<tedi:file-upload name="f" :has-clear-button="false" :files="[[\'name\' => \'a.pdf\']]" />'));
    }

    /**
     * CONVENTIONS.md §4 — upstream emits these, the stylesheet defines none of
     * them, so they are dropped. Pinned so a re-sync has to decide again.
     */
    public function test_file_upload_drops_the_unstyled_upstream_classes(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" label="Manused" size="small" />');

        $this->assertStringNotContainsString('tedi-file-upload__label', $html);
        $this->assertStringNotContainsString('tedi-file-upload__container--small', $html);
    }

    public function test_file_upload_show_restrictions_builds_a_hint_helper(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" accept=".pdf,.docx" :max-size="5" />');

        $this->assertStringContainsString('Allowed file extensions:', $html);
        $this->assertStringContainsString('.pdf, .docx', $html);
        $this->assertStringContainsString('Maximum size:', $html);
        $this->assertStringContainsString('5MB', $html);
        $this->assertStringContainsString('tedi-feedback-text', $html);
    }

    public function test_file_upload_show_restrictions_can_be_suppressed(): void
    {
        $html = Blade::render('<tedi:file-upload name="f" accept=".pdf" :max-size="5" :show-restrictions="false" />');

        $this->assertStringNotContainsString('Allowed file extensions:', $html);
        $this->assertStringNotContainsString('Maximum size:', $html);
    }

    public function test_file_upload_explicit_helper_wins_over_restrictions(): void
    {
        $html = Blade::render(
            '<tedi:file-upload name="f" accept=".pdf" :max-size="5" :helper="[\'text\' => \'Custom\', \'type\' => \'hint\']" />'
        );

        $this->assertStringContainsString('Custom', $html);
        $this->assertStringNotContainsString('Allowed file extensions:', $html);
    }

    // =====================================================================
    // Form / MultiValueField
    // =====================================================================

    public function test_multi_value_field_row_modifiers(): void
    {
        $stack = Blade::render('<tedi:multi-value-field :values="[\'a\']" />');
        $this->assertHasClass('tedi-multi-value-field__inner', $stack, on: 'tedi-multi-value-field__inner');
        $this->assertMissingClass('tedi-multi-value-field__inner--row', $stack, on: 'tedi-multi-value-field__inner');

        $row = Blade::render('<tedi:multi-value-field :values="[\'a\']" tags-direction="row" />');
        $this->assertHasClass('tedi-multi-value-field__inner--row', $row, on: 'tedi-multi-value-field__inner');
        $this->assertHasClass('tedi-multi-value-field__tags--row', $row, on: 'tedi-multi-value-field__tags');
    }

    /**
     * The overflow counter is driven by the explicit visible-count prop, since
     * upstream measures it with a ResizeObserver (CONVENTIONS.md §13.5).
     */
    public function test_multi_value_field_overflow_counter(): void
    {
        $measured = Blade::render('<tedi:multi-value-field :values="[\'a\',\'b\',\'c\']" tags-direction="row" :visible-count="2" />');
        $this->assertHasClass('tedi-multi-value-field__overflow-tag', $measured, on: 'tedi-multi-value-field__overflow-tag');
        $this->assertStringContainsString('+1', $measured);
        $this->assertStringContainsString('1 more', $measured);

        // Unmeasured: every tag renders and there is no counter — upstream's
        // pre-measurement paint.
        $unmeasured = Blade::render('<tedi:multi-value-field :values="[\'a\',\'b\',\'c\']" tags-direction="row" />');
        $this->assertStringNotContainsString('tedi-multi-value-field__overflow-tag', $unmeasured);

        // Stack mode never counts, even with a visible-count.
        $stack = Blade::render('<tedi:multi-value-field :values="[\'a\',\'b\',\'c\']" :visible-count="2" />');
        $this->assertStringNotContainsString('tedi-multi-value-field__overflow-tag', $stack);
    }

    public function test_multi_value_field_icon_renders_as_a_button_only_when_asked(): void
    {
        $div = Blade::render('<tedi:multi-value-field :values="[\'a\']" icon="expand_more" />');
        $this->assertMatchesRegularExpression('/<div class="tedi-multi-value-field__icon-wrapper"/', $div);

        $button = Blade::render('<tedi:multi-value-field :values="[\'a\']" icon="expand_more" icon-is-button :icon-button-attributes="[\'aria-expanded\' => \'false\']" />');
        $this->assertMatchesRegularExpression('/<button[^>]*class="tedi-multi-value-field__icon-wrapper"/', $button);
        $this->assertStringContainsString('aria-expanded="false"', $button);
    }

    /**
     * Upstream serialises to JSON, and to an EMPTY STRING — not "[]" — when
     * there are no values.
     */
    public function test_multi_value_field_hidden_input_serialisation(): void
    {
        $filled = Blade::render('<tedi:multi-value-field name="v" :values="[\'a\',\'b\']" />');
        $this->assertStringContainsString('value="[&quot;a&quot;,&quot;b&quot;]"', $filled);

        $empty = Blade::render('<tedi:multi-value-field name="v" :values="[]" />');
        $this->assertStringContainsString('value=""', $empty);

        $unnamed = Blade::render('<tedi:multi-value-field :values="[\'a\']" />');
        $this->assertStringNotContainsString('type="hidden"', $unnamed);
    }

    public function test_multi_value_field_right_area_only_renders_when_it_has_content(): void
    {
        $bare = Blade::render('<tedi:multi-value-field :values="[\'a\']" :is-clearable="false" />');
        $this->assertStringNotContainsString('tedi-multi-value-field__right-area', $bare);

        $clearable = Blade::render('<tedi:multi-value-field :values="[\'a\']" />');
        $this->assertHasClass('tedi-multi-value-field__right-area', $clearable, on: 'tedi-multi-value-field__right-area');

        // No values means nothing to clear, so no right area either.
        $empty = Blade::render('<tedi:multi-value-field :values="[]" />');
        $this->assertStringNotContainsString('tedi-multi-value-field__right-area', $empty);
    }

    /** CONVENTIONS.md §4 — the stylesheet defines neither of these. */
    public function test_multi_value_field_drops_the_unstyled_upstream_classes(): void
    {
        $html = Blade::render('<tedi:multi-value-field :values="[\'a\']" />');

        $this->assertMissingClass('tedi-multi-value-field', $html);
        $this->assertStringNotContainsString('tedi-multi-value-field__clear-button', $html);
    }

    // =====================================================================
    // Form / DateTimeField
    // =====================================================================

    public function test_date_time_field_closed_renders_the_input_without_a_popup(): void
    {
        $html = Blade::render('<tedi:date-time-field display="01.01.2026 10:00" />');

        $this->assertHasClass('tedi-date-time-field__container', $html);
        $this->assertStringContainsString('tedi-date-time-field__textfield', $html);
        $this->assertStringNotContainsString('tedi-date-time-field__popup', $html);
    }

    public function test_date_time_field_side_by_side_layout(): void
    {
        $html = Blade::render('<tedi:date-time-field :open="true" />');

        $this->assertStringContainsString('tedi-date-time-field__popup', $html);
        $this->assertStringContainsString('tedi-date-time-field__split"', $html);
        $this->assertStringContainsString('tedi-date-time-field__split-separator', $html);
        $this->assertStringContainsString('tedi-date-time-field__calendar--split', $html);
        $this->assertStringContainsString('tedi-date-time-field__split-time-body', $html);
    }

    public function test_date_time_field_range_layout_renders_two_time_pickers(): void
    {
        $html = Blade::render('<tedi:date-time-field mode="range" :open="true" />');

        $this->assertStringContainsString('tedi-date-time-field__range"', $html);
        $this->assertStringContainsString('tedi-date-time-field__range-separator', $html);
        $this->assertSame(2, substr_count($html, 'tedi-date-time-field__range-time"'));
        $this->assertStringNotContainsString('tedi-date-time-field__split"', $html);
    }

    public function test_date_time_field_multi_step_date_and_time_steps(): void
    {
        $date = Blade::render('<tedi:date-time-field layout="multi-step" :open="true" />');
        $this->assertStringContainsString('tedi-date-time-field__select-time-wrapper', $date);
        $this->assertStringContainsString('Select time', $date);
        $this->assertStringNotContainsString('tedi-date-time-field__time-step', $date);

        $time = Blade::render('<tedi:date-time-field layout="multi-step" step="time" value="2026-01-01 10:00" :open="true" />');
        $this->assertStringContainsString('tedi-date-time-field__time-step', $time);
        $this->assertStringContainsString('tedi-date-time-field__time-header', $time);
        $this->assertStringContainsString('01.01.2026', $time);
        $this->assertStringContainsString('Back', $time);
    }

    /**
     * isWheelMode: no availableTimes means the scrolling wheel, which is the
     * only case where the --wheel modifier is emitted.
     */
    public function test_date_time_field_wheel_modifier_tracks_available_times(): void
    {
        $wheel = Blade::render('<tedi:date-time-field :open="true" />');
        $this->assertStringContainsString('tedi-date-time-field__time-picker--wheel', $wheel);

        $slots = Blade::render('<tedi:date-time-field :open="true" :available-times="[\'09:00\']" />');
        $this->assertStringNotContainsString('tedi-date-time-field__time-picker--wheel', $slots);
    }

    public function test_date_time_field_disabled_never_opens_the_popup(): void
    {
        $html = Blade::render('<tedi:date-time-field :open="true" disabled />');

        $this->assertStringNotContainsString('tedi-date-time-field__popup', $html);
    }
}
