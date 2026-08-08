@storybook([
    'name' => 'Disabled Matchers',
    'order' => 8,
    'status' => 'subset',
    'args' => [],
])

@php
    // The Angular story passes matcher objects and a predicate function. Blade
    // renders on the server and cannot accept callables as attributes, so the
    // same three rules — every past date, weekends, and the 15th — are flattened
    // into an explicit list of Y-m-d strings for the month on screen.
    $year = (int) date('Y');
    $month = (int) date('n');
    $today = date('Y-m-d');

    $disabledDays = [];
    foreach (range(1, (int) date('t')) as $dayNumber) {
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $dayNumber, $year));
        $weekday = (int) date('w', mktime(0, 0, 0, $month, $dayNumber, $year));

        if ($date < $today || in_array($weekday, [0, 6], true) || $dayNumber === 15) {
            $disabledDays[] = $date;
        }
    }
@endphp

<tedi:calendar
    :current-month="date('Y-m-01')"
    :disabled-days="$disabledDays"
/>
