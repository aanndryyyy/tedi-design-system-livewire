@storybook([
    'name' => 'Range',
    'order' => 3,
    'status' => 'subset',
    'args' => [],
])

@php
    $day = fn (int $offset) => date('Y-m-d', mktime(0, 0, 0, (int) date('n'), (int) date('j') + $offset, (int) date('Y')));
    $range = ['from' => $day(3), 'to' => $day(10)];
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:calendar
        mode="range"
        :current-month="date('Y-m-01')"
        :value="$range"
    />
    <tedi:calendar
        mode="range"
        :current-month="date('Y-m-01')"
        :value="$range"
        :number-of-months="2"
        :show-navigation="false"
    />
    {{-- The preview modifiers are hover state in Angular; Blade has no hover, so
         `hoveredDate` is an explicit prop that renders the same markup. --}}
    <tedi:calendar
        mode="range"
        :current-month="date('Y-m-01')"
        :value="['from' => $day(3), 'to' => null]"
        :hovered-date="$day(10)"
    />
</div>
