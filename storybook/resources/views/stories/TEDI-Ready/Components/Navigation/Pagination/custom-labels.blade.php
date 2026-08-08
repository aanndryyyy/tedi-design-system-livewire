{{--
    Angular's CustomLabels example also overrides `pageAriaLabel`,
    `currentPageAriaLabel` and `results`, which are closures — not
    JSON-serializable Storybook args. Only the plain-string label overrides
    are portable here; the closure-driven labels keep pagination.blade.php's
    own PHP defaults (see its `$defaultLabels`).
--}}
@storybook([
    'name' => 'Custom Labels',
    'order' => 22,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 1,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50],
        'labelsAriaLabel' => 'Lehekülgede sirvimine',
        'labelsPrevious' => 'Eelmine lehekülg',
        'labelsNext' => 'Järgmine lehekülg',
        'labelsPageSize' => 'Kirjete arv lehel',
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
        'labelsAriaLabel' => [
            'control' => 'text',
            'description' => 'Override for labels.ariaLabel.',
        ],
        'labelsPrevious' => [
            'control' => 'text',
            'description' => 'Override for labels.previous.',
        ],
        'labelsNext' => [
            'control' => 'text',
            'description' => 'Override for labels.next.',
        ],
        'labelsPageSize' => [
            'control' => 'text',
            'description' => 'Override for labels.pageSize.',
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :total-items="$totalItems"
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :labels="[
        'ariaLabel' => $labelsAriaLabel,
        'previous' => $labelsPrevious,
        'next' => $labelsNext,
        'pageSize' => $labelsPageSize,
    ]"
    :page-url="fn ($p) => '?page='.$p"
/>
