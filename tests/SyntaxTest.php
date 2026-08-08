<?php

namespace Tedi\Livewire\Tests;

use Illuminate\Support\Facades\Blade;

class SyntaxTest extends TestCase
{
    public function test_short_prefix_syntax_compiles(): void
    {
        $out = Blade::render('<tedi:button variant="secondary">Hi</tedi:button>');

        $this->assertHasClass('tedi-button--secondary', $out);
        $this->assertStringNotContainsString('<tedi:', $out);
    }

    public function test_standard_namespace_syntax_compiles(): void
    {
        $out = Blade::render('<x-tedi::button variant="secondary">Hi</x-tedi::button>');

        $this->assertHasClass('tedi-button--secondary', $out);
    }

    public function test_components_nest_across_both_syntaxes(): void
    {
        // tag -> closing-button -> icon, three levels deep.
        $out = Blade::render('<tedi:tag type="danger" closable>Vigane</tedi:tag>');

        $this->assertHasClass('tedi-tag--danger', $out, 'tedi-tag');
        $this->assertHasClass('tedi-closing-button--small', $out, 'tedi-closing-button');
        $this->assertStringContainsString('material-symbols', $out);
        $this->assertStringNotContainsString('<tedi:', $out);
    }

    public function test_consumer_classes_merge_rather_than_replace(): void
    {
        $out = Blade::render('<tedi:button class="mt-4">Hi</tedi:button>');

        $this->assertHasClass('mt-4', $out, 'tedi-button');
        $this->assertHasClass('tedi-button', $out, 'tedi-button');
    }

    public function test_translations_resolve(): void
    {
        $out = Blade::render('<tedi:closing-button />');

        $this->assertStringContainsString('Close', $out);
    }

    public function test_button_icon_props_drive_padding_modifiers(): void
    {
        // BaseButtonDirective drops --pl when the first child is an icon.
        // NB: token helpers, not substrings — "tedi-button--pr" is a substring
        // of "tedi-button--primary".
        $out = Blade::render('<tedi:button icon-start="add">Lisa</tedi:button>');
        $this->assertMissingClass('tedi-button--pl', $out, 'tedi-button');
        $this->assertHasClass('tedi-button--pr', $out, 'tedi-button');

        $out = Blade::render('<tedi:button icon-end="arrow_forward">Edasi</tedi:button>');
        $this->assertHasClass('tedi-button--pl', $out, 'tedi-button');
        $this->assertMissingClass('tedi-button--pr', $out, 'tedi-button');

        $out = Blade::render('<tedi:button icon-only icon-start="close" aria-label="Sulge" />');
        $this->assertHasClass('tedi-button--icon-only', $out, 'tedi-button');
    }
}
