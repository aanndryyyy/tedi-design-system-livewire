@storybook([
    'name' => 'Height',
    'order' => 2,
    'status' => 'stable',
])

<tedi:skeleton label="Loading something">
    <tedi:skeleton-block height="p" />
    <tedi:skeleton-block height="h3" />
    <tedi:skeleton-block height="h2" />
    <tedi:skeleton-block height="h1" />
    <tedi:skeleton-block :height="100" />
</tedi:skeleton>
