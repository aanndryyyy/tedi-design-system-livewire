@storybook([
    'name' => 'Year View',
    'order' => 5,
    'status' => 'subset',
    'args' => [],
])

<tedi:calendar
    selection-level="years"
    view="years"
    mode="single"
    :current-month="date('Y-m-01')"
/>
