<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Class-string parity for the React-sourced card stepper (CONVENTIONS.md §13) —
 * react/src/tedi/components/navigation/card-stepper/card-stepper.tsx.
 */
class CardStepperComponentsTest extends TestCase
{
    private const STEPS = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Kontaktandmed', 'description' => 'Telefon ja e-post', 'state' => 'error'],
        ['title' => 'Lisainfo', 'disabled' => true],
        ['title' => 'Kinnitamine'],
    ];

    private function render(string $attributes = '', array $steps = self::STEPS, string $inner = ''): string
    {
        return Blade::render(
            '<tedi:card-stepper :steps="$steps" '.$attributes.'>'.$inner.'</tedi:card-stepper>',
            ['steps' => $steps]
        );
    }

    public function test_root_classes_and_group_role(): void
    {
        $html = $this->render('aria-label="Taotlus"');

        $this->assertHasClass('tedi-card-stepper', $html, on: 'tedi-card-stepper');
        // showProgress && no bottom slot.
        $this->assertHasClass('tedi-card-stepper--progress-flush', $html, on: 'tedi-card-stepper');
        // One step has a description and the position is bottom.
        $this->assertHasClass('tedi-card-stepper--reserve-description', $html, on: 'tedi-card-stepper');
        $this->assertStringContainsString('role="group"', $html);
        $this->assertStringContainsString('aria-label="Taotlus"', $html);
    }

    public function test_no_steps_renders_nothing(): void
    {
        $this->assertSame('', trim($this->render('', [])));
    }

    public function test_bottom_slot_drops_the_flush_progress(): void
    {
        $html = $this->render('', self::STEPS, '<x-slot:bottom>Lisa</x-slot:bottom> ');

        $this->assertMissingClass('tedi-card-stepper--progress-flush', $html, on: 'tedi-card-stepper');
        $this->assertHasClass('tedi-card-stepper__bottom-slot', $html, on: 'tedi-card-stepper__bottom-slot');
    }

    public function test_indicator_versus_navigation_lead(): void
    {
        $default = $this->render(':active-step="1"');
        $this->assertHasClass('tedi-card-stepper__indicator', $default, on: 'tedi-card-stepper__indicator');
        $this->assertMatchesRegularExpression('/tedi-card-stepper__indicator[^>]*>2</', $default);
        $this->assertMissingClass('tedi-card-stepper__main--no-lead', $default, on: 'tedi-card-stepper__main');

        $noLead = $this->render(':show-step-number="false"');
        $this->assertHasClass('tedi-card-stepper__main--no-lead', $noLead, on: 'tedi-card-stepper__main');

        // showNavigation takes the lead slot from the indicator.
        $nav = $this->render(':show-step-number="true" show-navigation');
        $this->assertStringNotContainsString('tedi-card-stepper__indicator', $nav);
        $this->assertHasClass('tedi-card-stepper__nav-prev', $nav, on: 'tedi-card-stepper__nav-prev');
        $this->assertHasClass('tedi-card-stepper__nav-next', $nav, on: 'tedi-card-stepper__nav-next');
    }

    /** adjacentEnabled() — the arrows skip disabled steps and stop at the ends. */
    public function test_navigation_skips_disabled_steps(): void
    {
        $html = $this->render(':active-step="1" show-navigation');
        $this->assertMatchesRegularExpression('/tedi-card-stepper__nav-prev[^>]*index: 0/s', $html);
        // Step 2 is disabled, so next goes to 3.
        $this->assertStringContainsString('index: 3', $html);

        $first = $this->render(':active-step="0" show-navigation');
        $this->assertMatchesRegularExpression('/<button[^>]*disabled[^>]*tedi-card-stepper__nav-prev/s', $first);
    }

    public function test_counter_and_description_positions(): void
    {
        $inline = $this->render(':active-step="1"');
        $this->assertStringContainsString('2 / 4', $inline);
        $this->assertStringContainsString(__('tedi::tedi.stepper.status', ['current' => 2, 'total' => 4]), $inline);
        $this->assertHasClass('tedi-card-stepper__description-bottom', $inline, on: 'tedi-card-stepper__description-bottom');
        $this->assertStringNotContainsString('tedi-card-stepper__top', $inline);

        $top = $this->render(':active-step="1" counter-position="top" description-position="top"');
        $this->assertHasClass('tedi-card-stepper__top', $top, on: 'tedi-card-stepper__top');
        $this->assertStringNotContainsString('tedi-card-stepper__description-bottom', $top);
        $this->assertMissingClass('tedi-card-stepper--reserve-description', $top, on: 'tedi-card-stepper');
    }

    public function test_progress_segments_mark_done_up_to_the_active_step(): void
    {
        $html = $this->render(':active-step="1"');

        $this->assertSame(4, substr_count($html, 'class="tedi-card-stepper__segment'));
        $this->assertSame(2, substr_count($html, 'tedi-card-stepper__segment--done'));

        $this->assertStringNotContainsString('tedi-card-stepper__progress', $this->render(':show-progress="false"'));
    }

    public function test_status_icon_follows_the_state(): void
    {
        $done = $this->render(':active-step="0" show-status-icon');
        $this->assertHasClass('tedi-card-stepper__status-icon', $done, on: 'tedi-card-stepper__status-icon');
        $this->assertHasClass('tedi-icon--color-success', $done, on: 'tedi-card-stepper__status-icon');

        $plain = $this->render(':active-step="3" show-status-icon');
        $this->assertStringNotContainsString('tedi-card-stepper__status-icon', $plain);
    }

    public function test_heading_element_keeps_the_h4_style(): void
    {
        $html = $this->render('heading-element="h2"');

        $this->assertMatchesRegularExpression('/<h2[^>]*tedi-text--h4[^>]*tedi-card-stepper__title/', $html);
    }

    /** isStepNavigable() decides which list entries dispatch. */
    public function test_allow_jump_union(): void
    {
        $count = fn (string $html) => substr_count($html, "tedi:step-change");

        // true: every enabled step but the active one (goTo returns early on it).
        $this->assertSame(2, $count($this->render(':active-step="1"')));
        // false: none.
        $this->assertSame(0, $count($this->render(':active-step="1" :allow-jump="false"')));
        // completed: step 0 only.
        $this->assertSame(1, $count($this->render(':active-step="1" allow-jump="completed"')));
        // completed-or-next: step 0, and step 2 is next but disabled.
        $this->assertSame(1, $count($this->render(':active-step="1" allow-jump="completed-or-next"')));
    }

    public function test_step_list_is_a_sheet_and_can_be_turned_off(): void
    {
        $html = $this->render();
        $this->assertStringContainsString('x-data="tediSheet(', $html);
        $this->assertHasClass('tedi-sheet__panel', $html, on: 'tedi-sheet__panel');
        $this->assertStringContainsString('tedi-vertical-stepper', $html);
        $this->assertStringContainsString('aria-haspopup="dialog"', $html);

        $off = $this->render(':show-step-list="false"');
        $this->assertStringNotContainsString('tedi-sheet', $off);
        $this->assertStringNotContainsString('aria-haspopup', $off);
    }

    public function test_sub_steps_render_under_their_step(): void
    {
        $steps = [
            ['title' => 'Andmed', 'sub_steps' => [
                ['title' => 'Isik', 'state' => 'completed'],
                ['title' => 'Aadress', 'current' => true],
            ]],
            ['title' => 'Kinnitus'],
        ];
        $html = $this->render('', $steps);

        $this->assertSame(2, substr_count($html, 'tedi-vertical-stepper-item--sub-item'));
        $this->assertStringContainsString('Aadress', $html);
    }

    public function test_label_overrides(): void
    {
        $html = $this->render(
            'show-navigation :labels="[\'previous\' => \'Tagasi\', \'next\' => \'Edasi\', \'openSteps\' => \'Sammud\', \'status\' => \'S :current/:total\']"'
        );

        $this->assertStringContainsString('aria-label="Tagasi"', $html);
        $this->assertStringContainsString('aria-label="Edasi"', $html);
        $this->assertStringContainsString('aria-label="Sammud"', $html);
        $this->assertStringContainsString('S 1/4', $html);
    }
}
