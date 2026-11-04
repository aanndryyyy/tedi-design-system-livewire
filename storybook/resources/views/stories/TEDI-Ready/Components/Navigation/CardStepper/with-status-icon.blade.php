@storybook([
    'name' => 'With Status Icon',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $done = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'state' => 'completed'],
        ['title' => 'Taotleja andmed', 'state' => 'completed'],
        ['title' => 'Ülevaade'],
    ];
    $failed = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'state' => 'completed'],
        ['title' => 'Taotleja andmed', 'state' => 'error'],
        ['title' => 'Ülevaade'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:card-stepper :steps="$done" :active-step="2" show-status-icon :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$failed" :active-step="2" show-status-icon :labels="$labels" aria-label="Taotluse sammud" />
    </tedi:vertical-spacing>
</div>
