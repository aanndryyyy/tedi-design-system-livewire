@storybook([
    'name' => 'Date Constraints',
    'order' => 6,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=7895-221619&m=dev',
    'args' => [
        'display' => '',
    ],
    'argTypes' => [
        'display' => ['control' => 'text'],
    ],
])

{{-- Upstream's matcher machinery (minDate, disablePast, shouldDisableMonth, …)
     collapses into the flat disabled-days list the calendar already takes —
     the same ruling tedi:date-field documents. --}}
<tedi:date-time-field
    :display="$display"
    current-month="2026-01-01"
    :disabled-days="['2026-01-02', '2026-01-03', '2026-01-06', '2026-01-07']"
    :open="true"
/>
