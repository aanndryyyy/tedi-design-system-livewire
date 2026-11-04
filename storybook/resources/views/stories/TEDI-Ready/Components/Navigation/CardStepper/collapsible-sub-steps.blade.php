@storybook([
    'name' => 'Collapsible sub-steps (open the list)',
    'order' => 10,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $steps = [
        ['title' => 'Kutse', 'state' => 'completed'],
        ['title' => 'Tahteavaldus', 'state' => 'completed', 'sub_steps' => [['title' => 'Nõusolek', 'state' => 'completed'], ['title' => 'Allkiri', 'state' => 'completed']]],
        ['title' => 'Geenianalüüs', 'description' => 'Kirjeldus', 'sub_steps' => [['title' => 'Proovi andmine', 'state' => 'completed'], ['title' => 'Analüüs', 'current' => true], ['title' => 'Tulemused']]],
        ['title' => 'Vastus'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:card-stepper :steps="$steps" :active-step="2" :labels="$labels" aria-label="Taotluse sammud" />
</div>
