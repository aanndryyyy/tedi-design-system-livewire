@storybook([
    'name' => 'Resize from Right',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/3DIVbgDcC0R4qgqWhZMfvw/Veera-Map-Design-System?node-id=88-17734&m=dev',
    'args' => [
        'minWidth' => 300,
        'maxWidth' => 600,
        'initialWidth' => 350,
        'minHeight' => 200,
        'maxHeight' => 600,
        'initialHeight' => 350,
        'handlePosition' => 'right',
        'showIndicator' => true,
    ],
    'argTypes' => [
        'minWidth' => ['control' => 'number', 'description' => 'Minimum allowed width in pixels. Optional.', 'table' => ['defaultValue' => ['summary' => '300']]],
        'maxWidth' => ['control' => 'number', 'description' => 'Maximum allowed width in pixels. Optional.', 'table' => ['defaultValue' => ['summary' => '600']]],
        'initialWidth' => ['control' => 'number', 'description' => 'Initial width of the container in pixels. Optional.', 'table' => ['defaultValue' => ['summary' => '350']]],
        'minHeight' => ['control' => 'number', 'description' => 'Minimum allowed height in pixels. Optional.', 'table' => ['defaultValue' => ['summary' => '200']]],
        'maxHeight' => ['control' => 'number', 'description' => 'Maximum allowed height in pixels. Optional.', 'table' => ['defaultValue' => ['summary' => '600']]],
        'initialHeight' => ['control' => 'number', 'description' => 'Initial height of the container in pixels. Optional.', 'table' => ['defaultValue' => ['summary' => '350']]],
        'handlePosition' => ['control' => 'select', 'options' => ['right', 'left', 'top', 'bottom'], 'description' => 'Position of the resize handle. Can be `\'right\'`, `\'left\'`, `\'top\'`, or `\'bottom\'`. Determines the direction from which resizing is possible.', 'table' => ['defaultValue' => ['summary' => '\'right\'']]],
        'showIndicator' => ['control' => 'boolean', 'description' => 'Whether to show a visual resize indicator (e.g., drag handle). Optional.', 'table' => ['defaultValue' => ['summary' => 'false']]],
    ],
])

{{-- Upstream's children is the deprecated community Placeholder, which is not
     ported; its replacement, tedi:empty-state, stands in for it. --}}
<tedi:resizer
    :handle-position="$handlePosition"
    :show-indicator="(bool) $showIndicator"
    :min-width="(int) $minWidth"
    :max-width="(int) $maxWidth"
    :initial-width="(int) $initialWidth"
    :min-height="(int) $minHeight"
    :max-height="(int) $maxHeight"
    :initial-height="(int) $initialHeight"
>
    <tedi:empty-state>You have no data to display.</tedi:empty-state>
</tedi:resizer>
