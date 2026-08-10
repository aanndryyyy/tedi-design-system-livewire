@storybook([
    'name' => 'Skeleton Width',
    'order' => 3,
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
        <tedi:skeleton-block :width="50" height="p" />
        <tedi:skeleton-block :width="75" height="h3" />
        <tedi:skeleton-block width="36px" height="h2" />
        <tedi:skeleton-block width="auto" height="h1" />
    </tedi:vertical-spacing>
</tedi:skeleton>
