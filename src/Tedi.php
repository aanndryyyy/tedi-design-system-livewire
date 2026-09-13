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

    /**
     * Resolve a TEDI max-width value to a CSS length.
     *
     * Port of resolveMaxWidth() in react/src/tedi/components/layout/top-nav/top-nav.tsx,
     * against BREAKPOINT_WIDTHS. A breakpoint name becomes that breakpoint's
     * min-width, a bare number becomes px, anything else passes through, and
     * 'none' / 0 / null mean "no constraint" and return null.
     *
     * It lives here rather than in a template because <tedi:top-nav> and
     * <tedi:top-nav-submenu> both need the same answer for the same input.
     */
    public static function maxWidth(mixed $value): ?string
    {
        // BREAKPOINT_WIDTHS, react/src/tedi/helpers.
        $breakpoints = [
            'sm' => '36rem',
            'md' => '48rem',
            'lg' => '62rem',
            'xl' => '75rem',
            'xxl' => '87.5rem',
        ];

        if ($value === null || $value === 'none' || $value === 0 || $value === '0') {
            return null;
        }

        if (is_string($value) && isset($breakpoints[$value])) {
            return $breakpoints[$value];
        }

        if (is_numeric($value)) {
            return $value.'px';
        }

        return (string) $value;
    }

    /**
     * Whether a nested field should skip painting `tedi-field-surface`.
     *
     * Angular's TEDI_FIELD_CONTEXT. Blade `@aware` cannot see values assigned
     * later in the parent view — the nested control renders while the parent's
     * slot is captured, so only the parent's *tag* attributes are visible.
     * Form-field's box is `icon || clearable`, both of which are those tags.
     */
    public static function fieldOwnsSurface(bool $explicit = false): bool
    {
        if ($explicit) {
            return true;
        }

        $view = app('view');

        return (bool) $view->getConsumableComponentData('icon', null)
            || (bool) $view->getConsumableComponentData('clearable', false);
    }
}
