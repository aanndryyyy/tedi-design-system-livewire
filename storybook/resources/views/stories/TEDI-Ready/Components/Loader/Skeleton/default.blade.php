@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.75.90?node-id=2188-34298&m=dev',
    'args' => [
        'label' => 'Loading something',
        'completedLabel' => '',
        'labelDelay' => 200,
        'width' => 50,
        'height' => 'h2',
    ],
    'argTypes' => [
        'label' => [
            'description' => 'Announced once the skeleton has been on screen for `labelDelay`. Name what is loading.',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => 'skeleton.loading translation'],
            ],
        ],
        'completedLabel' => [
            'description' => 'Announced when the skeleton is removed. Silent if `label` was never announced.',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => 'skeleton.loading-completed translation'],
            ],
        ],
        'labelDelay' => [
            'description' => 'Delay in ms before `label` is announced.',
            'control' => 'number',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '200'],
            ],
        ],
        'width' => [
            'description' => 'Width of the block. A number is a percentage of the container, a `px` string is absolute, `auto` fills it.',
            'control' => 'text',
            'table' => [
                'category' => 'SkeletonBlock inputs',
                'type' => ['summary' => 'SkeletonBlockWidth'],
                'defaultValue' => ['summary' => 'auto'],
            ],
        ],
        'height' => [
            'description' => "Height of the block. A text style takes that style's line height; a number is a height in px.",
            'control' => 'select',
            'options' => ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'table' => [
                'category' => 'SkeletonBlock inputs',
                'type' => ['summary' => 'SkeletonBlockHeight'],
                'defaultValue' => ['summary' => 'p'],
            ],
        ],
    ],
])

<tedi:skeleton
    :label="$label ?: null"
    :completed-label="$completedLabel ?: null"
    :label-delay="(int) $labelDelay"
>
    <tedi:skeleton-block :width="$width" :height="$height" />
</tedi:skeleton>
