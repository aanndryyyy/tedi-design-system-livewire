@storybook([
    'name' => 'Width',
    'order' => 3,
    'status' => 'stable',
])

<tedi:skeleton label="Loading something">
    <tedi:skeleton-block :width="50" height="p" />
    <tedi:skeleton-block :width="75" height="h3" />
    <tedi:skeleton-block width="36px" height="h2" />
    <tedi:skeleton-block width="auto" height="h1" />
</tedi:skeleton>
