@storybook([
    'name' => 'With Bottom Slot',
    'order' => 8,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

{{-- Upstream's bottomSlot belongs to the third step, which is the active one
     in every example; here it is the `bottom` slot, shown for the active step. --}}
@php
    $labels = ['previous' => 'Eelmine samm', 'next' => 'Järgmine samm', 'openSteps' => 'Ava sammud', 'modalHeading' => 'Sammud'];
    $steps = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Üldandmed', 'state' => 'completed'],
        ['title' => 'Dokumendid'],
        ['title' => 'Ülevaade'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:card-stepper :steps="$steps" :active-step="2" :show-progress="false" :labels="$labels" aria-label="Taotluse sammud">
        <x-slot:bottom>
            <tedi:alert type="warning">Andmeväljad sisaldavad ebatäpseid väärtuseid</tedi:alert>
        </x-slot:bottom>
    </tedi:card-stepper>
        <tedi:card-stepper :steps="$steps" :active-step="2" :labels="$labels" aria-label="Taotluse sammud">
        <x-slot:bottom>
            <tedi:alert type="success">Isikuandmed vastavad nõuetele</tedi:alert>
        </x-slot:bottom>
    </tedi:card-stepper>
        <tedi:card-stepper :steps="$steps" :active-step="2" show-navigation :labels="$labels" aria-label="Taotluse sammud">
        <x-slot:bottom>
            <tedi:collapse id="card-stepper-read-more" open-text="Loe lähemalt" close-text="Sulge" aria-label="Loe lähemalt"><tedi:text as="p">Lisainfo dokumentide kohta, mida selles sammus on vaja esitada.</tedi:text></tedi:collapse>
        </x-slot:bottom>
    </tedi:card-stepper>
        <tedi:card-stepper :steps="$steps" :active-step="2" show-navigation :labels="$labels" aria-label="Taotluse sammud">
        <x-slot:bottom>
            <tedi:button variant="secondary" icon-start="add" style="width: 100%">Lisa dokument</tedi:button>
        </x-slot:bottom>
    </tedi:card-stepper>
    </tedi:vertical-spacing>
</div>
