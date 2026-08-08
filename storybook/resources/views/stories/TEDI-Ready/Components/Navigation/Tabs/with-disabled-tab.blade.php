@storybook([
    'name' => 'With Disabled Tab',
    'order' => 7,
    'status' => 'subset',
])

@php
    $healthTimeline = 'Kronoloogiline ülevaade teie tervisesündmustest – visiidid, analüüsid ja diagnoosid on koondatud ühele ajateljele.';
    $diseaseCourse = 'Diagnoositud haiguste ülevaade ja nende areng ajas koos ravi- ning jälgimismärkmetega.';
    $medication = 'Teile välja kirjutatud ja väljastatud ravimite loetelu koos annuste ning manustamisperioodidega.';
@endphp

<tedi:tabs default-value="tab-1">
    <tedi:tabs.list aria-label="Keelatud sakiga sakid">
        <tedi:tabs.trigger id="tab-1">Terviseteekond</tedi:tabs.trigger>
        <tedi:tabs.trigger id="tab-2">Haiguste kulg</tedi:tabs.trigger>
        <tedi:tabs.trigger id="tab-3" :disabled="true">Ravimite ajalugu</tedi:tabs.trigger>
    </tedi:tabs.list>
    <tedi:tabs.content id="tab-1">
        <tedi:card-content><p>{{ $healthTimeline }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="tab-2">
        <tedi:card-content><p>{{ $diseaseCourse }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="tab-3">
        <tedi:card-content><p>{{ $medication }}</p></tedi:card-content>
    </tedi:tabs.content>
</tedi:tabs>
