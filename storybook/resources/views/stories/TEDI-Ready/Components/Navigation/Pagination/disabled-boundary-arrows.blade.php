{{--
    By default the prev/next button is removed from the DOM at the first/last
    page. Set disableArrowsAtBoundary=true to keep it rendered as a disabled
    button instead.
--}}
@storybook([
    'name' => 'Disabled Boundary Arrows',
    'order' => 16,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 1,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50, 100],
        'disableArrowsAtBoundary' => true,
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
        'disableArrowsAtBoundary' => [
            'control' => 'boolean',
            'description' => 'Keep the prev/next button rendered (but disabled) at the first/last page instead of removing it from the DOM.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :total-items="$totalItems"
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :disable-arrows-at-boundary="(bool) $disableArrowsAtBoundary"
    :page-url="fn ($p) => '?page='.$p"
/>
