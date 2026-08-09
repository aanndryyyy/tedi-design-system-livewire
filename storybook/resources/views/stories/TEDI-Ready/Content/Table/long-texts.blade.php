@storybook([
    'name' => 'Long Texts',
    'order' => 27,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
])

@php
    $columns = [
        ['key' => 'name', 'header' => 'Name', 'maxWidth' => 180],
        ['key' => 'description', 'header' => 'Description'],
        ['key' => 'location', 'header' => 'Location', 'maxWidth' => 140],
    ];

    $long = 'Väga pikk kirjeldus, mis ei mahu ühele reale ja peab seetõttu murduma mitmele reale, et tabeli veerg ei veniks lõputult laiaks.';

    $rows = [
        ['id' => '1', 'cells' => ['name' => 'Anna Tamm', 'description' => $long, 'location' => 'Tallinn']],
        ['id' => '2', 'cells' => ['name' => 'Jüri Kask', 'description' => $long, 'location' => 'Tartu']],
        ['id' => '3', 'cells' => ['name' => 'Maria Saar', 'description' => 'Lühike.', 'location' => 'Pärnu']],
    ];
@endphp

<tedi:table :columns="$columns" :rows="$rows" :fixed-layout="true" />
