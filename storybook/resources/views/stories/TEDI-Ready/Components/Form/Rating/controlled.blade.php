@storybook([
    'name' => 'Controlled',
    'order' => 7,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
])

{{-- Angular's `[(value)]` becomes Alpine's x-model here (wire:model in a Livewire component):
     tedi:rating copies either onto every radio. :value seeds the server render. --}}
<div class="flex flex-column gap-3" x-data="{ value: 2 }">
    <tedi:rating
        :value="2"
        x-model.number="value"
        :item-labels="['Ei jäänud üldse rahule', 'Ei jäänud rahule', 'Osaliselt rahul', 'Jäin rahule', 'Jäin väga rahule']"
        label="Hinnang"
    />
    <tedi:text>Valitud: <span x-text="value || '—'">2</span></tedi:text>
</div>
