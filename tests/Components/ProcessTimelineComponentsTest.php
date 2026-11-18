<?php

namespace Tedi\Livewire\Tests\Components;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string and ARIA parity for the community process timeline
 * (CONVENTIONS.md §12), ported from
 * `community/components/helpers/process-timeline/process-timeline.component.spec.ts`.
 *
 * The prop unions come from the community `export type` declarations:
 *   ProcessTimelineItemState, ProcessTimelineOrientation,
 *   ProcessTimelineConnectorThickness.
 */
class ProcessTimelineComponentsTest extends TestCase
{
    private const STATES = ['completed', 'active', 'upcoming'];

    private const ORIENTATIONS = ['horizontal', 'vertical'];

    private const THICKNESSES = [1, 2];

    /** The spec's host: completed, active (description + action), upcoming. */
    private function host(string $attrs = ''): string
    {
        return Blade::render('<tedi:process-timeline '.$attrs.'>'
            .'<tedi:process-timeline-item state="completed" :index="0">'
            .'<x-slot:title>Pakkumine esitatud</x-slot:title> '
            .'</tedi:process-timeline-item>'
            .'<tedi:process-timeline-item state="active" :index="1">'
            .'<x-slot:title>Kvalifitseerimine</x-slot:title> '
            .'<x-slot:description>Hankija kontrollib dokumente.</x-slot:description> '
            .'<button type="button">Lisa dokumendid</button>'
            .'</tedi:process-timeline-item>'
            .'<tedi:process-timeline-item state="upcoming" :index="2" last>'
            .'<x-slot:title>Tulemused</x-slot:title> '
            .'</tedi:process-timeline-item>'
            .'</tedi:process-timeline>');
    }

    private function xpath(string $html): DOMXPath
    {
        $doc = new DOMDocument;
        @$doc->loadHTML('<?xml encoding="utf-8"?><body>'.$html.'</body>');

        return new DOMXPath($doc);
    }

    private function hasClass(string $class): string
    {
        return "contains(concat(' ', normalize-space(@class), ' '), ' {$class} ')";
    }

    /** @return list<DOMElement> */
    private function items(DOMXPath $x): array
    {
        return iterator_to_array($x->query('//li['.$this->hasClass('tedi-process-timeline-item').']'));
    }

    /** @return list<string> */
    private function classList(DOMElement $el): array
    {
        return preg_split('/\s+/', trim($el->getAttribute('class')), -1, PREG_SPLIT_NO_EMPTY);
    }

    // -- process-timeline ------------------------------------------------

    public function test_root_is_a_native_ordered_list_with_an_explicit_list_role(): void
    {
        $x = $this->xpath($this->host());
        $ol = $x->query('//ol')->item(0);

        $this->assertTrue($ol->hasAttribute('tedi-process-timeline'));
        $this->assertSame('list', $ol->getAttribute('role'));
        $this->assertCount(3, $this->items($x));

        foreach ($this->items($x) as $li) {
            $this->assertTrue($li->hasAttribute('tedi-process-timeline-item'));
            $this->assertSame('listitem', $li->getAttribute('role'));
        }
    }

    public function test_orientation_classes(): void
    {
        foreach (self::ORIENTATIONS as $orientation) {
            $html = Blade::render('<tedi:process-timeline orientation="'.$orientation.'">x</tedi:process-timeline>');
            $other = $orientation === 'vertical' ? 'horizontal' : 'vertical';

            $this->assertSame(
                ['tedi-process-timeline', 'tedi-process-timeline--'.$orientation],
                $this->classesOf($html, 'tedi-process-timeline'),
            );
            $this->assertMissingClass('tedi-process-timeline--'.$other, $html, 'tedi-process-timeline');
        }
    }

    public function test_default_orientation_is_horizontal(): void
    {
        $html = Blade::render('<tedi:process-timeline>x</tedi:process-timeline>');

        $this->assertHasClass('tedi-process-timeline--horizontal', $html, 'tedi-process-timeline');
        $this->assertMissingClass('tedi-process-timeline--vertical', $html, 'tedi-process-timeline');
    }

    public function test_consumer_role_and_classes_merge(): void
    {
        $html = Blade::render('<tedi:process-timeline class="mine" role="presentation">x</tedi:process-timeline>');

        $this->assertHasClass('mine', $html, 'tedi-process-timeline');
        $this->assertHasClass('tedi-process-timeline', $html, 'tedi-process-timeline');
        $this->assertSame(1, substr_count($html, 'role='));
        $this->assertStringContainsString('role="presentation"', $html);
    }

    // -- process-timeline-item --------------------------------------------

    public function test_item_class_parity_over_state_orientation_and_last(): void
    {
        foreach (self::STATES as $state) {
            foreach (self::ORIENTATIONS as $orientation) {
                foreach ([false, true] as $last) {
                    $html = Blade::render(
                        '<tedi:process-timeline orientation="'.$orientation.'">'
                        .'<tedi:process-timeline-item state="'.$state.'" :last="'.($last ? 'true' : 'false').'">x</tedi:process-timeline-item>'
                        .'</tedi:process-timeline>'
                    );

                    $expected = array_values(array_filter([
                        'tedi-process-timeline-item',
                        'tedi-process-timeline-item--'.$state,
                        $orientation === 'vertical' ? 'tedi-process-timeline-item--vertical' : null,
                        $last ? 'tedi-process-timeline-item--last' : null,
                    ]));

                    $this->assertSame($expected, $this->classesOf($html, 'tedi-process-timeline-item'),
                        "state={$state} orientation={$orientation} last=".var_export($last, true));
                }
            }
        }
    }

    public function test_item_default_state_is_upcoming(): void
    {
        $html = Blade::render('<tedi:process-timeline-item>x</tedi:process-timeline-item>');

        $this->assertSame(
            ['tedi-process-timeline-item', 'tedi-process-timeline-item--upcoming'],
            $this->classesOf($html, 'tedi-process-timeline-item'),
        );
    }

    public function test_stages_are_numbered_from_index_and_indicators_are_hidden(): void
    {
        $x = $this->xpath($this->host());
        $indicators = $x->query('//*['.$this->hasClass('tedi-process-timeline-item__indicator').']');

        $this->assertSame(['1', '2', '3'], array_map(
            fn (DOMElement $i) => trim($i->textContent),
            iterator_to_array($indicators),
        ));

        foreach ($indicators as $indicator) {
            $this->assertSame('true', $indicator->getAttribute('aria-hidden'));
        }
    }

    public function test_standalone_item_is_numbered_one(): void
    {
        $x = $this->xpath(Blade::render('<ul><tedi:process-timeline-item state="completed" last /></ul>'));

        $this->assertSame('1', trim($x->query('//*['.$this->hasClass('tedi-process-timeline-item__indicator').']')->item(0)->textContent));
        $this->assertSame(0, $x->query('//*['.$this->hasClass('tedi-process-timeline-item__connector').']')->length);
    }

    public function test_state_modifiers(): void
    {
        $items = $this->items($this->xpath($this->host()));

        $this->assertContains('tedi-process-timeline-item--completed', $this->classList($items[0]));
        $this->assertContains('tedi-process-timeline-item--active', $this->classList($items[1]));
        $this->assertContains('tedi-process-timeline-item--upcoming', $this->classList($items[2]));
    }

    public function test_only_the_active_stage_has_aria_current_step(): void
    {
        $items = $this->items($this->xpath($this->host()));

        $this->assertSame([null, 'step', null], array_map(
            fn (DOMElement $li) => $li->hasAttribute('aria-current') ? $li->getAttribute('aria-current') : null,
            $items,
        ));
    }

    public function test_completed_and_upcoming_get_translated_state_text_active_does_not(): void
    {
        foreach (['en', 'et', 'ru'] as $locale) {
            App::setLocale($locale);
            $items = $this->items($this->xpath($this->host()));

            $texts = array_map(function (DOMElement $li) {
                $sr = (new DOMXPath($li->ownerDocument))->query('.//*['.$this->hasClass('sr-only').']', $li);

                return $sr->length ? trim($sr->item(0)->textContent) : null;
            }, $items);

            $this->assertSame([
                __('tedi::tedi.process-timeline.completed'),
                null,
                __('tedi::tedi.process-timeline.upcoming'),
            ], $texts, $locale);
        }

        App::setLocale('et');
        $this->assertSame('Lõpetatud', __('tedi::tedi.process-timeline.completed'));
        $this->assertSame('Tulemas', __('tedi::tedi.process-timeline.upcoming'));
    }

    public function test_state_text_follows_the_title_and_precedes_the_description(): void
    {
        $html = Blade::render(
            '<tedi:process-timeline-item state="upcoming">'
            .'<x-slot:title>T</x-slot:title> '
            .'<x-slot:description>D</x-slot:description> '
            .'</tedi:process-timeline-item>'
        );
        $x = $this->xpath($html);
        $body = $x->query('//*['.$this->hasClass('tedi-process-timeline-item__body').']')->item(0);

        $children = [];
        foreach ($body->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $children[] = $node->getAttribute('class');
            }
        }

        $this->assertSame([
            'tedi-process-timeline-title',
            'sr-only',
            'tedi-process-timeline-description',
            'tedi-process-timeline-item__content',
        ], $children);
    }

    public function test_connector_after_every_stage_except_the_last(): void
    {
        $x = $this->xpath($this->host());
        $connectors = $x->query('//*['.$this->hasClass('tedi-process-timeline-item__connector').']');
        $items = $this->items($x);

        $this->assertSame(2, $connectors->length);
        foreach ($connectors as $connector) {
            $this->assertSame('true', $connector->getAttribute('aria-hidden'));
        }

        $this->assertSame(0, $x->query('.//*['.$this->hasClass('tedi-process-timeline-item__connector').']', $items[2])->length);
        $this->assertContains('tedi-process-timeline-item--last', $this->classList($items[2]));
        $this->assertNotContains('tedi-process-timeline-item--last', $this->classList($items[1]));
    }

    public function test_connector_separator_follows_orientation_and_thickness(): void
    {
        foreach (self::ORIENTATIONS as $orientation) {
            foreach (self::THICKNESSES as $thickness) {
                $html = Blade::render(
                    '<tedi:process-timeline orientation="'.$orientation.'" :connector-thickness="'.$thickness.'">'
                    .'<tedi:process-timeline-item>x</tedi:process-timeline-item>'
                    .'</tedi:process-timeline>'
                );

                $this->assertSame([
                    'tedi-separator',
                    'tedi-separator--primary',
                    'tedi-separator--'.$orientation,
                    'tedi-separator--thickness-'.$thickness,
                ], $this->classesOf($html, 'tedi-separator'), "{$orientation} × {$thickness}");
            }
        }
    }

    public function test_connector_defaults_to_horizontal_thickness_one_without_a_timeline(): void
    {
        $html = Blade::render('<tedi:process-timeline-item>x</tedi:process-timeline-item>');

        $this->assertHasClass('tedi-separator--horizontal', $html, 'tedi-separator');
        $this->assertHasClass('tedi-separator--thickness-1', $html, 'tedi-separator');
        $this->assertMissingClass('tedi-process-timeline-item--vertical', $html, 'tedi-process-timeline-item');
    }

    public function test_stages_stay_out_of_the_tab_order(): void
    {
        $x = $this->xpath($this->host());

        foreach ($this->items($x) as $li) {
            $this->assertFalse($li->hasAttribute('tabindex'));
            $this->assertSame(0, $x->query('.//*[@tabindex]', $li)->length);
        }

        $focusable = $x->query('//button | //a[@href]');
        $this->assertSame(1, $focusable->length);
        $this->assertStringContainsString('Lisa dokumendid', $focusable->item(0)->textContent);
    }

    public function test_description_and_content_are_projected(): void
    {
        $x = $this->xpath($this->host());
        $active = $this->items($x)[1];

        $description = $x->query('.//tedi-process-timeline-description', $active)->item(0);
        $this->assertSame(['tedi-process-timeline-description'], $this->classList($description));
        $this->assertStringContainsString('Hankija kontrollib dokumente.', $description->textContent);

        $this->assertSame(1, $x->query('.//*['.$this->hasClass('tedi-process-timeline-item__content').']/button', $active)->length);
    }

    public function test_empty_content_is_empty_so_the_css_hides_it(): void
    {
        $html = Blade::render('<tedi:process-timeline-item><x-slot:title>T</x-slot:title></tedi:process-timeline-item>');

        $this->assertStringContainsString('<div class="tedi-process-timeline-item__content"></div>', $html);
    }

    public function test_title_and_description_render_their_custom_elements_and_forward_slot_attributes(): void
    {
        $html = Blade::render(
            '<tedi:process-timeline-item>'
            .'<x-slot:title class="extra" id="t1">T</x-slot:title> '
            .'<x-slot:description data-x="1">D</x-slot:description> '
            .'</tedi:process-timeline-item>'
        );

        $this->assertStringContainsString('<tedi-process-timeline-title', $html);
        $this->assertSame(['tedi-process-timeline-title', 'extra'], $this->classesOf($html, 'tedi-process-timeline-title'));
        $this->assertStringContainsString('id="t1"', $html);
        $this->assertStringContainsString('<tedi-process-timeline-description', $html);
        $this->assertStringContainsString('data-x="1"', $html);
    }

    public function test_title_and_description_components_standalone(): void
    {
        $title = Blade::render('<tedi:process-timeline-title class="a">T</tedi:process-timeline-title>');
        $description = Blade::render('<tedi:process-timeline-description>D</tedi:process-timeline-description>');

        $this->assertStringStartsWith('<tedi-process-timeline-title', trim($title));
        $this->assertSame(['tedi-process-timeline-title', 'a'], $this->classesOf($title, 'tedi-process-timeline-title'));
        $this->assertStringStartsWith('<tedi-process-timeline-description', trim($description));
        $this->assertSame(['tedi-process-timeline-description'], $this->classesOf($description, 'tedi-process-timeline-description'));
    }

    public function test_consumer_aria_current_wins_without_duplicating(): void
    {
        $html = Blade::render('<tedi:process-timeline-item state="active" aria-current="page">x</tedi:process-timeline-item>');

        $this->assertSame(1, substr_count($html, 'aria-current='));
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    // -- overflow fallback (tediProcessTimeline) --------------------------

    public function test_only_a_horizontal_timeline_registers_the_overflow_fallback(): void
    {
        $horizontal = Blade::render('<tedi:process-timeline>x</tedi:process-timeline>');
        $vertical = Blade::render('<tedi:process-timeline orientation="vertical">x</tedi:process-timeline>');

        $this->assertStringContainsString('x-data="tediProcessTimeline"', $horizontal);
        $this->assertStringNotContainsString('x-data', $vertical);
    }

    public function test_overflow_fallback_is_registered_in_the_bundle(): void
    {
        $entry = file_get_contents(__DIR__.'/../../resources/js/tedi.js');
        $dist = file_get_contents(__DIR__.'/../../dist/tedi.js');

        $this->assertStringContainsString("Alpine.data('tediProcessTimeline', processTimeline)", $entry);
        $this->assertStringContainsString('tediProcessTimeline', $dist);
    }
}
