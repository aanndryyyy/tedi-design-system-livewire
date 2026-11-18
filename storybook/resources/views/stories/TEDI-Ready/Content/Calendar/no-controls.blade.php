@storybook([
    'name' => 'No Controls',
    'order' => 12,
    'status' => 'subset',
    'args' => [],
])

<tedi:calendar
    :current-month="date('Y-m-01')"
    :show-navigation="false"
/>
