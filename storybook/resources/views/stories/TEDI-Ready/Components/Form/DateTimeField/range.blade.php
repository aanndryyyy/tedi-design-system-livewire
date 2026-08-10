@storybook([
    'name' => 'Range',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=7895-221619&m=dev',
    'args' => [
        'display' => '01.01.2026 10:00 – 05.01.2026 18:00',
    ],
    'argTypes' => [
        'display' => ['control' => 'text'],
    ],
])

{{-- Range renders two calendars side by side and a time picker per end. --}}
<tedi:date-time-field
    mode="range"
    :display="$display"
    :value="['from' => '2026-01-01', 'to' => '2026-01-05']"
    :open="true"
/>
