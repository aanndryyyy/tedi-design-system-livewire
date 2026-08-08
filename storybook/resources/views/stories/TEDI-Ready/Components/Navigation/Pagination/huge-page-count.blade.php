{{--
    Stress-test with a very large page count (5000 pages). The pager still
    renders a compact range with ellipses on both sides.
--}}
@storybook([
    'name' => 'Huge Page Count',
    'order' => 10,
    'status' => 'subset',
    'args' => [
        'pageCount' => 5000,
        'page' => 2500,
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
