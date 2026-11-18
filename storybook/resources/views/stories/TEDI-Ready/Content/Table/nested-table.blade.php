@storybook([
    'name' => 'Nested Table',
    'order' => 19,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
    'args' => [
        'striped' => true,
        'verticalBorders' => true,
        'size' => 'small',
    ],
    'argTypes' => [
        'striped' => ['control' => 'boolean'],
        'verticalBorders' => ['control' => 'boolean'],
        'size' => ['control' => 'select', 'options' => ['medium', 'small']],
    ],
])

@php
    // table-demo-data.ts — collapsiblePeople: parents with visit sub-rows.
    $people = [
        ['name' => 'Anna Tamm', 'age' => 34, 'visits' => 12, 'subRows' => [
            ['visits' => 4, 'status' => 'Kehtiv'],
            ['visits' => 8, 'status' => 'Aegunud'],
        ]],
        ['name' => 'Jüri Kask', 'age' => 41, 'visits' => 3],
        ['name' => 'Maria Saar', 'age' => 28, 'visits' => 7, 'subRows' => [
            ['visits' => 7, 'status' => 'Kehtiv'],
        ]],
        ['name' => 'Mart Mets', 'age' => 52, 'visits' => 1],
    ];

    $columns = [
        ['key' => 'name', 'header' => 'Isik'],
        ['key' => 'age', 'header' => 'Vanus'],
        ['key' => 'visits', 'header' => 'Külastuste arv'],
    ];

    $nestedColumns = [
        ['key' => 'visits', 'header' => 'Külastuste arv'],
        ['key' => 'status', 'header' => 'Tõendi staatus'],
    ];

    $rows = [];

    foreach ($people as $index => $person) {
        $id = (string) $index;
        $row = ['id' => $id, 'cells' => $person];

        if (isset($person['subRows'])) {
            // The nested table follows only its own props: the outer table's
            // striped / small / vertical borders do not reach it (#731).
            $row += [
                'expandable' => true,
                'expanded' => true,
                'sub' => new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(
                    '<tedi:table :id="$id" :columns="$columns" :rows="$rows" />',
                    [
                        'id' => 'tedi-table-nested-'.$id,
                        'columns' => $nestedColumns,
                        'rows' => array_map(fn ($sub) => ['cells' => $sub], $person['subRows']),
                    ],
                )),
            ];
        }

        $rows[] = $row;
    }
@endphp

<tedi:table
    id="tedi-table-nested"
    :columns="$columns"
    :rows="$rows"
    :expandable="true"
    :striped="(bool) $striped"
    :vertical-borders="(bool) $verticalBorders"
    :size="$size"
/>
