@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4514-63815&m=dev',
    'args' => [
        'size' => 'default',
        'iconSize' => 24,
        'icon' => 'close',
        'showTitle' => true,
    ],
    'argTypes' => [
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'The size of the button.',
            'table' => ['defaultValue' => ['summary' => 'default']],
        ],
        'iconSize' => [
            'control' => 'radio',
            'options' => [24, 18],
            'description' => 'The size of the icon inside the button in pixels.',
            'table' => ['defaultValue' => ['summary' => '24']],
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Material Symbols icon rendered inside the button. Override for other closing-like actions such as delete/remove (e.g. `delete`) and provide a matching `ariaLabel`.',
            'table' => ['defaultValue' => ['summary' => 'close']],
        ],
        'showTitle' => [
            'control' => 'boolean',
            'description' => 'Render the label as a native `title` attribute. Set to `false` when the button is wrapped in a `tedi-tooltip` so the native tooltip does not double the custom one.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
    ],
])

<tedi:closing-button :size="$size" :icon-size="$iconSize" :icon="$icon" :show-title="(bool) $showTitle" />
