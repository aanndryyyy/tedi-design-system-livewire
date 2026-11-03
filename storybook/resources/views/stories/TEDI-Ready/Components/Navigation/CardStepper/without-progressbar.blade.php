@storybook([
    'name' => 'Without Progressbar',
    'order' => 7,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $steps = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'state' => 'completed'],
        ['title' => 'Terviseandmed', 'description' => 'Kirjeldus'],
        ['title' => 'Ülevaade'],
    ];
    $plain = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'state' => 'completed'],
        ['title' => 'Terviseandmed'],
        ['title' => 'Ülevaade'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:card-stepper :steps="$steps" :active-step="2" show-navigation :show-progress="false" :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$plain" :active-step="2" show-navigation :show-progress="false" :labels="$labels" aria-label="Taotluse sammud" />
    </tedi:vertical-spacing>
</div>
