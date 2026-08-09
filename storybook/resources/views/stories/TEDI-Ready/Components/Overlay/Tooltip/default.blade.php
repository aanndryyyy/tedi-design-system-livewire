@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=5797-117363&m=dev',
    'args' => [
        'position' => 'top',
        'preventOverflow' => true,
        'timeoutDelay' => 100,
        'offset' => 4,
        'interactive' => true,
        'maxWidth' => 'medium',
        'openWith' => 'both',
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
        'openWith' => [
            'control' => 'radio',
            'description' => 'Which interactions open the tooltip. Use \'none\' for full external control via `open`.',
            'options' => ['hover', 'click', 'both', 'none'],
            'table' => [
                'category' => 'tooltip',
                'type' => ['summary' => 'TooltipOpenWith'],
                'defaultValue' => ['summary' => 'both'],
            ],
        ],
        'preventOverflow' => [
            'control' => 'boolean',
            'description' => 'Should position to opposite direction when overflowing screen?',
            'table' => [
                'category' => 'tooltip',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'timeoutDelay' => [
            'control' => 'number',
            'description' => 'Delay time (in ms) for closing tooltip when not hovering trigger or content.',
            'table' => [
                'category' => 'tooltip',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '100'],
            ],
        ],
        'offset' => [
            'control' => 'number',
            'description' => 'Extra distance (px) between the tooltip and its trigger, on top of the arrow allowance. Set to 0 to sit the tooltip directly against the trigger.',
            'table' => [
                'category' => 'tooltip',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '4'],
            ],
        ],
        'interactive' => [
            'control' => 'boolean',
            'description' => 'When false, the trigger is a pure positioning anchor (no synthesized tabindex, focus ring or aria-describedby). Set on tedi-tooltip-trigger.',
            'table' => [
                'category' => 'tooltip-trigger inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'maxWidth' => [
            'control' => 'select',
            'options' => ['none', 'small', 'medium', 'large'],
            'description' => 'The width of the tooltip.',
            'table' => [
                'category' => 'tooltip-content inputs',
                'type' => ['summary' => 'TooltipWidth'],
                'defaultValue' => ['summary' => 'medium'],
            ],
        ],
    ],
])

<tedi:tooltip
    :position="$position"
    :timeout-delay="(int) $timeoutDelay"
    :offset="(int) $offset"
    :prevent-overflow="(bool) $preventOverflow"
    :open-with="$openWith"
>
    <tedi:tooltip-trigger :interactive="(bool) $interactive">
        <tedi:info-button />
    </tedi:tooltip-trigger>
    <tedi:tooltip-content :max-width="$maxWidth">
        This is tooltip content. The quick brown fox jumps over the lazy dog.
    </tedi:tooltip-content>
</tedi:tooltip>
