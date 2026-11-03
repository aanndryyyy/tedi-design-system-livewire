@storybook([
    'name' => 'Inside Component',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

<div style="padding:16px;background:var(--card-background-primary);border:1px solid var(--card-border-primary);border-radius:var(--card-radius)">
    <tedi:empty-state type="inside">You have no data to display</tedi:empty-state>
</div>
