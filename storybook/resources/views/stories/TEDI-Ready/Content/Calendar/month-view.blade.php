@storybook([
    'name' => 'Month View',
    'order' => 4,
    'status' => 'subset',
    'args' => [],
])

<tedi:calendar
    selection-level="months"
    view="months"
    mode="single"
    :current-month="date('Y-m-01')"
/>
