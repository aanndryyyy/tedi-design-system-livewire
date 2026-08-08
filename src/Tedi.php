<?php

namespace Tedi\Livewire;

use Illuminate\Support\HtmlString;

class Tedi
{
    /**
     * Emit the stylesheet link tag for the compiled TEDI stylesheet.
     */
    public static function styles(): HtmlString
    {
        $href = asset(config('tedi.asset_path', 'vendor/tedi').'/tedi.css');

        return new HtmlString('<link rel="stylesheet" href="'.e($href).'">');
    }

    /**
     * Emit the Alpine behaviours bundle for interactive components.
     */
    public static function scripts(): HtmlString
    {
        // Most components inline their Alpine behaviour, so the bundle is
        // optional. Emit nothing rather than a 404-ing <script> when it hasn't
        // been published.
        if (! file_exists(public_path(config('tedi.asset_path', 'vendor/tedi').'/tedi.js'))) {
            return new HtmlString('');
        }

        $src = asset(config('tedi.asset_path', 'vendor/tedi').'/tedi.js');

        return new HtmlString('<script src="'.e($src).'" defer></script>');
    }

    /**
     * Build a deterministic-ish unique DOM id, mirroring Angular's _IdGenerator.
     */
    public static function id(string $prefix = 'tedi'): string
    {
        return $prefix.'-'.substr(md5(uniqid('', true)), 0, 8);
    }
}
