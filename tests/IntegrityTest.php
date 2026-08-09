<?php

namespace Tedi\Livewire\Tests;

use Illuminate\Support\Facades\Blade;

/**
 * Whole-library guardrails: these catch classes of defect that per-component
 * parity tests structurally cannot.
 */
class IntegrityTest extends TestCase
{
    /** @return string[] */
    protected function componentFiles(): array
    {
        $dir = __DIR__.'/../resources/views/components';
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

    /**
     * Every Blade template must compile to syntactically valid PHP.
     *
     * Catches malformed @php blocks in components that no matrix fixture or
     * parity test happens to exercise.
     */
    public function test_every_component_compiles(): void
    {
        $failures = [];
        $tmp = tempnam(sys_get_temp_dir(), 'tedi').'.php';

        foreach ($this->componentFiles() as $file) {
            $compiled = Blade::compileString(file_get_contents($file));
            file_put_contents($tmp, $compiled);

            exec('php -l '.escapeshellarg($tmp).' 2>&1', $output, $status);

            if ($status !== 0) {
                $failures[] = basename($file).': '.implode(' ', array_slice($output, 0, 2));
            }

            $output = [];
        }

        @unlink($tmp);

        $this->assertSame([], $failures,
            "Templates that compile to invalid PHP:\n  ".implode("\n  ", $failures));
    }

    /**
     * Every component must render with no optional props supplied.
     *
     * Catches variables assigned only inside an @if branch but referenced
     * unconditionally — a whole class of bug the matrices miss, because matrix
     * examples tend to pass every prop.
     */
    public function test_every_component_renders_with_no_props(): void
    {
        // Components with genuinely required props (Angular input.required()).
        // Everything NOT listed here must render bare without throwing.
        $requiredProps = [
            'icon' => 'name="add"',
            'attachment' => 'name="fail.pdf"',
            'feedback-text' => 'text="Viga"',
            'pagination' => ':page-count="3" :current-page="1"',
            'tabs.trigger' => 'id="a"',
            'horizontal-stepper-item' => 'label="Kutse"',
            'button-group-button' => 'label="Kuu"',
            'table-header-button' => 'icon="sort"',
            'header.mobile-button' => 'icon="menu"',
            'header.language' => ':languages="[\'et\' => \'Eesti\']"',
            'header.role' => ':representatives="[[\'id\' => 1, \'name\' => \'Firma\']]"'
                .' :current-representative="[\'id\' => 1, \'name\' => \'Firma\']"',
        ];

        $failures = [];

        foreach ($this->componentFiles() as $file) {
            $tag = str_replace(
                ['/', '.blade.php'], ['.', ''],
                ltrim(str_replace(realpath(__DIR__.'/../resources/views/components'), '', realpath($file)), '/')
            );

            $extra = $requiredProps[$tag] ?? '';

            try {
                Blade::render("<tedi:{$tag} {$extra}>x</tedi:{$tag}>");
            } catch (\Throwable $e) {
                $failures[] = $tag.': '.class_basename($e).' — '.
                    strtok(str_replace("\n", ' ', $e->getMessage()), '(');
            }
        }

        $this->assertSame([], $failures,
            "Components that fail to render with no props:\n  ".implode("\n  ", $failures));
    }

    /**
     * Harvest every class actually EMITTED by the variant matrices in
     * tests/fixtures/matrices and assert each exists in the stylesheet.
     *
     * This is the check that matters. test_emitted_classes_exist_in_stylesheet()
     * only sees single-quoted literals in the source, so interpolated modifiers
     * — 'tedi-button--'.$variant, 'tedi-alert--'.$type — are invisible to it,
     * and those are exactly where an invented variant name hides. Rendering the
     * matrices exercises each prop union and exposes the real class strings.
     *
     * The fixtures were the workbench gallery until the gallery was replaced by
     * the Blast storybook in storybook/; they are kept here because harvesting
     * them is the only coverage of interpolated class names.
     */
    public function test_rendered_matrix_classes_exist_in_stylesheet(): void
    {
        $css = @file_get_contents(__DIR__.'/../dist/tedi.css');

        if ($css === false) {
            $this->markTestSkipped('dist/tedi.css not built — run `npm run build`.');
        }

        view()->addNamespace('matrices', __DIR__.'/fixtures/matrices');

        $fragments = glob(__DIR__.'/fixtures/matrices/*.blade.php') ?: [];
        $this->assertNotEmpty($fragments, 'No matrix fixtures found to harvest.');

        $emitted = [];

        foreach ($fragments as $fragment) {
            $slug = basename($fragment, '.blade.php');
            $html = view('matrices::'.$slug)->render();

            preg_match_all('/class="([^"]*)"/', $html, $matches);

            foreach ($matches[1] as $attr) {
                foreach (preg_split('/\s+/', trim($attr), -1, PREG_SPLIT_NO_EMPTY) as $token) {
                    if (str_starts_with($token, 'tedi-')) {
                        $emitted[$token][$slug] = true;
                    }
                }
            }
        }

        $this->assertNotEmpty($emitted, 'Harvested no tedi-* classes — are the matrices rendering?');

        // Classes that @tedi-design-system/angular ALSO emits but for which TEDI
        // ships no CSS rule. Emitting them is correct — it is what the upstream
        // component does — so they are fidelity, not defects. Verified against
        // the Angular sources; re-verify if TEDI changes them.
        $deadUpstream = [
            'tedi-empty-state--default',
            'tedi-empty-state--separate',
            'tedi-feedback-text--hint',
            'tedi-form-field__icon',
            'tedi-text-group--vertical',
        ];

        $missing = [];

        foreach ($emitted as $class => $pages) {
            if (in_array($class, $deadUpstream, true)) {
                continue;
            }

            if (! str_contains($css, '.'.$class)) {
                $missing[] = $class.'  (in '.implode(', ', array_keys($pages)).')';
            }
        }

        // Keep the allowlist honest: an entry that is no longer emitted (or that
        // TEDI has since written a rule for) must be deleted, not left to rot.
        $stale = array_values(array_filter($deadUpstream, fn ($c) => ! isset($emitted[$c])));

        $this->assertSame([], $stale,
            "Stale \$deadUpstream entries — no longer emitted, remove them:\n  ".
            implode("\n  ", $stale));

        sort($missing);

        $this->assertSame([], $missing, sprintf(
            "Harvested %d distinct tedi-* classes from the matrices; these are not in dist/tedi.css:\n  %s",
            count($emitted), implode("\n  ", $missing)
        ));
    }

    /**
     * Every tedi-* class the library emits must actually exist in the compiled
     * stylesheet. Parity tests prove Blade emits `tedi-card--borderless`; only
     * this proves something styles it.
     */
    public function test_emitted_classes_exist_in_stylesheet(): void
    {
        $css = @file_get_contents(__DIR__.'/../dist/tedi.css');

        if ($css === false) {
            $this->markTestSkipped('dist/tedi.css not built — run `npm run build`.');
        }

        $unknown = [];

        foreach ($this->componentFiles() as $file) {
            $source = file_get_contents($file);

            // Class-literal tokens written into the template.
            preg_match_all("/'(tedi-[a-z0-9_-]+)'/", $source, $matches);

            foreach (array_unique($matches[1]) as $class) {
                // Interpolated classes (e.g. 'tedi-button--'.$variant) leave a
                // trailing "--" stem; those are covered by parity tests instead.
                if (str_ends_with($class, '-')) {
                    continue;
                }

                if (! str_contains($css, '.'.$class)) {
                    $unknown[] = basename($file, '.blade.php').' → .'.$class;
                }
            }
        }

        $this->assertSame([], array_values(array_unique($unknown)),
            "Classes emitted by components but absent from dist/tedi.css:\n  ".
            implode("\n  ", array_unique($unknown)));
    }
}
