<?php

namespace Tedi\Livewire\Tests;

/**
 * Guardrails for two Blade compiler traps that both fail SILENTLY, and which
 * `php -l` therefore cannot catch.
 *
 * 1. A component tag written inside a directive's argument — typically a
 *    `<tedi:…>` in an `@storybook` argTypes description, or in a `@props`
 *    docblock — is compiled as a real component tag, because the component-tag
 *    compiler runs over the whole file including directive arguments. An
 *    unpaired opening tag yields "Undefined variable $component" in a file that
 *    contains no `$component`; worse, it can pair with a genuine closing tag
 *    further down, and if that pairing straddles an `@if` it swallows the
 *    `@endif`. See storybook/CONTRACT.md §6a.
 *
 * 2. A component tag opened in one `@if` branch and closed in another does not
 *    error. The compiler emits its own `if ($component->shouldRender()):` guard
 *    between the two branches, which absorbs the first `@endif`, so everything
 *    between the branches ends up inside the condition and the false branch
 *    silently renders nothing. See CONVENTIONS.md §2.
 *
 * Both were found by hand during the overlay/modal port. Nothing else in the
 * suite can see either one: class-parity tests assert on rendered output that
 * looks individually plausible, and IntegrityTest's compile check passes
 * because the compiled PHP is syntactically valid.
 *
 * The detectors are proved live by the *_detector_* tests at the bottom, which
 * run them against known-bad and known-good fixtures. Those exist so this file
 * cannot silently rot into a no-op that passes because it stopped detecting
 * anything at all.
 */
class StoryIntegrityTest extends TestCase
{
    /** Directives whose argument is scanned for component tags, per root. */
    private const SCANNED_DIRECTIVES = [
        'stories' => ['storybook'],
        'components' => ['props', 'aware'],
    ];

    /** Blade directives that open a block scope. */
    private const BLOCK_OPEN = [
        'if', 'unless', 'isset', 'empty', 'switch', 'auth', 'guest', 'can', 'cannot', 'canany',
        'foreach', 'for', 'while', 'forelse', 'once', 'production', 'env', 'hasSection', 'sectionMissing',
    ];

    /** …and the ones that close it. */
    private const BLOCK_CLOSE = [
        'endif', 'endunless', 'endisset', 'endempty', 'endswitch', 'endauth', 'endguest',
        'endcan', 'endcannot', 'endcanany', 'endforeach', 'endfor', 'endwhile', 'endforelse',
        'endonce', 'endproduction', 'endenv',
    ];

    /** Directives that start a new branch within the current block. */
    private const BRANCH = ['elseif', 'else', 'elsecan', 'elsecannot', 'case', 'default'];

    // =====================================================================
    // the checks
    // =====================================================================

    public function test_no_component_tags_inside_directive_arguments(): void
    {
        $failures = [];

        foreach ($this->roots() as $label => $dir) {
            foreach ($this->bladeFiles($dir) as $file) {
                $source = file_get_contents($file);

                foreach ($this->tagsInDirectiveArguments($source, self::SCANNED_DIRECTIVES[$label]) as $hit) {
                    $failures[] = sprintf(
                        "%s:%d\n      inside @%s(...): %s\n      %s",
                        $this->relative($file), $hit['line'], $hit['directive'], $hit['tag'],
                        $this->quoteLine($source, $hit['line'])
                    );
                }
            }
        }

        $this->assertSame([], $failures, sprintf(
            "Component tags inside a directive's argument. Blade compiles these as real\n"
            ."component tags — the symptom is \"Undefined variable \$component\", or a\n"
            ."swallowed @endif. Write the tag without angle brackets (`tedi:modal-header`).\n"
            ."See storybook/CONTRACT.md §6a.\n\n  %s\n",
            implode("\n\n  ", $failures)
        ));
    }

    public function test_no_component_tag_split_across_blade_blocks(): void
    {
        $failures = [];

        foreach ($this->roots() as $dir) {
            foreach ($this->bladeFiles($dir) as $file) {
                $source = file_get_contents($file);
                $result = $this->scanSplitTags($source);

                // A file whose block directives don't balance cannot be judged:
                // the frame stack is meaningless past the imbalance. That case
                // is reported by its own test instead of guessed at here.
                if (! $result['balanced']) {
                    continue;
                }

                foreach ($result['offenses'] as $offense) {
                    $failures[] = sprintf(
                        "%s\n      <%s> opened line %d, closed line %d — different Blade blocks\n      %d: %s\n      %d: %s",
                        $this->relative($file), $offense['tag'], $offense['openLine'], $offense['closeLine'],
                        $offense['openLine'], $this->quoteLine($source, $offense['openLine']),
                        $offense['closeLine'], $this->quoteLine($source, $offense['closeLine'])
                    );
                }
            }
        }

        $this->assertSame([], $failures, sprintf(
            "Component tag opened in one Blade block/branch and closed in another.\n"
            ."This does NOT error: the compiler's own shouldRender() guard absorbs the\n"
            ."first @endif, so everything between the branches ends up inside the\n"
            ."condition and the false branch silently renders nothing. Duplicate the\n"
            ."markup, or move the condition inside the component.\n"
            ."See CONVENTIONS.md §2.\n\n  %s\n",
            implode("\n\n  ", $failures)
        ));
    }

    /**
     * Blade's `\b` directive matching means `@endifz` is not `@endif`, so a
     * directive hugged by a word character leaves its block unterminated. That
     * is the same family as the `</x-slot:…>x` → `@endslotx` trap, and it also
     * makes the split-tag scan above unjudgeable, so it gets its own check.
     */
    public function test_blade_block_directives_are_balanced(): void
    {
        $failures = [];

        foreach ($this->roots() as $dir) {
            foreach ($this->bladeFiles($dir) as $file) {
                $result = $this->scanSplitTags(file_get_contents($file));

                if (! $result['balanced']) {
                    $failures[] = $this->relative($file).' — '.$result['reason'];
                }
            }
        }

        $this->assertSame([], $failures,
            "Blade block directives do not balance in these files (an unterminated\n"
            ."@if/@foreach/…, often a directive hugged by a word character so Blade's\n"
            ."word-boundary match misses it):\n  ".implode("\n  ", $failures)."\n");
    }

    // =====================================================================
    // detector self-tests — proof the checks above can actually fail
    // =====================================================================

    public function test_split_tag_detector_flags_known_bad_shapes(): void
    {
        $shapes = [
            'opened in @if, closed in a second @if' => <<<'BLADE'
                @if ($fade)
                <tedi:scroll-fade>
                @endif
                    <p>body</p>
                @if ($fade)
                </tedi:scroll-fade>
                @endif
                BLADE,
            'opened in @if, closed in @else' => <<<'BLADE'
                @if ($x)
                <tedi:card>
                @else
                </tedi:card>
                @endif
                BLADE,
            'opened outside a loop, closed inside it' => <<<'BLADE'
                <tedi:card>
                @foreach ($xs as $x)
                </tedi:card>
                @endforeach
                BLADE,
        ];

        foreach ($shapes as $label => $source) {
            $result = $this->scanSplitTags($source);

            $this->assertNotSame([], $result['offenses'],
                "The split-tag detector failed to flag: {$label}");
        }
    }

    public function test_split_tag_detector_ignores_legitimate_shapes(): void
    {
        $shapes = [
            'conditional nested inside a component' => '<tedi:card>@if ($x)<p>a</p>@endif</tedi:card>',
            'component wrapping a whole if/elseif/else chain' => '<tedi:card>@if($a)a@elseif($b)b@else c@endif</tedi:card>',
            'the same component in both branches' => '@if ($x)<tedi:card>a</tedi:card>@else<tedi:card>b</tedi:card>@endif',
            'component inside a loop' => '@foreach ($xs as $x)<tedi:card>{{ $x }}</tedi:card>@endforeach',
            'forelse with a component in each leg' => '@forelse ($xs as $x)<tedi:card>a</tedi:card>@empty<tedi:card>b</tedi:card>@endforelse',
            'self-closing tag inside a conditional' => '@if ($x)<tedi:icon name="add" />@endif',
            'switch with a component per case' => '@switch ($x)@case(1)<tedi:card>a</tedi:card>@break@default<tedi:card>b</tedi:card>@endswitch',
            'nested same-name components' => '<tedi:card><tedi:card>x</tedi:card></tedi:card>',
            'attribute value containing a > character' => '<tedi:button x-on:click="a > b ? go() : stop()">x</tedi:button>',
            'conditional attribute inside the open tag' => '<tedi:button @if ($x) disabled @endif>x</tedi:button>',
            'isset and empty blocks' => '@isset ($x)<tedi:card>a</tedi:card>@endisset @empty ($y)<tedi:card>b</tedi:card>@endempty',
            'deeply nested balanced conditionals' => '@if($a)@if($b)<tedi:card>x</tedi:card>@endif@endif',
            'escaped @@if is not a directive' => '<tedi:card>@@if literal @if($a)x@endif</tedi:card>',
            'split tag inside a blade comment' => "<tedi:card>{{-- @if (\$x)\n<tedi:card>\n@endif --}}x</tedi:card>",
        ];

        foreach ($shapes as $label => $source) {
            $result = $this->scanSplitTags($source);

            $this->assertSame([], $result['offenses'],
                "The split-tag detector cried wolf on: {$label}");
            $this->assertTrue($result['balanced'],
                "The balance check cried wolf on: {$label}");
        }
    }

    public function test_directive_argument_detector_flags_known_bad_shapes(): void
    {
        $bad = [
            'paired tag in a description' => "@storybook(['argTypes' => ['a' => ['description' => 'use <tedi:card>x</tedi:card>']]])",
            'unpaired opening tag in a description' => "@storybook(['argTypes' => ['a' => ['description' => 'set on <tedi:modal-header>.']]])",
            'tag in a multi-line description' => "@storybook([\n 'argTypes' => [\n  'a' => ['description' => 'wrap in\n <tedi:scroll-fade> here'],\n ],\n])",
        ];

        foreach ($bad as $label => $source) {
            $this->assertNotSame([], $this->tagsInDirectiveArguments($source, ['storybook']),
                "The directive-argument detector failed to flag: {$label}");
        }

        $good = [
            'tag written without angle brackets' => "@storybook(['argTypes' => ['a' => ['description' => 'set on `tedi:modal-header`.']]])",
            'real component tags in the template body' => "@storybook(['args' => ['a' => 1]])\n<tedi:card>x</tedi:card>",
        ];

        foreach ($good as $label => $source) {
            $this->assertSame([], $this->tagsInDirectiveArguments($source, ['storybook']),
                "The directive-argument detector cried wolf on: {$label}");
        }
    }

    // =====================================================================
    // detectors
    // =====================================================================

    /**
     * Component tags appearing inside the parenthesised argument of one of the
     * given directives.
     *
     * @return array<int, array{directive: string, line: int, tag: string}>
     */
    private function tagsInDirectiveArguments(string $source, array $directives): array
    {
        $masked = $this->maskInert($source);
        $hits = [];

        foreach ($directives as $directive) {
            foreach ($this->directiveArgumentSpans($masked, $directive) as [$start, $end]) {
                $chunk = substr($masked, $start, $end - $start + 1);

                if (! preg_match_all('/<\s*\/?\s*(?:x-)?tedi[:.][a-zA-Z0-9._:-]*/', $chunk, $m, PREG_OFFSET_CAPTURE)) {
                    continue;
                }

                foreach ($m[0] as [$tag, $relative]) {
                    $hits[] = [
                        'directive' => $directive,
                        'line' => $this->lineAt($source, $start + $relative),
                        'tag' => $tag,
                    ];
                }
            }
        }

        return $hits;
    }

    /**
     * Component tags whose opening and closing tags sit in different Blade
     * block scopes or different branches of the same block.
     *
     * Each block directive pushes a frame; each branch directive bumps the
     * current frame's branch counter. A tag's "path" is the frame/branch stack
     * at the point it appears — if the path differs between open and close, the
     * pair straddles a conditional boundary.
     *
     * @return array{offenses: array<int, array{tag: string, openLine: int, closeLine: int}>, balanced: bool, reason: string}
     */
    private function scanSplitTags(string $source): array
    {
        $masked = $this->maskInert($source);

        $directives = implode('|', array_merge(self::BLOCK_CLOSE, self::BLOCK_OPEN, self::BRANCH));

        // `(?<!@)` skips Blade's `@@` escape. The trailing `\b` is what Blade
        // itself uses, so `@endifz` is correctly NOT treated as `@endif`.
        $pattern = '/'
            .'(?P<directive>(?<!@)@(?P<name>'.$directives.')\b)'
            .'|(?P<close><\s*\/\s*(?:x-)?(?P<cname>tedi[:.][a-zA-Z0-9._:-]*)\s*>)'
            .'|(?P<open><\s*(?:x-)?(?P<oname>tedi[:.][a-zA-Z0-9._:-]*)(?P<attrs>(?:"[^"]*"|\'[^\']*\'|[^>"\'])*)>)'
            .'/s';

        preg_match_all($pattern, $masked, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $frames = [];
        $frameSeq = 0;
        $tags = [];
        $offenses = [];
        $reason = '';

        $path = static function () use (&$frames): string {
            return implode('/', array_map(static fn ($f) => $f['id'].':'.$f['branch'], $frames));
        };

        foreach ($matches as $match) {
            $offset = $match[0][1];

            if (($match['directive'][0] ?? '') !== '') {
                $name = $match['name'][0];

                if (in_array($name, self::BLOCK_CLOSE, true)) {
                    if (! $frames) {
                        $reason = $reason ?: 'stray @'.$name.' on line '.$this->lineAt($source, $offset);

                        continue;
                    }

                    array_pop($frames);

                    continue;
                }

                // Bare `@empty` is @forelse's branch; `@empty(` opens a block.
                $isForelseBranch = $name === 'empty'
                    && ! preg_match('/^@empty\s*\(/', substr($masked, $offset, 16));

                if (in_array($name, self::BRANCH, true) || $isForelseBranch) {
                    if ($frames) {
                        $frames[count($frames) - 1]['branch']++;
                    }

                    continue;
                }

                $frames[] = ['id' => ++$frameSeq, 'branch' => 0];

                continue;
            }

            if (($match['close'][0] ?? '') !== '') {
                $name = $match['cname'][0];

                for ($i = count($tags) - 1; $i >= 0; $i--) {
                    if ($tags[$i]['name'] !== $name) {
                        continue;
                    }

                    $opened = $tags[$i];
                    array_splice($tags, $i);

                    if ($opened['path'] !== $path()) {
                        $offenses[] = [
                            'tag' => $name,
                            'openLine' => $opened['line'],
                            'closeLine' => $this->lineAt($source, $offset),
                        ];
                    }

                    break;
                }

                continue;
            }

            if (($match['open'][0] ?? '') !== '') {
                $attributes = rtrim($match['attrs'][0] ?? '');

                if (str_ends_with($attributes, '/')) {
                    continue; // self-closing
                }

                $tags[] = [
                    'name' => $match['oname'][0],
                    'path' => $path(),
                    'line' => $this->lineAt($source, $offset),
                ];
            }
        }

        if ($frames && ! $reason) {
            $reason = count($frames).' unterminated block directive(s)';
        }

        return [
            'offenses' => $offenses,
            'balanced' => $reason === '',
            'reason' => $reason,
        ];
    }

    // =====================================================================
    // plumbing
    // =====================================================================

    /**
     * Blank out regions Blade never compiles as markup, preserving byte offsets
     * (and therefore line numbers) so hits can still be located in the source.
     */
    private function maskInert(string $source): string
    {
        $inert = [
            '/\{\{--.*?--\}\}/s',
            '/@php\b.*?@endphp\b/s',
            '/@verbatim\b.*?@endverbatim\b/s',
            '/<!--.*?-->/s',
        ];

        foreach ($inert as $pattern) {
            $source = preg_replace_callback(
                $pattern,
                static fn ($m) => preg_replace('/[^\n]/', ' ', $m[0]),
                $source
            );
        }

        return $source;
    }

    /**
     * Paren-balanced spans of `@directive(...)`, as [start, end] offsets.
     *
     * @return array<int, array{0: int, 1: int}>
     */
    private function directiveArgumentSpans(string $source, string $directive): array
    {
        $spans = [];
        $offset = 0;
        $needle = '@'.$directive.'(';

        while (($start = strpos($source, $needle, $offset)) !== false) {
            $depth = 0;
            $end = null;

            for ($i = $start + strlen($directive) + 1, $n = strlen($source); $i < $n; $i++) {
                if ($source[$i] === '(') {
                    $depth++;
                } elseif ($source[$i] === ')') {
                    if (--$depth === 0) {
                        $end = $i;
                        break;
                    }
                }
            }

            if ($end === null) {
                break;
            }

            $spans[] = [$start, $end];
            $offset = $end;
        }

        return $spans;
    }

    private function lineAt(string $source, int $offset): int
    {
        return substr_count($source, "\n", 0, $offset) + 1;
    }

    private function quoteLine(string $source, int $line): string
    {
        $lines = explode("\n", $source);
        $text = trim($lines[$line - 1] ?? '');

        return strlen($text) > 160 ? substr($text, 0, 157).'…' : $text;
    }

    /** @return array<string, string> */
    private function roots(): array
    {
        $roots = ['components' => __DIR__.'/../resources/views/components'];

        // The Blast storybook is a sibling app and may be absent (or not yet
        // installed) in a bare checkout; scan it only when it is there.
        $stories = __DIR__.'/../storybook/resources/views/stories';

        if (is_dir($stories)) {
            $roots['stories'] = $stories;
        }

        return $roots;
    }

    /** @return string[] */
    private function bladeFiles(string $dir): array
    {
        $files = [];

        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($it as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    private function relative(string $file): string
    {
        return ltrim(str_replace(realpath(__DIR__.'/..') ?: '', '', realpath($file) ?: $file), '/');
    }
}
