{{--
    Override the default arrow_back / arrow_forward Material Symbols icons
    via previousIcon and nextIcon.
--}}
@storybook([
    'name' => 'Custom Arrow Icons',
    'order' => 18,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50, 100],
        'previousIcon' => 'chevron_left',
        'nextIcon' => 'chevron_right',
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
        'previousIcon' => [
            'control' => 'text',
            'description' => 'Material Symbols icon name for the previous-page arrow.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string'], 'defaultValue' => ['summary' => 'arrow_back']],
        ],
        'nextIcon' => [
            'control' => 'text',
            'description' => 'Material Symbols icon name for the next-page arrow.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string'], 'defaultValue' => ['summary' => 'arrow_forward']],
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :total-items="$totalItems"
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :previous-icon="$previousIcon"
    :next-icon="$nextIcon"
    :page-url="fn ($p) => '?page='.$p"
/>
