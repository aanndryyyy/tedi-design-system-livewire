<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Parity tests for form/rating (Angular 10), against rating.component.ts's
 * classes(), host bindings and items(), and rating.component.spec.ts.
 */
class RatingComponentsTest extends TestCase
{
    /** RatingType, RatingOrientation and RatingReadOnlyVariant from rating.component.ts. */
    private const TYPES = ['star', 'number', 'icon'];

    private const ORIENTATIONS = ['horizontal', 'vertical'];

    private const READ_ONLY_VARIANTS = ['summary', 'scale'];

    private function render(string $props = ''): string
    {
        return Blade::render('<tedi:rating label="Feedback" '.$props.' />');
    }

    /** The class tokens of every element carrying $class, in document order. @return string[][] */
    private function classListsOf(string $html, string $class): array
    {
        // Same lookbehind as TestCase::classesOf(): skip x-bind:class values.
        preg_match_all('/(?<![-:.\w])class="([^"]*)"/', $html, $matches);

        $lists = [];
        foreach ($matches[1] as $attr) {
            $tokens = preg_split('/\s+/', trim($attr), -1, PREG_SPLIT_NO_EMPTY);
            if (in_array($class, $tokens, true)) {
                $lists[] = $tokens;
            }
        }

        return $lists;
    }

    /** @return bool[] whether each `.tedi-rating__visual` is selected */
    private function selectedVisuals(string $html): array
    {
        return array_map(
            fn (array $tokens) => in_array('tedi-rating__visual--selected', $tokens, true),
            $this->classListsOf($html, 'tedi-rating__visual'),
        );
    }

    /** @return array<string, string> the root element's attributes */
    private function rootAttributes(string $html): array
    {
        $this->assertMatchesRegularExpression('/<tedi-rating\b[^>]*>/s', $html);
        preg_match('/<tedi-rating\b([^>]*)>/s', $html, $root);
        preg_match_all('/([\w:.-]+)="([^"]*)"/', $root[1], $pairs, PREG_SET_ORDER);

        $attributes = [];
        foreach ($pairs as [, $name, $value]) {
            $attributes[$name] = $value;
        }

        return $attributes;
    }

    /** @return array<int, string> aria-label of each radio, in order */
    private function radioLabels(string $html): array
    {
        preg_match_all('/<input\b[^>]*type="radio"[^>]*>/s', $html, $inputs);

        return array_map(function (string $input) {
            preg_match('/aria-label="([^"]*)"/', $input, $label);

            return html_entity_decode($label[1] ?? '');
        }, $inputs[0]);
    }

    // -- root / host ------------------------------------------------------

    public function test_root_is_the_tedi_rating_element(): void
    {
        $html = $this->render();

        $this->assertStringStartsWith('<tedi-rating', trim($html));
        $this->assertStringEndsWith('</tedi-rating>', trim($html));
    }

    public function test_label_is_required(): void
    {
        $this->expectException(\Throwable::class);

        Blade::render('<tedi:rating />');
    }

    public function test_type_classes_across_every_prop_union(): void
    {
        foreach (self::TYPES as $type) {
            foreach (self::ORIENTATIONS as $orientation) {
                foreach ([false, true] as $disabled) {
                    foreach ([false, true] as $readOnly) {
                        $html = $this->render(sprintf(
                            'type="%s" orientation="%s" :disabled="%s" :read-only="%s"',
                            $type, $orientation, var_export($disabled, true), var_export($readOnly, true),
                        ));

                        // classes() from rating.component.ts.
                        $expected = ['tedi-rating', 'tedi-rating--'.$type];
                        if ($readOnly) {
                            $expected[] = 'tedi-rating--readonly';
                        } else {
                            if ($type !== 'star' && $orientation === 'vertical') {
                                $expected[] = 'tedi-rating--vertical';
                            }
                            if ($disabled) {
                                $expected[] = 'tedi-rating--disabled';
                            }
                        }

                        $this->assertSame($expected, $this->classesOf($html, 'tedi-rating'),
                            "type=$type orientation=$orientation disabled=".(int) $disabled.' readOnly='.(int) $readOnly);
                    }
                }
            }
        }
    }

    public function test_type_classes_are_not_mistaken_for_one_another(): void
    {
        $html = $this->render('type="icon"');

        $this->assertHasClass('tedi-rating--icon', $html, 'tedi-rating');
        $this->assertMissingClass('tedi-rating--star', $html, 'tedi-rating');
        $this->assertMissingClass('tedi-rating--number', $html, 'tedi-rating');
    }

    public function test_consumer_class_merges_onto_the_root(): void
    {
        $html = $this->render('class="mine" id="r1"');

        $this->assertSame(['tedi-rating', 'tedi-rating--star', 'mine'], $this->classesOf($html, 'tedi-rating'));
        $this->assertSame('r1', $this->rootAttributes($html)['id']);
    }

    public function test_interactive_root_is_a_labelled_radiogroup(): void
    {
        $attributes = $this->rootAttributes($this->render());

        $this->assertSame('radiogroup', $attributes['role']);
        $this->assertSame('Feedback', $attributes['aria-label']);
        $this->assertStringContainsString('tediRating(', $attributes['x-data']);
    }

    public function test_consumer_aria_label_wins_over_the_computed_one(): void
    {
        $html = $this->render('aria-label="Own"');

        $this->assertSame('Own', $this->rootAttributes($html)['aria-label']);
        $this->assertSame(1, substr_count($html, ' aria-label="Own"'));
    }

    public function test_mobile_columns_style_is_set_for_the_number_type_only(): void
    {
        // mobileColumns(): ceil(total / 2) above 6, else the total.
        foreach (['10' => '5', '7' => '4', '6' => '6', '3' => '3'] as $count => $columns) {
            $attributes = $this->rootAttributes($this->render('type="number" :count="'.$count.'"'));
            $this->assertSame('--tedi-rating-columns: '.$columns.';', $attributes['style'], "count=$count");
        }

        // Still emitted in readOnly, as the host binding has no readOnly guard.
        $attributes = $this->rootAttributes($this->render('type="number" :read-only="true"'));
        $this->assertSame('--tedi-rating-columns: 5;', $attributes['style']);

        foreach (['star', 'icon'] as $type) {
            $this->assertArrayNotHasKey('style', $this->rootAttributes($this->render('type="'.$type.'"')));
        }
    }

    // -- items --------------------------------------------------------------

    public function test_item_count_defaults_per_type_and_falls_back_on_invalid_counts(): void
    {
        $this->assertCount(5, $this->radioLabels($this->render()));
        $this->assertCount(5, $this->radioLabels($this->render('type="icon"')));
        $this->assertCount(10, $this->radioLabels($this->render('type="number"')));
        $this->assertCount(7, $this->radioLabels($this->render(':count="7"')));
        $this->assertCount(7, $this->radioLabels($this->render('count="7"')));

        foreach ([':count="0"', ':count="-2"', ':count="2.5"', 'count="abc"'] as $count) {
            $this->assertCount(5, $this->radioLabels($this->render($count)), $count);
        }
    }

    public function test_radios_share_a_name_and_carry_their_position(): void
    {
        $html = $this->render('name="score"');

        $this->assertSame(5, substr_count($html, 'name="score"'));
        foreach (range(1, 5) as $position) {
            $this->assertStringContainsString('value="'.$position.'"', $html);
        }

        // Generated when omitted, and shared by every radio.
        $html = $this->render();
        preg_match_all('/name="(tedi-rating-[0-9a-f]+)"/', $html, $names);
        $this->assertCount(5, $names[1]);
        $this->assertCount(1, array_unique($names[1]));
    }

    public function test_the_radio_matching_the_value_is_checked(): void
    {
        $html = $this->render(':value="3"');

        preg_match_all('/<input\b[^>]*>/s', $html, $inputs);
        $checked = array_map(fn ($input) => (bool) preg_match('/\schecked[\s>\/]/', $input), $inputs[0]);
        $this->assertSame([false, false, true, false, false], $checked);

        // A fractional value checks no radio.
        $this->assertDoesNotMatchRegularExpression('/\schecked[\s>\/]/', $this->render(':value="3.5"'));
    }

    public function test_selected_classes_are_cumulative_for_star_and_number_and_single_for_icon(): void
    {
        $this->assertSame([true, true, true, false, false], $this->selectedVisuals($this->render(':value="3"')));
        $this->assertSame(
            [true, true, true, false, false, false, false, false, false, false],
            $this->selectedVisuals($this->render('type="number" :value="3"')),
        );
        $this->assertSame([false, false, true, false, false], $this->selectedVisuals($this->render('type="icon" :value="3"')));
        $this->assertSame([false, false, false, false, false], $this->selectedVisuals($this->render()));

        // Nothing is previewed on the server.
        $html = $this->render(':value="3"');
        foreach ($this->classListsOf($html, 'tedi-rating__visual') as $tokens) {
            $this->assertNotContains('tedi-rating__visual--hover', $tokens);
        }
    }

    public function test_star_glyph_is_filled_only_when_selected(): void
    {
        $html = $this->render(':value="2"');

        $stars = array_map(
            fn (array $tokens) => in_array('tedi-icon--filled', $tokens, true),
            $this->classListsOf($html, 'tedi-rating__star'),
        );
        $this->assertSame([true, true, false, false, false], $stars);

        // The backdrop is always the filled glyph.
        foreach ($this->classListsOf($html, 'tedi-rating__star-backdrop') as $tokens) {
            $this->assertContains('tedi-icon--filled', $tokens);
        }

        $this->assertSame(10, substr_count($html, '>kid_star</tedi-icon>'));
    }

    public function test_icons_swap_the_star_glyph_and_the_icon_scale(): void
    {
        $html = $this->render('icons="favorite"');
        $this->assertSame(10, substr_count($html, '>favorite</tedi-icon>'));

        $html = $this->render('type="icon"');
        foreach (['sentiment_sad', 'sentiment_dissatisfied', 'sentiment_neutral', 'sentiment_satisfied', 'mood'] as $icon) {
            $this->assertStringContainsString('>'.$icon.'</tedi-icon>', $html);
        }

        $html = $this->render('type="icon" :icons="[\'a1\', \'a2\', \'a3\']" :count="3"');
        $this->assertStringContainsString('>a3</tedi-icon>', $html);

        // No default icons for a count other than 5: the circle glyph.
        $html = $this->render('type="icon" :count="3"');
        $this->assertSame(3, substr_count($html, '>circle</tedi-icon>'));

        // The number scale shows the digit, with no icon.
        $html = $this->render('type="number" :count="3"');
        $this->assertStringNotContainsString('<tedi-icon', $html);
        $this->assertCount(3, $this->classListsOf($html, 'tedi-rating__circle'));
    }

    public function test_non_array_item_labels_and_icons_are_ignored(): void
    {
        $html = $this->render('item-labels="nope" :icons="42"');

        $this->assertCount(5, $this->radioLabels($html));
        $this->assertSame('1/5', $this->radioLabels($html)[0]);
        $this->assertStringContainsString('>kid_star</tedi-icon>', $html);
    }

    public function test_accessible_names_use_labels_and_fall_back_to_value_over_total(): void
    {
        $labels = "['Low', '', '', '', '', '', '', '', '', 'High']";

        $this->assertSame(
            ['1, Low', '2/10', '3/10', '4/10', '5/10', '6/10', '7/10', '8/10', '9/10', '10, High'],
            $this->radioLabels($this->render('type="number" :item-labels="'.$labels.'"')),
        );

        $this->assertSame(
            ['Bad', 'Poor', 'OK', 'Good', 'Great'],
            $this->radioLabels($this->render('type="icon" :item-labels="[\'Bad\', \'Poor\', \'OK\', \'Good\', \'Great\']"')),
        );

        $this->assertSame(['1/5', '2/5', '3/5', '4/5', '5/5'], $this->radioLabels($this->render()));
    }

    public function test_captions_show_at_the_number_endpoints_and_on_every_icon(): void
    {
        $labels = "['Low', '', '', '', '', '', '', '', '', 'High']";
        $html = $this->render('type="number" :item-labels="'.$labels.'"');

        preg_match_all('/<span class="tedi-rating__caption">([^<]*)<\/span>/', $html, $captions);
        $this->assertSame(['Low', 'High'], $captions[1]);
        $this->assertHasClass('tedi-rating__items--captioned', $html, 'tedi-rating__items');

        $html = $this->render('type="icon" :item-labels="[\'A\', \'B\', \'C\', \'D\', \'E\']"');
        preg_match_all('/<span class="tedi-rating__caption">([^<]*)<\/span>/', $html, $captions);
        $this->assertSame(['A', 'B', 'C', 'D', 'E'], $captions[1]);

        // The star scale never captions its items.
        $html = $this->render(':item-labels="[\'A\', \'B\', \'C\', \'D\', \'E\']"');
        $this->assertCount(0, $this->classListsOf($html, 'tedi-rating__caption'));
        $this->assertMissingClass('tedi-rating__items--captioned', $html, 'tedi-rating__items');
    }

    public function test_no_caption_space_without_labels(): void
    {
        $html = $this->render('type="number"');

        $this->assertSame(['tedi-rating__items'], $this->classesOf($html, 'tedi-rating__items'));
    }

    public function test_star_caption_tracks_the_value_and_keeps_its_slot_when_empty(): void
    {
        $labels = ':item-labels="[\'A\', \'B\', \'C\', \'D\', \'E\']"';

        $caption = function (string $html): ?string {
            return preg_match('/<div class="tedi-rating__star-caption"[^>]*>([^<]*)<\/div>/', $html, $m) ? $m[1] : null;
        };

        $this->assertSame('', $caption($this->render($labels)));
        $this->assertSame('B', $caption($this->render($labels.' :value="2"')));
        $this->assertSame('D', $caption($this->render($labels.' :value="4"')));

        // No labels: no caption element at all.
        $this->assertNull($caption($this->render(':value="2"')));
        // Number / icon scales never render it.
        $this->assertNull($caption($this->render('type="icon" '.$labels)));
    }

    public function test_disabled_disables_every_radio(): void
    {
        $html = $this->render(':disabled="true"');

        preg_match_all('/<input\b[^>]*>/s', $html, $inputs);
        $this->assertCount(5, $inputs[0]);
        foreach ($inputs[0] as $input) {
            $this->assertMatchesRegularExpression('/\sdisabled[\s>\/]/', $input);
        }

        $this->assertDoesNotMatchRegularExpression('/\sdisabled[\s>\/]/', $this->render());
    }

    // -- value binding ----------------------------------------------------

    public function test_wire_model_lands_on_every_radio_and_not_on_the_root(): void
    {
        $html = $this->render('wire:model.live="score" :value="2"');

        $this->assertSame(5, substr_count($html, 'wire:model.live="score"'));
        $this->assertArrayNotHasKey('wire:model.live', $this->rootAttributes($html));
        preg_match_all('/<input\b[^>]*>/s', $html, $inputs);
        foreach ($inputs[0] as $input) {
            $this->assertStringContainsString('wire:model.live="score"', $input);
        }
    }

    public function test_x_model_lands_on_every_radio(): void
    {
        $html = $this->render('x-model="value"');

        $this->assertSame(5, substr_count($html, 'x-model="value"'));
        $this->assertArrayNotHasKey('x-model', $this->rootAttributes($html));
    }

    // -- readOnly ---------------------------------------------------------

    public function test_read_only_renders_a_summary_image_with_the_raters_count(): void
    {
        app()->setLocale('et');

        $html = $this->render(':read-only="true" :value="3.5" :ratings-count="271"');
        $attributes = $this->rootAttributes($html);

        $this->assertSame('img', $attributes['role']);
        $this->assertSame('Feedback: 3,5/5 - 271 hindajat', $attributes['aria-label']);
        $this->assertStringContainsString('<span class="tedi-rating__summary-text">3,5/5 - 271 hindajat</span>', $html);
        $this->assertStringNotContainsString('<input', $html);
        $this->assertArrayNotHasKey('x-data', $attributes);
        $this->assertHasClass('tedi-rating__summary', $html, 'tedi-rating__summary');
        $this->assertStringContainsString('<div class="tedi-rating__summary" aria-hidden="true">', $html);
    }

    public function test_read_only_raters_count_pluralises(): void
    {
        app()->setLocale('et');
        $this->assertSame('Feedback: 4/5 - 1 hindaja',
            $this->rootAttributes($this->render(':read-only="true" :value="4" :ratings-count="1"'))['aria-label']);

        app()->setLocale('en');
        $this->assertSame('Feedback: 4/5 - 1 rating',
            $this->rootAttributes($this->render(':read-only="true" :value="4" :ratings-count="1"'))['aria-label']);
        $this->assertSame('Feedback: 3.5/5 - 271 ratings',
            $this->rootAttributes($this->render(':read-only="true" :value="3.5" :ratings-count="271"'))['aria-label']);
    }

    public function test_read_only_omits_the_count_when_unset_and_shows_the_number_only_on_request(): void
    {
        app()->setLocale('et');

        $this->assertSame('Feedback: 4/5',
            $this->rootAttributes($this->render(':read-only="true" :value="4"'))['aria-label']);
        $this->assertSame('Feedback: 3,5/5 - 271',
            $this->rootAttributes($this->render(':read-only="true" :value="3.5" :ratings-count="271" :show-ratings-count-label="false"'))['aria-label']);
    }

    public function test_read_only_value_keeps_at_most_one_fraction_digit(): void
    {
        app()->setLocale('en');

        // Intl.NumberFormat rounds half away from zero (halfExpand).
        $this->assertSame('Feedback: 4.3/5',
            $this->rootAttributes($this->render(':read-only="true" :value="4.25"'))['aria-label']);
        $this->assertSame('Feedback: 4.8/10',
            $this->rootAttributes($this->render('type="number" :read-only="true" :value="4.75"'))['aria-label']);
    }

    public function test_read_only_visual_per_type_and_variant(): void
    {
        foreach (self::TYPES as $type) {
            foreach (self::READ_ONLY_VARIANTS as $variant) {
                $html = $this->render('type="'.$type.'" :read-only="true" read-only-variant="'.$variant.'" :value="3.5"');

                $scale = $type === 'star' && $variant === 'scale';
                $this->assertCount($scale ? 1 : 0, $this->classListsOf($html, 'tedi-rating__stars'), "$type/$variant");
                // One filled star for the summary; one per partial fill (3.5 → 4) for the scale.
                $this->assertCount($scale ? 4 : ($type === 'star' ? 1 : 0),
                    array_filter($this->classListsOf($html, 'tedi-rating__star'), fn ($t) => in_array('tedi-icon--filled', $t, true)),
                    "$type/$variant");
                $this->assertCount($type === 'icon' ? 1 : 0, $this->classListsOf($html, 'tedi-rating__circle--filled'), "$type/$variant");
                $this->assertCount(1, $this->classListsOf($html, 'tedi-rating__summary-text'));
            }
        }
    }

    public function test_read_only_icon_follows_the_rounded_value(): void
    {
        $html = $this->render('type="icon" :read-only="true" :value="3.5"');
        $this->assertStringContainsString('>sentiment_satisfied</tedi-icon>', $html);

        // Always filled, even at zero (position clamps to 1).
        $html = $this->render('type="icon" :read-only="true" :value="0"');
        $this->assertSame('Feedback: 0/5', html_entity_decode($this->rootAttributes($html)['aria-label']));
        $this->assertSame(['tedi-rating__circle', 'tedi-rating__circle--filled'], $this->classesOf($html, 'tedi-rating__circle'));
        $this->assertStringContainsString('>sentiment_sad</tedi-icon>', $html);
    }

    public function test_read_only_scale_partially_fills_the_boundary_star(): void
    {
        $html = $this->render(':read-only="true" read-only-variant="scale" :value="3.5"');

        preg_match_all('/--tedi-rating-star-fill: ([^"]+)"/', $html, $fills);
        $this->assertSame(['100%', '100%', '100%', '50%'], $fills[1]);
        $this->assertCount(5, $this->classListsOf($html, 'tedi-rating__star-partial'));
    }

    public function test_read_only_ignores_disabled_and_vertical(): void
    {
        $html = $this->render('type="icon" orientation="vertical" :disabled="true" :read-only="true"');

        $this->assertSame(['tedi-rating', 'tedi-rating--icon', 'tedi-rating--readonly'], $this->classesOf($html, 'tedi-rating'));
    }
}
