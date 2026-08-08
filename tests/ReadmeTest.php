<?php

namespace Tedi\Livewire\Tests;

use Illuminate\Support\Facades\Blade;

/**
 * The README's Blade examples must actually render, so the documentation cannot
 * drift from the component API.
 */
class ReadmeTest extends TestCase
{
    public function test_readme_usage_examples_render(): void
    {
        $examples = [
            '<tedi:button variant="primary" icon-start="add">Lisa</tedi:button>',
            '<tedi:tag type="danger" closable>Vigane</tedi:tag>',
            '<tedi:alert type="warning">Tähelepanu</tedi:alert>',
            '<tedi:button variant="primary" size="small">Salvesta</tedi:button>',
            '<x-tedi::button variant="primary">Salvesta</x-tedi::button>',
        ];

        foreach ($examples as $example) {
            $html = Blade::render($example);

            $this->assertNotSame('', trim($html), "README example rendered empty: {$example}");
            $this->assertStringNotContainsString('<tedi:', $html,
                "README example left an uncompiled tag: {$example}");
        }
    }

    public function test_readme_livewire_examples_bind_wire_model_to_the_control(): void
    {
        $html = Blade::render(
            '<tedi:checkbox wire:model.live="accepted" name="accepted" label="Nõustun" />'
        );

        $this->assertStringContainsString('wire:model.live="accepted"', $html);
        // wire:model must land on the native control, not a wrapper.
        $this->assertMatchesRegularExpression(
            '/<input[^>]*wire:model\.live="accepted"/', $html,
            'wire:model must be on the <input>, not a wrapper element.'
        );
    }

    /**
     * The README's Livewire section claims every form control puts
     * {{ $attributes }} on the native control. That is the headline promise of
     * the form-value-binding phase, so each example is pinned to the element
     * the binding must actually land on.
     */
    public function test_readme_form_control_examples_bind_wire_model_to_the_control(): void
    {
        $examples = [
            'input' => '<tedi:text-field wire:model="name" :value="\'Mari\'" />',
            'textarea' => '<tedi:textarea wire:model="bio" :value="\'Tekst\'" />',
            'number' => '<tedi:number-field wire:model="quantity" :value="2" :min="0" />',
            'toggle' => '<tedi:toggle wire:model.live="enabled" :checked="true" />',
            'slider' => '<tedi:slider wire:model="volume" :value="40" :min="0" :max="100" />',
            'search' => '<tedi:search wire:model="q" />',
        ];

        foreach ($examples as $label => $example) {
            $html = Blade::render($example);
            $element = $label === 'textarea' ? 'textarea' : 'input';

            $this->assertMatchesRegularExpression(
                '/<'.$element.'[^>]*wire:model(\.live)?="[a-z]+"/', $html,
                "{$label}: wire:model must be on the <{$element}>, not a wrapper."
            );
        }
    }

    /**
     * An unconditional value="" would blank a wire:model-bound field on the
     * initial server render, so the attribute is omitted when empty. The
     * range input is the documented exception — it has no empty state.
     */
    public function test_empty_value_is_omitted_rather_than_rendered_blank(): void
    {
        $html = Blade::render('<tedi:text-field wire:model="name" />');
        $this->assertStringNotContainsString('value=""', $html);

        $html = Blade::render('<tedi:slider wire:model="volume" />');
        $this->assertMatchesRegularExpression('/<input[^>]*value="0"/', $html,
            'slider is the documented exception: a range input always emits a value.');
    }

    public function test_readme_select_example_binds_wire_model(): void
    {
        $html = Blade::render(
            '<tedi:select wire:model="county" :options="[\'harju\' => \'Harjumaa\']" placeholder="Vali maakond" />'
        );

        $this->assertMatchesRegularExpression(
            '/<select[^>]*wire:model="county"/', $html,
            'wire:model must be on the <select>.'
        );
        $this->assertStringContainsString('Harjumaa', $html);
    }

    public function test_theme_classes_referenced_by_readme_exist(): void
    {
        $css = @file_get_contents(__DIR__.'/../dist/tedi.css');

        if ($css === false) {
            $this->markTestSkipped('dist/tedi.css not built — run `npm run build`.');
        }

        $this->assertStringContainsString('.tedi-theme--default', $css);
        $this->assertStringContainsString('.tedi-theme--dark', $css);
    }
}
