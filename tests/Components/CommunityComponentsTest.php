<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the five components ported from Angular's
 * `community/` entry point (CONVENTIONS.md §12).
 *
 * The prop unions come from the community `export type` declarations:
 *   FloatingButtonVariant / Size / Axis, ChoicegroupVariant,
 *   TableOfContentsPosition / Breakpoint, ValidationState.
 *
 * table-of-contents is the one component here whose classes are not
 * `tedi-`-prefixed, so IntegrityTest's stylesheet guardrails skip it — these
 * assertions are its only coverage.
 */
class CommunityComponentsTest extends TestCase
{
    // -- floating-button ------------------------------------------------

    public function test_floating_button_renders_a_button_with_the_selector_attribute(): void
    {
        $html = Blade::render('<tedi:floating-button>Tagasiside</tedi:floating-button>');

        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('tedi-floating-button', $html);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertHasClass('tedi-floating-button', $html, 'tedi-floating-button');
    }

    public function test_floating_button_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:floating-button variant="'.$variant.'">x</tedi:floating-button>');

            $this->assertHasClass('tedi-floating-button--'.$variant, $html);
        }
    }

    public function test_floating_button_size_classes(): void
    {
        foreach (['default', 'large'] as $size) {
            $html = Blade::render('<tedi:floating-button size="'.$size.'">x</tedi:floating-button>');

            $this->assertHasClass('tedi-floating-button--'.$size, $html);
        }
    }

    public function test_floating_button_defaults_match_angular(): void
    {
        $html = Blade::render('<tedi:floating-button>x</tedi:floating-button>');

        $this->assertHasClass('tedi-floating-button--primary', $html);
        $this->assertHasClass('tedi-floating-button--default', $html);
    }

    public function test_floating_button_vertical_axis_emits_a_class_and_horizontal_does_not(): void
    {
        $vertical = Blade::render('<tedi:floating-button axis="vertical">x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--vertical', $vertical);

        // Dropped: TEDI ships no `--horizontal` rule (CONVENTIONS.md §4).
        $horizontal = Blade::render('<tedi:floating-button axis="horizontal">x</tedi:floating-button>');
        $this->assertMissingClass('tedi-floating-button--horizontal', $horizontal);
        $this->assertMissingClass('tedi-floating-button--vertical', $horizontal);
    }

    public function test_floating_button_padding_modifiers_mirror_base_button_directive(): void
    {
        $plain = Blade::render('<tedi:floating-button>x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--pl', $plain);
        $this->assertHasClass('tedi-floating-button--pr', $plain);

        $start = Blade::render('<tedi:floating-button icon-start="add">x</tedi:floating-button>');
        $this->assertMissingClass('tedi-floating-button--pl', $start);
        $this->assertHasClass('tedi-floating-button--pr', $start);

        $end = Blade::render('<tedi:floating-button icon-end="arrow_forward">x</tedi:floating-button>');
        $this->assertHasClass('tedi-floating-button--pl', $end);
        $this->assertMissingClass('tedi-floating-button--pr', $end);

        $only = Blade::render('<tedi:floating-button icon-only icon-start="add" aria-label="Lisa" />');
        $this->assertHasClass('tedi-floating-button--icon-only', $only);
        $this->assertMissingClass('tedi-floating-button--pl', $only);
        $this->assertMissingClass('tedi-floating-button--pr', $only);
    }

    public function test_floating_button_disabled(): void
    {
        $html = Blade::render('<tedi:floating-button disabled>x</tedi:floating-button>');

        $this->assertStringContainsString('disabled', $html);
    }

    // -- choicegroup ----------------------------------------------------

    public function test_choicegroup_variant_classes(): void
    {
        foreach (['primary', 'secondary'] as $variant) {
            $html = Blade::render('<tedi:choicegroup variant="'.$variant.'">x</tedi:choicegroup>');

            $this->assertHasClass('tedi-choicegroup', $html, 'tedi-choicegroup');
            $this->assertHasClass('tedi-choicegroup--'.$variant, $html, 'tedi-choicegroup');
        }
    }

    public function test_choicegroup_stacked_only_at_zero_spacing(): void
    {
        $stacked = Blade::render('<tedi:choicegroup :spacing="0">x</tedi:choicegroup>');
        $this->assertHasClass('tedi-choicegroup--stacked', $stacked, 'tedi-choicegroup');

        foreach (['<tedi:choicegroup>x</tedi:choicegroup>', '<tedi:choicegroup :spacing="8">x</tedi:choicegroup>'] as $template) {
            $this->assertMissingClass('tedi-choicegroup--stacked', Blade::render($template), 'tedi-choicegroup');
        }
    }

    public function test_choicegroup_plain_when_indicator_is_off(): void
    {
        $plain = Blade::render('<tedi:choicegroup :has-indicator="false">x</tedi:choicegroup>');
        $this->assertHasClass('tedi-choicegroup--plain', $plain, 'tedi-choicegroup');

        $default = Blade::render('<tedi:choicegroup>x</tedi:choicegroup>');
        $this->assertMissingClass('tedi-choicegroup--plain', $default, 'tedi-choicegroup');
    }

    // -- file-dropzone --------------------------------------------------

    public function test_file_dropzone_renders_the_custom_element_and_a_native_input(): void
    {
        $html = Blade::render('<tedi:file-dropzone />');

        $this->assertStringContainsString('<tedi-file-dropzone', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*type="file"/', $html);
        $this->assertHasClass('tedi-file-dropzone__input', $html, 'tedi-file-dropzone__input');
        $this->assertHasClass('tedi-file-dropzone', $html, 'tedi-file-dropzone');
    }

    public function test_file_dropzone_binds_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:file-dropzone wire:model="attachment" />');

        $this->assertMatchesRegularExpression('/<input[^>]*wire:model="attachment"/', $html,
            'wire:model must be on the <input>, not the wrapper.');
    }

    public function test_file_dropzone_state_classes(): void
    {
        foreach (['valid', 'invalid'] as $state) {
            $html = Blade::render('<tedi:file-dropzone state="'.$state.'" />');

            $this->assertHasClass('tedi-file-dropzone--'.$state, $html, 'tedi-file-dropzone');
        }

        $none = Blade::render('<tedi:file-dropzone state="none" />');
        $this->assertMissingClass('tedi-file-dropzone--valid', $none, 'tedi-file-dropzone');
        $this->assertMissingClass('tedi-file-dropzone--invalid', $none, 'tedi-file-dropzone');
        $this->assertMissingClass('tedi-file-dropzone--none', $none, 'tedi-file-dropzone');
    }

    public function test_file_dropzone_has_error_overrides_the_state(): void
    {
        $html = Blade::render('<tedi:file-dropzone has-error state="valid" />');

        $this->assertHasClass('tedi-file-dropzone--invalid', $html, 'tedi-file-dropzone');
        $this->assertMissingClass('tedi-file-dropzone--valid', $html, 'tedi-file-dropzone');
    }

    public function test_file_dropzone_disabled_class_and_attribute(): void
    {
        $html = Blade::render('<tedi:file-dropzone disabled />');

        $this->assertHasClass('tedi-file-dropzone--disabled', $html, 'tedi-file-dropzone');
        $this->assertMatchesRegularExpression('/<input[^>]*disabled/', $html);
    }

    public function test_file_dropzone_native_attributes(): void
    {
        $html = Blade::render('<tedi:file-dropzone accept=".pdf,.docx" multiple upload-folder name="fail" />');

        $this->assertStringContainsString('accept=".pdf,.docx"', $html);
        $this->assertStringContainsString('multiple', $html);
        $this->assertStringContainsString('webkitdirectory', $html);
        $this->assertStringContainsString('name="fail"', $html);
    }

    public function test_file_dropzone_hint_is_generated_from_accept_and_max_size(): void
    {
        // getDefaultHelpers() + formatBytes(): IEC by default, so 5 MiB.
        $html = Blade::render('<tedi:file-dropzone accept=".pdf,.docx" :max-size="5242880" />');

        $this->assertStringContainsString('.pdf, .docx', $html);
        $this->assertStringContainsString('5 MiB', $html);

        $si = Blade::render('<tedi:file-dropzone :max-size="5242880" size-display-standard="SI" />');
        $this->assertStringContainsString('5.24 MB', $si);
    }

    public function test_file_dropzone_renders_no_hint_without_accept_or_max_size(): void
    {
        $html = Blade::render('<tedi:file-dropzone />');

        $this->assertStringNotContainsString('tedi-feedback-text', $html);
    }

    public function test_file_dropzone_file_list(): void
    {
        $html = Blade::render(
            '<tedi:file-dropzone :files="[[\'name\' => \'avaldus.pdf\', \'size\' => 943718]]" />'
        );

        $this->assertHasClass('tedi-file-dropzone__file-list', $html, 'tedi-file-dropzone__file-list');
        $this->assertStringContainsString('avaldus.pdf', $html);
        $this->assertStringContainsString('921.6 KiB', $html);
    }

    public function test_file_dropzone_error_message(): void
    {
        $html = Blade::render('<tedi:file-dropzone error="Fail on liiga suur" />');

        $this->assertStringContainsString('Fail on liiga suur', $html);
    }

    // -- table-of-contents ----------------------------------------------

    public function test_table_of_contents_base_and_position_classes(): void
    {
        foreach (['default', 'fixed', 'sticky'] as $position)  {
            $html = Blade::render('<tedi:table-of-contents heading="Sisukord" position="'.$position.'" />');

            $this->assertHasClass('table-of-contents', $html, 'table-of-contents');
            $this->assertHasClass('table-of-contents--position-'.$position, $html, 'table-of-contents');
        }
    }

    public function test_table_of_contents_modal_breakpoint_classes(): void
    {
        foreach (['mobile', 'tablet', 'desktop'] as $breakpoint) {
            $html = Blade::render('<tedi:table-of-contents heading="Sisukord" modal-breakpoint="'.$breakpoint.'" />');

            $this->assertHasClass('table-of-contents--modal-breakpoint-'.$breakpoint, $html, 'table-of-contents');
            $this->assertHasClass('table-of-contents__footer--modal-breakpoint-'.$breakpoint, $html, 'table-of-contents__footer');
        }
    }

    public function test_table_of_contents_never_breakpoint_emits_no_nav_modifier(): void
    {
        $html = Blade::render('<tedi:table-of-contents heading="Sisukord" modal-breakpoint="never" />');

        $tokens = $this->classesOf($html, 'table-of-contents');

        foreach (['mobile', 'tablet', 'desktop', 'never'] as $breakpoint) {
            $this->assertNotContains('table-of-contents--modal-breakpoint-'.$breakpoint, $tokens);
        }
    }

    public function test_table_of_contents_heading_and_aria_label(): void
    {
        $html = Blade::render('<tedi:table-of-contents heading="Sisukord" />');
        $this->assertStringContainsString('Sisukord', $html);
        $this->assertStringContainsString('aria-label="Table of contents"', $html);
        $this->assertSame(2, substr_count($html, '<h1 '));

        $custom = Blade::render('<tedi:table-of-contents heading="Sisukord" aria-label="Peatükid" heading-level="h3" />');
        $this->assertStringContainsString('aria-label="Peatükid"', $custom);
        $this->assertSame(2, substr_count($custom, '<h3 '));
        $this->assertStringNotContainsString('<h1 ', $custom);
    }

    public function test_table_of_contents_item_classes_and_target(): void
    {
        $html = Blade::render('<tedi:table-of-contents-item id-to="ptk-1">Üldsätted</tedi:table-of-contents-item>');

        $this->assertHasClass('table-of-contents__item', $html, 'table-of-contents__item');
        $this->assertMissingClass('table-of-contents__item--active', $html, 'table-of-contents__item');
        $this->assertStringContainsString('data-toc-id="ptk-1"', $html);
        $this->assertStringContainsString('href="#ptk-1"', $html);
        $this->assertHasClass('table-of-contents__item-anchor', $html, 'table-of-contents__item-anchor');
    }

    public function test_table_of_contents_item_active_class(): void
    {
        $html = Blade::render('<tedi:table-of-contents-item id-to="ptk-1" :selected="true">x</tedi:table-of-contents-item>');

        $this->assertHasClass('table-of-contents__item--active', $html, 'table-of-contents__item');
    }

    public function test_table_of_contents_item_anchor_carries_the_neutral_button_classes(): void
    {
        // Angular renders <a tedi-button variant="neutral">, so the anchor gets
        // the full ButtonComponent class list.
        $html = Blade::render('<tedi:table-of-contents-item id-to="ptk-1">x</tedi:table-of-contents-item>');

        foreach (['tedi-button', 'tedi-button--neutral', 'tedi-button--default', 'tedi-button--pl', 'tedi-button--pr'] as $class) {
            $this->assertHasClass($class, $html, 'table-of-contents__item-anchor');
        }
    }

    // -- vertical-stepper -----------------------------------------------

    public function test_vertical_stepper_renders_the_custom_element_and_a_list(): void
    {
        $html = Blade::render('<tedi:vertical-stepper>x</tedi:vertical-stepper>');

        $this->assertStringContainsString('<tedi-vertical-stepper', $html);
        $this->assertHasClass('tedi-vertical-stepper', $html, 'tedi-vertical-stepper');
        $this->assertStringContainsString('role="list"', $html);
    }

    public function test_vertical_stepper_compact_class_is_dropped(): void
    {
        // No rule matches it in the vendored SCSS (CONVENTIONS.md §4); the
        // compact appearance comes from the items' own --compact class.
        $html = Blade::render('<tedi:vertical-stepper :compact="true">x</tedi:vertical-stepper>');

        $this->assertMissingClass('tedi-vertical-stepper--compact', $html, 'tedi-vertical-stepper');
    }

    public function test_vertical_stepper_aria_label(): void
    {
        $labelled = Blade::render('<tedi:vertical-stepper aria-label="Sammud">x</tedi:vertical-stepper>');
        $this->assertStringContainsString('aria-label="Sammud"', $labelled);

        $bare = Blade::render('<tedi:vertical-stepper>x</tedi:vertical-stepper>');
        $this->assertStringNotContainsString('aria-label', $bare);
    }

    public function test_vertical_stepper_item_state_classes(): void
    {
        $states = [
            'completed' => 'tedi-vertical-stepper-item--completed',
            'error' => 'tedi-vertical-stepper-item--error',
            'selected' => 'tedi-vertical-stepper-item--selected',
            'disabled' => 'tedi-vertical-stepper-item--disabled',
            'informative' => 'tedi-vertical-stepper-item--informative',
            'sub-item' => 'tedi-vertical-stepper-item--sub-item',
        ];

        foreach ($states as $prop => $class) {
            $html = Blade::render('<tedi:vertical-stepper-item title="Samm" :'.$prop.'="true" />');

            $this->assertHasClass($class, $html, 'tedi-vertical-stepper-item');
        }

        $bare = Blade::render('<tedi:vertical-stepper-item title="Samm" />');

        foreach ($states as $class) {
            $this->assertMissingClass($class, $bare, 'tedi-vertical-stepper-item');
        }
    }

    public function test_vertical_stepper_item_has_listitem_role(): void
    {
        $html = Blade::render('<tedi:vertical-stepper-item title="Samm" />');

        $this->assertStringContainsString('role="listitem"', $html);
    }

    public function test_vertical_stepper_item_inherits_compact_and_enumerated_through_aware(): void
    {
        $html = Blade::render(
            '<tedi:vertical-stepper :compact="true" :enumerated="true">'
            .'<tedi:vertical-stepper-item title="Samm" />'
            .'</tedi:vertical-stepper>'
        );

        $this->assertHasClass('tedi-vertical-stepper-item--compact', $html, 'tedi-vertical-stepper-item');
        $this->assertHasClass('tedi-vertical-stepper-item--enumerated', $html, 'tedi-vertical-stepper-item');
    }

    public function test_vertical_stepper_item_renders_a_link_when_href_is_given(): void
    {
        $html = Blade::render('<tedi:vertical-stepper-item title="Samm" href="/samm" />');
        $this->assertStringContainsString('href="/samm"', $html);

        $disabled = Blade::render('<tedi:vertical-stepper-item title="Samm" href="/samm" :disabled="true" />');
        $this->assertStringNotContainsString('href="/samm"', $disabled);
        $this->assertStringContainsString('aria-disabled="true"', $disabled);
    }

    public function test_vertical_stepper_item_marks_the_selected_step(): void
    {
        $html = Blade::render('<tedi:vertical-stepper-item title="Samm" :selected="true" />');

        $this->assertStringContainsString('aria-current="step"', $html);
    }

    public function test_vertical_stepper_item_status_icon_only_outside_compact(): void
    {
        $regular = Blade::render('<tedi:vertical-stepper-item title="Samm" :completed="true" />');
        $this->assertHasClass('tedi-vertical-stepper-item__status-icon', $regular, 'tedi-vertical-stepper-item__status-icon');

        // A compact top-level step shows the state inside the indicator instead.
        $compact = Blade::render(
            '<tedi:vertical-stepper :compact="true">'
            .'<tedi:vertical-stepper-item title="Samm" :completed="true" />'
            .'</tedi:vertical-stepper>'
        );
        $this->assertMissingClass('tedi-vertical-stepper-item__status-icon', $compact, 'tedi-vertical-stepper-item__status-icon');
        $this->assertStringContainsString('tedi-icon--color-white', $compact);
    }

    public function test_vertical_stepper_item_with_sub_items_renders_a_toggle(): void
    {
        $html = Blade::render(
            '<tedi:vertical-stepper-item title="Samm">'
            .'<x-slot:sub-items><tedi:vertical-stepper-item title="Alam" :sub-item="true" /></x-slot:sub-items>'
            .'</tedi:vertical-stepper-item>'
        );

        $this->assertHasClass('tedi-vertical-stepper-item__toggle', $html, 'tedi-vertical-stepper-item__toggle');
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('data-tedi-contents', $html);
        $this->assertStringContainsString('x-show="opened"', $html);
        // Per CONVENTIONS.md §8 the collapsed sub-items are in the DOM already.
        $this->assertHasClass('tedi-vertical-stepper-item--sub-item', $html, 'tedi-vertical-stepper-item--sub-item');
    }

    public function test_vertical_stepper_item_without_sub_items_has_no_toggle(): void
    {
        $html = Blade::render('<tedi:vertical-stepper-item title="Samm" />');

        $this->assertStringNotContainsString('x-data', $html);
        $this->assertStringNotContainsString('aria-expanded', $html);
    }

    /**
     * The `item-title` slot must REPLACE the generated link/button, not sit
     * beside it — a silent fallthrough would still render valid markup.
     */
    public function test_vertical_stepper_item_title_slot_replaces_the_generated_control(): void
    {
        $html = Blade::render(
            '<tedi:vertical-stepper-item title="Samm">'
            .'<x-slot:item-title><a href="#samm">Samm</a></x-slot:item-title>'
            .'</tedi:vertical-stepper-item>'
        );

        $this->assertStringContainsString('href="#samm"', $html);
        $this->assertStringNotContainsString('<button', $html,
            'The item-title slot must replace the generated <button>, not render alongside it.');
    }

    public function test_vertical_stepper_item_description_slot(): void
    {
        $html = Blade::render(
            '<tedi:vertical-stepper-item title="Samm">'
            .'<x-slot:description>Tähtaeg</x-slot:description>'
            .'</tedi:vertical-stepper-item>'
        );

        $this->assertStringContainsString('Tähtaeg', $html);
        $this->assertHasClass('tedi-vertical-stepper-item__description', $html, 'tedi-vertical-stepper-item__description');
    }

    // -- multiselect ----------------------------------------------------

    /** The options used by most of the multiselect cases below. */
    private const MS_OPTIONS = "[
        ['value' => 'tln', 'label' => 'Tallinn', 'group' => 'Harju'],
        ['value' => 'kei', 'label' => 'Keila', 'group' => 'Harju', 'disabled' => true],
        ['value' => 'trt', 'label' => 'Tartu', 'group' => 'Tartu'],
    ]";

    private function multiselect(string $attributes = '', string $options = self::MS_OPTIONS): string
    {
        return Blade::render(
            '<tedi:multiselect input-id="ms" :options="'.$options.'" '.$attributes.' />'
        );
    }

    public function test_multiselect_host_classes(): void
    {
        $html = $this->multiselect();

        $this->assertHasClass('tedi-select', $html, 'tedi-select');
        $this->assertHasClass('tedi-select--multiselect', $html, 'tedi-select');
    }

    public function test_multiselect_trigger_is_a_combobox_carrying_the_input_classes(): void
    {
        $html = $this->multiselect();

        $this->assertStringContainsString('role="combobox"', $html);
        $this->assertStringContainsString('aria-haspopup="listbox"', $html);
        $this->assertStringContainsString('aria-controls="ms-listbox"', $html);
        $this->assertHasClass('tedi-select__trigger', $html, 'tedi-select__trigger');
        $this->assertHasClass('tedi-input', $html, 'tedi-select__trigger');
    }

    public function test_multiselect_state_and_size_modifiers(): void
    {
        $error = $this->multiselect('state="error"');
        $this->assertHasClass('tedi-input--error', $error, 'tedi-select__trigger');

        $valid = $this->multiselect('state="valid"');
        $this->assertHasClass('tedi-input--valid', $valid, 'tedi-select__trigger');

        $small = $this->multiselect('size="small"');
        $this->assertHasClass('tedi-input--small', $small, 'tedi-select__trigger');

        $disabled = $this->multiselect('disabled');
        $this->assertHasClass('tedi-input--disabled', $disabled, 'tedi-select__trigger');

        $default = $this->multiselect();
        $this->assertMissingClass('tedi-input--error', $default, 'tedi-select__trigger');
        $this->assertMissingClass('tedi-input--valid', $default, 'tedi-select__trigger');
        $this->assertMissingClass('tedi-input--small', $default, 'tedi-select__trigger');
        $this->assertMissingClass('tedi-input--disabled', $default, 'tedi-select__trigger');
    }

    public function test_multiselect_tag_container_is_single_row_unless_multi_row(): void
    {
        $default = $this->multiselect();
        $this->assertHasClass(
            'tedi-select__multiselect-container--single-row', $default, 'tedi-select__multiselect-container'
        );

        $multiRow = $this->multiselect('multi-row');
        $this->assertMissingClass(
            'tedi-select__multiselect-container--single-row', $multiRow, 'tedi-select__multiselect-container'
        );
    }

    /**
     * CONVENTIONS.md §8: every tag is in the static render with its real class
     * list, hidden with an inline style rather than conjured by Alpine.
     */
    public function test_multiselect_renders_one_tag_per_option_and_hides_the_unselected_ones(): void
    {
        $html = $this->multiselect(':value="[\'tln\']"');

        $this->assertSame(3, substr_count($html, 'tedi-tag__content'));
        $this->assertStringContainsString('x-show="isOptionSelected(&quot;tln&quot;)"', $html);
        // Two hidden tags plus the placeholder, which the selection hides.
        $this->assertSame(3, substr_count($html, 'style="display: none;"'));
    }

    public function test_multiselect_tags_are_closable_only_when_asked(): void
    {
        $this->assertMissingClass('tedi-tag--closable', $this->multiselect(), 'tedi-tag');
        $this->assertHasClass('tedi-tag--closable', $this->multiselect('clearable-tags'), 'tedi-tag');
    }

    public function test_multiselect_clear_button_carries_the_closing_button_classes(): void
    {
        $html = $this->multiselect();

        $this->assertHasClass('tedi-select__clear', $html, 'tedi-select__clear');
        $this->assertHasClass('tedi-closing-button', $html, 'tedi-select__clear');
        $this->assertHasClass('tedi-closing-button--small', $html, 'tedi-select__clear');

        $this->assertStringNotContainsString('tedi-select__clear', $this->multiselect(':clearable="false"'));
    }

    public function test_multiselect_clear_attributes_reach_the_clear_button(): void
    {
        $html = $this->multiselect(':clear-attributes="[\'wire:click\' => \'reset\']"');

        $this->assertStringContainsString('wire:click="reset"', $html);
    }

    public function test_multiselect_panel_is_a_listbox_in_the_dom_while_closed(): void
    {
        $html = $this->multiselect();

        $this->assertStringContainsString('id="ms-listbox"', $html);
        $this->assertStringContainsString('role="listbox"', $html);
        $this->assertStringContainsString('aria-multiselectable="true"', $html);
        $this->assertHasClass('tedi-select__options', $html, 'tedi-select__options');
        $this->assertHasClass('tedi-select__dropdown', $html, 'tedi-card');
    }

    public function test_multiselect_options_are_dropdown_item_rows_with_a_checkbox(): void
    {
        $html = $this->multiselect();

        $this->assertSame(3, substr_count($html, 'class="tedi-dropdown-item-value__checkbox"'));
        $this->assertStringContainsString('<li', $html);
        $this->assertStringContainsString('tedi-dropdown-item', $html);
        $this->assertHasClass('tedi-dropdown-item-value--checkbox', $html, 'tedi-dropdown-item-value');
    }

    public function test_multiselect_disabled_option_is_marked_on_the_row(): void
    {
        $html = $this->multiselect();

        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    /** Row ids are what aria-activedescendant points at, so they must be stable. */
    public function test_multiselect_row_ids_number_the_listbox_rows_only(): void
    {
        // Unselectable group headings are role="presentation" and get no id,
        // exactly as upstream leaves them out of cdkListbox.
        $plain = $this->multiselect();
        $this->assertStringContainsString('id="ms-row-0"', $plain);
        $this->assertStringContainsString('role="presentation"', $plain);

        // With selectable groups every heading becomes a row and shifts the rest.
        $selectable = $this->multiselect('selectable-groups');
        $this->assertStringContainsString('id="ms-row-3"', $selectable);
    }

    public function test_multiselect_select_all_row_only_with_the_prop(): void
    {
        $this->assertStringNotContainsString('Select all', $this->multiselect());
        $this->assertStringContainsString('Select all', $this->multiselect('select-all'));

        // No options, no select-all row — upstream guards on options().length.
        $this->assertStringNotContainsString('Select all', $this->multiselect('select-all', '[]'));
    }

    public function test_multiselect_group_headings_become_selectable_rows(): void
    {
        $plain = $this->multiselect();
        $this->assertHasClass('tedi-select__group-name', $plain, 'tedi-select__group-name');
        $this->assertMissingClass('tedi-select__group-name--selectable', $plain, 'tedi-select__group-name');

        $selectable = $this->multiselect('selectable-groups');
        $this->assertHasClass('tedi-select__group-name--selectable', $selectable, 'tedi-select__group-name');
    }

    /** isGroupSelected() ignores disabled members — Keila is disabled. */
    public function test_multiselect_group_counts_as_selected_from_its_enabled_options_only(): void
    {
        $html = $this->multiselect('selectable-groups :value="[\'tln\']"');

        $this->assertMatchesRegularExpression(
            '/tedi-select__group-name--selectable"\s+role="option"\s+aria-selected="true"/', $html
        );
    }

    public function test_multiselect_empty_options_render_the_no_options_row(): void
    {
        $html = $this->multiselect('', '[]');

        $this->assertHasClass('tedi-select__no-options', $html, 'tedi-select__no-options');
        $this->assertStringContainsString('No options', $html);
    }

    public function test_multiselect_binds_through_x_modelable(): void
    {
        $html = $this->multiselect();

        $this->assertStringContainsString('x-modelable="model"', $html);
        $this->assertStringContainsString('tediMultiselect(', $html);
    }

    /**
     * Dropped classes, per CONVENTIONS.md §4 — see multiselect.blade.php's
     * header for each one's rationale.
     */
    public function test_multiselect_drops_the_unstyled_upstream_classes(): void
    {
        $html = $this->multiselect('select-all selectable-groups');

        $this->assertStringNotContainsString('tedi-select__multiselect-checkbox', $html);
        $this->assertStringNotContainsString('tedi-select__group-checkbox', $html);
        $this->assertStringNotContainsString('tedi-card--spacing-none', $html);
    }
}
