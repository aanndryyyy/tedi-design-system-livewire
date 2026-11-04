@storybook([
    'name' => 'Many steps (open the list)',
    'order' => 9,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $steps = [
        ['title' => 'Kutse', 'state' => 'completed'],
        ['title' => 'Tahteavaldus', 'state' => 'completed'],
        ['title' => 'Geenianalüüs'],
        ['title' => 'Vastus'],
        ['title' => 'Samm 5'],
        ['title' => 'Samm 6'],
        ['title' => 'Samm 7'],
        ['title' => 'Samm 8'],
        ['title' => 'Samm 9'],
        ['title' => 'Samm 10'],
        ['title' => 'Samm 11'],
        ['title' => 'Samm 12'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:card-stepper :steps="$steps" :active-step="2" :labels="$labels" aria-label="Geeniuuringu sammud" />
</div>
