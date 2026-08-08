@storybook([
    'name' => 'Day Statuses',
    'order' => 7,
    'status' => 'subset',
    'args' => [],
])

@php
    $day = fn (int $offset) => date('Y-m-d', mktime(0, 0, 0, (int) date('n'), (int) date('j') + $offset, (int) date('Y')));
    $status = ['type' => 'success', 'label' => 'Confirmed appointment'];
@endphp

<tedi:calendar
    :current-month="date('Y-m-01')"
    :day-status="[$day(-2) => $status, $day(4) => $status, $day(10) => $status]"
>
    <x-slot:footer>
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem 1rem; align-items: center;">
            <span style="display: inline-flex; align-items: center; gap: 0.375rem;">
                <tedi:status-indicator type="success" size="sm" :has-border="true" />
                Confirmed
            </span>
        </div>
    </x-slot:footer>
</tedi:calendar>
