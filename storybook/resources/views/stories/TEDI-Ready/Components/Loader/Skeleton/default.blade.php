@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2188-34298&m=dev',
    'args' => [
        'label' => 'Loading something',
        'blockWidth' => 50,
        'blockHeight' => 'h2',
    ],
    'argTypes' => [
        'label' => [
            'control' => 'text',
            'description' => 'The accessibility label announced by screen readers when the skeleton component mounts. This message informs users that content is loading. If omitted, all skeletons on the page are combined into a single status message. Pass an empty string to render no live region at all.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'skeleton.loading'],
            ],
        ],
        'blockWidth' => [
            'control' => 'text',
            'description' => 'Width of the block. A bare number is a percentage of the parent, a value ending in px is used verbatim, and auto leaves it unset.',
            'table' => ['category' => 'tedi:skeleton-block'],
        ],
        'blockHeight' => [
            'control' => 'select',
            'options' => ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'description' => 'Height of the block, as a typography token. A number is treated as pixels instead.',
            'table' => ['category' => 'tedi:skeleton-block'],
        ],
    ],
])

<tedi:skeleton :label="$label">
    <tedi:skeleton-block :width="$blockWidth" :height="$blockHeight" />
</tedi:skeleton>
