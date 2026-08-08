@storybook([
    'name' => 'Many Pages Ellipsis',
    'order' => 9,
    'status' => 'subset',
    'args' => [
        'pageCount' => 50,
        'page' => 12,
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
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :page-url="fn ($p) => '?page='.$p"
/>
