@storybook([
    'name' => 'With Close Button',
    'order' => 4,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/3DIVbgDcC0R4qgqWhZMfvw/TEDI-Map-Components-1.1.1?node-id=347-72394&t=UN6CIlRIfpadwwCZ-0',
    'args' => [
        'direction' => 'horizontal',
        'highlightedSide' => '',
        'closable' => true,
        'initialRatio' => 50,
        'minRatio' => 20,
        'maxRatio' => 80,
    ],
    'argTypes' => [
        'direction' => ['control' => 'inline-radio', 'options' => ['horizontal', 'vertical'], 'description' => 'Layout axis: `horizontal` places panes side by side, `vertical` stacks them.', 'table' => ['category' => 'Required props', 'type' => ['summary' => 'horizontal | vertical']]],
        'highlightedSide' => ['control' => 'select', 'options' => ['', 'first', 'second'], 'description' => 'Draws an accent ring around the given pane to mark it as active.', 'table' => ['type' => ['summary' => 'first | second']]],
        'closable' => ['control' => 'boolean', 'description' => 'Renders a close button next to the divider, which dispatches `tedi:close` (upstream: when onClose is provided).', 'table' => ['defaultValue' => ['summary' => 'false']]],
        'initialRatio' => ['control' => 'number', 'description' => 'Initial size of the first pane as a percentage of the container, clamped to `minRatio`–`maxRatio`.', 'table' => ['defaultValue' => ['summary' => '50']]],
        'minRatio' => ['control' => 'number', 'description' => 'Minimum size of the first pane, as a percentage of the container.', 'table' => ['defaultValue' => ['summary' => '20']]],
        'maxRatio' => ['control' => 'number', 'description' => 'Maximum size of the first pane, as a percentage of the container.', 'table' => ['defaultValue' => ['summary' => '80']]],
    ],
])

{{-- Upstream's Template and demoPane. tedi:ratio-change and tedi:close are
     DOM events; this story only renders. --}}
<div style="width: 100%; max-width: 800px; height: 400px">
    <tedi:split-pane
        :direction="$direction"
        :highlighted-side="$highlightedSide ?: null"
        :closable="(bool) $closable"
        :initial-ratio="(int) $initialRatio"
        :min-ratio="(int) $minRatio"
        :max-ratio="(int) $maxRatio"
    >
        <x-slot:first><div style="display: grid; width: 100%; height: 100%; place-items: center; background: var(--tedi-primary-100)">First</div></x-slot:first>
        <x-slot:second><div style="display: grid; width: 100%; height: 100%; place-items: center; background: var(--tedi-primary-200)">Second</div></x-slot:second>
    </tedi:split-pane>
</div>
