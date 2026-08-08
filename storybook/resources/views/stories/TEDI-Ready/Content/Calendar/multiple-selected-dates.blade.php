@storybook([
    'name' => 'Multiple Selected Dates',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
])

@php
    $day = fn (int $offset) => date('Y-m-d', mktime(0, 0, 0, (int) date('n'), (int) date('j') + $offset, (int) date('Y')));
    $selected = [$day(3), $day(5), $day(10)];
@endphp

<tedi:calendar
    mode="multiple"
    :current-month="date('Y-m-01')"
    :value="$selected"
/>
<tedi:alert type="info" :show-close="false" style="margin-top: 16px;">
    <tedi:text as="pre" modifiers="small" style="margin: 0;">{{ json_encode($selected) }}</tedi:text>
</tedi:alert>
