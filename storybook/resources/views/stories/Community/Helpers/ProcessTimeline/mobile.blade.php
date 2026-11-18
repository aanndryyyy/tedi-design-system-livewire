{{--
    Upstream renders the Default composition at Storybook's `mobile1` viewport
    (320px wide), where the breakpoint default resolves to vertical. Blast has
    no per-story viewport passthrough and breakpoint props are not ported
    (CONVENTIONS.md §7.1), so the story stands the timeline in a 320px-wide
    box instead. The timeline is horizontal, and the bundled
    `tediProcessTimeline` overflow fallback turns it vertical because its
    stages don't fit, which is the layout upstream shows here.
--}}
@storybook([
    'name' => 'Mobile',
    'order' => 7,
    'status' => 'subset',
    'args' => [],
])

<div style="max-width: 320px">
    <tedi:process-timeline>
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
</div>
