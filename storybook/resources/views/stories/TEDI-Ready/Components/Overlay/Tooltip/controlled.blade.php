{{--
    Angular's `open` is a two-way model; Blade's `open` is the *initial* state
    (CONVENTIONS.md §3 — `@props` cannot tell an explicit `null`/`undefined`
    from "not passed"). Live control is Alpine's: `toggle()` on the
    `tediOverlay` scope, which the trigger's slot sits inside.

    `trackPosition` is not ported — see tooltip.blade.php's header.
--}}
@storybook([
    'name' => 'Controlled (open + trackPosition)',
    'order' => 6,
    'status' => 'subset',
    'args' => [
        'position' => 'top',
        'open' => false,
    ],
    'argTypes' => [
        'position' => [
            'control' => 'select',
            'description' => 'The position of the tooltip relative to the trigger element.',
            'options' => [
                'auto', 'auto-start', 'auto-end',
                'top', 'top-start', 'top-end',
                'bottom', 'bottom-start', 'bottom-end',
                'right', 'right-start', 'right-end',
                'left', 'left-start', 'left-end',
            ],
            'table' => [
                'category' => 'tooltip',
                'type' => ['summary' => 'TooltipPosition'],
                'defaultValue' => ['summary' => 'top'],
            ],
        ],
        'open' => [
            'control' => 'boolean',
            'description' => 'Initial open state. Pair with openWith="none" and drive it from Alpine.',
            'table' => [
                'category' => 'tooltip',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

<tedi:row justify-items="center">
    <tedi:col>
        <tedi:tooltip open-with="none" :position="$position" :open="(bool) $open">
            <tedi:tooltip-trigger>
                <tedi:button
                    x-on:click="toggle()"
                    x-text="open ? 'Hide tooltip' : 'Show tooltip'"
                >Show tooltip</tedi:button>
            </tedi:tooltip-trigger>
            <tedi:tooltip-content>Controlled tooltip content</tedi:tooltip-content>
        </tedi:tooltip>
    </tedi:col>
</tedi:row>
