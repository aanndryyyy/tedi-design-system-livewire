@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'ngContent' => 'Seda välja kasutatakse teie isikusamasuse tuvastamiseks.',
        'position' => 'top',
        'openWith' => 'both',
        'maxWidth' => 'medium',
        'color' => 'primary',
        'ariaLabel' => '',
        'description' => '',
    ],
    'argTypes' => [
        'ngContent' => [
            'name' => 'ng-content',
            'description' => 'Tooltip content',
            'control' => 'text',
            'table' => [
                'type' => ['summary' => 'string'],
            ],
        ],
        'position' => [
            'control' => 'select',
            'options' => [
                'top', 'top-start', 'top-end',
                'bottom', 'bottom-start', 'bottom-end',
                'left', 'left-start', 'left-end',
                'right', 'right-start', 'right-end',
            ],
            'description' => 'Position of the tooltip relative to the info button.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'top'],
                'type' => ['summary' => 'TooltipPosition'],
            ],
        ],
        'openWith' => [
            'control' => 'radio',
            'options' => ['hover', 'click', 'both'],
            'description' => 'How the tooltip can be opened.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'both'],
                'type' => ['summary' => 'TooltipOpenWith'],
            ],
        ],
        'maxWidth' => [
            'control' => 'radio',
            'options' => ['none', 'small', 'medium', 'large'],
            'description' => 'Max width of the tooltip content.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'medium'],
                'type' => ['summary' => 'TooltipWidth'],
            ],
        ],
        'color' => [
            'control' => 'radio',
            'options' => ['primary', 'inverted'],
            'description' => 'Color of the info button. Use `inverted` on dark backgrounds.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'primary \ninverted'],
            ],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible name for the info button. Defaults to the translated info-button label.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'description' => [
            'control' => 'text',
            'description' => 'sr-only description the info button is aria-describedby. Angular derives it from the projected content at runtime; Blade cannot introspect its slot, so it is an explicit prop (CONVENTIONS.md §5).',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

<tedi:info-tooltip
    :position="$position"
    :open-with="$openWith"
    :max-width="$maxWidth"
    :color="$color"
    :aria-label="$ariaLabel ?: null"
    :description="$description ?: null"
>{{ $ngContent }}</tedi:info-tooltip>
