@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'args' => [
        'lineClamp' => 2,
        'position' => 'end',
    ],
    'argTypes' => [
        'lineClamp' => [
            'control' => 'number',
            'description' => 'Maximum number of lines before truncating. End (multi-line) only.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => '2'],
                'type' => ['summary' => 'number'],
            ],
        ],
        'position' => [
            'control' => 'radio',
            'options' => ['start', 'end'],
            'description' => "Ellipsis position. 'start' = leading, single-line. 'end' = trailing, multi-line.",
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => "'end'"],
                'type' => ['summary' => "'start' | 'end'"],
            ],
        ],
    ],
])

{{--
    Angular's `tooltip` input has no effect here — the hover/focus tooltip
    that reveals the full text requires a ResizeObserver measurement, which
    this port doesn't do (CONTRACT.md §5, status: subset). Only the CSS
    line-clamp is ported.
--}}
<div style="max-width:200px">
    <tedi:ellipsis :line-clamp="$lineClamp" :position="$position">
        Any inline <b>content (even bold)</b>, that is too long for the wrapper and dont fit in x number of rows
    </tedi:ellipsis>
</div>
