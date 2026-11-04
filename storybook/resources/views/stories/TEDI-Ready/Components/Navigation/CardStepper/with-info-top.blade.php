@storybook([
    'name' => 'With Info Top',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $plain = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'state' => 'completed'],
        ['title' => 'Terviseandmed'],
        ['title' => 'Ülevaade'],
    ];
    $steps = [
        ['title' => 'Isikuandmed', 'description' => 'Nimi ja kontakt', 'state' => 'completed'],
        ['title' => 'Dokumendid', 'description' => 'Üleslaaditud failid', 'state' => 'completed'],
        ['title' => 'Elukoht', 'description' => 'Aadress'],
        ['title' => 'Ülevaade', 'description' => 'Kokkuvõte'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:card-stepper :steps="$plain" :active-step="2" counter-position="top" :show-step-number="false" :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$steps" :active-step="2" description-position="top" :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$steps" :active-step="1" description-position="top" show-navigation :labels="$labels" aria-label="Taotluse sammud" />
        <tedi:card-stepper :steps="$steps" :active-step="2" description-position="top" :show-step-number="false" :labels="$labels" aria-label="Taotluse sammud" />
    </tedi:vertical-spacing>
</div>
