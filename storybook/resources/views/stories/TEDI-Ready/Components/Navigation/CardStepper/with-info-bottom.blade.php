@storybook([
    'name' => 'With Info Bottom',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $steps = [
        ['title' => 'Isikuandmed', 'description' => 'Taotleja või esindatav', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'description' => 'Taotleja või esindatav', 'state' => 'completed'],
        ['title' => 'Taotleja andmed', 'description' => 'Taotleja või esindatav'],
        ['title' => 'Ülevaade', 'description' => 'Taotleja või esindatav'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:card-stepper :steps="$steps" :active-step="2" :show-step-number="false" :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$steps" :active-step="1" show-navigation :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$steps" :active-step="2" :labels="$labels" aria-label="Taotluse sammud" />
    </tedi:vertical-spacing>
</div>
