@storybook([
    'name' => 'Availability',
    'order' => 6,
    'status' => 'subset',
    'args' => [],
])

@php
    $day = fn (int $offset) => date('Y-m-d', mktime(0, 0, 0, (int) date('n'), (int) date('j') + $offset, (int) date('Y')));
@endphp

<div style="display: flex; gap: 1rem; flex-wrap: wrap;">
    <tedi:calendar
        :current-month="date('Y-m-01')"
        :available-days="[$day(-1), $day(4), $day(5), $day(6)]"
    />
    <tedi:calendar
        :current-month="date('Y-m-01')"
        :unavailable-days="[$day(1), $day(2), $day(3)]"
    />
</div>
