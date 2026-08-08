{{--
    Use the per-slot hide toggles to render different parts of the pagination
    above and below a table. Top row shows results + page-size; bottom row
    shows only the pager.
--}}
@storybook([
    'name' => 'Top Bottom Split',
    'order' => 13,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50, 100],
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
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :total-items="$totalItems"
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :hide-pager="true"
    divider-position="bottom"
    :page-url="fn ($p) => '?page='.$p"
/>
<div style="padding: 24px 0; color: var(--general-text-tertiary); text-align: center;">— table content goes here —</div>
<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :total-items="$totalItems"
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :hide-results="true"
    :hide-page-size="true"
    :page-url="fn ($p) => '?page='.$p"
/>
