{{--
    Angular's `[tediPaginationResults]` projected content maps to the Blade
    `results` named slot — it replaces the default "X results" label
    entirely.
--}}
@storybook([
    'name' => 'Custom Results Slot',
    'order' => 20,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
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
    :page-size="$pageSize"
    :page-size-options="$pageSizeOptions"
    :page-url="fn ($p) => '?page='.$p"
>
    <x-slot:results>
        <span class="text-small">1000+ tulemust</span>
    </x-slot:results>
</tedi:pagination>
