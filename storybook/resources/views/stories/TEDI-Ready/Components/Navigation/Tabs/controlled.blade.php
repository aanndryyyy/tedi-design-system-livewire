{{--
    Angular's Controlled story demonstrates two-way `[value]`/`(valueChange)`
    binding with a live "Current tab" label. Output events aren't re-emitted
    (CONVENTIONS.md §3), so this port renders the same static composition
    with the initial value only — switching tabs still works client-side via
    the Alpine state in tabs.blade.php, but the "Current tab: X" label above
    it can't stay in sync without a re-emitted event.
--}}
@storybook([
    'name' => 'Controlled',
    'order' => 5,
    'status' => 'subset',
])

@php
    $healthTimeline = 'Kronoloogiline ülevaade teie tervisesündmustest – visiidid, analüüsid ja diagnoosid on koondatud ühele ajateljele.';
    $diseaseCourse = 'Diagnoositud haiguste ülevaade ja nende areng ajas koos ravi- ning jälgimismärkmetega.';
    $medication = 'Teile välja kirjutatud ja väljastatud ravimite loetelu koos annuste ning manustamisperioodidega.';
@endphp

<div class="flex flex-column gap-2">
    <p>Current tab: <strong>tab-1</strong></p>
    <tedi:tabs value="tab-1">
        <tedi:tabs.list aria-label="Juhitavad sakid">
            <tedi:tabs.trigger id="tab-1">Terviseteekond</tedi:tabs.trigger>
            <tedi:tabs.trigger id="tab-2">Haiguste kulg</tedi:tabs.trigger>
            <tedi:tabs.trigger id="tab-3">Ravimite ajalugu</tedi:tabs.trigger>
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
</div>
