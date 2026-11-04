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
     * The field context a form control sits in — the Blade stand-in for
     * Angular's `inject(TEDI_FIELD_CONTEXT, { optional: true })`
     * (angular/src/tedi/components/form/form-field/field-context.token.ts).
     *
     * The context is a View\FieldContext. The nearest one is found with
     * `$env->getConsumableComponentData('fieldContext')`, the public lookup
     * `@aware` makes: the calling control's own `fieldContext` prop first,
     * then the component frames on the stack, nearest first. The key is
     * unique to the providers, so the nearest hit is the nearest provider:
     *
     *   - `tedi:form-field` (View\Components\FormField) builds its context in
     *     its constructor, before its slot renders, from what the consumer
     *     wrote on it plus its defaults (`size` 'default', `clearable` true;
     *     `disabled` / `invalid` with `@aware` semantics), and carries it in
     *     its data frame. A control in its SLOT finds it there. Unlike
     *     `@aware`, the size comes from the nearest form-field only, never
     *     from an unrelated ancestor (a modal, a button group).
     *   - `tedi:date-field` / `tedi:date-time-field` build Angular's
     *     `childContext` (FieldContext::child(), `ownsSurface` always true) and
     *     pass it EXPLICITLY, as the `field-context` prop, through
     *     `tedi:date-input` to its text field: a control in a component's own
     *     template cannot find that component on the stack. A standalone
     *     date input passes on whatever context it sits in itself.
     *
     * `$control` describes the calling control, for the half of the contract
     * Angular runs the other way (`contentChild(TEDI_FORM_FIELD_CONTROL)`):
     * whether it can be reset (`reset`, which is what earns it the field's
     * clear button), its `value`, `disabled`, `readOnly` and `invalid`. The
     * first control to call registers itself on the context
     * (FieldContext::register()); the form-field's view renders after its
     * slot and reads it back. An empty `$control` registers nothing.
     *
     * Returns null outside any provider, otherwise FieldContext::toArray():
     * provider, size, ownsSurface, invalid, valid, disabled, clearable,
     * describedBy (the character counter's id, for aria-describedby).
     *
     * @param  array{reset?: bool, value?: mixed, disabled?: bool, readOnly?: bool, invalid?: bool}  $control
     * @return array<string, mixed>|null
     */
    public static function fieldContext(\Illuminate\View\Factory $env, array $control = []): ?array
    {
        $context = $env->getConsumableComponentData('fieldContext');

        if (! $context instanceof View\FieldContext) {
            return null;
        }

        $context->register($control);

        return $context->toArray();
    }

    /** Per-process random part of the table-of-contents numbering markers. */
    private static ?string $tocToken = null;

    /**
     * A placeholder table-of-contents items emit for their number, rewritten
     * by the enclosing item or root once the order is known. The random part
     * keeps consumer text that happens to contain the marker's fixed prefix
     * from being rewritten.
     */
    public static function tocMarker(string $kind): string
    {
        self::$tocToken ??= bin2hex(random_bytes(6));

        return '__TEDI_TOC_'.$kind.'_'.self::$tocToken.'__';
    }

    /** Matches both markers; group 1 is NUMBER or PREFIX. */
    public static function tocMarkerPattern(): string
    {
        self::$tocToken ??= bin2hex(random_bytes(6));

        return '/__TEDI_TOC_(NUMBER|PREFIX)_'.self::$tocToken.'__/';
    }

    /** A context size: anything but small/large (null included) is 'default'. */
    public static function fieldSize(mixed $size): string
    {
        return in_array($size, ['small', 'large'], true) ? $size : 'default';
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
}
