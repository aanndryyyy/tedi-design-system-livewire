@storybook([
    'name' => 'Column Sizing',
    'order' => 7,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
])

@php
    $columns = [
        ['key' => 'code', 'header' => 'Kood', 'maxWidth' => 72],
        ['key' => 'count', 'header' => 'Arv', 'width' => 64, 'minWidth' => 120],
        ['key' => 'name', 'header' => 'Nimi', 'maxWidth' => 140],
        ['key' => 'description', 'header' => 'Kirjeldus'],
    ];

    $rows = [
        ['id' => '1', 'cells' => ['code' => 'A-1', 'count' => '12', 'name' => 'Anna Tamm', 'description' => 'Pikk kirjeldus, mis mahub ülejäänud laiusesse ja murdub vajadusel mitmele reale.']],
        ['id' => '2', 'cells' => ['code' => 'B-2', 'count' => '4', 'name' => 'Jüri Kask', 'description' => 'Teine kirjeldus, mis venib veeru vabaks jäänud ruumi.']],
        ['id' => '3', 'cells' => ['code' => 'C-3', 'count' => '31', 'name' => 'Maria Saar', 'description' => 'Kolmas kirjeldus.']],
    ];
@endphp

<tedi:table :columns="$columns" :rows="$rows" :fixed-layout="true" :vertical-borders="true" />
