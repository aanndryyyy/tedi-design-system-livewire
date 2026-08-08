@storybook([
    'name' => 'With Icons',
    'order' => 2,
    'status' => 'subset',
])

@php
    $table = 'Andmed on kuvatud tabelina – sobib täpseks võrdluseks ja veergude kaupa sorteerimiseks.';
    $grid = 'Andmed on kuvatud ruudustikuna – sobib visuaalseks sirvimiseks ja kiireks ülevaateks.';
@endphp

<tedi:tabs default-value="tab-1">
    <tedi:tabs.list aria-label="Ikoonidega sakid">
        <tedi:tabs.trigger id="tab-1" icon="table_chart">Tabel</tedi:tabs.trigger>
        <tedi:tabs.trigger id="tab-2" icon="grid_on">Ruudustik</tedi:tabs.trigger>
    </tedi:tabs.list>
    <tedi:tabs.content id="tab-1">
        <tedi:card-content><p>{{ $table }}</p></tedi:card-content>
    </tedi:tabs.content>
    <tedi:tabs.content id="tab-2">
        <tedi:card-content><p>{{ $grid }}</p></tedi:card-content>
    </tedi:tabs.content>
</tedi:tabs>
