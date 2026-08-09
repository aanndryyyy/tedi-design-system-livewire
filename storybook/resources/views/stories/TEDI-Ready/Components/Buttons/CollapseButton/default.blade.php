{{--
    Angular renders this story through a `tedi-collapse-button-demo` wrapper that
    exists only to own the `open` signal and re-set it from `openChange`. The
    Blade component owns that state itself in Alpine (CONVENTIONS.md §8), so the
    wrapper has no equivalent and the component is used directly.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.49.74?node-id=15433-138256&m=dev',
    'args' => [
        'openText' => 'Open',
        'closeText' => 'Close',
        'hideText' => false,
        'arrowType' => 'default',
        'size' => 'default',
        'inverted' => false,
        'ariaLabel' => 'Toggle details',
    ],
    'argTypes' => [
        'openText' => [
            'description' => 'Label shown when collapsed.',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'closeText' => [
            'description' => 'Label shown when expanded.',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'hideText' => [
            'description' => 'Hide the label and render the chevron only.',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'arrowType' => [
            'description' => 'Chevron style. Only takes effect with `hideText`.',
            'control' => ['type' => 'inline-radio'],
            'options' => ['default', 'secondary'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'CollapseButtonArrowType'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'size' => [
            'description' => 'Visual size.',
            'control' => ['type' => 'inline-radio'],
            'options' => ['default', 'small'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'CollapseButtonSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'inverted' => [
            'description' => 'Use light text and icon for placement on a dark / brand background.',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'ariaLabel' => [
            'description' => 'Accessible label. Required when `hideText` is true.',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

<tedi:collapse-button
    :open-text="$openText ?: null"
    :close-text="$closeText ?: null"
    :hide-text="(bool) $hideText"
    :arrow-type="$arrowType"
    :size="$size"
    :inverted="(bool) $inverted"
    :aria-label="$ariaLabel ?: null"
/>
