@storybook([
    'name' => 'Grouped Rows',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
    'args' => [
        'rowGroupDividers' => 'all',
    ],
    'argTypes' => [
        'rowGroupDividers' => [
            'control' => 'inline-radio',
            'options' => ['all', 'between', 'none'],
            'description' => "When grouped, how row dividers are drawn: 'all' (every row), 'between' (only at group boundaries) or 'none'.",
            'table' => ['category' => 'grouping', 'defaultValue' => ['summary' => 'all'], 'type' => ['summary' => "'all' | 'between' | 'none'"]],
        ],
    ],
])

@php
    $columns = [
        ['key' => 'date', 'header' => 'Date', 'vAlign' => 'top'],
        ['key' => 'name', 'header' => 'Name'],
        ['key' => 'role', 'header' => 'Role'],
        ['key' => 'location', 'header' => 'Location'],
    ];

    // groupStart marks the first row of each group; the merged Date cell carries
    // the rowspan and the covered rows set rowspan => 0.
    $rows = [
        ['id' => '1', 'groupStart' => true, 'cells' => ['date' => ['value' => '22.03.2029', 'rowspan' => 2], 'name' => 'Anna Tamm', 'role' => 'Engineer', 'location' => 'Tallinn']],
        ['id' => '2', 'cells' => ['date' => ['value' => '', 'rowspan' => 0], 'name' => 'Jüri Kask', 'role' => 'Designer', 'location' => 'Tartu']],
        ['id' => '3', 'groupStart' => true, 'cells' => ['date' => ['value' => '23.03.2029', 'rowspan' => 3], 'name' => 'Maria Saar', 'role' => 'Product', 'location' => 'Pärnu']],
        ['id' => '4', 'cells' => ['date' => ['value' => '', 'rowspan' => 0], 'name' => 'Mart Mets', 'role' => 'Engineer', 'location' => 'Tallinn']],
        ['id' => '5', 'cells' => ['date' => ['value' => '', 'rowspan' => 0], 'name' => 'Liis Lepp', 'role' => 'Ops', 'location' => 'Narva']],
        ['id' => '6', 'groupStart' => true, 'cells' => ['date' => '24.03.2029', 'name' => 'Rain Roos', 'role' => 'Designer', 'location' => 'Rakvere']],
    ];
@endphp

<tedi:table
    :columns="$columns"
    :rows="$rows"
    :grouped="true"
    :row-group-dividers="$rowGroupDividers"
    :vertical-borders="true"
/>
