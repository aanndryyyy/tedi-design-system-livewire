@storybook([
    'name' => 'Skeleton Height',
    'order' => 2,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2188-34298&m=dev',
    'args' => [
        'label' => 'Loading something',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
    ],
])

<tedi:skeleton :label="$label">
    <tedi:vertical-spacing>
        <tedi:skeleton-block height="p" />
        <tedi:skeleton-block height="h3" />
        <tedi:skeleton-block height="h2" />
        <tedi:skeleton-block height="h1" />
        <tedi:skeleton-block :height="100" />
    </tedi:vertical-spacing>
</tedi:skeleton>
