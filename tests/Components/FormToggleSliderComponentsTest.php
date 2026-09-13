<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class FormToggleSliderComponentsTest extends TestCase
{
    // -- toggle -----------------------------------------------------------

    public function test_toggle_root_is_a_div_with_the_host_classes(): void
    {
        $html = Blade::render('<tedi:toggle />');

        // tedi-toggle has no element rule in the vendored SCSS, so the root is
        // a plain <div> per the batch ruling, not a <tedi-toggle> element.
        $this->assertStringContainsString('<div class="tedi-toggle', $html);
        $this->assertStringNotContainsString('<tedi-toggle', $html);
    }

    public function test_toggle_variant_type_product_classes(): void
    {
        foreach (['primary', 'colored'] as $variant) {
            foreach (['filled', 'outlined'] as $type) {
                $html = Blade::render(
                    '<tedi:toggle variant="'.$variant.'" type="'.$type.'" />'
                );

                $this->assertHasClass('tedi-toggle', $html, 'tedi-toggle');
                $this->assertHasClass(
                    'tedi-toggle--'.$variant.'-'.$type, $html, 'tedi-toggle'
                );
            }
        }
    }

    public function test_toggle_size_classes(): void
    {
        foreach (['default', 'large'] as $size) {
            $html = Blade::render('<tedi:toggle size="'.$size.'" />');

            $this->assertHasClass('tedi-toggle--size-'.$size, $html, 'tedi-toggle');
        }

        $html = Blade::render('<tedi:toggle size="large" />');
        $this->assertMissingClass('tedi-toggle--size-default', $html, 'tedi-toggle');
    }

    public function test_toggle_renders_the_native_switch_input(): void
    {
        $html = Blade::render('<tedi:toggle />');

        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('role="switch"', $html);
        $this->assertHasClass('tedi-toggle__input', $html, 'tedi-toggle__input');
        $this->assertStringContainsString('class="tedi-toggle__slider"', $html);
    }

    public function test_toggle_generates_an_input_id_when_omitted(): void
    {
        $html = Blade::render('<tedi:toggle />');
        $this->assertMatchesRegularExpression('/id="tedi-toggle-[0-9a-f]+"/', $html);

        $html = Blade::render('<tedi:toggle input-id="my-toggle" />');
        $this->assertStringContainsString('id="my-toggle"', $html);
    }

    public function test_toggle_checked_disabled_required(): void
    {
        // Bare-word attributes, matched with word boundaries: "aria-checked"
        // is always present, so a substring check would be meaningless here.
        $html = Blade::render('<tedi:toggle />');
        $this->assertDoesNotMatchRegularExpression('/\s(checked|disabled|required)[\s>]/', $html);

        $html = Blade::render('<tedi:toggle :checked="true" :disabled="true" :required="true" />');
        $this->assertMatchesRegularExpression('/\schecked[\s>]/', $html);
        $this->assertMatchesRegularExpression('/\sdisabled[\s>]/', $html);
        $this->assertMatchesRegularExpression('/\srequired[\s>]/', $html);
    }

    public function test_toggle_aria_checked_is_always_emitted(): void
    {
        // Angular binds [attr.aria-checked]="checked()", so "false" is a real
        // value, not an omission.
        $this->assertStringContainsString(
            'aria-checked="false"', Blade::render('<tedi:toggle />')
        );
        $this->assertStringContainsString(
            'aria-checked="true"', Blade::render('<tedi:toggle :checked="true" />')
        );
    }

    public function test_toggle_aria_label_is_conditional(): void
    {
        $html = Blade::render('<tedi:toggle />');
        $this->assertStringNotContainsString('aria-label', $html);

        $html = Blade::render('<tedi:toggle aria-label="Teavitused" />');
        $this->assertStringContainsString('aria-label="Teavitused"', $html);
    }

    public function test_toggle_icon_renders_only_for_the_large_size(): void
    {
        $html = Blade::render('<tedi:toggle :icon="true" size="default" />');
        $this->assertStringNotContainsString('tedi-toggle__icon', $html);

        $html = Blade::render('<tedi:toggle :icon="false" size="large" />');
        $this->assertStringNotContainsString('tedi-toggle__icon', $html);

        $html = Blade::render('<tedi:toggle :icon="true" size="large" />');
        $this->assertHasClass('tedi-toggle__icon', $html, 'tedi-toggle__icon');
    }

    public function test_toggle_icon_name_follows_checked(): void
    {
        $html = Blade::render('<tedi:toggle :icon="true" size="large" />');
        $this->assertStringContainsString('>lock</tedi-icon>', $html);

        $html = Blade::render('<tedi:toggle :icon="true" size="large" :checked="true" />');
        $this->assertStringContainsString('>lock_open_right</tedi-icon>', $html);
    }

    public function test_toggle_icon_color_matrix(): void
    {
        // iconColor() from toggle.component.ts: outlined is always white;
        // filled depends on variant and checked.
        $cases = [
            ['primary', 'outlined', false, 'white'],
            ['primary', 'outlined', true, 'white'],
            ['colored', 'outlined', false, 'white'],
            ['colored', 'outlined', true, 'white'],
            ['primary', 'filled', false, 'tertiary'],
            ['primary', 'filled', true, 'brand'],
            ['colored', 'filled', false, 'danger'],
            ['colored', 'filled', true, 'success'],
        ];

        foreach ($cases as [$variant, $type, $checked, $color]) {
            $html = Blade::render(sprintf(
                '<tedi:toggle size="large" :icon="true" variant="%s" type="%s" :checked="%s" />',
                $variant, $type, $checked ? 'true' : 'false'
            ));

            $this->assertHasClass('tedi-icon--color-'.$color, $html, 'tedi-toggle__icon');
        }
    }

    public function test_toggle_forwards_wire_model_to_the_input(): void
    {
        $html = Blade::render('<tedi:toggle wire:model="notifications" />');

        // $attributes lands on the control, not the wrapper, so wire:model
        // binds the real checkbox.
        $this->assertMatchesRegularExpression(
            '/<input[^>]*wire:model="notifications"/s', $html
        );
        $this->assertStringContainsString(
            '<div class="tedi-toggle tedi-toggle--primary-filled', $html
        );
    }

    // -- slider -----------------------------------------------------------

    public function test_slider_root_is_a_div_with_the_host_class(): void
    {
        $html = Blade::render('<tedi:slider />');

        $this->assertStringContainsString('<div class="tedi-slider"', $html);
        $this->assertStringNotContainsString('<tedi-slider', $html);
    }

    public function test_slider_disabled_class(): void
    {
        $html = Blade::render('<tedi:slider />');
        $this->assertMissingClass('tedi-slider--disabled', $html, 'tedi-slider');

        $html = Blade::render('<tedi:slider :disabled="true" />');
        $this->assertHasClass('tedi-slider--disabled', $html, 'tedi-slider');
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_slider_invalid_class_is_dropped_but_aria_invalid_is_not(): void
    {
        // tedi-slider--invalid has no rule in dist/tedi.css, so the class is
        // dropped per the guardrail; isInvalid() still drives aria-invalid.
        $html = Blade::render('<tedi:slider :invalid="true" />');

        $this->assertMissingClass('tedi-slider--invalid', $html, 'tedi-slider');
        $this->assertStringContainsString('aria-invalid="true"', $html);

        $html = Blade::render('<tedi:slider />');
        $this->assertStringNotContainsString('aria-invalid', $html);
    }

    public function test_slider_feedback_text_of_type_error_marks_the_input_invalid(): void
    {
        $html = Blade::render(
            '<tedi:slider :feedback-text="[\'text\' => \'Viga\', \'type\' => \'error\']" />'
        );

        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertMissingClass('tedi-slider--invalid', $html, 'tedi-slider');

        $html = Blade::render(
            '<tedi:slider :feedback-text="[\'text\' => \'Vihje\', \'type\' => \'hint\']" />'
        );
        $this->assertStringNotContainsString('aria-invalid', $html);
    }

    public function test_slider_dragging_class_is_never_emitted(): void
    {
        // Pure client-side pointer state; no server-side equivalent.
        $html = Blade::render('<tedi:slider :value="50" />');

        $this->assertMissingClass('tedi-slider--dragging', $html, 'tedi-slider');
    }

    public function test_slider_thumb_tooltip_is_not_rendered(): void
    {
        $html = Blade::render('<tedi:slider :value="50" />');

        $this->assertStringNotContainsString('tedi-tooltip', $html);
        $this->assertStringNotContainsString('tedi-slider__thumb-anchor', $html);
    }

    public function test_slider_structural_classes(): void
    {
        $html = Blade::render('<tedi:slider min-label="0%" max-label="100%" />');

        foreach ([
            'tedi-slider__container',
            'tedi-slider__track-row',
            'tedi-slider__track',
            'tedi-slider__input',
            'tedi-slider__range-label',
            'tedi-slider__addon',
        ] as $class) {
            $this->assertStringContainsString('"'.$class.'"', $html);
        }
    }

    public function test_slider_progress_custom_properties(): void
    {
        $html = Blade::render('<tedi:slider :min="0" :max="100" :value="25" />');
        $this->assertStringContainsString('--tedi-slider-progress: 25%', $html);
        $this->assertStringContainsString('--tedi-slider-progress-ratio: 0.25', $html);

        $html = Blade::render('<tedi:slider :min="10" :max="20" :value="15" />');
        $this->assertStringContainsString('--tedi-slider-progress: 50%', $html);
        $this->assertStringContainsString('--tedi-slider-progress-ratio: 0.5', $html);
    }

    public function test_slider_progress_guards_min_equal_to_max(): void
    {
        $html = Blade::render('<tedi:slider :min="5" :max="5" :value="5" />');

        $this->assertStringContainsString('--tedi-slider-progress: 0%', $html);
        $this->assertStringContainsString('--tedi-slider-progress-ratio: 0', $html);
    }

    public function test_slider_clamps_the_value_into_range(): void
    {
        $html = Blade::render('<tedi:slider :min="0" :max="100" :value="150" />');
        $this->assertStringContainsString('value="100"', $html);

        $html = Blade::render('<tedi:slider :min="10" :max="100" :value="-5" />');
        $this->assertStringContainsString('value="10"', $html);

        // Angular's writeValue() maps null/NaN to min().
        $html = Blade::render('<tedi:slider :min="7" :max="100" :value="null" />');
        $this->assertStringContainsString('value="7"', $html);
    }

    public function test_slider_emits_range_attributes(): void
    {
        $html = Blade::render('<tedi:slider :min="1" :max="10" :step="2" :value="3" name="rating" />');

        $this->assertStringContainsString('type="range"', $html);
        $this->assertStringContainsString('min="1"', $html);
        $this->assertStringContainsString('max="10"', $html);
        $this->assertStringContainsString('step="2"', $html);
        $this->assertStringContainsString('value="3"', $html);
        $this->assertStringContainsString('name="rating"', $html);

        // name is omitted entirely when not given, like Angular's [attr.name].
        $this->assertStringNotContainsString('name=', Blade::render('<tedi:slider />'));
    }

    public function test_slider_generates_an_input_id_when_omitted(): void
    {
        $html = Blade::render('<tedi:slider />');
        $this->assertMatchesRegularExpression('/id="tedi-slider-[0-9a-f]+"/', $html);

        $html = Blade::render('<tedi:slider input-id="volume" label="Heli" />');
        $this->assertStringContainsString('id="volume"', $html);
        $this->assertStringContainsString('for="volume"', $html);
    }

    public function test_slider_hide_label_union(): void
    {
        $html = Blade::render('<tedi:slider label="Väärtus" />');
        $this->assertHasClass('tedi-label', $html, 'tedi-label');
        $this->assertMissingClass('sr-only', $html, 'tedi-label');
        $this->assertMissingClass('tedi-label--reserve-space', $html, 'tedi-label');

        $html = Blade::render('<tedi:slider label="Väärtus" :hide-label="true" />');
        $this->assertHasClass('sr-only', $html, 'tedi-label');
        $this->assertMissingClass('tedi-label--reserve-space', $html, 'tedi-label');

        $html = Blade::render('<tedi:slider label="Väärtus" hide-label="keep-space" />');
        $this->assertHasClass('tedi-label--reserve-space', $html, 'tedi-label');
        $this->assertMissingClass('sr-only', $html, 'tedi-label');
    }

    public function test_slider_label_is_omitted_when_no_label_is_given(): void
    {
        $html = Blade::render('<tedi:slider aria-label="Väärtus" />');

        $this->assertStringNotContainsString('<label', $html);
        $this->assertStringContainsString('aria-label="Väärtus"', $html);
    }

    public function test_slider_range_labels(): void
    {
        $html = Blade::render('<tedi:slider />');
        $this->assertStringNotContainsString('tedi-slider__range-label', $html);

        $html = Blade::render('<tedi:slider min-label="0%" max-label="100%" />');
        $this->assertStringContainsString('>0%</span>', $html);
        $this->assertStringContainsString('>100%</span>', $html);
    }

    public function test_slider_show_current_value_replaces_the_max_label(): void
    {
        $html = Blade::render('<tedi:slider :value="42" max-label="100%" :show-current-value="true" />');

        $this->assertStringContainsString('>42</span>', $html);
        $this->assertStringNotContainsString('>100%</span>', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);

        // Without it the right label is maxLabel and is hidden from AT.
        $html = Blade::render('<tedi:slider :value="42" max-label="100%" />');
        $this->assertStringContainsString('>100%</span>', $html);
        $this->assertStringNotContainsString('aria-live="polite"', $html);
    }

    public function test_slider_value_formatter_is_applied_to_the_current_value(): void
    {
        $html = Blade::render(
            '<tedi:slider :value="42" :show-current-value="true" :value-formatter="$fmt" />',
            ['fmt' => fn ($value) => $value.'%']
        );

        $this->assertStringContainsString('>42%</span>', $html);
    }

    public function test_slider_feedback_text_is_wired_to_the_input(): void
    {
        $html = Blade::render(
            '<tedi:slider input-id="s" :feedback-text="[\'text\' => \'Vihje\']" />'
        );

        $this->assertStringContainsString('aria-describedby="s-feedback"', $html);
        $this->assertStringContainsString('id="s-feedback"', $html);
        $this->assertStringContainsString('Vihje', $html);
        $this->assertHasClass('tedi-feedback-text--hint', $html, 'tedi-feedback-text');

        // No feedbackText → no describedby at all.
        $this->assertStringNotContainsString(
            'aria-describedby', Blade::render('<tedi:slider />')
        );
    }

    public function test_slider_aria_attributes(): void
    {
        $html = Blade::render(
            '<tedi:slider aria-label="A" aria-labelledby="B" aria-valuetext="C" />'
        );

        $this->assertStringContainsString('aria-label="A"', $html);
        $this->assertStringContainsString('aria-labelledby="B"', $html);
        $this->assertStringContainsString('aria-valuetext="C"', $html);

        $bare = Blade::render('<tedi:slider />');
        $this->assertStringNotContainsString('aria-labelledby', $bare);
        $this->assertStringNotContainsString('aria-valuetext', $bare);
    }

    public function test_slider_addon_slot(): void
    {
        $html = Blade::render('<tedi:slider />');
        $this->assertStringContainsString('<div class="tedi-slider__addon"></div>', $html);

        $html = Blade::render(
            '<tedi:slider><x-slot:addon><b>addon</b></x-slot:addon></tedi:slider>'
        );
        $this->assertStringContainsString(
            '<div class="tedi-slider__addon"><b>addon</b></div>', $html
        );
    }

    public function test_slider_forwards_wire_model_to_the_range_input(): void
    {
        $html = Blade::render('<tedi:slider wire:model="volume" />');

        $this->assertMatchesRegularExpression(
            '/<input[^>]*wire:model="volume"/s', $html
        );
        $this->assertStringContainsString('<div class="tedi-slider"', $html);
    }
}
