{{--
    Use arrowVariant to apply any tedi-button variant to the prev/next
    buttons. Paired with showArrowLabels for a regular labelled button.
--}}
@storybook([
    'name' => 'Arrows Primary Variant',
    'order' => 19,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
        'totalItems' => 97,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 25, 50, 100],
        'arrowVariant' => 'primary',
        'showArrowLabels' => true,
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
        'arrowVariant' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'neutral', 'success', 'danger', 'danger-neutral', 'primary-inverted', 'secondary-inverted', 'neutral-inverted'],
            'description' => 'Variant for the prev/next arrow buttons. Accepts any tedi-button variant.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'ButtonVariant'], 'defaultValue' => ['summary' => 'neutral']],
        ],
        'showArrowLabels' => [
            'control' => 'boolean',
            'description' => 'Render the previous/next labels as visible text next to the arrow icon. When false, the buttons are icon-only.',
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
    :arrow-variant="$arrowVariant"
    :show-arrow-labels="(bool) $showArrowLabels"
    :page-url="fn ($p) => '?page='.$p"
/>
