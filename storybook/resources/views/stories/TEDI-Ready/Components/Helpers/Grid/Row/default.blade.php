@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'cols' => 'auto',
        'minColWidth' => 200,
        'justifyItems' => '',
        'alignItems' => '',
        'gap' => '',
        'gapX' => '',
        'gapY' => '',
    ],
    'argTypes' => [
        'cols' => [
            'control' => 'select',
            'options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 'auto'],
            'description' => 'The number of columns that will fit next to each other.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => 'auto'], 'type' => ['summary' => 'Cols']],
        ],
        'minColWidth' => [
            'control' => 'number',
            'description' => 'Applies minimum width (px) to the column when using auto layout.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => '200px'], 'type' => ['summary' => 'number']],
        ],
        'justifyItems' => [
            'control' => 'select',
            'options' => ['', 'start', 'end', 'center', 'stretch'],
            'description' => 'Aligns items horizontally inside their grid cell.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'JustifyItems']],
        ],
        'alignItems' => [
            'control' => 'select',
            'options' => ['', 'start', 'end', 'center', 'stretch'],
            'description' => 'Aligns items vertically inside their grid cell.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'AlignItems']],
        ],
        'gap' => [
            'control' => 'select',
            'options' => ['', 0, 1, 2, 3, 4, 5],
            'description' => 'Add horizontal and vertical gap between items.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'Gap']],
        ],
        'gapX' => [
            'control' => 'select',
            'options' => ['', 0, 1, 2, 3, 4, 5],
            'description' => 'Add horizontal gap between items.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'Gap']],
        ],
        'gapY' => [
            'control' => 'select',
            'options' => ['', 0, 1, 2, 3, 4, 5],
            'description' => 'Add vertical gap between items.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'Gap']],
        ],
    ],
])

{{-- Breakpoint props (xs–xxl) are not ported (CONTRACT.md §5) — only the base props are exposed. --}}
<tedi:row
    :cols="$cols"
    :min-col-width="$minColWidth"
    :justify-items="$justifyItems ?: null"
    :align-items="$alignItems ?: null"
    :gap="$gap !== '' ? $gap : null"
    :gap-x="$gapX !== '' ? $gapX : null"
    :gap-y="$gapY !== '' ? $gapY : null"
>
    <tedi:col class="example-col">Col 1</tedi:col>
    <tedi:col class="example-col">Col 2</tedi:col>
    <tedi:col class="example-col">Col 3</tedi:col>
    <tedi:col class="example-col">Col 4</tedi:col>
    <tedi:col class="example-col">Col 5</tedi:col>
    <tedi:col class="example-col">Col 6</tedi:col>
    <tedi:col class="example-col">Col 7</tedi:col>
    <tedi:col class="example-col">Col 8</tedi:col>
    <tedi:col class="example-col">Col 9</tedi:col>
    <tedi:col class="example-col">Col 10</tedi:col>
    <tedi:col class="example-col">Col 11</tedi:col>
    <tedi:col class="example-col">Col 12</tedi:col>
</tedi:row>
