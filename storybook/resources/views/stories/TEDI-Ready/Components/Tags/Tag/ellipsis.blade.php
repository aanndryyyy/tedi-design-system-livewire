@storybook([
    'name' => 'Ellipsis',
    'order' => 2,
    'status' => 'stable',
])

<div style="display: flex; flex-direction: column; gap: 0.5rem; width: 7rem;">
    <tedi:tag :closable="true">A fairly long tag label that wraps</tedi:tag>
    <tedi:tag :closable="true" ellipsis="end">A fairly long tag label, end</tedi:tag>
    <tedi:tag :closable="true" ellipsis="start">start, a fairly long tag label</tedi:tag>
</div>
