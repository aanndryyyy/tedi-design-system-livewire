@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.44?node-id=5784-114505&m=dev',
    'args' => [
        'type' => 'primary',
        'loading' => false,
        'closable' => false,
        'ellipsis' => false,
        'content' => 'Tag',
    ],
    'argTypes' => [
        'loading' => [
            'control' => 'boolean',
            'description' => 'Whether the tag is in loading state.',
            'table' => [
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'boolean'],
                'category' => 'inputs',
            ],
        ],
        'closable' => [
            'control' => 'boolean',
            'description' => 'Whether the tag can be closed.',
            'table' => [
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'boolean'],
                'category' => 'inputs',
            ],
        ],
        'content' => [
            'control' => 'text',
            'description' => 'The content of the tag.',
            'table' => ['category' => 'story-only'],
        ],
        'type' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'danger'],
            'description' => 'The type of the tag.',
            'table' => [
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'string'],
                'category' => 'inputs',
            ],
        ],
        'ellipsis' => [
            'control' => 'radio',
            'options' => [false, 'start', 'end'],
            'description' => "Which end the label truncates from when it doesn't fit. `false` never truncates; `end` → `Long label…`; `start` → `…label`. Truncation only kicks in when the tag is width-constrained, and the full label shows in a tooltip on hover/focus.",
            'table' => [
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'TagEllipsis'],
                'category' => 'inputs',
            ],
        ],
    ],
])

<tedi:tag :type="$type" :loading="(bool) $loading" :closable="(bool) $closable" :ellipsis="$ellipsis">
    {{ $content }}
</tedi:tag>
