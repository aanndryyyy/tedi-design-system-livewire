@storybook([
    'name' => 'With Status Badge',
    'order' => 3,
    'status' => 'subset',
])

@php
    $healthTimeline = 'Kronoloogiline ülevaade teie tervisesündmustest – visiidid, analüüsid ja diagnoosid on koondatud ühele ajateljele.';
    $unreadMessages = 'Teil on uusi lugemata teateid tervishoiuteenuse osutajatelt. Avage teade üksikasjade nägemiseks.';
    $medication = 'Teile välja kirjutatud ja väljastatud ravimite loetelu koos annuste ning manustamisperioodidega.';
@endphp

<tedi:tabs default-value="tab-1">
    <tedi:tabs.list aria-label="Olekumärgisega sakid">
        <tedi:tabs.trigger id="tab-1">
            <tedi:ellipsis :line-clamp="1">Terviseteekond</tedi:ellipsis>
            <tedi:status-badge color="brand" text="Esitatud" />
        </tedi:tabs.trigger>
        <tedi:tabs.trigger id="tab-2">
            <span style="position: relative">
                Lugemata teated&nbsp;<tedi:status-indicator type="danger" position="top-right" />
            </span>
        </tedi:tabs.trigger>
        <tedi:tabs.trigger id="tab-3">Ravimite ajalugu</tedi:tabs.trigger>
    </tedi:tabs.list>
    <tedi:tabs.content id="tab-1">
        <tedi:card-content><p>{{ $healthTimeline }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="tab-2">
        <tedi:card-content><p>{{ $unreadMessages }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="tab-3">
        <tedi:card-content><p>{{ $medication }}</p></tedi:card-content>
    </tedi:tabs.content>
</tedi:tabs>
