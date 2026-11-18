@storybook([
    'name' => 'Vertical',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

<tedi:process-timeline orientation="vertical">
    <tedi:process-timeline-item state="completed" :index="0">
        <x-slot:title>Pakkumine esitatud</x-slot:title>
        <tedi:status-badge color="success" text="Esitatud" icon="check" />
        <tedi:status-badge color="warning" text="Kinnituse ootel" />
    </tedi:process-timeline-item>
    <tedi:process-timeline-item state="active" :index="1">
        <x-slot:title>Kvalifitseerimine ja vastavuse kontroll</x-slot:title>
        <x-slot:description>
            Hankija kontrollib kvalifikatsiooni dokumente. Vastus hiljemalt 15.12.2026.
        </x-slot:description>
        <tedi:status-badge color="warning" text="Lisainfo vajalik" />
        <tedi:button variant="secondary" size="small">Lisa dokumendid</tedi:button>
        <tedi:link href="#" size="small">Vaata nõudeid</tedi:link>
    </tedi:process-timeline-item>
    <tedi:process-timeline-item state="upcoming" :index="2" last>
        <x-slot:title>Tulemused</x-slot:title>
    </tedi:process-timeline-item>
</tedi:process-timeline>
