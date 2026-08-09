@storybook([
    'name' => 'Merged Cells',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
])

@php
    // The spans themselves are computed by the consumer: this port renders the
    // RESULT (`rowspan` on a cell, `rowspan => 0` to skip a covered cell), not
    // TanStack's `rowSpan` / `groupBy` callbacks.
    $columns = [
        ['key' => 'location', 'header' => 'Location', 'vAlign' => 'top'],
        ['key' => 'name', 'header' => 'Name'],
        ['key' => 'role', 'header' => 'Role'],
    ];

    $rows = [
        ['id' => '1', 'cells' => ['location' => ['value' => 'Tallinn', 'rowspan' => 3], 'name' => 'Anna Tamm', 'role' => 'Engineer']],
        ['id' => '2', 'cells' => ['location' => ['value' => '', 'rowspan' => 0], 'name' => 'Mart Mets', 'role' => 'Engineer']],
        ['id' => '3', 'cells' => ['location' => ['value' => '', 'rowspan' => 0], 'name' => 'Kadri Kask', 'role' => 'Engineer']],
        ['id' => '4', 'cells' => ['location' => ['value' => 'Tartu', 'rowspan' => 2], 'name' => 'Jüri Kask', 'role' => 'Designer']],
        ['id' => '5', 'cells' => ['location' => ['value' => '', 'rowspan' => 0], 'name' => 'Rain Roos', 'role' => 'Designer']],
        ['id' => '6', 'cells' => ['location' => 'Pärnu', 'name' => 'Maria Saar', 'role' => 'Product']],
    ];
@endphp

<tedi:table :columns="$columns" :rows="$rows" :vertical-borders="true" />
