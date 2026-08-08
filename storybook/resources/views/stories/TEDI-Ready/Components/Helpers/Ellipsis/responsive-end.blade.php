@storybook([
    'name' => 'Responsive End',
    'order' => 2,
    'status' => 'subset',
    'args' => [
        'lineClamp' => 1,
        'position' => 'end',
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

{{-- Angular resizes the window to show the clamp reacting live; this port renders the static clamped markup only. --}}
<tedi:ellipsis :line-clamp="$lineClamp" :position="$position">
    Any inline <b>content (even bold)</b>, that is too long for the wrapper and dont fit in x number of rows
</tedi:ellipsis>
