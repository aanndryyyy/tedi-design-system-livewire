@storybook([
    'name' => 'Transparent',
    'order' => 12,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50, 100],
        'background' => 'transparent',
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
        'totalItems' => [
            'control' => 'number',
            'description' => 'Total number of items across all pages. When set, the "X results" label is rendered.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'pageSize' => [
            'control' => 'number',
            'description' => 'Current page size.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'pageSizeOptions' => [
            'control' => 'object',
            'description' => 'Options shown in the page-size dropdown. Empty array hides the dropdown.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => '(number|PaginationPageSizeOption)[]'], 'defaultValue' => ['summary' => '[]']],
        ],
        'background' => [
            'control' => 'radio',
            'options' => ['white', 'transparent'],
            'description' => 'Background variant. `transparent` removes the surface fill and top border.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'PaginationBackground'], 'defaultValue' => ['summary' => 'white']],
        ],
    ],
])

<div style="background: var(--general-surface-secondary); padding: 16px;">
    <tedi:pagination
        :page-count="$pageCount"
        :page="$page"
        :total-items="$totalItems"
        :page-size="$pageSize"
        :page-size-options="$pageSizeOptions"
        :background="$background"
        :page-url="fn ($p) => '?page='.$p"
    />
</div>
