@storybook([
    'name' => 'Predefined Time Slots',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=7895-221619&m=dev',
    'args' => [
        'display' => '01.01.2026 09:30',
    ],
    'argTypes' => [
        'display' => ['control' => 'text'],
    ],
])

{{-- With available times the picker renders a slot grid; with none it renders
     the scrolling wheel, which is what the --wheel modifier marks. --}}
<tedi:date-time-field
    :display="$display"
    time-variant="grid"
    :available-times="['09:00', '09:30', '10:00', '10:30', '11:00', '11:30']"
    :open="true"
/>
