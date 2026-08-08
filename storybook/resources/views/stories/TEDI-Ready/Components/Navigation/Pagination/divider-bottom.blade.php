{{--
    When pagination sits above the content, set dividerPosition="bottom" so
    the line separates the strip from the rows below it.
--}}
@storybook([
    'name' => 'Divider Bottom',
    'order' => 15,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50, 100],
        'dividerPosition' => 'bottom',
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
        'dividerPosition' => [
            'control' => 'radio',
            'options' => ['top', 'bottom', 'none'],
            'description' => "Position of the divider line. 'none' removes it entirely.",
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'PaginationDividerPosition']],
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :total-items="$totalItems"
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :divider-position="$dividerPosition"
    :page-url="fn ($p) => '?page='.$p"
/>
<div style="padding: 24px 0; color: var(--general-text-tertiary); text-align: center;">— table content goes here —</div>
