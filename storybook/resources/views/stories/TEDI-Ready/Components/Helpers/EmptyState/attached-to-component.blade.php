@storybook([
    'name' => 'Attached To Component',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

<div style="padding:16px;background:var(--card-background-primary);border:1px solid var(--card-border-primary);border-bottom:0;border-top-left-radius:var(--card-radius);border-top-right-radius:var(--card-radius)">Previous content</div>
<tedi:empty-state type="attached">You have no data to display</tedi:empty-state>
