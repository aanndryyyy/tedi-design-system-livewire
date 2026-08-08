<?php

namespace Tedi\Livewire;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TediServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tedi.php', 'tedi');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tedi');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'tedi');

        // Registers the standard <x-tedi::button /> namespace, resolving to
        // resources/views/components/button.blade.php
        Blade::anonymousComponentNamespace(__DIR__.'/../resources/views/components', 'tedi');

        $this->registerShortSyntax();
        $this->registerDirectives();
        $this->registerPublishing();
    }

    /**
     * Enable the Flux-style short tag syntax: <tedi:button>…</tedi:button>.
     *
     * Laravel's ComponentTagCompiler only recognises the "x-" / "x:" prefix, so
     * <tedi:foo> is rewritten into <x-tedi::foo> before Blade compiles component
     * tags. Both syntaxes therefore work, and the short one is purely sugar over
     * the registered anonymous component namespace.
     *
     * This must use prepareStringsForCompilationUsing() rather than
     * precompiler(): precompiler callbacks run *after* compileComponentTags(),
     * which is too late for the rewritten tag to be compiled.
     */
    protected function registerShortSyntax(): void
    {
        Blade::prepareStringsForCompilationUsing(function (string $template): string {
            return preg_replace(
                '/<(\/?)\s*tedi:([\w\-.:]+)/',
                '<$1x-tedi::$2',
                $template
            );
        });
    }

    protected function registerDirectives(): void
    {
        // @tediStyles — emits the <link> tag for the compiled stylesheet.
        Blade::directive('tediStyles', fn () => "<?php echo \Tedi\Livewire\Tedi::styles(); ?>");

        // @tediScripts — emits the Alpine behaviours bundle.
        Blade::directive('tediScripts', fn () => "<?php echo \Tedi\Livewire\Tedi::scripts(); ?>");
    }

    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/tedi'),
        ], 'tedi-assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/tedi'),
        ], 'tedi-views');

        $this->publishes([
            __DIR__.'/../config/tedi.php' => config_path('tedi.php'),
        ], 'tedi-config');
    }
}
