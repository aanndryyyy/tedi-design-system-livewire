@storybook([
    'name' => 'No Times Available',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=7895-221619&m=dev',
    'args' => [
        'display' => '',
    ],
    'argTypes' => [
        'display' => ['control' => 'text'],
    ],
])

<tedi:date-time-field
    :display="$display"
    time-variant="grid"
    :available-times="[]"
    :open="true"
/>
