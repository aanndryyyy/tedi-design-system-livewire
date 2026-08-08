@storybook([
    'name' => 'Wider Siblings',
    'order' => 11,
    'status' => 'subset',
    'args' => [
        'pageCount' => 40,
        'page' => 20,
        'boundaryCount' => 2,
        'siblingCount' => 2,
    ],
    'argTypes' => [
        'pageCount' => [
            'control' => 'number',
            'description' => 'Total number of pages.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'page' => [
            'control' => 'number',
            'description' => 'Current page (1-based).',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'boundaryCount' => [
            'control' => 'number',
            'description' => 'Pages always shown at the start and end of the range.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'siblingCount' => [
            'control' => 'number',
            'description' => 'Pages shown on either side of the current page.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :boundary-count="$boundaryCount"
    :sibling-count="$siblingCount"
    :page-url="fn ($p) => '?page='.$p"
/>
