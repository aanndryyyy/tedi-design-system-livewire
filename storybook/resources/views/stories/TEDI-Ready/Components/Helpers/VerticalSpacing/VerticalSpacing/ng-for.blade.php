@storybook([
    'name' => 'Vertical Spacing with a loop',
    'order' => 2,
    'status' => 'subset',
    'args' => [
        'size' => 0.5,
    ],
    'argTypes' => [
        'size' => [
            'control' => 'number',
            'options' => [0, 0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2, 2.5, 3, 4, 5],
            'description' => 'The size of the vertical spacing. Applied as margin-bottom with em units',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => '0'],
                'type' => ['summary' => 'VerticalSpacingSize'],
            ],
        ],
    ],
])

{{--
    Angular names this story "Vertical Spacing with (at)for", after its control-flow
    block. Blade's compiler treats that directive name as a real directive
    wherever it appears — including inside this file's @storybook argument — so
    the name is spelled without it here. The file name still kebab-cases the
    Angular export (NgFor), per CONTRACT.md §1.

    The Angular story hardcodes 0.5 in the template and ignores its own arg;
    this one honours the control.
--}}
@php
    $weekdays = [
        ['name' => 'Monday', 'dayNumber' => 1],
        ['name' => 'Tuesday', 'dayNumber' => 2],
        ['name' => 'Wednesday', 'dayNumber' => 3],
        ['name' => 'Thursday', 'dayNumber' => 4],
        ['name' => 'Friday', 'dayNumber' => 5],
        ['name' => 'Saturday', 'dayNumber' => 6],
        ['name' => 'Sunday', 'dayNumber' => 7],
    ];
@endphp

<tedi:vertical-spacing :size="$size">
    @foreach ($weekdays as $day)
        <div>
            Day {{ $day['dayNumber'] }} — {{ $day['name'] }}
        </div>
    @endforeach
</tedi:vertical-spacing>
