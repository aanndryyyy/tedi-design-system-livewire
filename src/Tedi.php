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
     * Two providers exist, and the NEAREST one wins, as with Angular's
     * injector:
     *
     *   - `tedi:form-field` — its context is computed from what the consumer
     *     wrote on it, with form-field's own `@props` defaults applied here
     *     (`size` 'default', `clearable` true; FIELD_DEFAULTS below, pinned
     *     against form-field.blade.php by tests/AwareTest.php).
     *   - `tedi:date-field` (and `tedi:date-time-field`) — Angular's date field
     *     provides its own `childContext` whose `ownsSurface` is always true:
     *     the date field paints the surface, never the text field inside its
     *     date input. That text field renders in the date field's own
     *     TEMPLATE, where the component stack no longer shows the date field,
     *     so it is found through trackRender() instead (RENDER_PROVIDERS).
     *
     * `$control` describes the calling control, for the half of the contract
     * Angular runs the other way (`contentChild(TEDI_FORM_FIELD_CONTROL)`):
     * whether it can be reset (`reset`, which is what earns it the field's
     * clear button), its `value`, `disabled`, `readOnly` and `invalid`. When
     * the provider is a form-field the first control to call registers itself
     * in the form-field's component-data frame, under `__tediFieldControl`.
     * The form-field's own template renders after its slot, and Laravel merges
     * that frame into the template's data, so it arrives there as the
     * variable `$__tediFieldControl` — never in `$attributes`. The shared
     * character-counter id travels the same way, as `$__tediFieldId`.
     *
     * Returns null outside any provider. Otherwise:
     *   provider     'form-field' | 'date-input'
     *   size         'small' | 'default' | 'large'
     *   ownsSurface  bool — the field's box paints the surface, not the control
     *   invalid      bool — the form-field's `invalid`, an exceeded character
     *                limit, or an enclosing input group's `invalid`
     *   valid        bool — the form-field's `valid`
     *   disabled     bool — the form-field's `disabled`, or an enclosing
     *                input group's
     *   clearable    bool
     *   describedBy  string[] — ids the control adds to its aria-describedby
     *
     * The form-field is found on the view factory's component stack, so it is
     * seen by a control rendered in the form-field's SLOT, which is where a
     * form control sits; by the time a component's own template renders, it
     * has already been popped from that stack. Like `@aware`, the form-field
     * half only sees what the consumer explicitly wrote — never its `@props`
     * defaults — so those are applied here from FIELD_DEFAULTS. Unlike
     * `@aware`, it reads the NEAREST form-field only, never `size` from some
     * unrelated ancestor (a modal, a button group).
     *
     * @param  array{reset?: bool, value?: mixed, disabled?: bool, readOnly?: bool, invalid?: bool}  $control
     * @return array<string, mixed>|null
     */
    public static function fieldContext(\Illuminate\View\Factory $env, array $control = []): ?array
    {
        // The nearest provider whose TEMPLATE is rendering right now (see
        // trackRender()). Component frames pushed after it started belong to
        // its own template and so sit nearer to the caller than it does.
        // A provider's own template (renderCount equal to its depth) is not
        // inside itself, so only shallower entries count.
        $depth = (fn () => $this->renderCount)->call($env);
        $ancestors = array_filter(static::$rendering, fn (array $entry) => $entry['depth'] < $depth);
        $rendering = end($ancestors) ?: null;

        return (function () use ($control, $rendering): ?array {
            $floor = $rendering['frames'] ?? 0;

            for ($i = count($this->componentStack) - 1; $i >= $floor; $i--) {
                $name = $this->componentStack[$i];

                if ($name !== 'tedi::components.form-field') {
                    continue;
                }

                // What `@aware` would see from this frame down: the first
                // frame at or above $i that was written the key.
                $aware = function (string $key, mixed $default) use ($i) {
                    for ($j = $i; $j >= 0; $j--) {
                        if (array_key_exists($key, $this->componentData[$j] ?? [])) {
                            return $this->componentData[$j][$key];
                        }
                    }

                    return $default;
                };

                $field = ($this->componentData[$i] ?? []) + Tedi::FIELD_DEFAULTS;

                if ($control !== [] && ! isset($this->componentData[$i]['__tediFieldControl'])) {
                    $this->componentData[$i]['__tediFieldControl'] = $control;
                }

                $registered = $this->componentData[$i]['__tediFieldControl'] ?? $control;

                $characterLimit = $field['characterLimit'];
                $registeredValue = $registered['value'] ?? '';
                // Only a string value has a length to count; a date field in
                // `multiple` / `range` mode registers an array.
                $characterCount = $field['characterCount'] ?? (is_scalar($registeredValue) ? mb_strlen((string) $registeredValue) : 0);
                $exceeded = $characterLimit !== null && $characterCount > $characterLimit;

                $describedBy = [];
                if ($characterLimit !== null) {
                    $this->componentData[$i]['__tediFieldId'] ??= Tedi::id('tedi-form-field');
                    $describedBy[] = $this->componentData[$i]['__tediFieldId'].'-character-count';
                }

                $clearable = (bool) $field['clearable'];

                return [
                    'provider' => 'form-field',
                    'size' => Tedi::fieldSize($field['size']),
                    // hasBox(): an icon, or a clear button for a control that
                    // can be reset.
                    'ownsSurface' => ! empty($field['icon']) || ($clearable && ! empty($control['reset'])),
                    'invalid' => (bool) $aware('invalid', false) || $exceeded,
                    'valid' => (bool) $field['valid'],
                    'disabled' => (bool) $aware('disabled', false),
                    'clearable' => $clearable,
                    'describedBy' => $describedBy,
                ];
            }

            return $rendering !== null ? Tedi::dateInputContext() : null;
        })->call($env);
    }

    /**
     * Angular's date field `childContext`: the date field paints the surface,
     * never the input inside it, and nothing inside asks a form-field for a
     * clear button (the date field owns its own).
     *
     * @internal
     */
    public static function dateInputContext(): array
    {
        return [
            'provider' => 'date-input',
            'size' => 'default',
            'ownsSurface' => true,
            'invalid' => false,
            'valid' => false,
            'disabled' => false,
            'clearable' => true,
            'describedBy' => [],
        ];
    }

    /**
     * Views that provide a field context to controls in their own template.
     * The date field's text field sits in the date input's template, which
     * sits in the date field's; keying on the date field rather than the date
     * input leaves a standalone `tedi:date-input` with no context, so its text
     * field paints its own surface as Angular's would. The React-sourced date
     * time field composes the same date input and owns its surface too.
     */
    private const RENDER_PROVIDERS = ['tedi::components.date-field', 'tedi::components.date-time-field'];

    /**
     * Providers currently mid-render, outermost first:
     * ['depth' => render depth, 'frames' => component-stack size when it began].
     *
     * @var list<array{depth: int, frames: int}>
     */
    private static array $rendering = [];

    /**
     * Called for every view as it is composed (TediServiceProvider). Views
     * render depth-first, so a view starting at depth N proves that every
     * provider recorded at depth N or deeper has finished; what remains is
     * exactly the chain of providers the new view is rendering inside.
     *
     * @internal
     */
    public static function trackRender(\Illuminate\View\Factory $env, string $name): void
    {
        [$depth, $frames] = (fn () => [$this->renderCount, count($this->componentStack)])->call($env);

        static::$rendering = array_values(array_filter(
            static::$rendering,
            fn (array $entry) => $entry['depth'] < $depth,
        ));

        if (in_array($name, self::RENDER_PROVIDERS, true)) {
            static::$rendering[] = ['depth' => $depth, 'frames' => $frames];
        }
    }

    /**
     * form-field's `@props` defaults for the props fieldContext() reads.
     * tests/AwareTest.php pins these against form-field.blade.php.
     */
    public const FIELD_DEFAULTS = [
        'size' => 'default',
        'icon' => null,
        'clearable' => true,
        'characterLimit' => null,
        'characterCount' => null,
        'valid' => false,
    ];

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
