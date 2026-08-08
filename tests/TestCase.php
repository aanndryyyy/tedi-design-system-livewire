<?php

namespace Tedi\Livewire\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Tedi\Livewire\TediServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [TediServiceProvider::class];
    }

    /**
     * Extract the class tokens of the first element carrying a TEDI class.
     *
     * @return string[]
     */
    protected function classesOf(string $html, ?string $matching = null): array
    {
        // (?<![-:.\w]) rejects Alpine/Vue bindings — `:class="{...}"`,
        // `x-bind:class="..."` — whose tail would otherwise match `class="..."`
        // and make the assertion inspect a JS expression instead of the real
        // class list. Without this, a component that writes its Alpine binding
        // before $attributes->class() silently passes or fails for the wrong
        // reason, depending only on attribute order.
        preg_match_all('/(?<![-:.\w])class="([^"]*)"/', $html, $matches);

        foreach ($matches[1] as $classAttr) {
            $tokens = preg_split('/\s+/', trim($classAttr), -1, PREG_SPLIT_NO_EMPTY);

            if ($matching === null || in_array($matching, $tokens, true)) {
                return $tokens;
            }
        }

        return [];
    }

    /**
     * Assert an exact class token is present.
     *
     * Never use assertStringContainsString() for class names: "tedi-button--pr"
     * is a substring of "tedi-button--primary", so substring assertions both
     * pass and fail for the wrong reasons.
     */
    protected function assertHasClass(string $class, string $html, ?string $on = null): void
    {
        $tokens = $this->classesOf($html, $on);

        $this->assertContains($class, $tokens, sprintf(
            'Expected class [%s]; got [%s].', $class, implode(' ', $tokens)
        ));
    }

    protected function assertMissingClass(string $class, string $html, ?string $on = null): void
    {
        $tokens = $this->classesOf($html, $on);

        $this->assertNotContains($class, $tokens, sprintf(
            'Did not expect class [%s]; got [%s].', $class, implode(' ', $tokens)
        ));
    }
}
