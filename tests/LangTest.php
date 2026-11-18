<?php

namespace Tedi\Livewire\Tests;

/**
 * lang/{en,et,ru}/tedi.php is the Angular-generated block plus the hand-kept
 * React block (CONVENTIONS.md §13.6). A key in both is a PHP duplicate array
 * key: the React copy silently wins over the regenerated Angular one.
 */
class LangTest extends TestCase
{
    public static function languages(): array
    {
        return [['en'], ['et'], ['ru']];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('languages')]
    public function test_no_key_is_defined_twice(string $lang): void
    {
        $source = file_get_contents(__DIR__.'/../lang/'.$lang.'/tedi.php');
        preg_match_all('/^\s*"([^"]+)"\s*=>/m', $source, $m);

        $duplicates = array_keys(array_filter(array_count_values($m[1]), fn ($n) => $n > 1));

        $this->assertSame([], $duplicates, "lang/{$lang}/tedi.php defines these keys twice");
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('languages')]
    public function test_every_language_has_the_same_keys(string $lang): void
    {
        $en = array_keys(require __DIR__.'/../lang/en/tedi.php');
        $other = array_keys(require __DIR__.'/../lang/'.$lang.'/tedi.php');

        $this->assertSame([], array_values(array_diff($en, $other)), "missing in {$lang}");
        $this->assertSame([], array_values(array_diff($other, $en)), "extra in {$lang}");
    }

    public function test_russian_three_form_plural_matches_pluralize_ru(): void
    {
        // generate-lang.cjs writes Angular's pluralizeRu (one / few / many) as a
        // plain "one|few|many" string, so trans_choice applies Laravel's own
        // Russian rule. It must pick the form Intl.PluralRules('ru') does.
        $expected = [
            1 => '1 оценка', 2 => '2 оценки', 4 => '4 оценки', 5 => '5 оценок',
            11 => '11 оценок', 12 => '12 оценок', 21 => '21 оценка', 22 => '22 оценки', 25 => '25 оценок',
        ];

        foreach ($expected as $n => $text) {
            $this->assertSame($text, trans_choice('tedi::tedi.rating.raters', $n, ['count' => $n], 'ru'));
        }
    }
}
