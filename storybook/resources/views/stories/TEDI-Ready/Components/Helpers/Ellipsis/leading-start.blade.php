@storybook([
    'name' => 'Leading Start',
    'order' => 3,
    'status' => 'subset',
    'args' => [
        'lineClamp' => 2,
        'position' => 'start',
    ],
    'argTypes' => [
        'lineClamp' => [
            'control' => 'number',
            'description' => 'Maximum number of lines before truncating. End (multi-line) only.',
        ],
        'position' => [
            'control' => 'radio',
            'options' => ['start', 'end'],
            'description' => "Ellipsis position. 'start' = leading, single-line. 'end' = trailing, multi-line.",
        ],
    ],
])

<div style="max-width:200px">
    <tedi:ellipsis :line-clamp="$lineClamp" :position="$position">
        Any inline <b>content (even bold)</b>, that is too long for the wrapper and dont fit in x number of rows
    </tedi:ellipsis>
</div>
