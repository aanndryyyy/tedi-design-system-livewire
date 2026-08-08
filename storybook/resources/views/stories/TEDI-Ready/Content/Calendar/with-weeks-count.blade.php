@storybook([
    'name' => 'With Weeks Count',
    'order' => 9,
    'status' => 'subset',
    'args' => [],
])

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:calendar
        :current-month="date('Y-m-01')"
        :show-week-numbers="true"
    />
    <tedi:calendar
        :current-month="date('Y-m-01')"
        :show-week-numbers="true"
        :number-of-months="2"
        :show-navigation="false"
    />
</div>
