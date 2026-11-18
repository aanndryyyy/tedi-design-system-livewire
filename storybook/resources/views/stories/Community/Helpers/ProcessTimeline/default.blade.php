{{--
    Upstream's `orientation` also accepts a breakpoint object and defaults to
    `{ xs: "vertical", md: "horizontal" }`. Breakpoint-valued props are not
    ported (CONVENTIONS.md §7.1), so the control is a plain radio and the
    default is horizontal — see process-timeline.blade.php.

    Stage numbers and the last stage are explicit (`index`, `last`), because
    Blade cannot count its slot's children (CONVENTIONS.md §5).
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'args' => [
        'orientation' => 'horizontal',
        'connectorThickness' => 1,
    ],
    'argTypes' => [
        'orientation' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => 'Layout direction.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ProcessTimelineOrientation'],
                'defaultValue' => ['summary' => 'horizontal'],
            ],
        ],
        'connectorThickness' => [
            'control' => 'radio',
            'options' => [1, 2],
            'description' => 'Thickness of the connectors between stages, in pixels.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ProcessTimelineConnectorThickness'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
    ],
])

<tedi:process-timeline :orientation="$orientation" :connector-thickness="(int) $connectorThickness">
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
