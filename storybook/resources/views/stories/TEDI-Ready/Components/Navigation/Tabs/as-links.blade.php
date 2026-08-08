@storybook([
    'name' => 'As Links',
    'order' => 6,
    'status' => 'subset',
])

@php
    $healthTimeline = 'Kronoloogiline ülevaade teie tervisesündmustest – visiidid, analüüsid ja diagnoosid on koondatud ühele ajateljele.';
    $diseaseCourse = 'Diagnoositud haiguste ülevaade ja nende areng ajas koos ravi- ning jälgimismärkmetega.';
    $medication = 'Teile välja kirjutatud ja väljastatud ravimite loetelu koos annuste ning manustamisperioodidega.';
@endphp

<tedi:tabs default-value="link-1">
    <tedi:tabs.list aria-label="Lingina sakid">
        <tedi:tabs.trigger id="link-1" href="#link-1-panel">Terviseteekond</tedi:tabs.trigger>
        <tedi:tabs.trigger id="link-2" href="#link-2-panel">Haiguste kulg</tedi:tabs.trigger>
        <tedi:tabs.trigger id="link-3" href="#link-3-panel">Ravimite ajalugu</tedi:tabs.trigger>
    </tedi:tabs.list>
    <tedi:tabs.content id="link-1">
        <tedi:card-content><p>{{ $healthTimeline }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="link-2">
        <tedi:card-content><p>{{ $diseaseCourse }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="link-3">
        <tedi:card-content><p>{{ $medication }}</p></tedi:card-content>
    </tedi:tabs.content>
</tedi:tabs>
