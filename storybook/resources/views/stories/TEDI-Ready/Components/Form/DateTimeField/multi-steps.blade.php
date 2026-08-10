@storybook([
    'name' => 'Multi Steps',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=7895-221619&m=dev',
    'args' => [
        'step' => 'date',
        'display' => '01.01.2026 10:00',
    ],
    'argTypes' => [
        'step' => [
            'control' => 'radio',
            'options' => ['date', 'time'],
            'description' => "Which step of the multi-step layout is showing. Upstream advances this from the calendar's Select time footer link and the time step's Back link; here it is an explicit prop, because the transition is runtime state.",
            'table' => ['defaultValue' => ['summary' => 'date']],
        ],
        'display' => ['control' => 'text'],
    ],
])

<tedi:date-time-field
    layout="multi-step"
    :step="$step"
    :display="$display"
    value="2026-01-01 10:00"
    :open="true"
/>
